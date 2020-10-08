@extends('layouts.admin')
@section('control_name', $control_name)
@section('header')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('static/editor.md/css/editormd.min.css') }}" media="all">
    <style>
        .editormd-preformatted-text-dialog,.editormd-code-block-dialog{
            width:600px !important;
            height:400px !important;
        }
        .editormd-preformatted-text-dialog .CodeMirror-empty,.editormd-preformatted-text-dialog .CodeMirror-wrap{
            height: 270px !important
        }
        .editormd-code-block-dialog .CodeMirror-empty,.editormd-code-block-dialog .CodeMirror-wrap{
            height: 240px !important
        }
    </style>
@endsection
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
                <select name="category_id" lay-verify="required">
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
                       class="layui-input" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标签</label>
            <div class="layui-input-block">
                @foreach($tags_list as $tk=>$tv)
                    @if($info->tags && in_array($tv->name,$info->tags))
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
                <textarea name="description" class="layui-textarea" lay-verify="required">{{ $info->description }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <div id="editor">
                    <textarea class="editormd-markdown-textarea" name="editor-html-doc">{{ $info->markdown }}</textarea>
                    <!-- html textarea 需要开启配置项 saveHTMLToTextarea == true -->
                    {{-- <textarea class="editormd-html-textarea" name="content-code"></textarea>--}}
                </div>
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
    <script src="{{ asset('static/editor.md/editormd.min.js') }}"></script>
    @if(config('app.locale') !== 'zh-CN')
        <script src="{{ asset('static/editor.md/languages/en.js') }}"></script>
    @endif
    {{--复制图片--}}
    <script src="{{asset('static/localResizeIMG/dist/lrz.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/editor.md/lib/marked.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/prettify.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/raphael.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/underscore.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/sequence-diagram.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/flowchart.min.js')}}"></script>
    <script src="{{asset('static/editor.md/lib/jquery.flowchart.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            var uploadImageUrl = '/admin/article/uploadImage';
            var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            editor = editormd("editor", {
                autoFocus: false,
                emoji: true,
                width: "100%",
                height: 640,
                path: "/static/editor.md/lib/",  // Autoload modules mode, codemirror,
                saveHTMLToTextarea: true, //这个配置，方便post提交表单，表单字段会自动加上一个字段content-html-code,形式为html格式
                tex: true,//科学公式TeX语言支持，默认关闭
                flowChart: true,//流程图支持，默认关闭
                sequenceDiagram: true,//时序/序列图支持，默认关闭
                /**上传图片相关配置如下*/
                imageUpload: true,
                imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL: uploadImageUrl,//注意你后端的上传图片服务地址
            });

            //testEditor.gotoLine(90);//跳转至第90行
            //testEditor.show();//显示编辑器
            //testEditor.hide;//隐藏编辑器
            //testEditor.getMarkdown();//获取markdown代码
            //testEditor.getHTML();//获取markdown解析后的html代码
            //testEditor.watch();//开启实时预览
            //testEditor.unwatch();//关闭实时预览
            //testEditor.previewing();//预览
            //testEditor.fullscreen();//全屏
            //testEditor.showToolbar();//显示工具栏
            //testEditor.hideToolbar();//隐藏工具栏

            /**
             * 复制粘贴图片功能
             * @param event
             */
            function paste(event) {
                var clipboardData = event.clipboardData;
                var items, item, types;
                if (clipboardData) {
                    items = clipboardData.items;
                    if (!items) {
                        return;
                    }
                    // 保存在剪贴板中的数据类型
                    types = clipboardData.types || [];
                    for (var i = 0; i < types.length; i++) {
                        if (types[i] === 'Files') {
                            item = items[i];
                            break;
                        }
                    }
                    // 判断是否为图片数据
                    if (item && item.kind === 'file' && item.type.match(/^image\//i)) {
                        // 读取该图片
                        var file = item.getAsFile(),
                            reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function () {
                            //前端压缩
                            lrz(reader.result, {width: 1080}).then(function (res) {
                                $.ajax({
                                    url: uploadImageUrl,
                                    type: 'post',
                                    headers: {
                                        'X-CSRF-TOKEN': csrf_token
                                    },
                                    data: {
                                        "base64_img": res.base64,
                                    },
                                    contentType: 'application/x-www-form-urlencoded;charest=UTF-8',
                                    success: function (res) {
                                        if (res.code === 0) {
                                            var imgShow = '![](' + res.data.src + ')';
                                            editor.insertValue(imgShow);
                                        } else {
                                            layer.msg(res.msg, {icon: 2});
                                        }
                                    }
                                })
                            });
                        }
                    }
                }
            }

            document.addEventListener('paste', function (event) {
                paste(event);
            });

            layui.config({
                base: "/static/layuiadmin/"
            }).extend({
                index: 'lib/index'
            }).use(['index', 'table', 'upload'], function () {
                var $ = layui.$
                    , upload = layui.upload
                    , form = layui.form;
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
                //图片上传
                var uploadInst = upload.render({
                    elem: '#up_cover'
                    , url: uploadImageUrl
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
                            //$('#up_cover').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        //如果上传失败
                        if (res.code > 0) {
                            return layer.msg('上传失败', {icon: 2});
                        }
                        //上传成功
                        $('input[name="cover"]').val(res.data.src);
                        $('#up_cover').attr('src', res.data.src); //图片链接（base64）
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
            });
        });

    </script>
@endsection
