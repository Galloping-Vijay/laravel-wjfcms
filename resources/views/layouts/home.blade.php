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
    <script src="{{ asset('js/home/hm.js') }}"></script>
    <link href="{{ asset('css/home/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/m.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/home/auth.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{ asset('js/home/modernizr.js') }}"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('js/home/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/home/layer.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/home/layer.css') }}" id="layui_layer_skinlayercss" style="">
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?42bfb0edc8942ae12e19e306f81c22a6";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    @yield('header')
    <script>
        window.onload = function () {
            var oH2 = document.getElementsByTagName("h2")[0];
            var oUl = document.getElementsByTagName("ul")[0];
            oH2.onclick = function () {
                var style = oUl.style;
                style.display = style.display == "block" ? "none" : "block";
                oH2.className = style.display == "block" ? "open" : ""
            }
        }
    </script>
    <script>
        function addLink() {
            var selection = window.getSelection();
            pagelink = "<br /><br />作者：Vijay<br />链接： " + document.location.href + "<br />来源：臭大佬<br />著作权归Vijay所有，任何形式的转载都请联系Vijay(1937832819@qq.com)获得授权并注明出处。";
            copytext = selection + pagelink;
            newdiv = document.createElement('div');
            newdiv.style.position = 'absolute';
            newdiv.style.left = '-99999px';
            document.body.appendChild(newdiv);
            newdiv.innerHTML = copytext;
            selection.selectAllChildren(newdiv);
            window.setTimeout(function () {
                document.body.removeChild(newdiv);
            }, 100);
        }
        document.oncopy = addLink;
    </script>
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
    </style>
</head>

<body style="" id="body_app">
<header>
    <div class="tophead">
        <div class="logo">
            <a href="/" title="臭大佬">
                <img src="/images/config/avatar.jpg"
                     style="width: 40px;height: 40px; border-radius: 20px;margin-right: 10px;" alt="臭大佬"
                     title="臭大佬">
            </a>
            <a href="/" title="臭大佬">臭大佬</a>
        </div>
        <div id="mnav">
            <h2><span class="navicon"></span></h2>
            <ul>
                <li><a href="/" title="首页">首页</a></li>
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
                <li><a href="/" title="首页" @if(request()->path() == '/') id="topnav_current" @endif >首页</a></li>
                @foreach($category_list as $ck=>$cv)
                    <li>
                        <a href="/category/{{ $cv['id'] }}" @if((request()->path() === 'category/' . $cv['id'])) id="topnav_current" @endif title="{{ $cv['name'] }}">{{ $cv['name'] }}</a>
                    </li>
                @endforeach
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" target="{{ $vv['target'] }}" @if((request()->path() === $vv["url"])) id="topnav_current" @endif title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
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
        All rights reserved&nbsp;<a href="#" title="臭大佬" rel="nofollow">{{ \App\Models\SystemConfig::getConfigCache('site_icp') }}</a>
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
    <script src="{{ asset('js/home/nav.js') }}"></script>
    <script src="{{ asset('js/home/jweixin-1.4.0.js') }}"></script>
    <script>
        wx.config({
            debug: false,
            appId: 'wxc2bb9e1e5c2b5171',
            timestamp: '1564574475',
            nonceStr: 'mLzpdFKstdapbWjq',
            signature: '5116f0b0b25c4730b6ac968c1e4b682976432e03',
            jsApiList: [
                'checkJsApi',
                'updateAppMessageShareData',
                'updateTimelineShareData'
            ]
        });
        wx.ready(function () {
            // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
            wx.checkJsApi({
                jsApiList: [
                    'updateAppMessageShareData',
                    'updateTimelineShareData'
                ],
                success: function (res) {
                }
            });
            // 2. 分享接口
            wx.updateAppMessageShareData({
                title: '臭大佬', // 分享标题
                desc: '每一次经历，每一段时光都值得被记录，它们将会是你未来的财富。', // 分享描述
                link: 'https://www.choudalao.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
            }, function (res) {
            });
            wx.updateTimelineShareData({
                title: '臭大佬', // 分享标题
                link: 'https://www.choudalao.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
            }, function (res) {
            });
        })
    </script>

    <div class="toolbar-open"></div>
    <div class="toolbar">
        <div class="toolbar-close">
            <span id="guanbi"></span>
        </div>
        <div class="toolbar-nav">
            <ul id="toolbar-menu">
                <li>
                    <i class="side-icon-user"></i>
                    <section>
                        <div class="login_herder">
                            <img src="/images/config/avatar.jpg" class="huiyuan-img" alt="臭大佬" title="臭大佬">
                            <span>登录</span>
                        </div>
                        <div class="userinfo">
                            <form name="login" method="post" action="/login">
                                <input name="username" type="text" class="inputText" size="16" placeholder="用户名">
                                <input name="password" type="password" class="inputText" size="16" placeholder="密码">
                                <input type="submit" value="登陆" class="inputsub-dl">
                                <a href="/register" class="inputsub-zc">注册</a>
                            </form>
                        </div>
                    </section>
                </li>
                <li>
                    <i class="side-icon-qq"></i>
                    <section class="qq-section">
                        <div class="qqinfo">
                            <a target="_blank"
                               href="http://wpa.qq.com/msgrd?v=3&amp;uin=1937832819&amp;site=qq&amp;menu=yes">站长QQ</a>
                        </div>
                    </section>
                </li>
                <li>
                    <i class="side-icon-weixin"></i>
                    <section class="weixin-section">
                        <div class="weixin-info">
                            <div class="kf">
                                <ul class="kfdh">
                                    <p class="kftext">微信</p>
                                    <p class="kfnum"><img src="/images/config/wx.jpg" alt="臭大佬" title="臭大佬"></p>
                                </ul>
                            </div>
                        </div>
                    </section>
                </li>
                <li>
                    <i class="side-icon-dashang"></i>
                    <section class="dashang-section">
                        <p>如果你觉得本站很棒，可以通过扫码支付打赏哦！</p>
                        <ul>
                            <li><img src="/images/config/weixin_pay.jpg" alt="臭大佬" title="臭大佬">微信收款码</li>
                            <li><img src="/images/config/ali_pay.jpg" alt="臭大佬" title="臭大佬">支付宝收款码</li>
                        </ul>
                    </section>
                </li>
            </ul>
        </div>
    </div>
    <script>
        //toolbar
        $("#guanbi").click(function () {
            $(".toolbar").addClass("guanbi");
            $(".toolbar-open").addClass("openviewd");
            $("#toolbar-menu li").removeClass("current");
        });
        $(".toolbar-open").click(function () {
            $(".toolbar-open").removeClass("openviewd");
            $(".toolbar").removeClass("guanbi");
        });
        //toolbar-menu
        $('#toolbar-menu li').click(function () {
            $(this).addClass('current').siblings().removeClass('current');
        });
    </script>
    <script>
        //自动推送功能
        (function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
@show
</body>
</html>
