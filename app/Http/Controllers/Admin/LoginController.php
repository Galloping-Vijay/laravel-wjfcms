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

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $data = [
            'code' => 0,
            'msg' => '操作成功',
            'data' => []
        ];

        return response($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     * @throws \Illuminate\Validation\ValidationException
     * Description:
     * User: Vijay
     * Date: 2019/5/11
     * Time: 21:26
     */
    public function ajaxLogin(Request $request)
    {
        $this->validateLogin($request);

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

    // 退出后跳转页面
    protected function loggedOut(Request $request)
    {
        return redirect(route('admin.login'));
    }
}
