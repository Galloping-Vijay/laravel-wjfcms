@extends('layouts.home')
@section('header')
    <link href="{{ asset('/css/home/message.css') }}" rel="stylesheet">
@endsection
@section('title', '个人中心 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('content')
    <div style="width: 100%;height: 76px;"></div>
    <article>
        <div class="blogs" style="margin-top: 20px;background: #fff;padding-bottom: 20px;">
            <div style="padding: 30px 35px;">
                <div class="article-text">
                    <h1 class="links-title">个人资料</h1>
                    <ul class="links-lists">
                        <li>用户ID：{{ $user->id }}</li>
                        <li> 邮箱：{{ $user->email }}</li>
                        <li> 昵称：{{ $user->name }}</li>
                        <li>注册时间：{{ $user->created_at }}</li>
                        <li class="user-edit">
                            <a href="/user/edit">修改资料</a>
                        </li>
                    </ul>
                    <div class="user_pic">
                        <img src="{{ $user->avatar }}" id="avatarurl" style="margin-bottom: 10px;cursor: pointer">
                        <span class="red">修改头像</span>
                        <form id="avatarurl_form" action="https://www.yxiaowei.com/mine/upload_cover.html" enctype="multipart/form-data">
                            <input type="hidden" name="pathname" value="users">
                            <input type="file" style="display: none" accept="image/gif, image/jpg, image/png" id="avatarurl_file" name="file" onchange="changepic(this)">
                        </form>
                    </div>
                </div>
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