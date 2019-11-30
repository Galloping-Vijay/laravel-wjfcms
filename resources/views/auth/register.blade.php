@extends('layouts.home')
@section('title', '注册 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('header')
    <style>
        .blogsbox{width: 100%;}
        .blogsbox{text-align:center;}
        .btn-login{cursor: pointer;}
    </style>
@endsection
@section('content')
    <article>
        <div style="width: 100%;height: 76px;"></div>
        <div class="blogsbox">
            <div id="content">
                <form class="layui-form layui-form-pane" method="post" action="/register">
                    <div class="login-header">
                        注册
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">昵称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" required lay-verify="required" placeholder="请输入昵称" value="{{ old('name') }}" autocomplete="off" class="layui-input">
                        </div>
                        @if ($errors->has('name'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱账号</label>
                        <div class="layui-input-block">
                            <input type="email" value="{{ old('email') }}" name="email" required lay-verify="email" placeholder="请输入邮箱账号"
                                   autocomplete="off" class="layui-input">
                        </div>
                        @if ($errors->has('email'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                                   autocomplete="off" class="layui-input">
                        </div>
                        @if ($errors->has('password'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password_confirmation" required lay-verify="required"  placeholder="请输入确认密码" autocomplete="off" class="layui-input">
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">验证码</label>
                        <div class="layui-input-inline" style="width: 100px;">
                            <img src="{{captcha_src()}}" style="cursor: pointer"
                                 onclick="this.src='{{captcha_src()}}'+Math.random()">
                        </div>
                        <div class="layui-input-inline" style="width: 192px;">
                            <input type="text" required lay-verify="required" placeholder="请输入验证码" class="layui-input" name="captcha">
                        </div>
                        @if ($errors->has('captcha'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('captcha') }}</div>
                        @endif
                    </div>
                    <div class="login-button-box">
                        <button type="submit" class="btn-login">开始注册</button>
                    </div>
                </form>
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

