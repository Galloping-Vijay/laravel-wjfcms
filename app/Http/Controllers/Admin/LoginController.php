<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    // 支持的登录字段
    protected $supportFields = ['name', 'email'];

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Instructions:
     * Author: vijay <1937832819@qq.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Instructions:
     * Author: vijay <1937832819@qq.com>
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Instructions:退出后跳转页面
     * Author: vijay <1937832819@qq.com>
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function loggedOut(Request $request)
    {
        return redirect(route('admin.login'));
    }
}
