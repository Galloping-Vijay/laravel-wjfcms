@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">设置我的资料</div>
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">我的角色</label>
                                <div class="layui-input-inline">
                                    <select name="role_names" id="role_names">
                                        <option value="">请选择</option>
                                        @foreach($role_list as $val)
                                            @if(in_array($val->name,$selected_role))
                                                <option selected value="{{ $val->name }}">{{ $val->name }}</option>
                                            @else
                                                <option value="{{ $val->name }}">{{ $val->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{  Auth::user()->id }}">
                            <div class="layui-form-item">
                                <label class="layui-form-label">账号</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="account" value="{{ Auth::user()->account }}"
                                           class="layui-input" readonly>
                                </div>
                                <div class="layui-form-mid layui-word-aux">不可修改。一般用于后台登入名</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="username" value="{{ Auth::user()->username }}"
                                           lay-verify="username" autocomplete="off" placeholder="请输入昵称"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">电话</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="tel" value="{{ Auth::user()->tel }}"
                                           placeholder="请输入手机号码" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-inline">
                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                           placeholder="请输入邮箱" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="sex" value="0"
                                           title="男" {{ Auth::user()->sex==0?'checked':'' }}>
                                    <input type="radio" name="sex" value="1"
                                           title="女" {{ Auth::user()->sex==1?'checked':'' }}>
                                    <input type="radio" name="sex" value="-1"
                                           title="保密" {{ Auth::user()->sex==-1?'checked':'' }}>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-inline">
                                    <input type="hidden" name="status" value="{{ Auth::user()->status }}">
                                    <input type="checkbox"
                                           {{ Auth::user()->status==1?'checked':'' }} lay-verify="required"
                                           lay-filter="status"
                                           lay-skin="switch"
                                           lay-text="正常|禁用">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="setmyinfo">确认修改</button>
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
        }).use(['index', 'admin', 'form'], function () {
            var $ = layui.$
                , admin = layui.admin
                , form = layui.form;
            var control_name = $('meta[name="control_name"]').attr('content');
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            //监听指定开关
            form.on('switch(status)', function () {
                if (this.checked) {
                    $("input[name='status']").val('1');
                } else {
                    $("input[name='status']").val('0');
                }
            });
            form.on('submit(setmyinfo)', function (data) {
                var field = data.field; //获取提交的字段
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
                            layer.msg('如果修改了角色或昵称,请退出重新登录', {
                                offset: '15px'
                                , icon: 1
                                , time: 5000
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