@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <!-- 搜索 -->
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">关键词</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body" style="min-height: 600px">
            <!-- 按钮组 -->
            <div style="padding-bottom: 10px;">
                @can('删除关键词')
                <button class="layui-btn layuiadmin-btn-list layui-btn-danger" data-type="batchdel">删除</button>
                @endcan
                @can('创建关键词')
                <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                @endcan
            </div>
            <!-- 表格 -->
            <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
            <!-- 模板渲染 -->
            <script type="text/html" id="statusTpl">
                @{{#  if(d.deleted_at == null){ }}
                <input type="checkbox" name="status" lay-skin="switch" lay-filter="table-button-status"
                       data-id="@{{ d.id }}" lay-text="已审核|待审核" @{{ d.status?'checked':'' }}>
                @{{#  } }}
            </script>
            <!-- 模板渲染 -->
            <script type="text/html" id="table-list">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">
                    <i class="layui-icon layui-icon-edit"></i>编辑
                </a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
                    <i class="layui-icon layui-icon-delete"></i>删除
                </a>
            </script>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var control_name = document.querySelector('meta[name="control_name"]').getAttribute('content');
    var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    layui.config({
        base: "/static/layuiadmin/"
    }).extend({
        index: 'lib/index'
    }).use(['index', 'table', 'form'], function () {
        var $ = layui.$
            , table = layui.table
            , layer = layui.layer
            , admin = layui.admin
            , form = layui.form;
        //表格数据
        table.render({
            elem: '#LAY-app-list'
            , url: '/admin/' + control_name + '/index'
            , method: 'post'
            , where: {}
            , headers: {
                'X-CSRF-TOKEN': csrf_token
            }
            , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', width: 100, title: 'ID', align: 'center', sort: true}
                , {field: 'sort', width: 80, align: 'center', title: '排序', edit: 'text', sort: true}
                , {field: 'key_name', title: '关键词', align: 'center',}
                , {field: 'key_value', title: '关键词内容', align: 'center',}
                , {field: 'status', title: '状态', templet: '#statusTpl', minWidth: 80, align: 'center'}
                , {field: 'created_at', title: '提交时间', sort: true}
                , {title: '操作', minWidth: 200, align: 'center', fixed: 'right', toolbar: '#table-list'}
            ]]
            , page: true
            , limit: 10
            , done: function (res, curr, count) {
                //如果是异步请求数据方式，res即为你接口返回的信息。
            }
        });

        //监听搜索
        form.on('submit(LAY-app-search)', function (data) {
            var field = data.field;
            //执行重载
            table.reload('LAY-app-list', {
                where: field
            });
        });

        //监听单元格编辑
        table.on('edit(LAY-app-list)', function (obj) {
            var value = obj.value //得到修改后的值
                , data = obj.data //得到所在行所有键值
                , field_name = obj.field; //得到字段
            var field = {
                id: data.id
                , [field_name]: value
            };
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
                        });
                    } else {
                        layer.msg(res.msg, {icon: 2});;
                    }
                }
            });
        });

        //监听指定开关
        form.on('switch(table-button-status)', function (data) {
            var field = {
                status: this.checked ? 1 : 0,
                id: $(this).data('id')
            };
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
                        });
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                }
            });
        });


        //监听工具条
        table.on('tool(LAY-app-list)', function (obj) {
            if (obj.event === 'del') {
                layer.confirm('确定删除数据吗?', function (index) {
                    admin.req({
                        url: '/admin/' + control_name + '/destroy'
                        , data: {id: obj.data.id}
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
                                    obj.del();
                                    layer.close(index);
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2});;
                            }
                        }
                    });
                });
            } else if (obj.event === 'edit') {
                layer.open({
                    type: 2
                    , title: '编辑'
                    , content: '/admin/' + control_name + '/edit/' + obj.data.id
                    , maxmin: true
                    , area: ['400px', '400px']
                    , btn: ['确定', '取消']
                    , yes: function (index, layero) {
                        var iframeWindow = window['layui-layer-iframe' + index]
                            , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-edit");
                        //监听提交
                        iframeWindow.layui.form.on('submit(layuiadmin-app-form-edit)', function (data) {
                            var field = data.field; //获取提交的字段
                            //提交 Ajax 成功后，静态更新表格中的数据
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
                                            table.reload('LAY-app-list');
                                            layer.close(index); //关闭弹层
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});;
                                    }
                                }
                            });
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
                    return layer.msg('请选择数据', {icon: 2});
                }
                var ids = [];
                for (i in checkData) {
                    ids.push(checkData[i].id);
                }
                layer.confirm('确定批量删除吗？', function (index) {
                    admin.req({
                        url: '/admin/' + control_name + '/destroy'
                        , data: {id: ids}
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
                                    table.reload('LAY-app-list');
                                    layer.close(index); //关闭弹层
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2});;
                            }
                        }
                    });
                });
            },
            add: function () {
                layer.open({
                    type: 2
                    , title: '添加'
                    , content: '/admin/' + control_name + '/create'
                    , maxmin: true
                    , area: ['400px', '400px']
                    , btn: ['确定', '取消']
                    , yes: function (index, layero) {
                        //点击确认触发 iframe 内容中的按钮提交
                        var iframeWindow = window['layui-layer-iframe' + index]
                            , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-add");
                        //监听提交
                        iframeWindow.layui.form.on('submit(layuiadmin-app-form-add)', function (data) {
                            var field = data.field;
                            //提交 Ajax 成功后，静态更新表格中的数据
                            admin.req({
                                url: '/admin/' + control_name + '/store'
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
                                            table.reload('LAY-app-list');
                                            layer.close(index); //关闭弹层
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});;
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
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
