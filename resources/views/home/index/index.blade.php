@extends('layouts.home')
@section('title', \App\Models\SystemConfig::getConfigCache('seo_title'))
@section('content')
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
                            <a href="/article/{{ $article->id }}"
                               title="{{ $article->title }}">{{ $article->title }}</a>
                        </h3>
                        <div class="bloginfo">
                            <p>{{ $article->description }}</p>
                        </div>
                        <div class="autor">
                            <span class="author">{{ $article->author }}</span>
                        <span class="lm">
                            <a href="javascript:void(0);" title="{{ $article->keywords }}"
                               class="classname">{{ $article->keywords }}</a>
                        </span>
                            <span class="dtime">{{ $article->updated_at }}</span>
                            <span class="viewnum">浏览（<a href="javascript:void(0);">{{ $article->click }}</a>）</span>
                            <span class="readmore"><a href="/article/{{ $article->id }}" title="{{ $article->title }}">阅读原文</a></span>
                        </div>
                    </li>
                @endforeach
            </ul>
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
            @component('./layouts/home/about')
            @endcomponent
            @component('./layouts/home/search')
            @endcomponent
            @component('./layouts/home/ad')
            @endcomponent
            @component('./layouts/home/publicwx')
            @endcomponent
            @component('./layouts/home/links')
            @endcomponent
            @component('./layouts/home/cloud')
            @endcomponent
            @component('./layouts/home/tag')
            @endcomponent
            @component('./layouts/home/hot')
            @endcomponent
        </div>
    </article>
@endsection
