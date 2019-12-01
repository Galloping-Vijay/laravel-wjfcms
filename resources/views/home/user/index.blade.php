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
                        <li><strong>用户ID：</strong>{{ $user->id }}</li>
                        <li><strong>邮箱：</strong>{{ $user->email }}</li>
                        <li><strong>昵称：</strong>{{ $user->name }}</li>
                        <li><strong>性别：</strong>{{ $user->sex_text }}</li>
                        <li><strong>手机：</strong>{{ $user->tel }}</li>
                        <li><strong>城市：</strong>{{ $user->city }}</li>
                        <li><strong>简介：</strong>{{ $user->intro }}</li>
                        <li><strong>注册时间：</strong>{{ $user->created_at }}</li>
                        <li class="user-edit">
                            <a href="/user/modify">修改资料</a>
                        </li>
                    </ul>
                    <div class="user_pic">
                        <img id="avatar" src="{{ $user->avatar??'/images/config/avatar_l.jpg' }}" style="margin-bottom: 10px;cursor: pointer">
                        <span class="red"><strong>修改头像</strong></span>
                        <p id="up_cover_text"></p>
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
            layui.config({
                base: "/static/layuiadmin/"
            }).extend({
                index: 'lib/index'
            }).use(['index', 'upload'], function () {
                var $ = layui.$
                    , upload = layui.upload;
                //图片上传
                var uploadInst = upload.render({
                    elem: '#avatar'
                    , url: '/user/uploadImage'
                    , headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                    , accept: 'images'
                    , field: "file"
                    , type: 'images'
                    , exts: 'jpg|png|gif' //设置一些后缀，用于演示前端验证和后端的验证
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#avatar').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        //如果上传失败
                        if (res.code > 0) {
                            return layer.msg('上传失败', {icon: 2});
                        }
                        console.log(res.data.src);
                        //上传成功
                        $.ajax({
                            type: "POST",
                            url: "/user/modify",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id:"{{ $user->id }}",
                                avatar: res.data.src,
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.code == 0) {
                                    layer.msg(data.msg, {icon: 1});
                                } else {
                                    layer.msg(data.msg, {icon: 2});
                                }
                                return false;
                            }
                        });
                        //$('input[name="cover"]').val(res.data.src);
                    }
                    , error: function () {
                        //演示失败状态，并实现重传
                        var up_logo_text = $('#up_cover_text');
                        up_logo_text.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        up_logo_text.find('.demo-reload').on('click', function () {
                            uploadInst.upload();
                        });
                    }
                });
            });
        });
    </script>
@endsection