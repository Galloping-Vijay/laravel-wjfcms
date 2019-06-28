@extends('layouts.admin')
@section('title', '文章列表')
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
                                    <option value="{{ $cv->id }}">{{ $cv->name }}</option>
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
            <!-- 控制名 -->
            <input type="hidden" name="control_name" value="{{ $control_name }}">
            <div class="layui-card-body">
                <div style="padding-bottom: 10px;">
                    @can('创建文章')
                        <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                    @endcan
                    @can('删除文章')
                        <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
                    @endcan
                </div>
                <table id="LAY-app-list" lay-filter="LAY-app-list"></table>
                <script type="text/html" id="statusTpl">
                    @{{#  if(d.status){ }}
                    <button class="layui-btn layui-btn-xs">已发布</button>
                    @{{#  } else { }}
                    <button class="layui-btn layui-btn-primary layui-btn-xs">待发布</button>
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
        var control_name = document.querySelector('input[name="control_name"]').getAttribute('value');
        var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table', 'form', 'crud'], function () {
            var table = layui.table;
            //文章管理
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
                    , {field: 'id', width: 100, title: '文章ID', sort: true}
                    , {field: 'cate_name', title: '文章分类', minWidth: 100}
                    , {field: 'title', title: '文章标题'}
                    , {field: 'author', title: '作者'}
                    , {field: 'created_at', title: '提交时间', sort: true}
                    , {field: 'status', title: '发布状态', templet: '#statusTpl', minWidth: 80, align: 'center'}
                    , {title: '操作', minWidth: 150, align: 'center', fixed: 'right', toolbar: '#table-list'}
                ]]
                , page: true
                , limit: 10
            });

        });
    </script>
@endsection