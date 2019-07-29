@extends('layouts.admin')
@section('control_name', $control_name)
@section('header')
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/dtree/dtree.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('static/layuiadmin/style/dtree/font/dtreefont.css') }}" media="all">
@endsection
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="hidden" name="id" value="{{ $info->id }}">
                <input type="text" name="name" value="{{ $info->name }}" lay-verify="required" placeholder="请输入"
                       autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <input type="text" name="description" lay-verify="required" placeholder="请输入" autocomplete="off"
                       value="{{ $info->description }}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色组</label>
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
            <label class="layui-form-label">权限</label>
            <ul class="layui-input-inline dtree" id="permissionTree" style="height: 300px;overflow: auto;" class="dtree"
                data-id="0">

            </ul>
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
        <!-- 控制名 -->
        <input type="hidden" name="control_name" value="{{ $control_name }}">
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
        }).use(['index', 'table', 'dtree', 'admin'], function () {
            var $ = layui.$
                , dtree = layui.dtree
                , admin = layui.admin
                , form = layui.form;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var control_name = $('input[name="control_name"]').val();
            //监听指定开关
            form.on('switch(status)', function () {
                if (this.checked) {
                    $("input[name='status']").val('1');
                } else {
                    $("input[name='status']").val('0');
                }
            });
            //异步加载权限菜单
            dtree.render({
                elem: "#permissionTree",
                url: "/admin/permission/permissionTree",
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                request: {
                    guard_name: 'admin',
                    role_id: $('input[name="id"]').val()
                },
                initLevel: 0,
                checkbarType: "all", // 默认就是all，其他的值为： no-all  p-casc   self  only
                dataFormat: "list",  //配置data的风格为list
                checkbar: true,//开启复选框
                menubar: true,
                menubarFun: {
                    remove: function (checkbarNodes) { // 必须将该方法实现了，节点才会真正的从后台删除哦
                        return true;
                    }
                }
            });
            // 点击节点触发回调，其中obj内包含丰富的数据，打开浏览器F12查看！
            dtree.on("node('permissionTree')", function (obj) {
                layer.msg(JSON.stringify(obj.param));
            });

            //监听提交
            form.on('submit(layuiadmin-app-form-edit)', function (data) {
                var field = data.field; //获取提交的字段
                field.permission_name = [];
                var params = dtree.getCheckbarNodesParam("permissionTree");//获取复选框选中值
                if (params.length > 0) {
                    for (var n in params) {
                        field.permission_name.push(params[n].context)
                    }
                }
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                //提交 Ajax 成功后，关闭当前弹层并重载表格
                admin.req({
                    url: '/admin/' + control_name + '/update'
                    , data: field
                    , method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                    , beforeSend: function (XMLHttpRequest) {
                        layer.load();
                    }
                    , done: function (res) {
                        layer.closeAll('loading');
                        if (res.code === 0) {
                            layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                parent.layui.table.reload('LAY-app-list'); //重载表格
                                parent.layer.close(index); //再执行关闭
                            });
                        } else {
                            layer.msg(res.msg, {icon: 2});;
                        }
                    }
                });
            });
        });
    </script>
@endsection