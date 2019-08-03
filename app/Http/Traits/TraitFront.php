<?php
/**
 * Description:前台操作
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/8/3
 * Time: 11:46
 */

namespace App\Http\Traits;


trait TraitFront
{
    /**
     * @param int $code 返回状态码
     * @param string $msg 返回信息
     * @param null $data 返回数据
     * @param array $additional 附加数据
     * @param array $header 头信息
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Description:返回json数据
     * User: Vijay
     * Date: 2019/5/26
     * Time: 16:41
     */
    protected function resJson($code = 0, $msg = '', $data = null, array $additional = [], array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        if (count($additional) > 0) {
            foreach ($additional as $key => $val) {
                $result[$key] = $val;
            }
        }
        $result['create_time'] = date('Y-m-d H:i:s', time());
        return response($result)->withHeaders($header);
    }
}