<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/4/28
 * Time: 21:04
 */

namespace App\Http\Controllers\Admin\Baijiahao;

use App\Http\Controllers\Controller;

class BaijiahaoBase extends Controller
{
    /**
     * @var array
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/28
     * Time: 12:41
     */
    protected $config = [];

    const BLOG_URL = 'https://www.choudalao.com';

    /**
     * BaijiahaoBase constructor.
     */
    public function __construct()
    {
        $this->getConfig();
    }

    /**
     * Description:设置参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/28
     * Time: 12:45
     * @return $this
     */
    private function getConfig()
    {
        $this->config = [
            'app_id'          => env('BAIJIAHAO_APP_ID'),
            'app_token'       => env('BAIJIAHAO_APP_TOKEN'),
            'you_token'       => env('BAIJIAHAO_APP_YOU_TOKEN'),
            'encoding_AESKey' => env('BAIJIAHAO_APP_Encoding_AESKe'),
        ];

        return $this;
    }
}