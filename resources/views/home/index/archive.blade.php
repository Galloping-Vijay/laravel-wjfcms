@extends('layouts.home')
@section('header')
<style>
    .timebox {
        background: #FFF;
    }

    #list2 {
        padding: 20px 0;
    }

    .timebox ul {
        overflow: hidden;
    }

    .timebox span {
        position: relative;
        line-height: 32px;
        padding-right: 40px;
        color: #999
    }

    .timebox span:after {
        position: absolute;
        content: "";
        width: 2px;
        height: 40px;
        background: #e0dfdf;
        right: 18px
    }

    .timebox li {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        margin-bottom: 0;
        padding: 0 20px;
        background: #fff;
    }

    .timebox li i {
        position: relative;
        font-style: normal;
    }

    .timebox li i:before {
        content: " ";
        height: 10px;
        width: 10px;
        border: 2px solid #cccaca;
        background: #fff;
        position: absolute;
        top: 4px;
        left: -26px;
        border-radius: 50%;
        -webkit-transition: all .5s ease;
        -moz-transition: all .5s ease;
        -ms-transition: all .5s ease;
        -o-transition: all .5s ease;
        transition: all .5s ease;
    }

    .timebox li:hover i:before {
        background: #080808
    }
</style>
@endsection
@section('title', '文章归档 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('content')
<div style="width: 100%;height: 76px;"></div>
<article>
    <h1 class="t_nav"><span>文章归档</span></h1>
    <div class="blogs">
        <div class="mt20"></div>
        <div class="timebox">
            <ul id="list2">
                <div class="layui-collapse" lay-accordion=""  lay-filter="categary-collapse">
                    @foreach($data as $tiem)
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">{{ $tiem['name']}}</h2>
                        <div class="layui-colla-content">
                            @foreach($tiem['art'] as $art)
                            <li>
                                <span></span><i><a class="chat_tiem" href="/article/{{ $art['id']}}">{{ $art['title'] }}</a></i>
                            </li>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </ul>
        </div>
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
    $(function() {
        layui.use(['element', 'layer'], function() {
            var layer = layui.layer;
            var element = layui.element;
            //监听折叠
            element.on('collapse(categary-collapse)', function(data) {
                //layer.msg('展开状态：' + data.show);
            });
        });
    });
</script>
@endsection