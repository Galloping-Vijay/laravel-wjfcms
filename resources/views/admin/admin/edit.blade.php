@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="hidden" name="id" value="{{ $info->id }}">
                <input type="text" name="username" value="{{ $info->username }}" lay-verify="required"
                       placeholder="请输入昵称" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-inline">
                <select name="role_names" id="role_names">
                    <option value="">请选择</option>
                    @foreach($role_list as $val)
                        @if(in_array($val->name,$selected_role))
                            <option selected value="{{ $val->name }}">{{ $val->name }}</option>
                        @else
                            <option value="{{ $val->name }}">{{ $val->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">电话</label>
            <div class="layui-input-inline">
                <input type="number" name="tel" value="{{ $info->tel }}" placeholder="请输入手机号码" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-inline">
                <input type="email" name="email" value="{{ $info->email }}" placeholder="请输入邮箱" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value="0" title="男" {{ $info->sex==0?'checked':'' }}>
                <input type="radio" name="sex" value="1" title="女" {{ $info->sex==1?'checked':'' }}>
                <input type="radio" name="sex" value="-1" title="保密" {{ $info->sex==-1?'checked':'' }}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="hidden" name="status" value="{{ $info->status }}">
                <input type="checkbox" {{ $info->status==1?'checked':'' }} lay-verify="required" lay-filter="status"
                       lay-skin="switch"
                       lay-text="正常|禁用">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="layuiadmin-app-form-add" id="layuiadmin-app-form-add"
                   value="确认添加">
            <input type="button" lay-submit lay-filter="layuiadmin-app-form-edit" id="layuiadmin-app-form-edit"
                   value="确认编辑">
        </div>
    </div>
@endsection

@section('footer')
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table'], function () {
            var $ = layui.$
                , form = layui.form;
            //监听指定开关
            form.on('switch(status)', function () {
                if (this.checked) {
                    $("input[name='status']").val('1');
                } else {
                    $("input[name='status']").val('0');
                }
            });
        });
    </script>
@endsection