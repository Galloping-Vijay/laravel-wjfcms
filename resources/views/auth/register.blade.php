@extends('layouts.home')
@section('title', '注册 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('header')
    <link href="{{ asset('/css/home/easyForm.css') }}" rel="stylesheet">
    <style>
        .error-msg {
            color: red;
        }

        .blogsbox {
            width: 100%;
        }

        #img-vali-code {
            margin-left: 18px;
            height: 25px;
        }

        .vali-code img {
            vertical-align: middle;
            display: inline-block;
            border-radius: 8px;
        }

        .vali-code input {
            /* display: none;*/
            display: inline-block;
            width: 40%;
        }

        .blogsbox {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <article>
        <div style="width: 100%;height: 76px;"></div>
        <div class="blogsbox">
            <div id="content">
                <div class="login-header">
                    注册
                </div>
                <div class="login-input-box">
                    <input type="text" name="name" id="name" placeholder="昵称">
                </div>
                <div class="login-input-box">
                    <input type="email" name="email" id="email" placeholder="邮箱账号">
                </div>
                <div class="login-input-box">
                    <input type="password" name="password" id="password" autocomplete="new-password" placeholder="密码">
                </div>
                <div class="login-input-box">
                    <input type="password" id="password_confirmation" placeholder="确认密码">
                </div>

                <div class="login-input-box vali-code">
                    <img src="{{captcha_src()}}" style="cursor: pointer"
                         onclick="this.src='{{captcha_src()}}'+Math.random()">
                    <input type="text" id="code" placeholder="请输入验证码">
                </div>
                <div class="remember-box">
                    <p class="error-msg"></p>
                </div>
                <div class="login-button-box">
                    <button type="button" class="btn-login">开始注册</button>
                </div>
                <div class="logon-box">
                    <a href="/login">去登陆</a>
                </div>
                <div class="auth-title">—— 社交账号登入 ——</div>
                <div class="auth-apps">
                    <a href="/auth/github" data-type="github">
                        <img src="/images/home/app_logo/github.png">
                    </a>
                    <a href="/auth/qq" data-type="qq">
                        <img src="/images/home/app_logo/qq.png">
                    </a>
                    <a href="/auth/weibo" data-type="weibo">
                        <img src="/images/home/app_logo/weibo.png">
                    </a>
                </div>
            </div>
        </div>
    </article>
@endsection
