<!DOCTYPE html>
<!-- saved from url=(0041)https://www.choudalao.com/index/index.html -->
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baidu-site-verification" content="">
    <meta name="google-site-verification" content="">
    <title>@yield('title','Vijay`s Blog')</title>
    <meta name="keywords" content="@yield('keywords','Vijay`s Blog')">
    <meta name="description" content="@yield('description','Vijay`s Blog')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/home/base.css') }}" rel="stylesheet">
    <script src="{{ asset('js/home/hm.js') }}"></script>
    <link href="{{ asset('css/home/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/m.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{ asset('js/home/modernizr.js') }}"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('js/home/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/home/layer.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/home/layer.css') }}" id="layui_layer_skinlayercss" style="">
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
        var _hmt = _hmt || [];
        (function () {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();

        function addLink() {
            var selection = window.getSelection();
            pagelink = "<br /><br />作者：Vijay<br />链接： " + document.location.href + "<br />来源：Vijay个人博客<br />著作权归Vijay所有，任何形式的转载都请联系Vijay获得授权并注明出处。";
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

<body style="">
<header>
    <div class="tophead">
        <div class="logo">
            <a href="/" title="Vijay个人博客">
                <img src="/images/config/avatar.jpg"
                     style="width: 40px;height: 40px; border-radius: 20px;margin-right: 10px;" alt="Vijay个人博客"
                     title="Vijay个人博客">
            </a>
            <a href="/" title="Vijay个人博客">Vijay个人博客</a>
        </div>
        <div id="mnav">
            <h2><span class="navicon"></span></h2>
            <ul>
                <li><a href="/" title="首页">首页</a></li>
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <nav class="topnav" id="topnav">
            <ul>
                <li><a href="/" title="首页" id="topnav_current">首页</a></li>
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
<div style="width: 100%;height: 76px;"></div>
<article>
    <div class="pics">
        <ul>
            @foreach($top_article as $top)
                <li>
                    <i>
                        <a href="/article/{{$top->id}}" title="{{$top->title}}">
                            <img src="{{ $top->cover }}" alt="{{ $top->title }}" title="{{ $top->title }}">
                        </a>
                    </i>
                    <span>{{ $top->title }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="blank"></div>
    <div class="blogs">
        <ul>
            @foreach ($articles as $article)
                <li>
                <span class="blogpic">
                    <a href="/article/{{ $article->id }}" title="{{ $article->title }}">
                        <img src="{{ $article->cover }}" alt="{{ $article->title }}" title="{{ $article->title }}">
                    </a>
                </span>
                    <h3 class="blogtitle">
                        <a href="/article/{{ $article->id }}" title="{{ $article->title }}">{{ $article->title }}</a>
                    </h3>
                    <div class="bloginfo">
                        <p>{{ $article->description }}</p>
                    </div>
                    <div class="autor">
                        <span class="lm">
                            <a href="javascript:void(0);" title="{{ $article->keywords }}" class="classname">{{ $article->keywords }}</a>
                        </span>
                        <span class="dtime">{{ $article->created_at }}</span>
                        <span class="viewnum">浏览（<a href="javascript:void(0);">{{ $article->click }}</a>）</span>
                        <span class="readmore"><a href="/article/{{ $article->id }}" title="{{ $article->title }}">阅读原文</a></span>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="pagelist">
            <div>
                @if(!$articles->onFirstPage())
                    <a class="prev" href="{{ $articles->previousPageUrl() }}">上一页</a>
                @endif
                <span class="current">第{{ $articles->currentPage() }}页</span>
                @if($articles->lastPage() !== $articles->currentPage())
                    <a class="next" href="{{ $articles->nextPageUrl() }}">下一页</a>
                @endif
            </div>
        </div>
    </div>
    <div class="sidebar">
        <div class="about">
            <div class="avatar">
                <img src="/images/config/avatar.jpg" alt="Vijay" title="Vijay">
            </div>
            <p class="abname">Vijay</p>
            <p class="abposition">拍黄片(PHP)开发工程师</p>
            <p style="text-align: center;">
                <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email=1937832819@qq.com" rel="nofollow" target="_blank">1937832819@qq.com</a>
            </p>
            <div class="abtext">每一次经历，每一段时光都值得被记录，它们将会是你未来的财富。</div>
        </div>
        <div class="search">
            <form action="/" method="post">
                <input name="keyboard" class="input_text" value="" style="color: rgb(153, 153, 153);" placeholder="请输入搜索关键词" type="text">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input name="Submit" class="input_submit" value="搜索" type="submit">
            </form>
        </div>
        <div class="cloud">
            <h2 class="hometitle">标签云</h2>
            <ul>
                @foreach($tags as $tag)
                    <a href="/tag/{{ $tag->id }}">{{ $tag->name }}</a>
                @endforeach
            </ul>
        </div>
        <div class="paihang" style="margin-top:20px;">
            <h2 class="hometitle">热度榜</h2>
            <ul>
                @foreach($click_article as $click)
                <li>
                    <b>
                        <a href="/article/{{ $click->id }}" title="{{ $click->title }}">{{ $click->title }}</a>
                    </b>
                    <p>
                        <i>
                            <img src="{{ $click->cover }}" alt="{{ $click->title }}" title="{{ $click->title }}">
                        </i>
                        {{ $click->description }}
                    </p>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="links">
            <h2 class="hometitle">友情链接
                <a href="javascript:;" title="申请友链" class="add_links">申请友链</a>
            </h2>
            <p style="padding: 10px;word-wrap: break-word;word-break: normal;word-break:break-all;">
                本站信息如下：<br>
                网站名称：<a href="/" title="Vijay个人博客" target="_blank">Vijay</a> <br>
                网站链接:<a href="/" title="Vijay个人博客">https://www.choudalao.com</a><br>
                网站logo:<a href="https://www.choudalao.com/images/config/avatar.jpg" title="Vijay个人博客" target="_blank">https://www.choudalao.com/images/config/avatar.jpg</a><br>
                <b><span style="color: red;">注：</span>申请友链之前，请务必先将本站添置友链，Vijay收到后会立马处理，处理结果会以邮件形式通知您~</b>
            </p>
            <ul style="padding: 10px 20px;">
                @foreach($links as $link)
                    <li>
                        <a href="{{ $link->url }}" title="{{ $link->name }}" target="_blank">{{ $link->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <script>
            $('.add_links').click(function () {
                layer.open({
                    title: '友链提交',
                    content: '网站名称:<input type="text" name="title" class="layui-layer-input" placeholder="请输入网站名称" value=""></p ><p>网站链接<input type="text" name="link" class="layui-layer-input" placeholder="请输入以http或https开头的url" value=""></p >联系邮箱:<input type="text" name="email" class="layui-layer-input" placeholder="联系邮箱" value=""></p >',
                    yes: function () {
                        if ($("input[name='title']").val() != "" && $("input[name='link']").val() != "" && $("input[name='email']").val() != "") {
                            $.ajax({
                                type: "POST",
                                url: "/index/linkshandle.html",
                                data: {
                                    title: $("input[name='title']").val(),
                                    link: $("input[name='link']").val(),
                                    email: $("input[name='email']").val()
                                },
                                dataType: "json",
                                success: function (data) {
                                    if (data.status == 0) {
                                        layer.msg(data.msg, {icon: 1});
                                        window.location.reload();
                                    } else {
                                        layer.msg(data.msg, {icon: 0});
                                        return false;
                                    }
                                }
                            });
                        } else {
                            layer.msg('网站名称或网站链接以及联系邮箱不能为空', {icon: 0});
                        }
                    }
                });
            })
        </script>
        <div class="cloud" style="margin-top: 20px;">
            <h2 class="hometitle">小应用</h2>
            <ul>
                <a href="https://github.com/Galloping-Vijay/laravel-wjfcms" target="_blank">laravel-wjfcms</a>
                <a href="https://packagist.org/packages/galloping-vijay/php-tools" target="_blank">php-tools</a>
            </ul>
        </div>
        <link href="{{ asset('css/home/history_icon.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home/history_day.css') }}" rel="stylesheet">
        <div class="paihang">
            <h2 class="hometitle">历史上的今天</h2>
            <ul class="events" id="events">

            </ul>
        </div>
        <script>
            $(function () {
                getHistoryDay();

                function getHistoryDay() {
                    $('#events').append('<li>加载中，请稍候</li>');
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        data: {}, //传接收到的参数id
                        url: '/history',
                        success: function (data) {
                            var li = '';
                            var res = data['data'];
                            if (res.length > 0) {
                                for (var i = 0; i < res.length; i++) {
                                    if(i>4){
                                        break;
                                    }
                                    if (res[i].pic) {
                                        li += '<li>' +
                                            '<div class="icon">' +
                                            '<em class="cmn-icon wiki-home-eventsOnHistory-icons wiki-home-eventsOnHistory-icons_event"></em>' +
                                            '</div>'
                                            + '<div class="event">' +
                                            '<div class="event_tit-wrapper">' +
                                            '<div class="event_tit">' + res[i].year + '年 '+res[i].lunar+'<br />' + res[i].title + '</div>' +
                                            '</div>' +
                                            '<div class="event_cnt">'
                                            + '<img src="' + res[i].pic + '" width="80%"/></div> </div> </li>';
                                    } else {
                                        li += '<li><div class="icon">' +
                                            '<em class="cmn-icon wiki-home-eventsOnHistory-icons wiki-home-eventsOnHistory-icons_death"></em>' +
                                            '</div>'
                                            + '<div class="event">' +
                                            '<div class="event_tit-wrapper">' +
                                            '<div class="event_tit">' + res[i]['year'] + '年<br />' + res[i].title + '</div>' +
                                            '</div>' +
                                            '</div> ' +
                                            '</li>';
                                    }
                                }
                            } else {
                                li += '<li>网络异常，获取数据失败</li>';
                            }
                            $('#events').find('li').remove();
                            $('#events').append(li);
                        }
                    })
                }
            })
        </script>
    </div>
</article>
<footer>
    <p>Copyright © 2019 <a href="/" title="Vijay个人博客">Vijay个人博客</a>
        All rights reserved&nbsp;<a href="/" title="Vijay个人博客" rel="nofollow">蜀ICP备18031108号-1</a>
    </p>
    <p>所有文章未经授权禁止转载、摘编、复制或建立镜像，如有违反，追究法律责任。举报邮箱：<a
                href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email=1937832819@qq.com" rel="nofollow" target="_blank">1937832819@qq.com</a>
    </p>
    <a href="/">
        <div class="top_tag"></div>
    </a>
</footer>
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
            title: 'Vijay Blog', // 分享标题
            desc: '每一次经历，每一段时光都值得被记录，它们将会是你未来的财富。', // 分享描述
            link: 'https://www.choudalao.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
        }, function (res) {
        });
        wx.updateTimelineShareData({
            title: 'Vijay Blog', // 分享标题
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
                        <img src="/images/config/avatar.jpg" class="huiyuan-img" alt="Vijay个人博客" title="Vijay个人博客">
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
                                <p class="kfnum"><img src="/images/config/wx.jpg" alt="Vijay个人博客" title="Vijay个人博客"></p>
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
                        <li><img src="/images/config/weixin_pay.jpg" alt="Vijay个人博客" title="Vijay个人博客">微信收款码</li>
                        <li><img src="/images/config/ali_pay.jpg" alt="Vijay个人博客" title="Vijay个人博客">支付宝收款码</li>
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
</body>
</html>