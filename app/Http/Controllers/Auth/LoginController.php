<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Instructions:修改为name登录
     * Author: vijay <1937832819@qq.com>
     * @return string
     */
    /*public function username()
    {
        return 'name';
    }*/

    /**
     * Instructions:api和web登录都可以用了
     * Author: vijay <1937832819@qq.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (strpos($request->path(), 'api') !== false) {//api方式登录
            if ($this->attemptLogin($request)) {
                $user = $this->guard()->user();
                $user->generateToken();
                return response()->json([
                    'data' => $user->toArray(),
                ]);
            }
        } else {//传统登录方式
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }
            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }
            $this->incrementLoginAttempts($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Instructions:api和web模块都适用
     * Author: vijay <1937832819@qq.com>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        if (strpos($request->path(), 'api') !== false) {//api接口退出操作
            $user = Auth::guard('api')->user();
            if ($user) {
                $user->api_token = null;
                $user->save();
            }
            return response()->json(['data' => 'User logged out.'], 200);
        } else {
            $this->guard()->logout();
            $request->session()->invalidate();
            return $this->loggedOut($request) ?: redirect('/');
        }
    }
}
