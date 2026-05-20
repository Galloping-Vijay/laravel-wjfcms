<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- SEO Meta Tags -->
    <title>@yield('title',\App\Models\SystemConfig::getConfigCache('seo_title'))</title>
    <meta name="keywords" content="@yield('keywords',\App\Models\SystemConfig::getConfigCache('site_seo_keywords'))">
    <meta name="description" content="@yield('description',\App\Models\SystemConfig::getConfigCache('site_seo_description'))">
    <meta name="author" content="{{ \App\Models\SystemConfig::getConfigCache('site_name') }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:title" content="@yield('title',\App\Models\SystemConfig::getConfigCache('seo_title'))">
    <meta property="og:description" content="@yield('description',\App\Models\SystemConfig::getConfigCache('site_seo_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ \App\Models\SystemConfig::getConfigCache('site_name') }}">
    @yield('og_image')

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title',\App\Models\SystemConfig::getConfigCache('seo_title'))">
    <meta name="twitter:description" content="@yield('description',\App\Models\SystemConfig::getConfigCache('site_seo_description'))">
    @yield('twitter_image')

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 百度平台验证 -->
    <meta name="baidu-site-verification" content="9jxVRatXIs" />
    <meta name="baidu_union_verify" content="285b65cf325abe072bde0437c133e008">
    <meta name="360-site-verification" content="6f7a678e74c316eb393d9fd80d103ca2" />
    <meta name="baidu-site-verification" content="">
    <meta name="google-site-verification" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/images/config/avatar.jpg">

    <!-- Stylesheets -->
    <link href="{{ asset('css/home/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/m.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/home/auth.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="{{ asset('js/home/modernizr.js') }}"></script>
    <![endif]-->

    <!-- JavaScript Libraries -->
    <script type="text/javascript" src="{{ asset('js/home/jquery-2.1.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/layui/css/layui.css') }}" media="all">
    <script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>

    <!-- Page Agent AI Assistant -->
    <script src="https://registry.npmmirror.com/page-agent/1.8.1/files/dist/iife/page-agent.demo.js" crossorigin="true"></script>

    @yield('header')

    <!-- 统计代码 -->
    <script>
        {!! htmlspecialchars_decode(\App\Models\SystemConfig::getConfigCache('site_tongji')) !!}
    </script>

    <!-- 谷歌广告 -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4281894096969033"
            crossorigin="anonymous"></script>

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ \App\Models\SystemConfig::getConfigCache('site_name') }}",
        "url": "{{ url('/') }}",
        "description": "{{ \App\Models\SystemConfig::getConfigCache('site_seo_description') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/search') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            font: 15px "PingFang SC", "Helvetica Neue", Helvetica, "Microsoft YaHei", Arial, sans-serif;
            transition: all 0.3s ease;
        }
        header {
            color: #333;
            position: fixed;
            top: 0;
            z-index: 1000;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        header.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,0.12);
        }
        .pics a img:hover {
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1.1);
        }
        .captcha{
            margin-bottom: 20px;
        }
        /* 页面进入动画 */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body id="body_app">
<header class="main-header">
    <div class="tophead">
        <div class="logo">
            <a href="/" title="臭大佬" class="logo-link" style="display:flex;align-items:center;white-space:nowrap;">
                <img src="/images/config/avatar.jpg" class="logo-avatar" alt="臭大佬" title="臭大佬" style="flex-shrink:0;">
                <div class="logo-text" style="display:inline!important;">臭大佬</div>
            </a>
        </div>

        <div id="mnav">
            <h2 class="mobile-menu-toggle">
                <span class="navicon"></span>
            </h2>
            <ul class="mobile-menu">
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
            <ul class="nav-menu">
                <li class="nav-item"><a href="/" title="臭大佬" class="nav-link" @if(request()->path() === '/') id="topnav_current" @endif >首页</a></li>
                @foreach($category_list as $ck=>$cv)
                    <li class="nav-item">
                        <a href="/category/{{ $cv['id'] }}" class="nav-link" @if((request()->path() === 'category/' . $cv['id']) || (isset($is_article) && $info->category_id === $cv['id'])) id="topnav_current" @endif title="{{ $cv['name'] }}">{{ $cv['name'] }}</a>
                    </li>
                @endforeach
                @foreach($nav_list as $vk=>$vv)
                    <li class="nav-item">
                        <a href="{{ $vv['url'] }}" class="nav-link" target="{{ $vv['target'] }}" @if(('/'.request()->path() === $vv["url"])) id="topnav_current" @endif title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
<div class="header-spacer"></div>
@yield('content')

@section('footer')
<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-info">
            <p class="copyright">
                Copyright © 2019-{{ date('Y') }}
                <a href="/" title="{{ \App\Models\SystemConfig::getConfigCache('site_name') }}">{{ \App\Models\SystemConfig::getConfigCache('site_name') }}</a>
                All rights reserved
            </p>
            <p class="icp-info">
                <a href="https://beian.miit.gov.cn/" target="_blank" title="工信部" rel="nofollow">{{ \App\Models\SystemConfig::getConfigCache('site_icp') }}</a>
            </p>
            <p class="copyright-notice">
                所有文章未经授权禁止转载、摘编、复制或建立镜像，如有违反，追究法律责任。<br>
                举报邮箱：<a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email={{ \App\Models\SystemConfig::getConfigCache('site_email') }}" rel="nofollow" target="_blank">{{ \App\Models\SystemConfig::getConfigCache('site_email') }}</a>
            </p>
        </div>
    </div>
    <a href="#" class="back-to-top" aria-label="返回顶部">
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

        // 现代化交互效果
        $(document).ready(function() {
            // 滚动时更新导航栏样式
            var header = $('.main-header');
            var lastScrollTop = 0;

            $(window).scroll(function() {
                var scrollTop = $(this).scrollTop();

                if (scrollTop > 50) {
                    header.addClass('scrolled');
                } else {
                    header.removeClass('scrolled');
                }

                lastScrollTop = scrollTop;
            });

            // 平滑滚动到顶部
            $('.back-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 600, 'swing');
                return false;
            });

            // 移动端菜单切换
            $('.mobile-menu-toggle').click(function() {
                $(this).toggleClass('open');
                $('.mobile-menu').slideToggle(300);
            });

            // 导航链接点击效果
            $('.nav-link').click(function() {
                if ($(window).width() <= 768) {
                    $('.mobile-menu').slideUp(300);
                    $('.mobile-menu-toggle').removeClass('open');
                }
            });

            // 为内容添加淡入效果
            $('.blogs li, .sidebar > div').addClass('fade-in-up');
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
