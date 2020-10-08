<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    /**
     * Description:未授权跳转页
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/29
     * Time: 18:36
     * @param $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::with($account)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect('/register');
        }
    }

    /**
     * Description:OAuth 回调中获取用户信息
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/29
     * Time: 18:36
     * @param $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getSocialCallback($account)
    {
        $socialUser = Socialite::with($account)->stateless()->user();
        //获取名称
        $name = $socialUser->getName() == '' ? $socialUser->getNickname() : $socialUser->getName();
        $email = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
        // 在本地 users 表中查询该用户来判断是否已存在
        if ($email) {
            $user = User::where('email', $email)->first();
        } else if ($name) {
            $user = User::where('name', $name)->first();
        } else {
            $user = User::where('provider_id', '=', $socialUser->id)
                ->where('provider', '=', $account)
                ->first();
        }
        // 如果该用户不存在则将其保存到 users 表
        if ($user == null) {
            $user = new User();
            if ($name) {
                $user->name = $name;
            }
            if ($email) {
                $user->email = $email;
            }
            $user->avatar = $socialUser->getAvatar();
            $user->password = Hash::make('123456');
            $user->provider = $account;
            $user->provider_id = $socialUser->getId();
            $user->save();
        }
        // 手动登录该用户
        Auth::guard('web')->login($user);
        //登录成功后跳转登录前的那页
        return redirect()->back();
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2019/11/30
     * Time: 12:53
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect()->back();
    }
}
