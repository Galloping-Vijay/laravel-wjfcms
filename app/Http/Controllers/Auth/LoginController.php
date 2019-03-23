<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

//    // 单位时间内最大登录尝试次数
//    protected $maxAttempts = 3;
//    // 单位时间值
//    protected $decayMinutes = 30;

    // 支持的登录字段
    protected $supportFields = ['name', 'email'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

//    /**
//     * Instructions:修改为name登录
//     * Author: vijay <1937832819@qq.com>
//     * @return string
//     */
//    public function username()
//    {
//        return 'name';
//    }
}
