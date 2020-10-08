@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
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
                        <label class="layui-form-label">文章分类</label>
                        <div class="layui-input-inline">
                            <select name="category_id">
                                <option value="">全部分类</option>
                                @foreach($category_list as $ck=>$cv)
                                    @if($category_id == $cv['id'])
                                        <option value="{{ $cv['id'] }}" selected>{!! $cv['name'] !!}</option>
                                    @else
                                        <option value="{{ $cv['id'] }}">{!! $cv['name'] !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">全部</option>
                                @foreach($status_list as $sk=>$sv)
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
            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    @can('删除文章')
                        <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                    @endcan
                    @can('创建文章')
                        <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <!-- 模板渲染 -->
                <script type="text/html" id="statusTpl">
                    @{{#  if(d.deleted_at == null){ }}
                    <input type="checkbox" name="status" lay-skin="switch" lay-filter="table-button-status"
                           data-id="@{{ d.id }}" lay-text="已审核|待审核" @{{ d.status?'checked':'' }}>
                    @{{#  } }}
                </script>
                <script type="text/html" id="topTpl">
                    @{{#  if(d.deleted_at == null){ }}
                    <input type="checkbox" name="is_top" lay-skin="switch" lay-filter="table-button-top"
                           data-id="@{{ d.id }}" lay-text="是|否" @{{ d.is_top?'checked':'' }}>
                    @{{#  } }}
                </script>
                <script type="text/html" id="table-list">
                    @{{#  if(d.deleted_at == null){ }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">
                        <i class="layui-icon layui-icon-edit"></i>编辑
                    </a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
                        <i class="layui-icon layui-icon-delete"></i>删除
                    </a>
                    @{{#  if(d.status == 1 && d.is_baijiahao == 0){ }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="baijiahao_publish">
                        <i class="layui-icon layui-icon-top"></i>推送到百家号</a>
                    @{{#  } else { }}
                    <a class="layui-btn layui-btn-disabled layui-btn-xs">
                        <i class="layui-icon layui-icon-top"></i>推送到百家号</a>
                    @{{#  } }}
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
            var category_id = getQueryVariable('category_id');

            //将以下代码粘入相关页面中
            $(document).off('mousedown','.layui-table-grid-down').
            on('mousedown','.layui-table-grid-down',function (event) {
                table._tableTrCurrr = $(this).closest('td');
            });
            $(document).off('click','.layui-table-tips-main [lay-event]').
            on('click','.layui-table-tips-main [lay-event]',function (event) {
                var elem = $(this);
                var tableTrCurrr =  table._tableTrCurrr;
                if(!tableTrCurrr){
                    return;
                }
                var layerIndex = elem.closest('.layui-table-tips').attr('times');
                console.log(layerIndex);
                layer.close(layerIndex);
                table._tableTrCurrr.find('[lay-event="' + elem.attr('lay-event') +           '"]').children("i").first().click();
            });

            //表格数据
            table.render({
                elem: '#LAY-app-list'
                , url: '/admin/' + control_name + '/index'
                , method: 'post'
                , where: {
                    category_id: category_id
                }
                , headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', width: 100, title: '文章ID', sort: true, align: 'center'}
                    , {field: 'title', title: '文章标题', align: 'center'}
                    , {field: 'cate_name', title: '文章分类', minWidth: 100, align: 'center'}
                    , {field: 'keywords', title: '文章标签', minWidth: 100, align: 'center'}
                    , {field: 'author', title: '作者', align: 'center'}
                    , {field: 'created_at', title: '提交时间', sort: true, align: 'center'}
                    , {field: 'is_top', title: '是否置顶', templet: '#topTpl', minWidth: 80, align: 'center'}
                    , {field: 'status', title: '状态', templet: '#statusTpl', minWidth: 80, align: 'center'}
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

            //监听指定开关
            form.on('switch(table-button-top)', function (data) {
                var field = {
                    is_top: this.checked ? 1 : 0,
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
                                    layer.msg(res.msg, {icon: 2});
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
                        , area: ['800px', '500px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-edit");
                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-edit)', function (data) {
                                var field = data.field; //获取提交的字段
                                if (!field.title) {
                                    layer.msg('"标题"不能为空', {icon: 2});
                                    return false;
                                }
                                if (!field.category_id) {
                                    layer.msg('请选择分类', {icon: 2});
                                    return false;
                                }
                                if (!field.author) {
                                    layer.msg('作者不能为空', {icon: 2});
                                    return false;
                                }
                                if (!field.description) {
                                    layer.msg('描述不能为空', {icon: 2});
                                    return false;
                                }
                                var code_html = 'editor-html-code';
                                if (!field[code_html]) {
                                    layer.msg('内容不能为空', {icon: 2});
                                    return false;
                                }
                                var doc_html = 'editor-html-doc';
                                if (!field[doc_html]) {
                                    layer.msg('内容不能为空', {icon: 2});
                                    return false;
                                }
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
                                            layer.msg(res.msg, {icon: 2});
                                        }
                                    }
                                });
                            });
                            submit.trigger('click');
                        }
                    });
                    $('.layui-layer-max').trigger("click");
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
                } else if (obj.event === 'baijiahao_publish') {
                    layer.confirm('确定要推送到百家号吗?', function (index) {
                        admin.req({
                            url: '/admin/baijiahao/article/publish'
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
                    layer.open({
                        type: 2
                        , title: '添加'
                        , content: '/admin/' + control_name + '/create?category_id=' + category_id
                        , maxmin: true
                        , area: ['800px', '500px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            //点击确认触发 iframe 内容中的按钮提交
                            var iframeWindow = window['layui-layer-iframe' + index]
                                , submit = layero.find('iframe').contents().find("#layuiadmin-app-form-add");
                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-add)', function (data) {
                                var field = data.field;
                                if (!field.cover) {
                                    field.cover = window.location.protocol + "//" + window.location.host + '/images/config/default-img.jpg';
                                }
                                if (!field.title) {
                                    layer.msg('"标题"不能为空', {icon: 2});
                                    return false;
                                }
                                if (!field.category_id) {
                                    layer.msg('请选择分类', {icon: 2});
                                    return false;
                                }
                                if (!field.author) {
                                    layer.msg('作者不能为空', {icon: 2});
                                    return false;
                                }
                                if (!field.description) {
                                    layer.msg('描述不能为空', {icon: 2});
                                    return false;
                                }
                                var code_html = 'editor-html-code';
                                if (!field[code_html]) {
                                    layer.msg('内容不能为空', {icon: 2});
                                    return false;
                                }
                                var doc_html = 'editor-html-doc';
                                if (!field[doc_html]) {
                                    layer.msg('内容不能为空', {icon: 2});
                                    return false;
                                }
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
                                            layer.msg(res.msg, {icon: 2});
                                        }
                                    }
                                });
                            });
                            submit.trigger('click');
                        }
                    });
                    $('.layui-layer-max').trigger("click");
                }
            };
            $('.layui-btn.layuiadmin-btn-list').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        });

        /**
         * 获取url上的参数
         * @param variable
         * @returns {*}
         */
        function getQueryVariable(variable) {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                if (pair[0] == variable) {
                    return pair[1];
                }
            }
            return ('');
        }
    </script>
@endsection
