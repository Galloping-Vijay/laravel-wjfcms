@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">修改密码</div>
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">当前密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="oldPassword" lay-verify="required" lay-verType="tips"
                                           class="layui-input">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{  Auth::user()->id }}">
                            <div class="layui-form-item">
                                <label class="layui-form-label">新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password" lay-verify="required" lay-verType="tips"
                                           autocomplete="off" id="LAY_password" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">5到16个字符</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">确认新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="repassword" lay-verify="required" lay-verType="tips"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="setmypass">确认修改</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection

@section('script')
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'form', 'admin'], function () {
            var $ = layui.$
                , admin = layui.admin
                , form = layui.form;
            var control_name = $('meta[name="control_name"]').attr('content');
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            form.on('submit(setmypass)', function (data) {
                var field = data.field; //获取提交的字段
                if (field.oldPassword === field.password) {
                    layer.msg('新密码与旧密码相同', {icon: 2});
                    return false;
                }
                if (field.password != field.repassword) {
                    layer.msg('两次密码不一样', {icon: 2});
                    return false;
                }
                admin.req({
                    url: '/admin/' + control_name + '/password'
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
        });
    </script>
@endsection