<?php
/**
 * Description:
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/04/10
 * Time: 14:02
 */

namespace App\Libs\api\driver;

use App\Libs\api\Driver;
use Vijay\Curl\Curl;

class Tuling extends Driver
{

    /**
     * Tuling constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:26
     * @return mixed|void
     */
    public function init()
    {
        // TODO: Implement init() method.
        $this->api_key = env('TULING_API_KEY');
        $this->app_url = env('TULING_API_URL');
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:04
     * @return mixed|void
     */
    public static function handle()
    {
        if (is_null(self::$handle)) {
            self::$handle = new self();
        }
        return self::$handle;
    }

    /**
     * Description:设置参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:13
     * @param null $str
     * @param int $reqType
     * @param int $uid
     * @return $this|bool
     */
    public function param($str = null, $reqType = 0, $uid = 12345)
    {
        if (is_null($str)) {
            $this->setError('参数错误');
            return false;
        }
        //如果是url或者微信表情包,强制回复图片
        if (preg_match('/(http:|https:)\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $str) || $str == "【收到不支持的消息类型，暂无法显示】") {
            $reqType = 1;
        }

        if ($reqType == 0) {
            $perception = [
                'inputText' => [
                    'text' => $str
                ]];
        } elseif ($reqType == 1) {
            $perception = [
                'inputImage' => [
                    'url' => $str
                ]
            ];
        } elseif ($reqType == 2) {
            $perception = [
                'inputMedia' => [
                    'url' => $str
                ]
            ];
        } else {
            $this->setError('参数错误');
            return false;
        }
        $this->param = [
            'reqType'    => $reqType,
            'perception' => $perception,
            'userInfo'   => [
                'userId' => $uid,
                'apiKey' => $this->api_key
            ]
        ];
        return $this;
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:12
     * @return array|mixed
     */
    public function answer()
    {
        // TODO: Implement answer() method.
        $curl = new Curl();
        $data = $curl->post($this->app_url, json_encode($this->param));
        if (!isset($data) || empty($data)) {
            $this->setError('获取失败');
            $res = [
                'resultType' => 'text',
                'content'    => '亲，换个方式问我吧'
            ];
            return $res;
        }

        $data = json_decode($data, true);
        if (!isset($data['results'])) {
            $res = [
                'resultType' => 'text',
                'content'    => '亲，换个方式问我吧'
            ];
            return $res;
        }
        $type = $data['results'][0]['resultType'];
        switch ($type) {
            case'text':
                $text = $data['results'][0]['values']['text'];
                if (iconv_strlen($text) > 500) {
                    $text = mb_substr($data['results'][0]['values']['text'], 0, 500) . '......';
                }
                $res = [
                    'resultType' => 'text',
                    'content'    => $text
                ];
                break;
            case'image':
                $res = [
                    'resultType' => 'image',
                    'content'    => $data['results'][0]['values']['image']
                ];
                break;
            case'url':
                $res = [
                    'resultType' => 'text',
                    'content'    => $data['results'][0]['values']['url']
                ];
                break;
            default:
                $res = [
                    'resultType' => 'text',
                    'content'    => '亲，换个方式问我吧'
                ];
                break;
        }
        return $res;
    }

}
