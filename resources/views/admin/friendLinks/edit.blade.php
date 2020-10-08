@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" value="{{ $info->name }}" lay-verify="required" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <input type="hidden" value="{{ $info->id }}" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">链接地址</label>
            <div class="layui-input-inline">
                <input type="text" name="url" lay-verify="url" placeholder="请输入" autocomplete="off" value="{{ $info->url }}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="sort" value="{{ $info->sort }}" value="0" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="hidden" name="status" value="{{ $info->status }}">
                <input type="checkbox" {{ $info->status==1?'checked':'' }} lay-verify="required" lay-filter="status"
                       lay-skin="switch"
                       lay-text="已审核|待审核">
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
        }).use(['index', 'table', 'admin'], function () {
            var $ = layui.$
                , admin = layui.admin
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