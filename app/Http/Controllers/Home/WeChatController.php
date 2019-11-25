<?php

namespace App\Http\Controllers\Home;

use EasyWeChat\Factory;
use App\Http\Traits\TraitUpload;
use App\Libs\Tuling;
use EasyWeChat\Kernel\Messages\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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
     * Date: 2019/11/24
     * Time: 20:29
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        $this->config = config('wechat.official_account.default');
        $app = Factory::officialAccount($this->config);
        Log::info('微信相应');
        //$app = app('wechat.official_account');
        $app->server->push(function ($message) use ($app) {
            Log::info(json_encode($message));
            //return "欢迎关注 心若野马";
            switch ($message['MsgType']) {
                case 'event':
                    # 事件消息...
                    switch ($message->Event) {
                        case 'subscribe':
                            # code...
                            $res = Tuling::handle()->param('你好啊')->answer();
                            return $res['content'];
                            break;
                        case 'unsubscribe':
                            # code...
                            //取消关注
                            break;
                        case 'CLICK':
                            # code...
                            //点击自定义click菜单
                            switch ($message->EventKey) {
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
                    Log::info($message->Content);
                    $res = Tuling::handle()->param($message->Content)->answer();
                    switch ($res['resultType']) {
                        case 'text':
                            Log::info('返回内容:' . $res['content']);
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
                    $res = Tuling::handle()->param($message->PicUrl, 1)->answer();
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
