<?php

namespace App\Http\Controllers\Home;

use App\Libs\api\driver\QqAi;
use App\Models\WxKeyword;
use App\Http\Traits\TraitUpload;
use EasyWeChat\Kernel\Messages\Image;
use App\Http\Controllers\Controller;

class WeChatController extends Controller
{

    use TraitUpload;

    /**
     * @var array|null
     */
    private $config = [];


    /**
     * Description:处理微信的请求消息
     * User: Vijay
     * Date: 2019/11/25
     * Time: 21:05
     * @return mixed
     */
    public function serve()
    {
        $app = app('wechat.official_account');
        $app->server->push(function ($message) use ($app) {
            //Log::info(json_encode($message, JSON_UNESCAPED_UNICODE));
            switch ($message['MsgType']) {
                case 'event':
                    # 事件消息...
                    switch ($message['Event']) {
                        case 'subscribe':
                            //查询数据库是否存在关键词
                            $keyword = WxKeyword::query()->where('status', '=', 1)->where('key_name', 'like', '%关注%')->first();
                            if ($keyword) {
                                return $keyword->key_value;
                            }
                            return '我是一个有爆料、有态度、有内涵、有技术的公众号。感谢关注！不妨试试跟我聊天，回复关键词可解锁技能哦。';
                            break;
                        case 'unsubscribe':
                            # code...
                            //取消关注
                            break;
                        case 'CLICK':
                            # code...
                            //点击自定义click菜单
                            switch ($message['EventKey']) {
                                case 'key1'://如果为key1菜单,执行
                                    break;
                                default :
                                    # code...
                                    break;
                            }
                            break;
                        default :
                            # code...
                            break;
                    }
                    break;
                case 'text':
                    //查询数据库是否存在关键词
                    $keyword = WxKeyword::query()->where('status', '=', 1)->where('key_name', 'like', '%' . $message['Content'] . '%')->first();
                    if ($keyword) {
                        return $keyword->key_value;
                    }
                    $res = QqAi::handle()->param($message['Content'])->answer();
                    switch ($res['resultType']) {
                        case 'text':
                            //Log::info('返回内容:' . $res['content']);
                            return $res['content'];
                            break;
                        case 'image':
                            //上传文件并返回路径
                            $result = self::imageUpload($res['content']);
                            $path = $result['url'];
                            //微信临时素材返回数据
                            $material = $app->material_temporary;
                            $result = $material->uploadImage($path);
                            return new Image($result->media_id);
                            break;
                    }
                    break;
                case 'image':
                    # 图片消息...
                    //图灵返回的图片结果
                    $res = QqAi::handle()->param($message->PicUrl, 1)->answer();
                    if ($res['resultType'] == 'image') {
                        //上传文件并返回路径
                        $result = self::imageUpload($res['content']);
                        $path = $result['url'];
                        //微信临时素材返回数据
                        $material = $app->material_temporary;
                        $result = $material->uploadImage($path);
                        return new Image($result->media_id);
                    } else {
                        return $res['content'];
                    }
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
        return $app->server->serve();
    }
}
