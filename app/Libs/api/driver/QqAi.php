<?php
/**
 * Description:
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/04/10
 * Time: 14:28
 */

namespace App\Libs\api\driver;

use App\Libs\api\Driver;
use Vijay\Curl\Curl;

class QqAi extends Driver
{
    /**
     * QqAi constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Description:获取操作句柄
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:01
     * @return mixed
     */
    public static function handle()
    {
        if (is_null(self::$handle)) {
            self::$handle = new self();
        }
        return self::$handle;
    }

    /**
     * Description:初始化操作
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:13
     * @return mixed
     */
    public function init()
    {
        // TODO: Implement init() method.
        $this->api_appid = env('QQ_AI_APPID');
        $this->api_key   = env('QQ_AI_APPKEY');
        $this->app_url   = env('QQ_AI_URL');
    }

    /**
     * Description:设置签名
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:59
     * @return string
     */
    private function getReqSign()
    {
        // 1. 字典升序排序
        ksort($this->param);
        // 2. 拼按URL键值对
        $str = '';
        foreach ($this->param as $key => $value) {
            if ($value !== '') {
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }
        // 3. 拼接app_key
        $str .= 'app_key=' . $this->api_key;
        // 4. MD5运算+转换大写，得到请求签名
        $sign = strtoupper(md5($str));
        return $sign;
    }

    /**
     * Description:设置参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:59
     * @param null $str
     * @return mixed
     */
    public function param($str = null)
    {
        if (is_null($str)) {
            $this->setError('参数错误');
            return false;
        }
        $this->param         = [
            'app_id'     => $this->api_appid,
            'time_stamp' => strval(time()),
            'nonce_str'  => strval(rand()),
            'question'   => $str,
            'session'    => $str,
            'sign'       => '',
        ];
        $this->param['sign'] = $this->getReqSign();
        return $this;
    }

    /**
     * Description:通用回复接口
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:11
     * @return mixed
     */
    public function answer()
    {
        $res  = [
            'resultType' => 'text',
            'content'    => '亲，换个方式问我吧'
        ];
        $curl = new Curl();
        $data = $curl->post($this->app_url, $this->param);
        if (!isset($data) || empty($data)) {
            $this->setError('获取失败');
            return $res;
        }
        $dataArr = json_decode($data, true);
        if ($dataArr['msg'] == 'ok' && isset($dataArr['data']['answer'])) {
            $res['content'] = $dataArr['data']['answer'];
        } else {
            //失败就调用图灵的接口
            $tuling = new Tuling();
            $res    = $tuling->param($this->param['question'])->answer();
        }
        return $res;
    }
}
