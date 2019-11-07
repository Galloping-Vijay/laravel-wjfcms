@extends('layouts.home')
@section('header')
{{--高亮  --}}
<script src="{{ asset('static/highlight/highlight.pack.js') }}"></script>
<link href="{{ asset('static/highlight/styles/sunburst.css') }}" rel="stylesheet">
<script>hljs.initHighlightingOnLoad();</script>
{{--复制--}}
<script src="{{ asset('static/clipboard/dist/clipboard.min.js') }}" type="text/javascript" ></script>
<style>
    pre{
        position: relative;
        padding: 0;
        display: inherit;
    }
    pre:hover .btn-copy{
        opacity: 1;
    }
    .btn-copy {
        display: inline-block;
        cursor: pointer;
        background-color: #eee;
        background-image: linear-gradient(#fcfcfc,#eee);
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
</style>
@endsection
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
            <div class="share"></div>
            <p>
            {{--<span class="diggits praise">--}}
            {{--<a href="javascript:;"> 很赞哦！ </a>--}}
            {{--(<b>0</b>)--}}
            {{--</span>--}}
            {{--</p>--}}
            <p>
    <span class="diggit award">
        赏
    </span>
            </p>
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
        <div class="sidebar">
            @component('./layouts/home/hot')
            @endcomponent
            @component('./layouts/home/tag')
            @endcomponent
            @component('./layouts/home/publicwx')
            @endcomponent
            @component('./layouts/home/applets')
            @endcomponent
        </div>
    </article>
@endsection

@section('script')
    @parent
    <script src="{{ asset('static/layuiadmin/layui/layui.js') }}"></script>
    <script>
        $(function() {
            var layer = null;
            //一般直接写在一个js文件中
            layui.use(['layer'], function(){
                layer = layui.layer;
            });
            /* code */
            var initCopyCode = function(){
                var copyHtml = '';
                copyHtml += '<button class="btn-copy" data-clipboard-action="copy" data-clipboard-target=".copyCode">';
                copyHtml += '  <i class="fa fa-globe"></i><span>copy</span>';
                copyHtml += '</button>';
                $("pre code").before(copyHtml);
                var clipboard  = new ClipboardJS('.btn-copy', {
                    target: function(trigger) {
                        return trigger.nextElementSibling;
                    }
                });
                clipboard.on('success', function(e) {
                    layer.msg('复制成功');
                });
                clipboard.on('error', function(e) {
                    console.log(e);
                });
            };
            initCopyCode();
        })
    </script>
@endsection
