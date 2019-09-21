@extends('layouts.admin')
@section('control_name', $control_name)
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="hidden" name="id" value="{{ $info->id }}">
                <input type="text" name="title" value="{{ $info->title }}" lay-verify="required" placeholder="请输入标题"
                       autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-block">
                <select name="category_id">
                    <option value="">全部分类</option>
                    @foreach($category_list as $ck=>$cv)
                        @if($info->category_id == $cv['id'])
                            <option value="{{ $cv['id'] }}" selected>{!! $cv['name'] !!}</option>
                        @else
                            <option value="{{ $cv['id'] }}">{!! $cv['name'] !!}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">作者</label>
            <div class="layui-input-block">
                <input type="text" name="author" placeholder="admin" value="{{ $info->author }}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标签</label>
            <div class="layui-input-block">
                @foreach($tags_list as $tk=>$tv)
                    @if(in_array($tv->name,$info->tags))
                        <input type="checkbox" checked name="tags[{{ $tv->name }}]" title="{{ $tv->name }}">
                    @else
                        <input type="checkbox" name="tags[{{ $tv->name }}]" title="{{ $tv->name }}">
                    @endif
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <div class="layui-upload-list">
                        <input type="hidden" name="cover" value="{{ $info->cover??'' }}">
                        <img class="layui-upload-img" id="up_cover"
                             src="{{ $info->cover?$info->cover:'/images/config/default-img.jpg' }}"
                             style="cursor:pointer">
                        <p id="up_cover_text"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <textarea name="description" class="layui-textarea">{{ $info->description }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea id="content" name="content" style="display: none;">{{ $info->content }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">浏览量</label>
            <div class="layui-input-block">
                <input type="number" name="click" value="{{ $info->click }}" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否置顶</label>
            <div class="layui-input-block">
                <input type="hidden" name="is_top" value="{{ $info->is_top }}">
                <input type="checkbox" lay-filter="is_top" {{ $info->is_top==1?'checked':'' }} lay-skin="switch"
                       lay-text="是|否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="hidden" name="status" value="{{ $info->status }}">
                <input type="checkbox" {{ $info->status==1?'checked':'' }} lay-verify="required" lay-filter="status"
                       lay-skin="switch"
                       lay-text="已审核|待审核">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="layuiadmin-app-form-add" id="layuiadmin-app-form-add"
                   value="确认添加">
            <input type="button" lay-submit lay-filter="layuiadmin-app-form-edit" id="layuiadmin-app-form-edit"
                   value="确认编辑">
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
        }).use(['index', 'table', 'layedit', 'upload'], function () {
            var $ = layui.$
                , layedit = layui.layedit
                , upload = layui.upload
                , form = layui.form;
            var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            //图片上传
            var uploadInst = upload.render({
                elem: '#up_cover'
                , url: '/admin/article/uploadImage'
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
                        $('#up_cover').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败', {icon: 2});
                    }
                    //上传成功
                    $('input[name="cover"]').val(res.data.src);
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    var up_logo_text = $('#up_cover_text');
                    up_logo_text.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    up_logo_text.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });

            layedit.set({
                //暴露layupload参数设置接口 --详细查看layupload参数说明
                uploadImage: {
                    url: '/admin/article/uploadImage' //接口url
                    , type: 'post'//默认post
                    , headers: {
                        'X-CSRF-TOKEN': csrf_token
                    }
                    , data: {
                        _token: csrf_token
                    }
                    , done: function (data) {
                        //console.log(data);
                    }
                }
                , uploadVideo: {
                    url: '/admin/article/uploadImage', //接口url
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    data: {
                        _token: csrf_token
                    },
                    accept: 'video',
                    acceptMime: 'video/*',
                    exts: 'mp4|flv|avi|rm|rmvb',
                    size: 1024 * 10 * 2,
                    done: function (data) {
                        console.log(data);
                    }
                }
                , uploadFiles: {
                    url: '/admin/article/uploadImage' ,//接口url
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    data: {
                        _token: csrf_token
                    },
                    accept: 'file',
                    acceptMime: 'file/*',
                    size: '20480',
                    done: function (data) {
                        console.log(data);
                    }
                }
                //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
                //传递参数：
                //图片： imgpath --图片路径
                //视频： filepath --视频路径 imgpath --封面路径
                , calldel: {
                    url: '/admin/article/uploadImage', //接口url
                    headers: {
                        'X-CSRF-TOKEN': csrf_token
                    },
                    data: {
                        _token: csrf_token
                    },
                    done: function (data) {
                        console.log(data);
                    }
                }
                //开发者模式 --默认为false
                , devmode: true
                //插入代码设置 --hide:true 等同于不配置codeConfig
                , codeConfig: {
                    hide: false,  //是否显示编码语言选择框
                    default: 'javascript' //hide为true时的默认语言格式
                }
                //新增iframe外置样式和js
                //, quote:{
                //    style: ['/Content/Layui-KnifeZ/css/layui.css','/others'],
                //    js: ['/Content/Layui-KnifeZ/lay/modules/jquery.js']
                //}
                //自定义样式-暂只支持video添加
                //, customTheme: {
                //    video: {
                //        title: ['原版', 'custom_1', 'custom_2']
                //        , content: ['', 'theme1', 'theme2']
                //        , preview: ['', '/images/prive.jpg', '/images/prive2.jpg']
                //    }
                //}
                //插入自定义链接
                , customlink: {
                    title: '插入layui官网'
                    , href: 'https://www.layui.com'
                    , onmouseup: ''
                }
                , facePath: 'http://knifez.gitee.io/kz.layedit/Content/Layui-KnifeZ/'
                , videoAttr: ' preload="none" '
                , tool: [
                    'html', 'undo', 'redo', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'fontfamily', 'fontSize', 'fontBackColor', 'colorpicker', 'face'
                    , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'video', 'attachment', 'anchors'
                    , '|'
                    , 'table', 'customlink'
                    , 'fullScreen'
                ]
                , height: '600px'
            });
            //注意：layedit.set 一定要放在 build 前面，否则配置全局接口将无效。
            var layedit_index = layedit.build('content'); //建立编辑器

            //监听指定开关
            form.on('switch(status)', function () {
                if (this.checked) {
                    $("input[name='status']").val('1');
                } else {
                    $("input[name='status']").val('0');
                }
            });
            //监听指定开关
            form.on('switch(is_top)', function () {
                if (this.checked) {
                    $("input[name='is_top']").val('1');
                } else {
                    $("input[name='is_top']").val('0');
                }
            });

            $('#layuiadmin-app-form-edit').click(function () {
                $("textarea[name='content']").val(layedit.getContent(layedit_index));
            });
        });
    </script>
@endsection