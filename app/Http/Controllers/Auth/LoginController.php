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
     * User: Vijay
     * Date: 2019/12/7
     * Time: 17:15
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return collect(['name', 'email', 'tel'])->contains(function ($value) use ($request) {
            $account = $request->get('account');
            $password = $request->get('password');
            return $this->guard()->attempt([$value => $account, 'password' => $password], $request->filled('remember'));
        });
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/7
     * Time: 17:18
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => ':attribute 不能为空',
            'captcha.captcha' => '请输入正确的 :attribute',
        ], [
            $this->username() => '账号',
            'captcha' => '验证码',
        ]);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/12/7
     * Time: 17:18
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/11/30
     * Time: 19:16
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajaxLogin(Request $request)
    {
        try {
            $this->validateLogin($request);
        } catch (\Exception $e) {
            $data = [
                'code' => 1,
                'msg' => '登录失败,请检查账号密码',
                'data' => []
            ];
            return response($data);
        }

        /* If the class is using the ThrottlesLogins trait, we can automatically throttle
         the login attempts for this application. We'll key this by the username and
         the IP address of the client making these requests into this application.*/
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $data = [
            'code' => 1,
            'msg' => '登录失败',
            'data' => []
        ];
        if ($this->attemptLogin($request)) {
            $data = [
                'code' => 0,
                'msg' => '登录成功',
                'data' => []
            ];

        }
        return response($data);
    }
}
