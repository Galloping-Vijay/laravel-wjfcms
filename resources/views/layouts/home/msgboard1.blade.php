<!--评论PC版-->
{{--
<div id="SOHUCS" sid="{{ $info->id }}" style="width: 84%;"></div>
<script charset="utf-8" type="text/javascript" src="{{ asset('js/home/changyan/changyan.js') }}" ></script>
<script type="text/javascript">
    window.changyan.api.config({
        appid: 'cyuz620gF',
        conf: 'prod_3ef1231e159e9e6b689e2bd15babaa0c'
    });
</script>--}}
<style>
    .layui-fluid {
        position: relative;
        margin: 0 auto;
        padding: 0 15px;
    }

    .layadmin-message-fluid .layui-form {
        padding: 20px 40px 0 40px;
    }

    .layadmin-message-fluid .layui-col-md12 {
        background: #fff;
        height: auto;
        padding-bottom: 0;
    }

    .media-left {
        float: left;
    }
    .media-left img{
        width: 46px;
        height:46px;
    }

    .message-text {
        margin-top: -30px;
    }

    .message-content .media-body {
        margin-bottom: 10px;
        border-bottom: 1px dashed #e5e5e5;
    }

    .message-content p {
        margin-top: 0;
        margin-bottom: 3px;
    }

    .layui-breadcrumb {
        visibility: inherit;
        font-size: 2px;
    }

    .message-content .layui-btn {
        display: inline-block;
        height: 38px;
        line-height: 38px;
        padding: 0 18px;
        white-space: nowrap;
        text-align: center;
        font-size: 14px;
        border-radius: 2px;
    }

    .message-content-btn {
        margin-bottom: 30px;
    }

    .message-action {
        text-align: right;
        cursor: pointer;
        margin-bottom: 5px;
        color: #009688;
    }

    .message-action span {
        margin-left: 8px;
    }

    .message-action span:hover {
        color: #E2523A;
    }

    .message-action .message-reply img:hover {
        content: url('/images/home/icon/reply_red.png');
    }

    .message-action .message-zan img:hover {
        content: url('/images/home/icon/zan_red.png');
    }

    .message-action .message-cai img:hover {
        content: url('/images/home/icon/cai_red.png');
    }

    .message-action em {
        margin-left: 5px;
    }

    .message-action .succeed {
        color: #E2523A;
    }

    .reply-content {
        display: none;
    }

    .reply-content .layui-form {
        padding: 5px 0;
    }

    .media-body-child {
        margin-left: 50px;
    }
</style>
<div class="layui-fluid layadmin-message-fluid">
    <div class="layui-row">
        <div class="layui-col-md12">
            <form class="layui-form" method="post" action="">
                <div class="layui-form-item layui-form-text">
                    <div class="layui-input-block">
                        <textarea name="content"  required  lay-verify="required" placeholder="请输入内容" class="layui-textarea"></textarea>
                    </div>
                </div>
                <input type="hidden" name="pid" value="0">
                <div class="layui-form-item" style="overflow: hidden;">
                    <div class="layui-input-block layui-input-right">
                        <button class="layui-btn" lay-submit lay-filter="ajaxComment">发表</button>
                    </div>
                    <div class="layadmin-messag-icon">
                        <a href="javascript:;"><i class="layui-icon layui-icon-face-smile-b"></i></a>
                        <a href="javascript:;"><i class="layui-icon layui-icon-picture"></i></a>
                        <a href="javascript:;"><i class="layui-icon layui-icon-link"></i></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-col-md12 layadmin-homepage-list-imgtxt message-content">
            <div class="layui-row message-content-btn">
                <a href="javascript:;" class="layui-btn">更多</a>
            </div>
        </div>

    </div>
</div>
<script>
    window.onbeforeunload = function() {
        var scrollPos;
        if(typeof window.pageYOffset != 'undefined') {
            scrollPos = window.pageYOffset;
        } else if(typeof document.compatMode != 'undefined' &&
            document.compatMode != 'BackCompat') {
            scrollPos = document.documentElement.scrollTop;
        } else if(typeof document.body != 'undefined') {
            scrollPos = document.body.scrollTop;
        }
        document.cookie = "scrollTop=" + scrollPos; //存储滚动条位置到cookies中
    };
    window.onload = function() {
        if(document.cookie.match(/scrollTop=([^;]+)(;|$)/) != null) {
            var arr = document.cookie.match(/scrollTop=([^;]+)(;|$)/); //cookies中不为空，则读取滚动条位置
            document.documentElement.scrollTop = parseInt(arr[1]);
            document.body.scrollTop = parseInt(arr[1]);
        }
    };

    layui.config({
        base: "/static/layuiadmin/"
    }).use(['form'], function () {
        var layer = layui.layer;
        var form = layui.form;
        var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var article_id = "{{ $article_id }}";
        var user_id = "{{ auth()->id() }}";

        //获取评论
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {article_id:article_id},
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            url: '/ajaxComment',
            success: function (res) {
                if (res.code == 0) {
                    var html = '';
                    if(res.data.length>0){
                        console.log(res.data.length);
                        for(var i=0;i<res.data.length;i++){
                            html +='<div class="media-body">\n' +
                                '\t<a href="javascript:;" class="media-left">\n' +
                                '\t\t<img src="'+res.data[i].avatar+'">\n' +
                                '\t</a>\n' +
                                '\t<div class="pad-btm">\n' +
                                '\t\t<p class="fontColor"><a href="javascript:;">'+res.data[i].user_name+'</a></p>\n' +
                                '\t\t<p class="min-font">\n' +
                                '\t\t <span class="layui-breadcrumb" lay-separator="-">\n' +
                                '\t\t\t<a href="javascript:;" class="layui-icon layui-icon-cellphone"></a>\n' +
                                '\t\t\t<a href="javascript:;">'+res.data[i].created_at+'</a>\n' +
                                /*'\t\t\t<a href="javascript:;">11分钟前</a>\n' +*/
                                '\t\t  </span>\n' +
                                '\t\t</p>\n' +
                                '\t</div>\n' +
                                '\t<p class="message-text">'+res.data[i].content+'</p>\n' +
                                '\t<p class="message-action">\n' +
                                '\t\t<span class="message-reply"><img src="/images/home/icon/reply.png" alt="回复"\n' +
                                '\t\t\t\t\t\t\t\t\t\t title="回复"><em>+2</em></span>\n' +
                                '\t\t<span class="message-zan"><img src="/images/home/icon/zan.png" alt="点赞"\n' +
                                '\t\t\t\t\t\t\t\t\t   title="点赞"> <em>+2</em></span>\n' +
                                '\t\t<span class="message-cai"><img src="/images/home/icon/cai.png" alt="狂踩"\n' +
                                '\t\t\t\t\t\t\t\t\t   title="狂踩"> <em>+30</em></span>\n' +
                                '\t</p>\n' +
                                '\t<div class="layui-col-md12 reply-content">\n' +
                                '\t\t<form class="layui-form" method="post" action="">\n' +
                                '\t\t\t<div class="layui-form-item layui-form-text">\n' +
                                '\t\t\t\t<div class="layui-input-block">\n' +
                                '\t\t\t\t\t<input type="hidden" name="pid" value="'+res.data[i].id+'"><input type="hidden" name="origin_id" value="'+res.data[i].origin_id+'">\n' +
                                '\t\t\t\t\t<textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>\n' +
                                '\t\t\t\t</div>\n' +
                                '\t\t\t</div>\n' +
                                '\t\t\t<div class="layui-form-item" style="overflow: hidden;">\n' +
                                '\t\t\t\t<div class="layui-input-block layui-input-right">\n' +
                                '\t\t\t\t\t<button class="layui-btn layui-btn-sm layui-btn-primary" lay-filter="primary">取消\n' +
                                '\t\t\t\t\t</button>\n' +
                                '\t\t\t\t\t<button class="layui-btn layui-btn-sm" lay-submit lay-filter="formDemo">发表</button>\n' +
                                '\t\t\t\t</div>\n' +
                                '\t\t\t\t<div class="layadmin-messag-icon">\n' +
                                '\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-face-smile-b"></i></a>\n' +
                                '\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-picture"></i></a>\n' +
                                '\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-link"></i></a>\n' +
                                '\t\t\t\t</div>\n' +
                                '\t\t\t</div>\n' +
                                '\t\t</form>\n' +
                                '\t</div>\n';
                                if(res.data[i].child.length >0){
                                    for(var j=0;j<res.data[i].child.length;j++){
                                        html +='\t<div class="layui-col-md12">\n'+
                                            '\t\t<div class="media-body-child">\n' +
                                            '\t\t\t<a href="javascript:;" class="media-left">\n' +
                                            '\t\t\t\t<img src="'+res.data[i].child[j].avatar+'">\n' +
                                            '\t\t\t</a>\n' +
                                            '\t\t\t<div class="pad-btm">\n' +
                                            '\t\t\t\t<p class="fontColor"><a href="javascript:;">'+res.data[i].child[j].user_name+'</a></p>\n' +
                                            '\t\t\t\t<p class="min-font">\n' +
                                            '\t\t <span class="layui-breadcrumb" lay-separator="-">\n' +
                                            '\t\t\t<a href="javascript:;" class="layui-icon layui-icon-cellphone"></a>\n' +
                                            /*'\t\t\t<a href="javascript:;">从移动</a>\n' +*/
                                            '\t\t\t<a href="javascript:;">'+res.data[i].child[j].created_at+'</a>\n' +
                                            '\t\t  </span>\n' +
                                            '\t\t\t\t</p>\n' +
                                            '\t\t\t</div>\n' +
                                            '\t\t\t<p class="message-text"><strong>@'+res.data[i].child[j].answered_user_name+':</strong>'+res.data[i].child[j].content+'</p>\n' +
                                            '\t\t\t<p class="message-action">\n' +
                                            '\t\t\t\t<span class="message-reply"><img src="/images/home/icon/reply.png" alt="回复" title="回复"><em>+2</em></span>\n' +
                                            '\t\t\t\t<span class="message-zan"><img src="/images/home/icon/zan.png" alt="点赞"\n' +
                                            '\t\t\t\t\t\t\t\t\t\t\t   title="点赞"> <em>+2</em></span>\n' +
                                            '\t\t\t\t<span class="message-cai"><img src="/images/home/icon/cai.png" alt="狂踩"\n' +
                                            '\t\t\t\t\t\t\t\t\t\t\t   title="狂踩"> <em>+30</em></span>\n' +
                                            '\t\t\t</p>\n' +
                                            '\t\t\t<div class="layui-col-md12 reply-content">\n' +
                                            '\t\t\t\t<form class="layui-form"  method="post" action="">\n' +
                                            '\t\t\t\t\t<div class="layui-form-item layui-form-text">\n' +
                                            '\t\t\t\t\t\t<div class="layui-input-block">\n' +
                                            '\t\t\t\t\t\t\t<textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>\n' +
                                            '\t\t\t\t\t\t\t<input type="hidden" name="pid" value="'+res.data[i].child[j].id+'"><input type="hidden" name="origin_id" value="'+res.data[i].child[j].origin_id+'">\n' +
                                            '\t\t\t\t\t\t</div>\n' +
                                            '\t\t\t\t\t</div>\n' +
                                            '\t\t\t\t\t<div class="layui-form-item" style="overflow: hidden;">\n' +
                                            '\t\t\t\t\t\t<div class="layui-input-block layui-input-right">\n' +
                                            '\t\t\t\t\t\t\t<button class="layui-btn layui-btn-primary" lay-filter="primary">取消</button>\n' +
                                            '\t\t\t\t\t\t\t<button class="layui-btn" lay-submit lay-filter="formDemo">发表</button>\n' +
                                            '\t\t\t\t\t\t</div>\n' +
                                            '\t\t\t\t\t\t<div class="layadmin-messag-icon">\n' +
                                            '\t\t\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-face-smile-b"></i></a>\n' +
                                            '\t\t\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-picture"></i></a>\n' +
                                            '\t\t\t\t\t\t\t<a href="javascript:;"><i class="layui-icon layui-icon-link"></i></a>\n' +
                                            '\t\t\t\t\t\t</div>\n' +
                                            '\t\t\t\t\t</div>\n' +
                                            '\t\t\t\t</form>\n' +
                                            '\t\t\t</div>\n' +
                                            '\t\t</div>\n' +
                                            '\t</div>\n';
                                    }
                                }
                                html +='</div>';
                        }
                        console.log(html);
                        $('.message-content-row').html(html);
                        form.render();
                    }
                }
            }
        });

        form.on('submit(ajaxComment)', function (data) {
            if (!user_id) {
                layer.msg('请从右侧登录,再进行评论');
                $('.side-icon-user').click();
                return false;
            }
            if(!data.field.content){
                layer.msg('评论内容不能为空');
                return false;
            }
            var postData = {
                content: data.field.content,
                pid: data.field.pid,
                article_id: article_id,
                user_id: user_id,
            };
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: postData, //传接收到的参数id
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                url: '/user/comment',
                success: function (res) {
                    if (res.code === 0) {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        }, function () {
                            location.reload();
                        });
                    } else {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 1000
                        }, function () {

                        });
                    }
                }
            });
            return false;
        });
        $("body").delegate(".message-reply","click", function(){
            $(this).parent().next().toggle();
        });
        $("body").delegate(".message-zan","click", function(){
            layer.msg('点赞加1,变红');
            //$.ajax();
            var html = '<img src="/images/home/icon/zan_red.png" alt="点赞" title="点赞"><em class="succeed">+3</em>';
            $(this).html(html);
        });
        $("body").delegate(".message-cai","click", function(){
            layer.msg('踩加1,变红');
            var html = '<img src="/images/home/icon/cai_red.png" alt="狂踩" title="狂踩"><em class="succeed">+31</em>';
            $(this).html(html);
        });
        $("body").delegate(".layui-btn-primary","click", function(){
            $(this).parents('.reply-content').hide();
            return false;
        })
    });
</script>
