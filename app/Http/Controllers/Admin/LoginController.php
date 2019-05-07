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

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function index()
    {
        return view('admin.login.index');
    }

    public function showLoginForm()
    {
        return view('admin.login.index');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    // 退出后跳转页面
    protected function loggedOut(Request $request)
    {
        return redirect(route('admin.login.index'));
    }
}
