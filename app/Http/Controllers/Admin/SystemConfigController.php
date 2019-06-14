<?php

namespace App\Http\Controllers\admin;

use App\Models\SystemConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemConfigController extends Controller
{

    //基本设置
    public function basal(Request $request)
    {
        if ($request->isMethod('post')) {

        }
        //搞一个权限中间件
        $list = SystemConfig::where([
            ['status', '=', '1'],
            ['config_type', '=', '0']
        ])->get();
        return $list;
    }
}
