@extends('layouts.admin')
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
                                                              class="layui-textarea">{{ html_entity_decode($config['site_tongji']) }}</textarea>
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
                                                    <button class="layui-btn" lay-submit id="set_website"
                                                            lay-filter="set_website">确认保存
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="layui-tab-item">
                                    <div class="layui-card-body" pad15>
                                        <div class="layui-form" wid100 lay-filter="">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">公司名称</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_co_name"
                                                           value="{{ $config['site_co_name'] }}" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">公司地址</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="address"
                                                           value="{{ $config['address'] }}" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">地图lat</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="map_lat" value="{{ $config['map_lat'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">地图lng</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="map_lng" value="{{ $config['map_lng'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">联系电话</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_phone" value="{{ $config['site_phone'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">联系邮箱</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_email" value="{{ $config['site_email'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">QQ</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_qq" value="{{ $config['site_qq'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">WeChat</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="site_wechat" value="{{ $config['site_wechat'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>

                                            <div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit id="set_website"
                                                            lay-filter="set_website">确认保存
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-tab-item">
                                    <div class="layui-card-body" pad15>
                                        <div class="layui-form" wid100 lay-filter="">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">首页SEO标题</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="seo_title" value="{{ $config['seo_title'] }}"
                                                           class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">首页SEO关键字</label>
                                                <div class="layui-input-block">
                                                    <textarea name="site_seo_keywords" class="layui-textarea"
                                                              placeholder="多个关键词用英文状态 , 号分割">{{ $config['site_seo_keywords'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="layui-form-item layui-form-text">
                                                <label class="layui-form-label">首页SEO描述</label>
                                                <div class="layui-input-block">
                                                    <textarea name="site_seo_description" class="layui-textarea">{{ $config['site_seo_description'] }}</textarea>
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
            base: "/static/layuiadmin/" //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'admin', 'upload', 'form'], function () {
            var table = layui.table
                , $ = layui.$
                , admin = layui.admin
                , form = layui.form
                , upload = layui.upload
                , element = layui.element
                , router = layui.router();
            form.render();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            //导航切换事件
            // element.on('tab(component-tabs-brief)', function (obj) {
            //     layer.msg(obj.index + '：' + this.innerHTML);
            // });
            //图片上传
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
                        return layer.msg('上传失败', {icon: 2});
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

            //监听提交
            form.on('submit(set_website)', function (data) {
                var field = data.field; //获取提交的字段
                //提交 Ajax 成功后，关闭当前弹层并重载表格
                admin.req({
                    url: '/admin/systemConfig/basal'
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
                            layer.msg(res.msg, {icon: 2});;
                        }
                    }
                });
            });
        });
    </script>
@endsection