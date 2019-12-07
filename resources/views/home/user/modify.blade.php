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
                    <h1 class="links-title">修改资料</h1>
                    <ul class="links-lists">
                        <form class="layui-form layui-form-pane" method="post" action="/user/update">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="layui-form-item">
                                <label class="layui-form-label">ID</label>
                                <div class="layui-input-block">
                                    <input type="text" value="{{ $user->id }}" autocomplete="off" class="layui-input" readonly disabled>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    @if($user->email)
                                        <input name="email" type="text" value="{{ $user->email }}" autocomplete="off" class="layui-input" readonly disabled>
                                    @else
                                        <input name="email" type="text" value="" autocomplete="off" class="layui-input">
                                    @endif
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" value="{{ $user->name }}" required lay-verify="required" placeholder="请输入昵称" autocomplete="off" class="layui-input">
                                </div>
                                @if ($errors->has('name'))
                                    <div class="layui-form-mid layui-word-aux">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    @foreach($sexList as $key => $val)
                                        <input type="radio" name="sex" value="{{ $key }}" title="{{ $val }}" @if($user->sex ==$key) checked @endif>
                                    @endforeach
                                </div>
                                @if ($errors->has('sex'))
                                    <div class="layui-form-mid layui-word-aux">{{ $errors->first('sex') }}</div>
                                @endif
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机</label>
                                <div class="layui-input-block">
                                    <input type="tel" name="tel" value="{{ $user->tel }}" required lay-verify="required|phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                                </div>
                                @if ($errors->has('tel'))
                                    <div class="layui-form-mid layui-word-aux">{{ $errors->first('tel') }}</div>
                                @endif
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">城市</label>
                                <div class="layui-input-block">
                                    <input type="text" name="city" value="{{ $user->city }}" placeholder="请输入城市" autocomplete="off" class="layui-input">
                                </div>
                                @if ($errors->has('tel'))
                                    <div class="layui-form-mid layui-word-aux">{{ $errors->first('tel') }}</div>
                                @endif
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">简介</label>
                                <div class="layui-input-block">
                                    <textarea name="intro" placeholder="请输入简介" class="layui-textarea">{{ $user->intro }}</textarea>
                                </div>
                                @if ($errors->has('tel'))
                                    <div class="layui-form-mid layui-word-aux">{{ $errors->first('tel') }}</div>
                                @endif
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="button"  lay-submit  lay-filter="ajaxUpdate"  class="layui-btn layui-btn layui-btn-normal" >修改资料</button>
                                </div>
                            </div>
                        </form>
                    </ul>
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
            }).use(['form'], function () {
                var layer = layui.layer;
                var form = layui.form;
                //提交
                form.on('submit(ajaxUpdate)', function(data){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        data: data.field, //传接收到的参数id
                        url: '/user/update',
                        success: function (res) {
                            if(res.code === 0){
                                layer.msg(res.msg, {
                                    offset: '15px'
                                    , icon: 1
                                    , time: 1000
                                }, function () {
                                    location.href = '/user/modify';
                                });
                            }else{
                                layer.msg(res.msg, {
                                    offset: '15px'
                                    , icon: 2
                                    , time: 1000
                                }, function () {

                                });
                            }
                        }
                    });
                    return false;
                });
            });
        });
    </script>
@endsection