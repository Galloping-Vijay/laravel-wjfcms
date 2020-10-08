<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>wjfcms-laravel - @yield('title','后台管理系统')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- controller name -->
    <meta name="control_name" content="@yield('control_name','index')">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/admin.css') }}" media="all">
    <script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>
    @yield('header')
    <style>
        .footer {
            padding: 30px 0;
            line-height: 30px;
            text-align: center;
            color: #666;
            font-weight: 300;
        }

        .footer a {
            padding: 0 5px;
            color: initial;
            font-weight: 600;
        }
    </style>
</head>
<body>

@yield('content')

@section('footer')
    <div class="layui-footer footer footer-index">
        <div class="layui-main">
            <p>© {{ config('vijay.copyright') }} <a href="{{ config('vijay.website') }}"
                                                    target="_blank">{{ config('vijay.company') }} </a> 版权所有</p>
            <p>
                GitHub <a href="{{ config('vijay.github') }}" target="_blank">{{ config('vijay.object_name') }}</a>
            </p>
        </div>
    </div>
@show

@yield('script')

</body>
</html>

