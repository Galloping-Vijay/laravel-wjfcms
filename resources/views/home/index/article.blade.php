@extends('layouts.home')
@section('header')
    <script src="{{ asset('static/highlight/highlight.pack.js') }}"></script>
    <link href="{{ asset('static/highlight/styles/sunburst.css') }}" rel="stylesheet">
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="{{ asset('static/clipboard/dist/clipboard.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('static/layuiadmin/style/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('static/layuiadmin/style/template.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/zoomify.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/home/zoomify.min.js') }}" type="text/javascript"></script>
    <style>
        .news_infos h1,.news_infos h2,.news_infos h3,.news_infos h4,.news_infos h5,.news_infos h6{
            font-weight: bold;
            margin: 10px auto;
        }
        pre {
            position: relative;
            padding: 0;
            display: inherit;
            font-size: 16px;
        }

        pre:hover .btn-copy {
            opacity: 1;
        }

        .btn-copy {
            display: inline-block;
            cursor: pointer;
            background-color: #eee;
            background-image: linear-gradient(#fcfcfc, #eee);
            border: 1px solid #d5d5d5;
            border-radius: 3px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-appearance: none;
            font-size: 13px;
            font-weight: 700;
            line-height: 20px;
            color: #333;
            -webkit-transition: opacity .3s ease-in-out;
            -o-transition: opacity .3s ease-in-out;
            transition: opacity .3s ease-in-out;
            padding: 2px 6px;
            position: absolute;
            right: 5px;
            top: 5px;
            opacity: 0;
        }

        .btn-copy span {
            margin-left: 5px;
        }

        .infos-aid {
            width: 85%;
            margin: 0 auto;
        }
        blockquote{
            margin-top: 0;
            margin-bottom: 16px;
            padding-top: 0;
            padding-right: 15px;
            padding-left: 20px;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            box-sizing: border-box;
            color: rgb(102, 102, 102);
            font-style: italic;
            font-family: "Microsoft YaHei", Helvetica, "Meiryo UI", "Malgun Gothic", "Segoe UI", "Trebuchet MS", Monaco, monospace, Tahoma, STXihei, 华文细黑, STHeiti, "Helvetica Neue", "Droid Sans", "wenquanyi micro hei", FreeSans, Arimo, Arial, SimSun, 宋体, Heiti, 黑体, sans-serif;
            text-align: left;
            white-space: normal;
            border-left: 4px solid rgb(221, 221, 221);
        }
        .zoomify.zoomed{
            position: absolute;
        }
    </style>
@endsection
@section('title', $info->title.' | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('keywords', $info->title)
@section('description', $info->description)
@section('content')
    <div style="width: 100%;height: 76px;"></div>
    <article>
        <div class="infos">
            <div class="newsview">
                <h3 class="news_title">{{ $info->title }}</h3>
                <div class="news_author">
                    <span class="au01">{{ $info->author }}</span>
                    <span class="au02">{{ $info->created_at }}</span>
                    <span class="viewnum">{{ $info->click }}</span>
                </div>
                <div class="tags">
                    @foreach($info_tags as $info_tag)
                        <a href="/tag/{{ $info_tag['id'] }}">{{ $info_tag['name'] }}</a>&nbsp;
                    @endforeach
                </div>
                <div class="news_about">
                    <strong>简介</strong>
                    {{ $info->description }}
                </div>
                <div class="news_infos">
                    {!! htmlspecialchars_decode($info->content) !!}
                </div>
            </div>
            <div class="infos-aid">
                 <div class="share"></div>
                <span class="diggits praise">
               {{-- <a href="javascript:;"> 很赞哦！ </a>
                (<b>0</b>)--}}
                </span>
                </p>
                <p><span class="diggit award">赏</span></p>
                <script>
                    $('.award').click(function () {
                        layer.open({
                            title: '谢谢臭大佬的打赏',
                            type: 1,
                            area: ['25rem', '15rem'],
                            shadeClose: true,
                            content: '<img src="/images/home/award.jpg"  style="width:100%"/>'
                        });
                    })
                </script>
                <div class="nextinfo">
                    <p>上一篇：
                        @if(!empty($pre))
                            <a href="/article/{{ $pre->id }}" title="{{ $pre->title }}">{{ $pre->title }}</a>
                        @else
                            <a href="javascript:void(0);">已经是第一篇了</a>
                        @endif
                    </p>
                    <p>下一篇：
                        @if(!empty($next))
                            <a href="/article/{{ $next->id }}" title="{{ $next->title }}">{{ $next->title }}</a>
                        @else
                            <a href="javascript:void(0);">已经是最后一篇了</a>
                        @endif
                    </p>
                </div>
            </div>
            {{-- 留言开始--}}
            @component('./layouts/home/msgboard', ['article_id' =>$info->id])
            @endcomponent
            {{-- 留言结束--}}
        </div>
        <div class="sidebar">
            @component('./layouts/home/search')
            @endcomponent
            @component('./layouts/home/ad')
            @endcomponent
            @component('./layouts/home/hot')
            @endcomponent
            @component('./layouts/home/tag')
            @endcomponent
            @component('./layouts/home/publicwx')
            @endcomponent
        </div>
    </article>
@endsection

@section('script')
    @parent
    <script>
        $(function () {
            var layer = null;
            //一般直接写在一个js文件中
            layui.use(['layer'], function () {
                layer = layui.layer;
            });
            /* code */
            var initCopyCode = function () {
                var copyHtml = '';
                copyHtml += '<button class="btn-copy" data-clipboard-action="copy" data-clipboard-target=".copyCode">';
                copyHtml += '  <i class="fa fa-globe"></i><span>copy</span>';
                copyHtml += '</button>';
                $("pre code").before(copyHtml);
                var clipboard = new ClipboardJS('.btn-copy', {
                    target: function (trigger) {
                        return trigger.nextElementSibling;
                    }
                });
                clipboard.on('success', function (e) {
                    layer.msg('复制成功');
                });
                clipboard.on('error', function (e) {
                    console.log(e);
                });
            };
            initCopyCode();
            $('.news_infos img').zoomify();
        })
    </script>
@endsection
