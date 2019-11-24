<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/11/24
 * Time: 19:21
 */

namespace App\Libs;


use Vijay\Curl\Curl;

class Tuling
{
    //官方文档
    //http://doc.tuling123.com/openapi2/263611
    protected $param = [];
    /**
     * 实例
     * @var null
     */
    protected static $handle = null;
    /**
     * 错误信息
     * @var string
     */
    protected $error = '';

    /**
     * @var null
     */
    private $api_key = null;

    /**
     * @var null
     */
    private $app_url = null;


    /**
     * Tuling constructor.
     */
    public function __construct()
    {
        $this->api_key = env('TULING_API_KEY');
        $this->api_url = env('TULING_API_URL');
    }


    /**
     * 获取操作句柄
     * @return null|Tuling
     */
    public static function handle()
    {
        if (is_null(self::$handle)) {
            self::$handle = new self();
        }
        return self::$handle;
    }

    /**
     * 设置参数
     * Author: vijay <1937832819@qq.com>
     * @param null $str 回复内容
     * @param int $reqType 0为文本 1为图片
     * @param int $uid 用户id
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
            'reqType' => $reqType,
            'perception' => $perception,
            'userInfo' => [
                'userId' => $uid,
                'apiKey' => $this->api_key
            ]
        ];
        return $this;
    }

    /**
     * 获取最后一次上传错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误
     * @param $error
     * @return $this
     */
    protected function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * 回复内容
     * @return string
     */
    public function reply()
    {
        $curl = new Curl();
        $data = $curl->post($this->app_url, json_encode($this->param));
        if ($data) {
            return json_decode($data, true);
        } else {
            $this->setError('获取失败');
            return '亲，换个方式问我吧';
        }
    }

    /**
     *通用接口
     * @return array
     */
    public function answer()
    {
        $curl = new Curl();
        $data = $curl->post($this->app_url, json_encode($this->param));
        if (!isset($data) || empty($data)) {
            $this->setError('获取失败');
            $res = [
                'resultType' => 'text',
                'content' => '亲，换个方式问我吧'
            ];
            return $res;
        }

        $data = json_decode($data, true);
        if (!isset($data['results'])) {
            $res = [
                'resultType' => 'text',
                'content' => '亲，换个方式问我吧'
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
                    'content' => $text
                ];
                break;
            case'image':
                $res = [
                    'resultType' => 'image',
                    'content' => $data['results'][0]['values']['image']
                ];
                break;
            case'url':
                $res = [
                    'resultType' => 'text',
                    'content' => $data['results'][0]['values']['url']
                ];
                break;
            default:
                $res = [
                    'resultType' => 'text',
                    'content' => '亲，换个方式问我吧'
                ];
                break;
        }
        return $res;
    }
}