@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <!-- 搜索 -->
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否删除</label>
                        <div class="layui-input-inline">
                            <select name="delete">
                                @foreach($delete_list as $dk=>$dv)
                                    <option value="{{ $dk }}">{{ $dv }}</option>
                                @endforeach
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

            <div class="layui-card-body" style="min-height: 600px">
                <!-- 按钮组 -->
                <div style="padding-bottom: 10px;">
                    @can('删除前台菜单')
                        <button class="layui-btn layuiadmin-btn-list layui-btn-danger" data-type="batchdel">删除</button>
                    @endcan
                    @can('创建前台菜单')
                        <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <!-- 表格 -->
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <!-- 模板渲染 -->
                <script type="text/html" id="table-list">
                    @{{#  if(d.deleted_at == null){ }}
                    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="submenu">
                        <i class="layui-icon layui-icon-add-circle-fine"></i>添加子菜单</a>
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">
                        <i class="layui-icon layui-icon-edit"></i>编辑
                    </a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
                        <i class="layui-icon layui-icon-delete"></i>删除
                    </a>
                    @{{#  } else { }}
                    <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="restore">
                        <i class="layui-icon layui-icon-refresh-3"></i>恢复数据</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="force_del">
                        <i class="layui-icon layui-icon-fonts-del"></i>彻底删除</a>
                    @{{#  } }}
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
        }).use(['index', 'table', 'admin', 'treeGrid'], function () {
            var table = layui.table
                , $ = layui.$
                , admin = layui.admin
                , treeGrid = layui.treeGrid
                , form = layui.form;
            var control_name = $('meta[name="control_name"]').attr('content');
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            //表格数据树
            ptable = treeGrid.render({
                id: 'LAY-app-list'
                , elem: '#LAY-app-list'
                , idField: 'id'
                , url: '/admin/' + control_name + '/index'
                , cellMinWidth: 100
                , treeId: 'id'//树形id字段名称
                , treeUpId: 'pid'//树形父id字段名称
                , treeShowName: 'name'//以树形式显示的字段
                , headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
                , where: {_token: csrf_token}
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', width: 80, title: 'ID', sort: true}
                    , {field: 'sort', width: 80, title: '排序', edit: 'text', sort: true}
                    , {field: 'name', title: '名称'}
                    , {field: 'url', title: '地址'}
                    , {title: '操作', width: 350, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                , page: false
                , done: function (res, curr, count) {
                    //如果是异步请求数据方式，res即为你接口返回的信息。
                }
            });

            //监听单元格编辑
            treeGrid.on('edit(LAY-app-list)', function (obj) {
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

            //监听工具条
            treeGrid.on('tool(LAY-app-list)', function (obj) {
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
                                                obj.update({
                                                    sort_order: field.sort_order
                                                    , name: field.name
                                                    , guard_name: field.guard_name
                                                    , url: field.url
                                                    , icon: field.icon
                                                    , display_menu: field.display_menu
                                                });
                                                treeGrid.reload('LAY-app-list');
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
                } else if (obj.event === 'submenu') {
                    layer.open({
                        type: 2
                        , title: '添加子菜单'
                        , content: '/admin/' + control_name + '/create?superclass_id=' + obj.data.id
                        , headers: {
                            'X-CSRF-TOKEN': csrf_token
                        }
                        , maxmin: true
                        , area: ['400px', '400px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-add");
                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-add)', function (data) {
                                var field = data.field;
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
                                                treeGrid.reload('LAY-app-list');
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
                } else if (obj.event === 'restore') {
                    layer.confirm('确定恢复数据吗?', function (index) {
                        admin.req({
                            url: '/admin/' + control_name + '/restore'
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
                                        treeGrid.reload('LAY-app-list');
                                        layer.close(index); //关闭弹层
                                    });
                                } else {
                                    layer.msg(res.msg, {icon: 2});;
                                }
                            }
                        });
                    });
                } else if (obj.event === 'force_del') {
                    layer.confirm('确定要彻底删除数据吗?', function (index) {
                        admin.req({
                            url: '/admin/' + control_name + '/forceDelete'
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
                }
            });

            //监听搜索
            form.on('submit(LAY-app-search)', function (data) {
                var field = data.field;
                //执行重载
                treeGrid.reload('LAY-app-list', {
                    where: field
                });
            });

            //按钮组
            var $ = layui.$, active = {
                batchdel: function () {
                    return layer.msg('暂不支持菜单批量删除', {icon: 2});
                    var checkStatus = treeGrid.checkStatus('LAY-app-list')
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
                                        treeGrid.reload('LAY-app-list');
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
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-add");
                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-add)', function (data) {
                                var field = data.field;
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
                                                treeGrid.reload('LAY-app-list');
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

        })
        ;
    </script>
@endsection