@extends('layouts.admin')
@section('title', '网站设置')
@section('header')
    <style>
        #up_logo {
            width: 92px;
            height: 92px;
            cursor: pointer
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid" id="component-tabs">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">卡片风格</div>
                    <div class="layui-card-body">
                        <div class="layui-tab layui-tab-card" lay-filter="component-tabs-brief">
                            <ul class="layui-tab-title">
                                <li class="layui-this">基本信息</li>
                                <li>联系方式</li>
                                <li>SEO设置</li>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show">
                                    <div class="layui-card-body" pad15>
                                        <div class="layui-form" wid100 lay-filter="">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">网站名称</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_name"
                                                           value="{{ $config['site_name'] }}" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">网站域名</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_url" lay-verify="url"
                                                           value="{{ $config['site_url'] }}" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">网站LOGO</label>
                                                <div class="layui-input-block">
                                                    <div class="layui-upload">
                                                        <div class="layui-upload-list">
                                                            <input type="hidden" name="site_logo"
                                                                   value="{{ $config['site_logo'] }}">
                                                            <img class="layui-upload-img" id="up_logo"
                                                                 src="{{ !empty($config['site_logo'])?$config['site_logo']:'/images/config/default-img.jpg' }}">
                                                            <p id="up_logo_text"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">备案信息</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_icp" value="{{ $config['site_icp'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">统计代码</label>
                                                <div class="layui-input-block">
                                                    <textarea name="site_tongji"
                                                              class="layui-textarea">{{ $config['site_tongji'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">版权信息</label>
                                                <div class="layui-input-block">
                                                    <textarea name="site_copyright"
                                                              class="layui-textarea">{{ $config['site_copyright'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit lay-filter="set_website">确认保存
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="layui-tab-item">2</div>
                                <div class="layui-tab-item">
                                    <div class="layui-card-body" pad15>
                                        <div class="layui-form" wid100 lay-filter="">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">网站名称</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="sitename" value="layuiAdmin"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">网站域名</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="domain" lay-verify="url"
                                                           value="http://www.layui.com" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">首页标题</label>
                                                <div class="layui-input-block">
                                                    <textarea name="title"
                                                              class="layui-textarea">layuiAdmin 通用后台管理模板系统</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">META关键词</label>
                                                <div class="layui-input-block">
                                                    <textarea name="keywords" class="layui-textarea"
                                                              placeholder="多个关键词用英文状态 , 号分割"></textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">META描述</label>
                                                <div class="layui-input-block">
                                                    <textarea name="descript" class="layui-textarea">layuiAdmin 是 layui 官方出品的通用型后台模板解决方案，提供了单页版和 iframe 版两种开发模式。layuiAdmin 是目前非常流行的后台模板框架，广泛用于各类管理平台。</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">版权信息</label>
                                                <div class="layui-input-block">
                                                    <textarea name="copyright" class="layui-textarea">© 2018 layui.com MIT license</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit lay-filter="set_website">确认保存
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        }).use(['index', 'admin', 'upload'], function () {
            var table = layui.table
                , $ = layui.$
                , admin = layui.admin
                , form = layui.form
                , upload = layui.upload
                , element = layui.element
                , router = layui.router();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            element.on('tab(component-tabs-brief)', function (obj) {
                layer.msg(obj.index + '：' + this.innerHTML);
            });

            var uploadInst = upload.render({
                elem: '#up_logo'
                , url: '/admin/systemConfig/basal'
                , headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
                , accept: 'images'
                , field: "file"
                , type: 'images'
                , exts: 'jpg|png|gif' //设置一些后缀，用于演示前端验证和后端的验证
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#up_logo').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('input[name="site_logo"]').val(res.data);
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    var up_logo_text = $('#up_logo_text');
                    up_logo_text.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    up_logo_text.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });


        });
    </script>
@endsection