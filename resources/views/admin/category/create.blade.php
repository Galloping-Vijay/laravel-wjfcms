@extends('layouts.admin')
@section('title', '文章分类添加')
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <input type="hidden" name="superclass_id" value="{{ $superclass_id }}">
        <div class="layui-form-item">
            <label class="layui-form-label">上级：</label>
            <div class="layui-input-inline" id="">
                <select name="" lay-verify="required" lay-search="" lay-filter="pid" id="pid">

                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关键字</label>
            <div class="layui-input-inline">
                <input type="text" name="keywords" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea name="description" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="sort" value="0" placeholder="请输入" autocomplete="off"
                       class="layui-input">
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
            var superclass_id = $("input[name='superclass_id']").val();
            //初始化菜单
            getMenu();
            /**
             * 获取分类树
             */
            function getMenu() {
                admin.req({
                    url: '/admin/category/tree'
                    , data: {}
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , method: 'POST'
                    , done: function (res) {
                        if (res.code === 0) {
                            var html = '<option value="0-0">一级菜单</option>';
                            for (var p in res.data) {
                                if (res.data[p].id == superclass_id) {
                                    html += ' <option value="' + res.data[p].id + '" selected>' + res.data[p].name + '</option>';
                                    $("input[name='pid']").val(res.data[p].id);
                                } else {
                                    html += ' <option value="' + res.data[p].id + '">' + res.data[p].name + '</option>';
                                }
                            }
                            $('#pid').html(html);
                            form.render('select');
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                });
            }
        });
    </script>
@endsection