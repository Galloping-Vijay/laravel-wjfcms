<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="@yield('keywords','Vijay`s Blog')">
    <meta name="description" content="@yield('description','Vijay`s Blog')">
    <title>@yield('title','Vijay`s Blog')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('css/home/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/m.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home/layer.css') }}" id="layui_layer_skinlayercss" style="">

    {{--历史上的今天--}}
    <link href="{{ asset('css/home/history_icon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/history_day.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('js/home/hm.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/home/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/home/layer.js') }}"></script>

    <!-- Fonts -->

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
    </style>
</head>
<body style="">
<header>
    <div class="tophead">
        <div class="logo">
            <a href="/" title="">
                <img src="/images/config/avatar.jpg" style="width: 40px; border-radius: 20px;margin-right: 10px;"
                     alt="Vijay个人博客" title="Vijay个人博客">
            </a>
            <a href="/" title="Vijay个人博客">Vijay个人博客</a>
        </div>
        <div id="mnav">
            <h2><span class="navicon"></span></h2>
            <ul>
                <li><a href="/" title="首页">首页</a></li>
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" target="{{ $vv['target'] }}"
                           title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <nav class="topnav" id="topnav">
            <ul>
                <li><a href="/" title="首页" id="topnav_current">首页</a></li>
                @foreach($nav_list as $vk=>$vv)
                    <li>
                        <a href="{{ $vv['url'] }}" target="{{ $vv['target'] }}"
                           title="{{ $vv['name'] }}">{{ $vv['name'] }}</a>
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
            @foreach($top_article as $item)
                <li>
                    <i>
                        <a href="/article/{{$item->id}}" title="{{$item->title}}">
                            <img src="{{ $item->cover }}" alt="{{$item->title}}" title="{{$item->title}}">
                        </a>
                    </i>
                    <span>{{ $item->title }}</span>
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
                    <a href="/article/{{$article->id}}" title="{{ $article->title }}">
                        <img src="{{ $article->cover }}" alt="{{ $article->title }}" title="{{ $article->title }}">
                    </a>
                </span>
                    <h3 class="blogtitle">
                        <a href="/article/{{$article->id}}"
                           title="{{ $article->title }}">                        {{ $article->title }}
                        </a>
                    </h3>
                    <div class="bloginfo">
                        <p>{{ $article->description }}</p>
                    </div>
                    <div class="autor">
                    <span class="lm">
                        <a href="javascript:void(0);" title="{{ $article->keywords }}"
                           class="classname">{{ $article->keywords }}</a>
                    </span>
                        <span class="dtime">{{ $article->created_at }}</span>
                        <span class="viewnum">浏览（<a href="javascript:void(0);">{{ $article->click }}</a>）
                    </span>
                        <span class="readmore">
                        <a href="/article/{{$article->id}}" title="{{ $article->title }}">阅读原文</a>
                    </span>
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
        <style>
            .pics a img:hover {
                transition: all 1s;
                transform: scale(1.2)
            }
        </style>

    </div>
    <div class="sidebar">
        <div class="about">
            <div class="avatar"><img src="/images/config/avatar.jpg" alt="Vijay" title="Vijay"></div>
            <p class="abname">Vijay</p>
            <p class="abposition">PHP开发工程师</p>
            <p style="text-align: center;">
                <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email=1937832819@qq.com" rel="nofollow"
                   target="_blank">1937832819@qq.com</a>
            </p>
            <div class="abtext">每一次经历，每一段时光都值得被记录，它们将会是你未来的财富。</div>
        </div>
        <div class="search">
            <form action="/" method="post">
                <input name="keytitle" class="input_text" value="" style="color: rgb(153, 153, 153);"
                       placeholder="请输入搜索关键词" type="text">
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
            <h2 class="hometitle">
                友情链接
                <a href="javascript:;" title="申请友链" class="add_links">申请友链</a>
            </h2>
            {{--<p style="padding: 10px;word-wrap: break-word;word-break: normal;word-break:break-all;">--}}
                {{--本站信息如下：<br>--}}
                {{--网站名称：<a href="/" title="Vijay个人博客">Vijay个人博客</a> <br>--}}
                {{--网站链接:<a href="https://www.choudalao.com" title="Vijay个人博客">https://www.choudalao.com</a><br>--}}
                {{--网站logo:<a href="https://www.choudalao.com/" title="Vijay个人博客">https://www.choudalao.com/Public/static/images/avatar.jpg</a><br>--}}
                {{--<b><span style="color: red;">注：</span>申请友链之前，请务必先将本站添置友链，Vijay收到后会立马处理，处理结果会以邮件形式通知您~</b>--}}
            {{--</p>--}}
            <ul style="padding: 10px 20px;">
                @foreach($links as $link)
                <li>
                    <a href="{{ $link->url }}" title="{{ $link->name }}" target="_blank">{{ $link->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="cloud" style="margin-top: 20px;">
            <h2 class="hometitle">小应用</h2>
            <ul>
                <a href="https://github.com/Galloping-Vijay/laravel-wjfcms" target="_blank">laravel-wjfcms</a>
                <a href="https://packagist.org/packages/galloping-vijay/php-tools" target="_blank">php-tools</a>
            </ul>
        </div>
        <div class="paihang">
            <h2 class="hometitle">历史上的今天</h2>
            <ul class="events" id="events">

            </ul>
        </div>
    </div>
</article>
<footer>
    <p>Copyright © 2019 <a href="/" title="Vijay个人博客" target="_blank">Vijay个人博客</a>
        All rights reserved&nbsp;<a href="http://www.miitbeian.gov.cn/" title="Vijay个人博客" rel="nofollow" target="_blank">蜀ICP备18031108号-1</a>
    </p>
    <p>所有文章未经授权禁止转载、摘编、复制或建立镜像，如有违反，追究法律责任。举报邮箱：<a
                href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email=1937832819@qq.com" rel="nofollow" target="_blank">1937832819@qq.com</a></p>
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
        timestamp: '1564293853',
        nonceStr: 'DxF7AHKEOV9Kk7Xv',
        signature: 'b6cb07a076864cc46066549ba26964eee9a1b655',
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
            link: 'https://www.choudalao.coml', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
        }, function (res) {
        });
    })
</script>
<!--页面背景特效-->
<script src="{{ asset('js/home/canvas-nest.min.js') }}" count="200" zindex="-2" opacity="0.8"
        color="47,135,193" type="text/javascript"></script>
<!--页面背景特效-->
<canvas id="c_n6" width="1366" height="638"
        style="position: fixed; top: 0px; left: 0px; z-index: -2; opacity: 0.8;"></canvas>
<!--页面背景特效-->
<!--鼠标特效-->
<!--<script>
    !function(e, t, a) {
        function r() {
            for (var e = 0; e < s.length; e++) s[e].alpha <= 0 ? (t.body.removeChild(s[e].el), s.splice(e, 1)) : (s[e].y&#45;&#45;, s[e].scale += .004, s[e].alpha -= .013, s[e].el.style.cssText = "left:" + s[e].x + "px;top:" + s[e].y + "px;opacity:" + s[e].alpha + ";transform:scale(" + s[e].scale + "," + s[e].scale + ") rotate(45deg);background:" + s[e].color + ";z-index:99999");
            requestAnimationFrame(r)
        }
        function n() {
            var t = "function" == typeof e.onclick && e.onclick;
            e.onclick = function(e) {
                t && t(),
                    o(e)
            }
        }
        function o(e) {
            var a = t.createElement("div");
            a.className = "heart",
                s.push({
                    el: a,
                    x: e.clientX - 5,
                    y: e.clientY - 5,
                    scale: 1,
                    alpha: 1,
                    color: c()
                }),
                t.body.appendChild(a)
        }
        function i(e) {
            var a = t.createElement("style");
            a.type = "text/css";
            try {
                a.appendChild(t.createTextNode(e))
            } catch(t) {
                a.styleSheet.cssText = e
            }
            t.getElementsByTagName("head")[0].appendChild(a)
        }
        function c() {
            return "rgb(" + ~~ (255 * Math.random()) + "," + ~~ (255 * Math.random()) + "," + ~~ (255 * Math.random()) + ")"
        }
        var s = [];
        e.requestAnimationFrame = e.requestAnimationFrame || e.webkitRequestAnimationFrame || e.mozRequestAnimationFrame || e.oRequestAnimationFrame || e.msRequestAnimationFrame ||
            function(e) {
                setTimeout(e, 1e3 / 60)
            },
            i(".heart{width: 10px;height: 10px;position: fixed;background: #f00;transform: rotate(45deg);-webkit-transform: rotate(45deg);-moz-transform: rotate(45deg);}.heart:after,.heart:before{content: '';width: inherit;height: inherit;background: inherit;border-radius: 50%;-webkit-border-radius: 50%;-moz-border-radius: 50%;position: fixed;}.heart:after{top: -5px;}.heart:before{left: -5px;}"),
            n(),
            r()
    } (window, document);
</script>-->
<!--鼠标特效-爱心-->
<!--文艺风鼠标特效-->
<script>
    var a_idx = 0;
    var b_idx = 0;
    jQuery(document).ready(function ($) {
        $("body").click(function (e) {
            var a = new Array("如果", "从你的", "全世界", "路过", "便是", "此生", "无与伦比", "的", "美丽", "Vijay", "感恩", "有你");
            var b = new Array("#09ebfc", "#ff6651", "#ffb351", "#51ff65", "#5197ff", "#a551ff", "#ff51f7", "#ff518e", "#ff5163", "#09ebfc", "#ff6651", "#ffb351");
            var $i = $("<span/>").text(a[a_idx]);
            a_idx = (a_idx + 1) % a.length;
            b_idx = (b_idx + 1) % b.length;
            var x = e.pageX,
                y = e.pageY;
            $i.css({
                "z-index": 999999999999999999999999999999999999999999999999999999999999999999999,
                "top": y - 20,
                "left": x,
                "position": "absolute",
                "font-weight": "bold",
                "color": b[b_idx]
            });
            $("body").append($i);
            $i.animate({
                    "top": y - 180,
                    "opacity": 0
                },
                1500,
                function () {
                    $i.remove();
                });
        });
    });
</script>
<!--文艺风鼠标特效-->
<!--下雪-->
<script type="text/javascript">
    /* 控制下雪 */
    function snowFall(snow) {
        /* 可配置属性 */
        snow = snow || {};
        this.maxFlake = snow.maxFlake || 200;
        /* 最多片数 */
        this.flakeSize = snow.flakeSize || 10;
        /* 雪花形状 */
        this.fallSpeed = snow.fallSpeed || 3;
        /* 坠落速度 */
    }

    /* 兼容写法 */
    requestAnimationFrame = window.requestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        function (callback) {
            setTimeout(callback, 1000 / 60);
        };

    cancelAnimationFrame = window.cancelAnimationFrame ||
        window.mozCancelAnimationFrame ||
        window.webkitCancelAnimationFrame ||
        window.msCancelAnimationFrame ||
        window.oCancelAnimationFrame;
    /* 开始下雪 */
    snowFall.prototype.start = function () {
        /* 创建画布 */
        snowCanvas.apply(this);
        /* 创建雪花形状 */
        createFlakes.apply(this);
        /* 画雪 */
        drawSnow.apply(this)
    }

    /* 创建画布 */
    function snowCanvas() {
        /* 添加Dom结点 */
        var snowcanvas = document.createElement("canvas");
        snowcanvas.id = "snowfall";
        snowcanvas.width = window.innerWidth - 20;
        snowcanvas.height = document.body.clientHeight;
        snowcanvas.setAttribute("style", "position:absolute; top: 0; left: 0; z-index: 1; pointer-events: none;");
        document.getElementsByTagName("body")[0].appendChild(snowcanvas);
        this.canvas = snowcanvas;
        this.ctx = snowcanvas.getContext("2d");
        /* 窗口大小改变的处理 */
        window.onresize = function () {
            snowcanvas.width = window.innerWidth;
            /* snowcanvas.height = window.innerHeight */
        }
    }

    /* 雪运动对象 */
    function flakeMove(canvasWidth, canvasHeight, flakeSize, fallSpeed) {
        this.x = Math.floor(Math.random() * canvasWidth);
        /* x坐标 */
        this.y = Math.floor(Math.random() * canvasHeight);
        /* y坐标 */
        this.size = Math.random() * flakeSize + 2;
        /* 形状 */
        this.maxSize = flakeSize;
        /* 最大形状 */
        this.speed = Math.random() * 1 + fallSpeed;
        /* 坠落速度 */
        this.fallSpeed = fallSpeed;
        /* 坠落速度 */
        this.velY = this.speed;
        /* Y方向速度 */
        this.velX = 0;
        /* X方向速度 */
        this.stepSize = Math.random() / 30;
        /* 步长 */
        this.step = 0
        /* 步数 */
    }

    flakeMove.prototype.update = function () {
        var x = this.x,
            y = this.y;
        /* 左右摆动(余弦) */
        this.velX *= 0.98;
        if (this.velY <= this.speed) {
            this.velY = this.speed
        }
        this.velX += Math.cos(this.step += .05) * this.stepSize;

        this.y += this.velY;
        this.x += this.velX;
        /* 飞出边界的处理 */
        if (this.x >= canvas.width || this.x <= 0 || this.y >= canvas.height || this.y <= 0) {
            this.reset(canvas.width, canvas.height)
        }
    };
    /* 飞出边界-放置最顶端继续坠落 */
    flakeMove.prototype.reset = function (width, height) {
        this.x = Math.floor(Math.random() * width);
        this.y = 0;
        this.size = Math.random() * this.maxSize + 2;
        this.speed = Math.random() * 1 + this.fallSpeed;
        this.velY = this.speed;
        this.velX = 0;
    };
    // 渲染雪花-随机形状（此处可修改雪花颜色！！！）
    flakeMove.prototype.render = function (ctx) {
        var snowFlake = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.size);
        snowFlake.addColorStop(0, "rgba(255, 255, 255, 0.9)");
        /* 此处是雪花颜色，默认是白色 */
        snowFlake.addColorStop(.5, "rgba(255, 255, 255, 0.5)");
        /* 若要改为其他颜色，请自行查 */
        snowFlake.addColorStop(1, "rgba(255, 255, 255, 0)");
        /* 找16进制的RGB 颜色代码。 */
        ctx.save();
        ctx.fillStyle = snowFlake;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    };

    /* 创建雪花-定义形状 */
    function createFlakes() {
        var maxFlake = this.maxFlake,
            flakes = this.flakes = [],
            canvas = this.canvas;
        for (var i = 0; i < maxFlake; i++) {
            flakes.push(new flakeMove(canvas.width, canvas.height, this.flakeSize, this.fallSpeed))
        }
    }

    /* 画雪 */
    function drawSnow() {
        var maxFlake = this.maxFlake,
            flakes = this.flakes;
        ctx = this.ctx, canvas = this.canvas, that = this;
        /* 清空雪花 */
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (var e = 0; e < maxFlake; e++) {
            flakes[e].update();
            flakes[e].render(ctx);
        }
        /*  一帧一帧的画 */
        this.loop = requestAnimationFrame(function () {
            drawSnow.apply(that);
        });
    }

    /* 调用及控制方法 */
    var snow = new snowFall({maxFlake: 60});
    snow.start();
</script>
<canvas id="snowfall" width="1366" height="4142"
        style="position:absolute; top: 0; left: 0; z-index: 1; pointer-events: none;"></canvas>
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
                        <img src="/images/config/avatar.jpg" class="huiyuan-img" alt="Vijay个人博客"
                             title="Vijay个人博客">
                        <span>登录</span>
                    </div>
                    <div class="userinfo">
                        <form name="login" method="post" action="https://www.choudalao.com/login/login.html">
                            <input name="username" type="text" class="inputText" size="16" placeholder="用户名">
                            <input name="password" type="password" class="inputText" size="16" placeholder="密码">
                            <input type="submit" value="登陆" class="inputsub-dl">
                            <a href="https://www.choudalao.com/login/register.html" class="inputsub-zc">注册</a>
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
                                <p class="kftext">站长微信</p>
                                <p class="kfnum"><img src="/images/config/wx.jpg" alt="站长微信" title="站长微信"></p>
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
                        <li><img src="/images/config/weixin_pay.jpg" alt="微信收款码" title="微信收款码">微信收款码
                        </li>
                        <li><img src="/images/config/ali_pay.jpg" alt="支付宝收款码" title="支付宝收款码">支付宝收款码
                        </li>
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
    //历史上的今天
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
                    if (data['data'].length > 0) {
                        for (var i = 0; i < data['data'].length; i++) {
                            if(i>4){
                                break;
                            }
                            li += '<li><div class="icon"><em class="cmn-icon wiki-home-eventsOnHistory-icons wiki-home-eventsOnHistory-icons_death"></em></div>'
                                + '<div class="event"><div class="event_tit-wrapper"><div class="event_tit">' + data['data'][i]['year'] + '年 '+data['data'][i].lunar+'<br />' + data['data'][i].title + '</div></div><div class="event_cnt">'
                                + '<img src="' + data['data'][i].pic+ '" width="80%"/></div> </div> </li>';
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
</body>
</html>
