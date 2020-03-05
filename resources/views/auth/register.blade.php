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
                        <label class="layui-form-label">邮箱验证</label>
                        <div class="layui-col-xs4">
                            <input type="text" required lay-verify="required" placeholder="邮箱验证码" class="layui-input" name="email_code">
                        </div>
                        <div class="layui-col-xs4">
                            <div style="margin-left: 10px;">
                                <button type="button" class="layui-btn layui-btn-primary layui-btn-fluid" id="getEmailCode">获取验证码</button>
                            </div>
                        </div>
                        @if ($errors->has('email_code'))
                            <div class="layui-form-mid layui-word-aux">{{ $errors->first('email_code') }}</div>
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
                                 onclick="this.src='{{captcha_src()}}'+Math.random()" id="captcha_src_class">
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

@section('script')
    @parent
    <script>
        $(document).ready(function(){
            setTimeout( function(){
                document.getElementById('captcha_src_class').click();
            }, 3 * 1000 );

            var seconds = 60;
            var disabledClass = 'layui-disabled';
            function countdown(loop){
                seconds--;
                if(seconds < 0){
                    $("#getEmailCode").removeClass(disabledClass).html('获取验证码');
                    seconds = 60;
                    clearInterval(timer);
                } else {
                    $("#getEmailCode").addClass(disabledClass).html(seconds + '秒后重获');
                }
                if(!loop){
                    timer = setInterval(function(){
                        countdown(true);
                    }, 1000);
                }
            }
            $('#getEmailCode').click(function () {
                if(seconds != 60)  return false;
                var email =  $("input[name='email']").val();
                if(!email){
                    layer.msg('请输入正确的邮箱!');
                    return false;
                }
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {email:email,_token:"{{ csrf_token() }}"},
                    url: '/tools/getEmailCode',
                    success: function (res) {
                        if(res.code === 0){
                            layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                //成功开始倒计时
                                countdown(false);
                            });
                        }else{
                            layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 2
                                , time: 1000
                            }, function () {

                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection


