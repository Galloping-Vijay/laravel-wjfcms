<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Nav;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
    protected $redirectTo = '/';

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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $navList = Nav::getMenuTree(Nav::orderBy('sort', 'asc')->get()->toArray());
        $categories = Category::getMenuTree(Category::orderBy('sort', 'asc')->select('id', 'name', 'pid')->get()->toArray());
        View::share([
            'nav_list' => $navList,
            'category_list' => $categories,
        ]);
        return view('auth.login');
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/30
     * Time: 17:53
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ],[
            'captcha.captcha' => trans('validation.captcha'),
        ]);
    }
}
