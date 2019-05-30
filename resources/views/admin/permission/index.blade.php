@extends('layouts.admin')
@section('title', '菜单列表')
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
                        <label class="layui-form-label">权限组</label>
                        <div class="layui-input-inline">
                            <input type="text" name="guard_name" placeholder="请输入" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">菜单是否显示</label>
                        <div class="layui-input-inline">
                            <select name="display_menu">
                                <option value="">全部</option>
                                @foreach($display_menu as $sk=>$sv)
                                    <option value="{{ $sk }}">{{ $sv }}</option>
                                @endforeach
                            </select>
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
                    @can('删除权限')
                        <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                    @endcan
                    @can('创建权限')
                        <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <!-- 表格 -->
                <table id="LAY-app-list" lay-filter="LAY-app-list" ></table>
                <!-- 控制名 -->
                <input type="hidden" name="control_name" value="{{ $control_name }}">
                <!-- 模板渲染 -->
                <script type="text/html" id="statusTpl">
                    @{{#  if(d.deleted_at == null){ }}
                    <input type="checkbox" name="display_menu" lay-skin="switch" lay-filter="table-button-status"
                           data-id="@{{ d.id }}" lay-text="ON|OFF" @{{ d.display_menu?'checked':'' }}>
                    @{{#  } }}
                </script>
                <script type="text/html" id="table-list">
                    @{{#  if(d.deleted_at == null){ }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">
                        <i class="layui-icon layui-icon-edit"></i>编辑
                    </a>
                    <a class="layui-btn layui-btn-xs" lay-event="account">
                        <i class="layui-icon layui-icon-password"></i>账号密码
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
            var control_name = $('input[name="control_name"]').val();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            //表格数据树
            ptable = treeGrid.render({
                id: 'LAY-app-list'
                , elem: '#LAY-app-list'
                , idField: 'id'
                , url: '/admin/' + control_name + '/index'
                , cellMinWidth: 100
                , treeId: 'id'//树形id字段名称
                , treeUpId: 'parent_id'//树形父id字段名称
                , treeShowName: 'name'//以树形式显示的字段
                , headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
                , where: {_token: csrf_token}
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', width: 80, title: 'ID', sort: true}
                    , {field: 'sort_order', width: 80, title: '排序', edit: 'text', sort: true}
                    , {field: 'name', title: '名称'}
                    , {field: 'guard_name', title: '权限组'}
                    , {field: 'url', title: '权限地址'}
                    , {field: 'icon', title: '图标'}
                    , {field: 'display_menu', width: 80, title: '显示菜单', templet: '#statusTpl', align: 'center'}
                    , {title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                , page: false
                , done: function (res, curr, count) {
                    //如果是异步请求数据方式，res即为你接口返回的信息。
                }
            });
            //监听工具条
            treeGrid.on('tool(LAY-app-list)', function (obj) {
                if (obj.event === 'del') {
                    layer.confirm('确定删除数据吗?', function (index) {
                        layer.msg('111');
                        return false;
                        admin.req({
                            url: '/admin/' + control_name + '/destroy'
                            , data: {id: obj.data.id}
                            , method: 'POST'
                            , headers: {
                                'X-CSRF-TOKEN': csrf_token
                            }
                            , done: function (res) {
                                if (res.code === 0) {
                                    //登入成功的提示与跳转
                                    layer.msg('操作成功', {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    }, function () {
                                        obj.del();
                                        layer.close(index);
                                    });
                                } else {
                                    layer.msg('操作失败');
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    layer.open({
                        type: 2
                        , title: '编辑文章'
                        , content: '/admin/' + control_name + '/edit/' + obj.data.id
                        , maxmin: true
                        , area: ['450px', '400px']
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
                                    , done: function (res) {
                                        if (res.code === 0) {
                                            //登入成功的提示与跳转
                                            layer.msg('操作成功', {
                                                offset: '15px'
                                                , icon: 1
                                                , time: 1000
                                            }, function () {
                                                obj.update({
                                                    account: field.account
                                                    , username: field.username
                                                    , tel: field.tel
                                                    , sex: field.sex
                                                    , display_menu: field.display_menu
                                                });
                                                table.reload('LAY-app-list');
                                                layer.close(index); //关闭弹层
                                            });
                                        } else {
                                            layer.msg('操作失败');
                                        }

                                    }
                                });
                            });
                            submit.trigger('click');
                        }
                    });
                } else if (obj.event === 'account') {
                    var html = '<div style="margin-top: 5%" class="layui-form-item"> <label class="layui-form-label">登录账号：</label> <div class="layui-input-inline"> <input type="text" name="account_val" value="' + obj.data.account + '" lay-verify="required" placeholder="请输入账号" class="layui-input"> </div> </div> <div class="layui-form-item"><label class="layui-form-label">登录密码：</label> <div class="layui-input-inline"><input type="password" name="password" lay-verify="required"  class="layui-input"></div></div>';
                    layer.open({
                        type: 1
                        , title: '账号密码'
                        , offset: 'auto'
                        , id: 'layerDemo'
                        , content: html
                        , btn: ['确定', '取消']
                        , btnAlign: 'c' //按钮居中
                        , shade: 0 //不显示遮罩
                        , yes: function (index, layero) {
                            var password = $("input[name='password']").val();
                            var account_val = $("input[name='account_val']").val();
                            if (password == '') {
                                layer.msg('密码不能为空!');
                                return false;
                            }
                            if (account_val == '') {
                                layer.msg('账号不能为空!');
                                return false;
                            }
                            var field = {
                                id: obj.data.id,
                                password: password,
                                account: account_val
                            };
                            admin.req({
                                url: '/admin/' + control_name + '/update'
                                , data: field
                                , method: 'POST'
                                , headers: {
                                    'X-CSRF-TOKEN': csrf_token
                                }
                                , done: function (res) {
                                    if (res.code === 0) {
                                        layer.msg(res.msg, {
                                            offset: '15px'
                                            , icon: 1
                                            , time: 1000
                                        }, function () {
                                            obj.update({
                                                account: field.account
                                            }); //数据更新
                                            layer.close(index); //关闭弹层
                                        });
                                    } else {
                                        layer.msg(res.msg);
                                    }
                                }
                            });
                        }
                        , btn2: function (index, layero) {
                            layer.closeAll();
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
                            , done: function (res) {
                                if (res.code === 0) {
                                    //登入成功的提示与跳转
                                    layer.msg('操作成功', {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    }, function () {
                                        table.reload('LAY-app-list');
                                        layer.close(index); //关闭弹层
                                    });
                                } else {
                                    layer.msg('操作失败');
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
                            , done: function (res) {
                                if (res.code === 0) {
                                    //登入成功的提示与跳转
                                    layer.msg('操作成功', {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    }, function () {
                                        obj.del();
                                        layer.close(index);
                                    });
                                } else {
                                    layer.msg('操作失败');
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
                table.reload('LAY-app-list', {
                    where: field
                });
            });

            //监听指定开关
            form.on('switch(table-button-status)', function (data) {
                var field = {
                    display_menu: this.checked ? 1 : 0,
                    id: $(this).data('id')
                };
                admin.req({
                    url: '/admin/' + control_name + '/update'
                    , data: field
                    , method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
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

            //按钮组
            var $ = layui.$, active = {
                batchdel: function () {
                    var checkStatus = table.checkStatus('LAY-app-list')
                        , checkData = checkStatus.data; //得到选中的数据
                    if (checkData.length === 0) {
                        return layer.msg('请选择数据');
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
                            , done: function (res) {
                                if (res.code === 0) {
                                    //登入成功的提示与跳转
                                    layer.msg('操作成功', {
                                        offset: '15px'
                                        , icon: 1
                                        , time: 1000
                                    }, function () {
                                        table.reload('LAY-app-list');
                                        layer.close(index); //关闭弹层
                                    });
                                } else {
                                    layer.msg('操作失败');
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
                        , area: ['450px', '400px']
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
                                    , done: function (res) {
                                        if (res.code === 0) {
                                            //登入成功的提示与跳转
                                            layer.msg('操作成功', {
                                                offset: '15px'
                                                , icon: 1
                                                , time: 1000
                                            }, function () {
                                                table.reload('LAY-app-list');
                                                layer.close(index); //关闭弹层
                                            });
                                        } else {
                                            layer.msg('操作失败');
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