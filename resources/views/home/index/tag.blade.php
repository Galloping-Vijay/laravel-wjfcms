@extends('layouts.home')
@section('title', $info->name.' | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('header')
<style>
    .picinfo h3 {
        overflow: hidden;
        border-bottom: #ccc 1px solid;
        padding: 20px 0;
        margin: 0 20px;
        width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .picinfo span {
        padding: 20px;
        display: block;
        color: #666;
        width: 200px;
        white-space: break-spaces;
        overflow: hidden;
        height: 38px;
        text-overflow: ellipsis;
        line-height: 30px;
    }
    .picbox ul li {
        display: block;
        background: #FFF;
        margin: 0 0 20px 0;
        border: 1px #d9d9d9 solid;
        height: 410px;
        overflow: hidden;
        border-radius: 20px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@endsection
@section('content')
    <div style="width: 100%;height: 76px;"></div>
    <article>
        <h1 class="t_nav">
            <span class="lm" style="float: left">
                <a href="javascript:void(0);" style="color: #f8a8a8;font-weight: bold" title="{{ $info->name }}"
                   class="classname">{{ $info->name }}</a>
            </span>
        </h1>
        <div class="picbox">
            @foreach($data as $item)
                <ul>
                    @foreach($item as $k=>$article )
                        <li>
                            <a href="/article/{{ $article['id'] }}" title="{{ $article['title'] }}">
                                <i>
                                    <img src="{{ $article['cover'] }}" title="{{ $article['title'] }}">
                                </i>
                                <div class="picinfo">
                                    <h3>{{ $article['title'] }}</h3>
                                    <span>{{ $article['description'] }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
            <div class="blank"></div>
            @if(!empty($articles->items()))
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
            @endif
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
