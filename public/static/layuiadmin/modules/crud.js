/**
 @Name：通用增删改查
 @Author：Vijay
 @Site：http://www.choudalao.com
 */
layui.define(['table', 'form'], function (exports) {
    var $ = layui.$
        , table = layui.table
        , layer = layui.layer
        , admin = layui.admin
        , form = layui.form;
    if (typeof(control_name) == "undefined") {
        control_name = $('meta[name="control_name"]').attr('content');
    }
    if (typeof(csrf_token) == "undefined") {
        csrf_token = $('meta[name="csrf-token"]').attr('content');
    }
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
                    layer.msg(res.msg);
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
                            layer.msg(res.msg);
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
                                        table.reload('LAY-app-list');
                                        layer.close(index); //关闭弹层
                                    });
                                } else {
                                    layer.msg(res.msg);
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
                                table.reload('LAY-app-list');
                                layer.close(index); //关闭弹层
                            });
                        } else {
                            layer.msg(res.msg);
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
                            layer.msg(res.msg);
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
                            layer.msg(res.msg);
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
                                    layer.msg(res.msg);
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
    exports('crud', {})
});