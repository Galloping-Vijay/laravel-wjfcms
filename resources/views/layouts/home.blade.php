<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    {{--百度平台--}}
    <meta name="baidu-site-verification" content="9jxVRatXIs" />
    <meta name="baidu_union_verify" content="285b65cf325abe072bde0437c133e008">
    <meta name="360-site-verification" content="6f7a678e74c316eb393d9fd80d103ca2" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baidu-site-verification" content="">
    <meta name="google-site-verification" content="">
    <title>@yield('title',\App\Models\SystemConfig::getConfigCache('seo_title'))</title>
    <meta name="keywords" content="@yield('keywords',\App\Models\SystemConfig::getConfigCache('site_seo_keywords'))">
    <meta name="description" content="@yield('description',\App\Models\SystemConfig::getConfigCache('site_seo_description'))">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/home/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/m.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/home/auth.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{ asset('js/home/modernizr.js') }}"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('js/home/jquery-2.1.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/layui/css/layui.css') }}" media="all">
    <script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>
    @yield('header')
    <script>
        //统计代码
        {!! htmlspecialchars_decode(\App\Models\SystemConfig::getConfigCache('site_tongji')) !!}
    </script>
    {{--谷歌广告--}}
    <script data-ad-client="ca-pub-4281894096969033" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <style>
        header {
            color: #FFF;
            position: fixed;
            top: 0;
            z-index: 100;
        }
        .pics a img:hover {
            transition: all 1s;
            transform: scale(1.2)
        }
        .captcha{
            margin-bottom: 20px;
        }
    </style>
</head>

<body style="" id="body_app">
<header>
    <div class="tophead">
        <h1>
        <div class="logo">
            <a href="/" title="臭大佬">
                <img src="/images/config/avatar.jpg"
                     style="width: 40px;height: 40px; border-radius: 20px;margin-right: 10px;" alt="臭大佬"
                     title="臭大佬">
            </a>
           <a href="/" title="臭大佬">臭大佬</a>
        </div>
        </h1>
        <div id="mnav">
            <h2><span class="navicon"></span></h2>
            <ul>
                <li><a href="/" title="臭大佬">首页</a></li>
                @foreach($category_list as $ck=>$cv)
                    <li>
                        <a href="/category/{{ $cv['id'] }}" title="{{ $cv['name'] }}">{{ $cv['name'] }}</a>
                    </li>
                @endforeach
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <nav class="topnav" id="topnav">
            <ul>
                <li><a href="/" title="臭大佬" class="pc_home" @if(request()->path() === '/') id="topnav_current" @endif >首页</a></li>
                @foreach($category_list as $ck=>$cv)
                    <li>
                        <a href="/category/{{ $cv['id'] }}" @if((request()->path() === 'category/' . $cv['id']) || (isset($is_article) && $info->category_id === $cv['id'])) id="topnav_current" @endif title="{{ $cv['name'] }}">{{ $cv['name'] }}</a>
                    </li>
                @endforeach
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" target="{{ $vv['target'] }}" @if(('/'.request()->path() === $vv["url"])) id="topnav_current" @endif title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
@yield('content')

@section('footer')
<footer>
    <p>Copyright © 2019 <a href="/" title="{{ \App\Models\SystemConfig::getConfigCache('site_name') }}">{{ \App\Models\SystemConfig::getConfigCache('site_name') }}</a>
        All rights reserved&nbsp;<a href="http://www.beian.miit.gov.cn" target="_blank"  title="工信部" rel="nofollow">{{ \App\Models\SystemConfig::getConfigCache('site_icp') }}</a>
    </p>
    <p>所有文章未经授权禁止转载、摘编、复制或建立镜像，如有违反，追究法律责任。举报邮箱：<a
                href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email={{ \App\Models\SystemConfig::getConfigCache('site_email') }}" rel="nofollow" target="_blank">{{ \App\Models\SystemConfig::getConfigCache('site_email') }}</a>
    </p>
    <a href="#">
        <div class="top_tag"></div>
    </a>
</footer>
@show

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).use([ 'table', 'form'], function () {
            layer = layui.layer;
        });
    </script>
    <script src="{{ asset('js/home/jweixin-1.4.0.js') }}"></script>
    <script src="{{ asset('js/home/hm.js') }}"></script>
    <script src="{{ asset('js/home/jweixin-1.4.0.js') }}"></script>
    <script src="{{ asset('js/home/wx.js') }}"></script>
    @component('./layouts/home/toolbar')
    @endcomponent
@show
</body>
</html>
