@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" value="{{ $info->name }}" lay-verify="required" placeholder="请输入权限名称" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <input type="hidden" value="{{ $info->parent_id }}" name="parent_id">
        <input type="hidden" value="{{ $info->level }}" name="level">
        <input type="hidden" value="{{ $info->id }}" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">权限组</label>
            <div class="layui-input-inline">
                <select name="guard_name" lay-verify="required" lay-filter="guard_name" id="guard_name">
                    @foreach($guard_name_list as $val)
                        @if($val == $info->guard_name)
                            <option selected value="{{ $val }}">{{ $val }}</option>
                        @else
                            <option value="{{ $val }}">{{ $val }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上级菜单</label>
            <div class="layui-input-inline" id="">
                <select name="" lay-verify="required" lay-search="" lay-filter="pid" id="pid">

                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限地址</label>
            <div class="layui-input-inline">
                <input type="text" name="url" value="{{ $info->url }}" lay-verify="required" placeholder="如:/admin/index/index"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">ICON图标</label>
            <div class="layui-input-inline">
                <input type="text" name="icon" value="{{ $info->icon }}" placeholder="请输入图标" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux"><a style="color: red"
                                                          href="https://www.layui.com/doc/element/icon.html"
                                                          target="_blank">去挑选</a></div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否显示为菜单</label>
            <div class="layui-input-inline">
                <input type="hidden" name="display_menu" value="{{ $info->display_menu }}">
                <input type="checkbox" {{ $info->display_menu==1?'checked':'' }} lay-verify="required" lay-filter="status" lay-skin="switch"
                       lay-text="显示|隐藏">
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
            var parent_id = $("input[name='parent_id']").val();
            //初始化菜单
            getMenu('admin');
            //监听指定开关
            form.on('switch(status)', function () {
                if (this.checked) {
                    $("input[name='display_menu']").val('1');
                } else {
                    $("input[name='display_menu']").val('0');
                }
            });
            //监听select改变
            form.on('select(guard_name)', function (data) {
                getMenu(data.value)
            });
            //设置字段
            form.on('select(pid)', function (data) {
                var str = data.value;
                var arr = str.split('-');
                $("input[name='parent_id']").val(parseInt(arr[0]));
                $("input[name='level']").val(parseInt(arr[1]) + 1);
            });

            /**
             * 获取菜单
             * @param guard_name
             */
            function getMenu(guard_name = 'admin') {
                admin.req({
                    url: '/admin/permission/menu'
                    , data: {guard_name: guard_name}
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , method: 'POST'
                    , done: function (res) {
                        if (res.code === 0) {
                            var html = '<option value="0-0">一级菜单</option>';
                            for (var p in res.data) {
                                if (res.data[p].id == parent_id) {
                                    html += ' <option value="' + res.data[p].id + '-' + res.data[p].level + '" selected>' + res.data[p].name + '</option>';
                                    $("input[name='parent_id']").val(res.data[p].id);
                                    $("input[name='level']").val(parseInt(res.data[p].level) + 1);
                                } else {
                                    html += ' <option value="' + res.data[p].id + '-' + res.data[p].level + '">' + res.data[p].name + '</option>';
                                }
                            }
                            $('#pid').html(html);
                            form.render('select');
                        } else {
                            layer.msg(res.msg, {icon: 2});;
                        }
                    }
                });
            }
        });
    </script>
@endsection