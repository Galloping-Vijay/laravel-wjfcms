@extends('layouts.admin')

@section('title', '欢迎来到我的cms')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">数据概览</div>
                            <div class="layui-card-body">
                                <div class="layui-carousel layadmin-carousel layadmin-backlog">
                                    <div carousel-item>
                                        <ul class="layui-row layui-col-space10">
                                            <li class="layui-col-xs6">
                                                <a lay-href="/admin/article/index" class="layadmin-backlog-body">
                                                    <h3>文章总数</h3>
                                                    <p><cite>{{ $articleNum }}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="/admin/comment/index" class="layadmin-backlog-body">
                                                    <h3>总评论数</h3>
                                                    <p><cite>{{ $commentNum }}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="/admin/user/index" class="layadmin-backlog-body">
                                                    <h3>总会员</h3>
                                                    <p><cite>{{ $userNum }}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a href="/admin/admin/index"
                                                   class="layadmin-backlog-body">
                                                    <h3>管理员</h3>
                                                    <p><cite>{{ $adminNum }}</cite></p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-tab layui-tab-brief layadmin-latestData">
                                <ul class="layui-tab-title">
                                    <li class="layui-this">热门文章</li>
                                    <li>热门碎语</li>
                                </ul>
                                <div class="layui-tab-content">
                                    <div class="layui-tab-item layui-show">
                                        <table id="LAY-index-articleList"></table>
                                    </div>
                                    <div class="layui-tab-item">
                                        <table id="LAY-index-chatList"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">框架信息</div>
                    <div class="layui-card-body layui-text">
                        <table class="layui-table">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>框架作者</td>
                                <td>
                                    <script type="text/html" template>
                                        <a href="" target="_blank">{{ config('vijay.author') }}</a> ({{ config('vijay.email') }})
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>交流群</td>
                                <td>
                                    <script type="text/html" template>
                                        <a href="#" target="_blank">{{ config('vijay.qq_qun') }}</a>
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>开发文档</td>
                                <td>
                                    <script type="text/html" template>
                                        <a href="{{ config('vijay.document') }}" target="_blank"
                                           style="padding-left: 15px;">文档地址</a>
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>当前版本</td>
                                <td>
                                    <script type="text/html" template>
                                        {{ config('vijay.version') }}
                                        <a href="{{ config('vijay.update_log') }}"
                                           target="_blank" style="padding-left: 15px;">(更新日志)</a>
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>技术栈</td>
                                <td>
                                    laravel / layui
                                </td>
                            </tr>
                            <tr>
                                <td>主要特色</td>
                                <td>零门槛 / 响应式 / 清爽 / 极简</td>
                            </tr>
                            <tr>
                                <td>获取渠道</td>
                                <td style="padding-bottom: 0;">
                                    <div class="layui-btn-container">
                                        <a href="{{ config('vijay.authorize') }}"
                                           target="_blank" class="layui-btn layui-btn-danger">获取授权</a>
                                        <a href="{{ config('vijay.github') }}" target="_blank"
                                           class="layui-btn">GitHub</a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">产品周边</div>
                    <div class="layui-card-body">
                        <div class="layui-carousel layadmin-carousel layadmin-news" data-autoplay="true"
                             data-anim="fade" lay-filter="news">
                            <div carousel-item>
                                <div><a href="{{ config('vijay.shop') }}" target="_blank" class="layui-bg-red">商城</a></div>
                                <div><a href="{{ config('vijay.base_url') }}" target="_blank" class="layui-bg-green">博客</a></div>
                                <div><a href="{{ config('vijay.development') }}" target="_blank" class="layui-bg-blue">外包官网</a></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-card">
                    <div class="layui-card-header">
                        作者心语
                        <i class="layui-icon layui-icon-tips" lay-tips="要支持的噢" lay-offset="5"></i>
                    </div>
                    <div class="layui-card-body layui-text layadmin-text">
                        <p>{{ config('vijay.motto') }}</p>
                        <p>—— {{ config('vijay.author') }}（<a href="{{ config('vijay.base_url') }}" target="_blank">{{ config('vijay.object_name') }}</a>）</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table', 'form', 'carousel'], function () {//console
            var $ = layui.$
                , admin = layui.admin
                , carousel = layui.carousel
                , element = layui.element
                , device = layui.device();
            //轮播切换
            $('.layadmin-carousel').each(function () {
                var othis = $(this);
                carousel.render({
                    elem: this
                    , width: '100%'
                    , arrow: 'none'
                    , interval: othis.data('interval')
                    , autoplay: othis.data('autoplay') === true
                    , trigger: (device.ios || device.android) ? 'click' : 'hover'
                    , anim: othis.data('anim')
                });
            });
            element.render('progress');

            layui.use('table', function () {
                var $ = layui.$
                    , table = layui.table;

                //文章
                table.render({
                    elem: '#LAY-index-articleList'
                    , data: {!! $articleList !!}
                    , cols: [[
                        {type: 'numbers', title: 'ID', fixed: 'left'}
                        , {
                            field: 'title', title: '文章标题', minWidth: 300
                            , templet: function (d) {
                                return '<div><a href="/article/'+d.id+'" target="_blank" class="layui-table-link">' + d.title + '</div>';
                            }
                        }
                        , {field: 'cate_name', title: '分类', minWidth: 120}
                        , {field: 'click', title: '点击数', minWidth: 120, sort: true}
                    ]]
                    , skin: 'line'
                });

                //碎语
                table.render({
                    elem: '#LAY-index-chatList'
                    , data:{!! $chatList !!}
                    , cellMinWidth: 120
                    , cols: [[
                        {type: 'numbers', title: 'ID'}
                        , {
                            field: 'content',
                            title: '内容',
                            minWidth: 300,
                            templet: function (d) {
                                return '<div><a href="javascript:void(0);" class="layui-table-link">' + d.content + '</div>';
                            }
                        }
                        , {field: 'created_at', title: '创建时间', sort: true}
                    ]]
                    , skin: 'line'
                });
            });

        });
    </script>
@endsection
