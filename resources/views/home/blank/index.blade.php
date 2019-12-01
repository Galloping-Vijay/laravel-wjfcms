@extends('layouts.home')
@section('title', '页面错误 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('header')
    <style>
        .message{
            min-height: 200px;
            font-size: 20px;
            text-align: center;
            line-height: 200px;
            vertical-align: middle;
            background-color: #fff;
        }
    </style>
@endsection
@section('content')
    <div style="width: 100%;height: 76px;"></div>
    <article>
        <h1 class="t_nav"><span></span></h1>
        <div class="blogs">
            <div class="mt20"></div>
            <div class="message">
                <h1>{{ $message }}</h1>
            </div>
        </div>
        <div class="sidebar">
            @component('./layouts/home/search')
            @endcomponent
            @component('./layouts/home/about')
            @endcomponent
            @component('./layouts/home/cloud')
            @endcomponent
            @component('./layouts/home/publicwx')
            @endcomponent
            @component('./layouts/home/publicwx')
            @endcomponent
        </div>
    </article>
@endsection