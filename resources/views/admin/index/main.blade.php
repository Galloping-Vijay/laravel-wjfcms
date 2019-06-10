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
                                                <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                                                    <h3>文章总数</h3>
                                                    <p><cite>66</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="app/forum/list.html" class="layadmin-backlog-body">
                                                    <h3>总评论数</h3>
                                                    <p><cite>12</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="template/goodslist.html" class="layadmin-backlog-body">
                                                    <h3>总会员</h3>
                                                    <p><cite>99</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a href="javascript:;" onclick="layer.tips('不跳转', this, {tips: 3});" class="layadmin-backlog-body">
                                                    <h3>管理员</h3>
                                                    <p><cite>5</cite></p>
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
                                        <table id="LAY-index-topSearch"></table>
                                    </div>
                                    <div class="layui-tab-item">
                                        <table id="LAY-index-topCard"></table>
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
                                        <a href="" target="_blank">Vijay</a> (1937832819@qq.com)
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>开发文档</td>
                                <td>
                                    <script type="text/html" template>
                                        <a href="" target="_blank" style="padding-left: 15px;">文档地址</a>
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>当前版本</td>
                                <td>
                                    <script type="text/html" template>
                                        <a href="" target="_blank" style="padding-left: 15px;">更新日志</a>
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
                                        <a href="" target="_blank" class="layui-btn layui-btn-danger">获取授权</a>
                                        <a href="https://github.com/Galloping-Vijay/laravel-wjfcms" target="_blank" class="layui-btn">GitHub</a>
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
                        <div class="layui-carousel layadmin-carousel layadmin-news" data-autoplay="true" data-anim="fade" lay-filter="news">
                            <div carousel-item>
                                <div><a href="" target="_blank" class="layui-bg-red">商城</a></div>
                                <div><a href="" target="_blank" class="layui-bg-green">博客</a></div>
                                <div><a href="" target="_blank" class="layui-bg-blue">外包官网</a></div>
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
                        <p>一直以来，layui 秉承无偿开源的初心，虔诚致力于服务各层次前后端 Web 开发者，在商业横飞的当今时代，这一信念从未动摇。即便身单力薄，仍然重拾决心，埋头造轮，以尽可能地填补产品本身的缺口。</p>
                        <p>在过去的一段的时间，我一直在寻求持久之道，已维持你眼前所见的一切。而 layuiAdmin 是我们尝试解决的手段之一。我相信真正有爱于 layui 生态的你，定然不会错过这一拥抱吧。</p>
                        <p>—— Vijay（<a href="" target="_blank">yuemeet.com</a>）</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'console']);
    </script>
@endsection