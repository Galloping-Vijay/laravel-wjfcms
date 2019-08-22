@extends('layouts.home')
@section('header')
<<script src="{{ asset('static/highlight/highlight.pack.js') }}"></script>
<link href="{{ asset('static/highlight/styles/vs2015.css') }}" rel="stylesheet">
<script>hljs.initHighlightingOnLoad();</script>
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
