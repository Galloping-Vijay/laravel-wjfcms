<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vijay\Curl\Curl;

class ToolsController extends Controller
{
    /**
     * Description:
     * User: Vijay
     * Date: 2019/11/7
     * Time: 21:48
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkSubmit()
    {
        $curl = new Curl();
        $url = 'http://data.zz.baidu.com/urls?site=https://www.choudalao.com&token=nsdmyfRcySMSxYl1';
        $res = $curl->get($url);
        return response()->json($res);
    }
}
