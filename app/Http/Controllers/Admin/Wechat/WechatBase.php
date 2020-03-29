<?php
/**
 * Description:
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/03/05
 * Time: 17:31
 */

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class WechatBase extends Controller
{
    protected $app = null;

    public function __construct()
    {
//        if(is_null($this->app)){
//            $this->app =  app('wechat.official_account');
//        }
    }
}
