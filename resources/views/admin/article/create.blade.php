@extends('layouts.admin')
@section('control_name', $control_name)
@section('header')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('static/editor.md/css/editormd.min.css') }}" media="all">
@endsection
@section('content')
    <div class="layui-form" lay-filter="layuiadmin-app-list" id="layuiadmin-app-form-list"
         style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" placeholder="请输入标题" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-block">
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
        <div class="layui-form-item">
            <label class="layui-form-label">作者</label>
            <div class="layui-input-block">
                <input type="text" name="author" placeholder="admin" value="Vijay" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标签</label>
            <div class="layui-input-block">
                @foreach($tags_list as $tk=>$tv)
                    <input type="checkbox" name="tags[{{ $tv->name }}]" title="{{ $tv->name }}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <div class="layui-upload-list">
                        <input type="hidden" name="cover" value="">
                        <img class="layui-upload-img" id="up_cover" src="/images/config/default-img.jpg"
                             style="cursor:pointer">
                        <p id="up_cover_text"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <textarea name="description" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <div id="editor">
                    <textarea class="editormd-markdown-textarea" name="contentn-doc"></textarea>
                    <!-- html textarea 需要开启配置项 saveHTMLToTextarea == true -->
                    <textarea class="editormd-html-textarea" name="content-code"></textarea>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">浏览量</label>
            <div class="layui-input-block">
                <input type="number" name="click" value="99" placeholder="请输入" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否置顶</label>
            <div class="layui-input-block">
                <input type="hidden" name="is_top" value="0">
                <input type="checkbox" lay-filter="is_top" lay-skin="switch"
                       lay-text="是|否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="hidden" name="status" value="1">
                <input type="checkbox" checked lay-filter="status" lay-skin="switch"
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
    <script type="text/javascript">
        $(function() {
            var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var uploadImageUrl = '/admin/article/uploadImage';
            editor = editormd("editor", {
                autoFocus : false,
                width     : "100%",
                height    : 640,
                path : "/static/editor.md/lib/",  // Autoload modules mode, codemirror,
                saveHTMLToTextarea : true,//这个配置，方便post提交表单，表单字段会自动加上一个字段content-html-code,形式为html格式
                /**上传图片相关配置如下*/
                imageUpload    : true,
                imageFormats   : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : uploadImageUrl,//注意你后端的上传图片服务地址
            });

            //editor.getMarkdown();       // 获取 Markdown 源码
            //editor.getHTML();           // 获取 Textarea 保存的 HTML 源码
            //editor.getPreviewedHTML();  // 获取预览窗口里的 HTML，在开启 watch 且没有开启 saveHTMLToTextarea 时使用

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
                                            var imgShow= '![]('+res.data.src+')';
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
            })
        });
    </script>
    <script>
        layui.config({
            base: "/static/layuiadmin/"
        }).extend({
            index: 'lib/index'
        }).use(['index', 'table',], function () {
            var $ = layui.$
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

        });
    </script>
@endsection
