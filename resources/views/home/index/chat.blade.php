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
@section('title', '有些话 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('content')
    <div style="width: 100%;height: 76px;"></div>
    <article>
        <h1 class="t_nav"><span>有些话</span></h1>
        <div class="blogs">
            <div class="mt20"></div>
            <div class="timebox">
                <ul id="list2">
                    @foreach($chats as $chat)
                    <li>
                        <span>{{ $chat->created_at }}</span><i><a class="chat_tiem" href="javascript:void(0);" >{{ $chat->content }}</a></i>
                    </li>
                    @endforeach
                </ul>
            </div>
            @if(!empty($chats->items()))
            <div class="pagelist">
                <div>
                    @if(!$chats->onFirstPage())
                        <a class="prev" href="{{ $chats->previousPageUrl() }}">上一页</a>
                    @endif
                    <span class="current">第{{ $chats->currentPage() }}页</span>
                    @if($chats->lastPage() !== $chats->currentPage())
                        <a class="next" href="{{ $chats->nextPageUrl() }}">下一页</a>
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
@section('script')
    @parent
    <script>
        $(function() {
            layui.use(['layer'], function(){
                var layer = layui.layer;
                $(document).on('click','.chat_tiem',function (d){
                    layer.msg($(this).text(), {
                        time: 60000,
                        btn: ['朕已阅']
                    });
                })
            });
        });
    </script>
@endsection
