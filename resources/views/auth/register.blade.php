@extends('layouts.home')
@section('header')
    <style>
        .error-msg{
            color: red;
        }
        .blogsbox{
            width: 100%;
        }
        #img-vali-code{
            margin-left: 18px;
            height: 25px;
        }
        .vali-code{
            display: none;
        }
    </style>
@endsection
@section('content')
    <article>
        <div style="width: 100%;height: 76px;"></div>
        <div class="blogsbox">
            <div id="content">
                <div class="login-header">
                    欢迎登录
                </div>
                <div class="login-input-box">
                    <input type="email" id="email" placeholder="请输入邮箱账号">
                </div>
                <div class="login-input-box">
                    <input type="password" id="password" placeholder="请输入密码">
                </div>

                <div class="login-input-box  vali-code">
                    <input type="text" id="code" placeholder="请输入验证码">
                </div>
                <div class="remember-box">
                    <p class="error-msg"></p>
                </div>
                <div class="login-button-box">
                    <button type="button" class="btn-login">开始登录</button>
                </div>
                <div class="logon-box">
                    <a href="https://www.link-nemo.com/forget">忘记密码?</a>
                    <a href="https://www.link-nemo.com/register">注册</a>
                </div>
            </div>
        </div>
    </article>
@endsection
