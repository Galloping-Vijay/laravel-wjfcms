<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登入 - {{ config('vijay.app_name') }}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/admin.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/login.css') }}" media="all">
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>{{ config('vijay.app_name') }}</h2>
            <p>{{ config('vijay.app_Introduction') }}</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            @csrf
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input type="text" name="account" id="LAY-user-login-username" lay-verify="required" placeholder="账号"
                       class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                       placeholder="密码" class="layui-input">
            </div>
            {{--<div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                        <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="https://www.oschina.net/action/user/captcha" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
                        </div>
                    </div>
                </div>
            </div>--}}
            {{-- <div class="layui-form-item" style="margin-bottom: 20px;">
                 <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
                 <a href="forget.html" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
             </div>--}}
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
            </div>
        </div>
    </div>
    <div class="layui-trans layadmin-user-login-footer">

        <p>© 2017~2020 <a href="https://www.choudalao.com" target="_blank">{{ config('vijay.company') }}</a> 版权所有</p>
        <p>
            <span><a href="{{ config('vijay.document') }}" target="_blank">开发文档</a></span>
            <span><a href="{{ config('vijay.base_url') }}" target="_blank">臭大佬首页</a></span>
            <span><a href="{{ config('vijay.github') }}" target="_blank">Github</a></span>
        </p>
    </div>
</div>

<script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>
<script>
    layui.config({
        base: "/static/layuiadmin/" //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user'], function () {
        var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            , form = layui.form
            , router = layui.router()
            , search = router.search;

        //过期跳转
        if (top != self) {
            top.location.href = '/admin/login';
        }

        form.render();

        //提交
        form.on('submit(LAY-user-login-submit)', function (obj) {

            //请求登入接口
            admin.req({
                url: "{{ route('admin.login') }}"
                , method: 'POST'
                , data: obj.field
                , done: function (res) {
                    //请求成功后，写入 access_token
                    layui.data(setter.tableName, {
                        key: setter.request.tokenName
                        , value: res.data.access_token
                    });
                    if(res.code === 0){
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        }, function () {
                            location.href = '/admin/index/index'; //后台主页
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
</body>
</html>
