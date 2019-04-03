<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TestController extends Controller
{
    //
    public function index()
    {
//        Config::set('entrust.role','App\Models\Role');
//        Config::set('entrust.permission','App\Models\permission');
//        Config::set('entrust.role_user_table','App\Models\RoleUser');
        dd(Config::get('entrust'));
        $admin= Role::where('name','=','admin')->first();
        $user = User::where('name', '=', '魏花花')->first();

        //调用EntrustUserTrait提供的attachRole方法
        $user->attachRole($admin); // 参数可以是Role对象，数组或id

        // 或者也可以使用Eloquent原生的方法
        $user->roles()->attach($admin->id); //只需传递id即可
    }
}
