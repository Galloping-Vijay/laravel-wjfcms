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
     * Time: 22:08
     */
    public function linkSubmit()
    {
        $urls = array(
            'https://www.choudalao.com/',
            'https://www.choudalao.com/chat',
            'https://www.choudalao.com/category/1',
            'https://www.choudalao.com/category/3',
            'https://www.choudalao.com/category/4',
            'https://www.choudalao.com/category/5',
            'https://www.choudalao.com/category/6',
            'https://www.choudalao.com/category/7',
            'https://www.choudalao.com/category/8',
            'https://www.choudalao.com/tag/1',
            'https://www.choudalao.com/tag/2',
            'https://www.choudalao.com/tag/3',
            'https://www.choudalao.com/tag/4',
            'https://www.choudalao.com/tag/5',
            'https://www.choudalao.com/tag/6',
            'https://www.choudalao.com/tag/7',
            'https://www.choudalao.com/tag/8',
        );
        $api = 'http://data.zz.baidu.com/urls?site=https://www.choudalao.com&token=nsdmyfRcySMSxYl1';
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }
}
