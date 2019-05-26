@extends('layouts.admin')

@section('title', '欢迎来到我的cms')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
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
                        <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-contlist-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                    <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                </div>
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <script type="text/html" id="buttonTpl">
                    @{{#  if(d.status){ }}
                    <button class="layui-btn layui-btn-xs">已发布</button>
                    @{{#  } else { }}
                    <button class="layui-btn layui-btn-primary layui-btn-xs">待修改</button>
                    @{{#  } }}
                </script>
                <script type="text/html" id="table-list">
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                </script>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.config({
            base: '../../../layuiadmin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'table'], function(){
            var table = layui.table
                ,$ = layui.$
                ,form = layui.form;

            table.render({
                elem: '#LAY-app-list'
                ,url: '/admin/permission/index'
                ,method:'post'
                ,where:{}
                ,headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                ,cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    ,{field: 'id', width: 100, title: '文章ID', sort: true}
                    ,{field: 'label', title: '文章标签', minWidth: 100}
                    ,{field: 'title', title: '文章标题'}
                    ,{field: 'author', title: '作者'}
                    ,{field: 'uploadtime', title: '上传时间', sort: true}
                    ,{field: 'status', title: '发布状态', templet: '#buttonTpl', minWidth: 80, align: 'center'}
                    ,{title: '操作', minWidth: 150, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                ,page: true
                ,limit: 10
                ,limits: [10, 15, 20, 25, 30]
                ,text: '对不起，加载出现异常！'
            });

            //监听工具条
            table.on('tool(LAY-app-list)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('确定删除此文章？', function(index){
                        obj.del();
                        layer.close(index);
                    });
                } else if(obj.event === 'edit'){
                    layer.open({
                        type: 2
                        ,title: '编辑文章'
                        ,content: '../../../views/app/content/listform.html?id='+ data.id
                        ,maxmin: true
                        ,area: ['550px', '550px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submit = layero.find('iframe').contents().find("#layuiadmin-app-form-edit");

                            //监听提交
                            iframeWindow.layui.form.on('submit(layuiadmin-app-form-edit)', function(data){
                                var field = data.field; //获取提交的字段

                                //提交 Ajax 成功后，静态更新表格中的数据
                                //$.ajax({});
                                obj.update({
                                    label: field.label
                                    ,title: field.title
                                    ,author: field.author
                                    ,status: field.status
                                }); //数据更新

                                form.render();
                                layer.close(index); //关闭弹层
                            });

                            submit.trigger('click');
                        }
                    });
                }
            });

            //监听搜索
            form.on('submit(LAY-app-contlist-search)', function(data){
                var field = data.field;

                //执行重载
                table.reload('LAY-app-list', {
                    where: field
                });
            });

            var $ = layui.$, active = {
                batchdel: function(){
                    var checkStatus = table.checkStatus('LAY-app-list')
                        ,checkData = checkStatus.data; //得到选中的数据

                    if(checkData.length === 0){
                        return layer.msg('请选择数据');
                    }

                    layer.confirm('确定删除吗？', function(index) {

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
                add: function(){
                    layer.open({
                        type: 2
                        ,title: '添加文章'
                        ,content: 'listform.html'
                        ,maxmin: true
                        ,area: ['550px', '550px']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            //点击确认触发 iframe 内容中的按钮提交
                            var submit = layero.find('iframe').contents().find("#layuiadmin-app-form-submit");
                            submit.click();
                        }
                    });
                }
            };

            $('.layui-btn.layuiadmin-btn-list').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        });
    </script>
@endsection