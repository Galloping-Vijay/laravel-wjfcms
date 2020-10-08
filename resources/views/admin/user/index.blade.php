@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <!-- 搜索 -->
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">昵称</label>
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

            <div class="layui-card-body">
                <!-- 按钮组 -->
                <div style="padding-bottom: 10px;">
                    @can('删除用户')
                        <button class="layui-btn layuiadmin-btn-list layui-btn-danger" data-type="batchdel">删除</button>
                    @endcan
                    @can('创建用户')
                            <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <!-- 表格 -->
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <!-- 模板渲染 -->
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
        }).use(['index', 'table', 'admin'], function () {
            var table = layui.table
                , $ = layui.$
                , admin = layui.admin
                , form = layui.form;
            var control_name = $('meta[name="control_name"]').attr('content');
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
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
                    , {field: 'id', align: 'center',  width: 80, title: 'ID', sort: true}
                    , {field: 'name', align: 'center',  title: '昵称'}
                    , {field: 'email', align: 'center',  title: '邮箱'}
                    , {field: 'created_at', align: 'center',  title: '创建时间'}
                    , {title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                , page: true
                , limit: 10
            });

            //监听搜索
            form.on('submit(LAY-app-search)', function (data) {
                var field = data.field;
                //执行重载
                table.reload('LAY-app-list', {
                    where: field
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
                                                    name: field.name
                                                    , tel: field.tel
                                                });
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
                } else if (obj.event === 'account') {
                    var html = '<div style="margin-top: 5%" class="layui-form-item"> <label class="layui-form-label">登录邮箱：</label> <div class="layui-input-inline"> <input type="text" name="email_val" value="' + obj.data.email + '" lay-verify="required" placeholder="请输入邮箱" class="layui-input"> </div> </div> <div class="layui-form-item"><label class="layui-form-label">登录密码：</label> <div class="layui-input-inline"><input type="password" name="password" lay-verify="required"  class="layui-input"></div></div>';
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
                            var email_val = $("input[name='email_val']").val();
                            if (password == '') {
                                layer.msg('密码不能为空', {icon: 2});
                                return false;
                            }
                            if (email_val == '') {
                                layer.msg('账号不能为空', {icon: 2});
                                return false;
                            }
                            var field = {
                                id: obj.data.id,
                                password: password,
                                email: email_val
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
                                            obj.update({
                                                account: field.account
                                            }); //数据更新
                                            layer.close(index); //关闭弹层
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});;
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

        })
        ;
    </script>
@endsection