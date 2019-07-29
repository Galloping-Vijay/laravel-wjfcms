<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <title>wjfcms-laravel - @yield('title','后台管理系统')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/admin.css') }}" media="all">
    <script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>
    @yield('header')
</head>
<body class="layui-layout-body">
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="/" target="_blank" title="前台">
                        <i class="layui-icon layui-icon-website"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search"
                           layadmin-event="serach" lay-action="template/search.html?keywords=">
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" onclick="layer.tips('待开发', this, {tips: 3});" layadmin-event="message"
                       lay-text="消息中心" title="消息中心">
                        <i class="layui-icon layui-icon-notice"></i>

                        <!-- 如果有新消息，则显示小圆点 -->
                        <span class="layui-badge-dot"></span>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme" title="主题">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note" title="便签">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen" title="全屏">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite>{{ $admin->username }}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="/admin/admin/info">基本资料</a></dd>
                        <dd><a lay-href="/admin/admin/password">修改密码</a></dd>
                        <hr>
                        <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="about"><i
                                class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="/admin/index/main">
                    <span>laravel-wjfcms</span>
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                    lay-filter="layadmin-system-side-menu">
                    @foreach ($menus as $menu)
                        @if(!empty($menu['child']))
                            <li data-name="{{ $menu['name'] }}" class="layui-nav-item">
                                <a href="javascript:;" lay-tips="{{ $menu['name'] }}" lay-direction="2">
                                    <i class="layui-icon {{ $menu['icon'] }}"></i>
                                    <cite>{{ $menu['name'] }}</cite>
                                </a>
                                <dl class="layui-nav-child">
                                    @foreach ($menu['child'] as $son)
                                        @if(!empty($son['child']))
                                            <dd data-name="grid">
                                                <a href="javascript:;">
                                                    <i class="layui-icon {{ $son['icon'] }}"></i>
                                                    {{ $son['name'] }}
                                                </a>
                                                <dl class="layui-nav-child">
                                                    @foreach ($son['child'] as $grandson)
                                                        <dd data-name="list">
                                                            <a lay-href="{{ $grandson['url'] }}">
                                                                <i class="layui-icon {{ $grandson['icon'] }}"></i>
                                                                {{ $grandson['name'] }}
                                                            </a>
                                                        </dd>
                                                    @endforeach
                                                </dl>
                                            </dd>
                                        @else
                                            @if($loop->parent->first)
                                                <dd data-name="{{ $son['name'] }}" class="layui-this">
                                                    <a lay-href="{{ $son['url'] }}">
                                                        <i class="layui-icon {{ $son['icon'] }}"></i>
                                                        {{ $son['name'] }}
                                                    </a>
                                                </dd>
                                            @else
                                                <dd data-name="{{ $son['name'] }}">
                                                    <a lay-href="{{ $son['url'] }}">
                                                        <i class="layui-icon {{ $son['icon'] }}"></i>
                                                        {{ $son['name'] }}
                                                    </a>
                                                </dd>
                                            @endif
                                        @endif
                                    @endforeach
                                </dl>
                            </li>
                        @else
                            <li data-name="{{ $menu['name'] }}" class="layui-nav-item">
                                <a href="javascript:;" lay-href="{{ $menu['url'] }}" lay-tips="{{ $menu['name'] }}"
                                   lay-direction="2">
                                    <i class="layui-icon layui-icon-auz"></i>
                                    <cite>{{ $menu['name'] }}</cite>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="/admin/index/main" lay-attr="/admin/index/main" class="layui-this"><i
                                class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="/admin/index/main" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>
<script>
    layui.config({
        base: "/static/layuiadmin/" //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>
</body>
</html>