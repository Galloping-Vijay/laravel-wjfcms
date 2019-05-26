@extends('layouts.admin')
@section('title', '管理员列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <!-- 搜索 -->
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">文章ID</label>
                        <div class="layui-input-inline">
                            <input type="text" name="id" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">作者</label>
                        <div class="layui-input-inline">
                            <input type="text" name="author" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">文章标签</label>
                        <div class="layui-input-inline">
                            <select name="label">
                                <option value="">请选择标签</option>
                                <option value="0">美食</option>
                                <option value="1">新闻</option>
                                <option value="2">八卦</option>
                                <option value="3">体育</option>
                                <option value="4">音乐</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="layui-card-body">
                <!-- 按钮组 -->
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                    <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                </div>
                <!-- 表格 -->
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <!-- 控制名 -->
                <input type="hidden" name="control_name" value="{{ $control_name }}">
                <!-- 模板渲染 -->
                <script type="text/html" id="statusTpl">
                    <input type="checkbox" name="status" lay-skin="switch" lay-filter="table-button-status"
                           data-id="@{{ d.id }}" lay-text="ON|OFF" @{{ d.status?'checked':'' }}>
                </script>
                <script type="text/html" id="sexTpl">
                    @{{#  if(d.sex=='-1'){ }}
                    <button class="layui-btn layui-btn-xs layui-btn ayui-btn-primary">保密</button>
                    @{{#  } else if(d.sex=='0'){ }}
                    <button class="layui-btn layui-btn-xs layui-btn layui-btn-warm">男</button>
                    @{{#  } else { }}
                    <button class="layui-btn layui-btn-primary layui-btn-danger">女</button>
                    @{{#  } }}
                </script>
                <script type="text/html" id="table-list">
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                                class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i
                                class="layui-icon layui-icon-delete"></i>删除</a>
                </script>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table', 'admin'], function () {
            var table = layui.table
                , $ = layui.$
                , admin = layui.admin
                , form = layui.form;
            var control_name = $('input[name="control_name"]').val();

            table.render({
                elem: '#LAY-app-list'
                , url: '/admin/' + control_name + '/index'
                , method: 'post'
                , where: {}
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', width: 100, title: 'ID', sort: true}
                    , {field: 'account', title: '账号', minWidth: 100}
                    , {field: 'username', title: '昵称'}
                    , {field: 'tel', title: '电话'}
                    , {field: 'email', title: '邮箱'}
                    , {field: 'sex', title: '性别', templet: '#sexTpl', minWidth: 80, align: 'center'}
                    , {field: 'status', title: '状态', templet: '#statusTpl', minWidth: 80, align: 'center'}
                    , {title: '操作', minWidth: 150, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                , page: true
                , limit: 10
                , text: '对不起，加载出现异常！'
            });

            //监听搜索
            form.on('submit(LAY-app-search)', function (data) {
                var field = data.field;
                //执行重载
                table.reload('LAY-app-list', {
                    where: field
                });
            });

            //监听指定开关
            form.on('switch(table-button-status)', function (data) {
                var field = {
                    id: $(this).data('id')
                    , status: this.checked ? 1 : 0
                };
                admin.req({
                    url: '/admin/' + control_name + '/update'
                    , data: field
                    , method: 'POST'
                    , done: function (res) {
                        if (res.code === 0) {
                            layer.msg(res.msg, {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                            });
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                });
            });

            //监听工具条
            table.on('tool(LAY-app-list)', function (obj) {
                var data = obj.data;
                if (obj.event === 'del') {
                    layer.confirm('确定删除此文章？', function (index) {
                        obj.del();
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    layer.open({
                        type: 2
                        , title: '编辑文章'
                        , content: '../../../views/app/content/listform.html?id=' + data.id
                        , maxmin: true
                        , area: ['550px', '550px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-edit");

                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-edit)', function (data) {
                                var field = data.field; //获取提交的字段

                                //提交 Ajax 成功后，静态更新表格中的数据
                                //$.ajax({});
                                obj.update({
                                    label: field.label
                                    , title: field.title
                                    , author: field.author
                                    , status: field.status
                                }); //数据更新

                                form.render();
                                layer.close(index); //关闭弹层
                            });

                            submit.trigger('click');
                        }
                    });
                }
            });

            //按钮组
            var $ = layui.$, active = {
                batchdel: function () {
                    var checkStatus = table.checkStatus('LAY-app-list')
                        , checkData = checkStatus.data; //得到选中的数据

                    if (checkData.length === 0) {
                        return layer.msg('请选择数据');
                    }

                    layer.confirm('确定删除吗？', function (index) {

                        //执行 Ajax 后重载
                        /*
                        admin.req({
                          url: 'xxx'
                          //,……
                        });
                        */
                        table.reload('LAY-app-list');
                        layer.msg('已删除');
                    });
                },
                add: function () {
                    layer.open({
                        type: 2
                        , title: '添加文章'
                        , content: 'listform.html'
                        , maxmin: true
                        , area: ['550px', '550px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            //点击确认触发 iframe 内容中的按钮提交
                            var submit = layero.find('iframe').contents().find("#layuiadmin-app-form-submit");
                            submit.click();
                        }
                    });
                }
            };

            $('.layui-btn.layuiadmin-btn-list').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        });
    </script>
@endsection