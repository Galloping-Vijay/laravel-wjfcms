@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">内容</label>
                        <div class="layui-input-inline">
                            <input type="text" name="content" placeholder="" autocomplete="off" class="layui-input">
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
                <div style="padding-bottom: 10px;">
                    @can('删除有些话')
                        <button class="layui-btn layuiadmin-btn-list layui-btn-danger" data-type="batchdel">删除</button>
                    @endcan
                    @can('添加有些话')
                        <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <!-- 模板渲染 -->
                <script type="text/html" id="table-list">
                    @{{#  if(d.deleted_at == null){ }}
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
                    , {field: 'id', width: 100, title: 'ID', sort: true, align: 'center'}
                    , {field: 'content', title: '内容', align: 'center'}
                    , {field: 'created_at', title: '提交时间', sort: true, align: 'center'}
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
                                    layer.msg(res.msg, {icon: 2});
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    var html = '<div style="margin-top: 5%" class="layui-form-item"><label class="layui-form-label">内容：</label><div class="layui-input-block"><textarea rows="8px" style="width: 300px;" name="content_val" class="layui-textarea">' + obj.data.content + '</textarea></div></div>';
                    layer.open({
                        type: 1
                        , title: '有些话'
                        , offset: 'auto'
                        , id: 'layerDemo'
                        , content: html
                        , area: ['500px', '300px']
                        , btn: ['确定', '取消']
                        , btnAlign: 'c' //按钮居中
                        , shade: 0 //不显示遮罩
                        , yes: function (index, layero) {
                            var content = $("textarea[name='content_val']").val();
                            if (content == '') {
                                layer.msg('内容不能为空!', {icon: 2});
                                return false;
                            }
                            admin.req({
                                url: '/admin/' + control_name + '/update'
                                , data: {id: obj.data.id, content: content}
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
                                            layer.close(index); //关闭弹层
                                            table.reload('LAY-app-list');
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
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
                                    layer.msg(res.msg, {icon: 2});
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
                                    layer.msg(res.msg, {icon: 2});
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
                                    layer.msg(res.msg, {icon: 2});
                                }
                            }
                        });
                    });
                },
                add: function () {
                    var html = '<div style="margin-top: 5%" class="layui-form-item"><label class="layui-form-label">内容：</label><div class="layui-input-block"><textarea rows="8px" style="width: 300px;" name="content_val" class="layui-textarea"></textarea></div></div>';
                    layer.open({
                        type: 1
                        , title: '有些话'
                        , offset: 'auto'
                        , id: 'layerDemo'
                        , content: html
                        , area: ['500px', '300px']
                        , btn: ['确定', '取消']
                        , btnAlign: 'c' //按钮居中
                        , shade: 0 //不显示遮罩
                        , yes: function (index, layero) {
                            var content = $("textarea[name='content_val']").val();
                            if (content == '') {
                                layer.msg('内容不能为空', {icon: 2});
                                return false;
                            }
                            admin.req({
                                url: '/admin/' + control_name + '/store'
                                , data: {content: content}
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
                                            layer.close(index); //关闭弹层
                                            table.reload('LAY-app-list');
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                }
                            });
                        }
                        , btn2: function (index, layero) {
                            layer.closeAll();
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