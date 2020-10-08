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

    .media-left img {
        width: 46px;
        height: 46px;
    }

    .message-text {
        margin-top: -30px;
    }

    /*.message-content .media-body {
            margin-bottom: 10px;
            border-bottom: 1px dashed #e5e5e5;
        }*/
    .reply-content-child {
        margin-top: 10px;
    }

    .reply-content-child:last-child {
        border-bottom: none;
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
        margin-bottom: 10px;
        color: #009688;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e5e5e5;
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
<script src="/js/vue/vue.min.js"></script>
<script src="/js/vue/axios.min.js"></script>
<div class="layui-fluid layadmin-message-fluid">
    <div class="layui-row">
        <div class="layui-col-md12">
            <div class="layui-form" method="post" action="">
                <div class="layui-form-item layui-form-text">
                    <div class="layui-input-block">
                        <textarea name="content" required lay-verify="required" placeholder="请输入内容"
                                  class="layui-textarea"></textarea>
                    </div>
                </div>
                <input type="hidden" name="pid" value="0">
                <input type="hidden" name="origin_id" value="0">
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
            </div>
        </div>
        <div class="layui-col-md12 layadmin-homepage-list-imgtxt message-content" id="vue_app">
            <div class="media-body" v-for="comment in comments">
                <a href="javascript:;" class="media-left">
                    <img v-bind:src="comment.avatar">
                </a>
                <div class="pad-btm">
                    <p class="fontColor"><a href="javascript:;"> @{{ comment.user_name }} </a></p>
                    <p class="min-font">
                     <span class="layui-breadcrumb" lay-separator="-">
                        <a href="javascript:;" class="layui-icon layui-icon-date"></a>
                        <a href="javascript:;">@{{ comment.created_at }}</a>
                         {{--  <a href="javascript:;">11分钟前</a>--}}
                      </span>
                    </p>
                </div>
                <p class="message-text">@{{ comment.content }}</p>
                <p class="message-action">
                    <span class="message-reply"><img src="/images/home/icon/reply.png" alt="回复"
                                                     title="回复"><em></em></span>
                    {{--<span class="message-zan"><img src="/images/home/icon/zan.png"
                                                   alt="点赞"
                                                   title="点赞"> <em>+ @{{ comment.zan }}</em></span>
                    <span class="message-cai"><img src="/images/home/icon/cai.png" alt="狂踩"
                                                   title="狂踩"> <em>+@{{ comment.cai }}</em></span>--}}
                </p>
                <div class="layui-col-md12 reply-content">
                    <div class="layui-form" method="post" action="">
                        <div class="layui-form-item layui-form-text">
                            <div class="layui-input-block">
                                <textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        @csrf
                        <input type="hidden" v-model="comment.id" name="origin_id">
                        <input type="hidden" v-model="comment.id" name="pid">
                        <div class="layui-form-item" style="overflow: hidden;">
                            <div class="layui-input-block layui-input-right">
                                <button class="layui-btn layui-btn-sm layui-btn-primary" lay-filter="primary">取消
                                </button>
                                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="ajaxComment">发表</button>
                            </div>
                            <div class="layadmin-messag-icon">
                                <a href="javascript:;"><i class="layui-icon layui-icon-face-smile-b"></i></a>
                                <a href="javascript:;"><i class="layui-icon layui-icon-picture"></i></a>
                                <a href="javascript:;"><i class="layui-icon layui-icon-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="comment.child.length>0">
                    <div class="layui-col-md12 reply-content-child" v-for="child in comment.child">
                        <div class="media-body-child">
                            <a href="javascript:;" class="media-left">
                                <img v-bind:src="child.avatar">
                            </a>
                            <div class="pad-btm">
                                <p class="fontColor"><a href="javascript:;">@{{ child.user_name }}</a></p>
                                <p class="min-font">
                                 <span class="layui-breadcrumb" lay-separator="-">
                                    <a href="javascript:;" class="layui-icon layui-icon-date"></a>
                                    <a href="javascript:;">@{{ child.created_at }}</a>
                                     {{-- <a href="javascript:;">11分钟前</a>--}}
                                  </span>
                                </p>
                            </div>
                            <p class="message-text"><strong>@ @{{ child.answered_user_name }}:</strong>@{{ child.content
                                }}</p>
                            <p class="message-action">
                                <span class="message-reply"><img src="/images/home/icon/reply.png" alt="回复"
                                                                 title="回复"><em></em></span>
                                {{--<span class="message-zan"><img src="/images/home/icon/zan.png" alt="点赞"
                                                               title="点赞"> <em>+@{{ comment.zan }}</em></span>
                                <span class="message-cai"><img src="/images/home/icon/cai.png" alt="狂踩"
                                                               title="狂踩"> <em>+@{{ comment.cai }}</em></span>--}}
                            </p>
                            <div class="layui-col-md12 reply-content">
                                <div class="layui-form" method="post" action="">
                                    <div class="layui-form-item layui-form-text">
                                        <div class="layui-input-block">
                                            <textarea name="content" placeholder="请输入内容"
                                                      class="layui-textarea"></textarea>
                                        </div>
                                    </div>
                                    @csrf
                                    <input type="hidden" v-model="comment.id" name="origin_id">
                                    <input type="hidden" v-model="child.id" name="pid">
                                    <div class="layui-form-item" style="overflow: hidden;">
                                        <div class="layui-input-block layui-input-right">
                                            <button class="layui-btn layui-btn-primary" lay-filter="primary">取消</button>
                                            <button class="layui-btn" lay-submit lay-filter="ajaxComment">发表</button>
                                        </div>
                                        <div class="layadmin-messag-icon">
                                            <a href="javascript:;"><i
                                                        class="layui-icon layui-icon-face-smile-b"></i></a>
                                            <a href="javascript:;"><i class="layui-icon layui-icon-picture"></i></a>
                                            <a href="javascript:;"><i class="layui-icon layui-icon-link"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="layui-row message-content-btn">
                <a href="javascript:;" class="layui-btn comment-more" v-on:click="commentMore">更多</a>
            </div>
        </div>

    </div>
</div>
<script>
    window.onbeforeunload = function () {
        var scrollPos;
        if (typeof window.pageYOffset != 'undefined') {
            scrollPos = window.pageYOffset;
        } else if (typeof document.compatMode != 'undefined' &&
            document.compatMode != 'BackCompat') {
            scrollPos = document.documentElement.scrollTop;
        } else if (typeof document.body != 'undefined') {
            scrollPos = document.body.scrollTop;
        }
        document.cookie = "scrollTop=" + scrollPos; //存储滚动条位置到cookies中
    };
    window.onload = function () {
        if (document.cookie.match(/scrollTop=([^;]+)(;|$)/) != null) {
            var arr = document.cookie.match(/scrollTop=([^;]+)(;|$)/); //cookies中不为空，则读取滚动条位置
            document.documentElement.scrollTop = parseInt(arr[1]);
            document.body.scrollTop = parseInt(arr[1]);
        }
    };

    /**
     * 判断pc还是手机
     */
    function browserRedirect() {
        var sUserAgent = navigator.userAgent.toLowerCase();
        if (/ipad|iphone|midp|rv:1.2.3.4|ucweb|android|windows ce|windows mobile/.test(sUserAgent)) {
            //跳转移动端页面
            layer.msg('请先登录再评论');
            location.href ='/login';
        } else {
            //pc端页面
            layer.msg('请从右侧登录,再进行评论');
            $('.side-icon-user').click();
        }
    }

    var dataComment = {
        comments: [],
        csrf_token: "{{ csrf_token() }}",
        article_id: "{{ $article_id }}",
        user_id: "{{ auth()->id() }}",
        current_page: 1,
        per_page: 5,
        total: 0,
    };
    layui.config({
        base: "/static/layuiadmin/"
    }).use(['form'], function () {
        layer = layui.layer;
        form = layui.form;

        form.on('submit(ajaxComment)', function (data) {
            var user_id = "{{ auth()->id() }}";
            if (!user_id) {
                browserRedirect();
                return false;
            }
            if (!data.field.content) {
                layer.msg('评论内容不能为空');
                return false;
            }
            var postData = {
                content: data.field.content,
                pid: data.field.pid,
                origin_id: data.field.origin_id,
                article_id: dataComment.article_id,
                user_id: dataComment.user_id,
            };
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: postData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
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

        $("body").delegate(".message-reply", "click", function () {
            $(this).parent().next().toggle();
        });
        $("body").delegate('.layui-icon-face-smile-b','click',function(){
            layer.msg('表情包暂未开发完哦');
        });
        $("body").delegate('.layui-icon-picture','click',function(){
            layer.msg('图片暂不能上传哦');
        });
        $("body").delegate('.layui-icon-link','click',function(){
            layer.msg('链接暂不能用哦');
        });
        $("body").delegate(".message-zan", "click", function () {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: postData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
            var html = '<img src="/images/home/icon/zan_red.png" alt="点赞" title="点赞"><em class="succeed">+3</em>';
            $(this).html(html);
        });
        $("body").delegate(".message-cai", "click", function () {
            layer.msg('踩加1,变红');
            var html = '<img src="/images/home/icon/cai_red.png" alt="狂踩" title="狂踩"><em class="succeed">+31</em>';
            $(this).html(html);
        });
        $("body").delegate(".layui-btn-primary", "click", function () {
            $(this).parents('.reply-content').hide();
            return false;
        })
    });

    var vueObj = new Vue({
        el: '#vue_app',
        data() {
            return dataComment
        },
        mounted() {
            var that = this;
            axios.post('/ajaxComment', {
                article_id: this.article_id,
                _token: this.csrf_token
            })
                .then(function (response) {
                    //console.log(response);
                    if (response.data.code == 0) {
                        that.comments = response.data.data.data;
                        that.current_page = response.data.data.current_page;
                        that.per_page = response.data.data.per_page;
                        that.total = response.data.data.total;
                    }
                })
                .catch(function (error) {

                });
        },
        // 在 `methods` 对象中定义方法
        methods: {
            commentMore: function (event) {
                // `this` 在方法里指当前 Vue 实例
                // `event` 是原生 DOM 事件
                var that = this;
                axios.post('/ajaxComment', {
                    article_id: this.article_id,
                    comment_page: that.current_page+1 ,
                    comment_limit: that.per_page,
                    _token: this.csrf_token
                })
                    .then(function (response) {
                        if (response.data.code == 0) {
                            if(response.data.data.data.length>0){
                                for (i in response.data.data.data)
                                {
                                    that.comments.push(response.data.data.data[i]);
                                }
                                that.current_page = response.data.data.current_page;
                                that.per_page = response.data.data.per_page;
                                that.total = response.data.data.total;
                            }else{
                                layer.msg('没有更多了');
                                $('.comment-more').addClass('layui-btn-disabled');
                            }
                        }else{
                            layer.msg('请求错误');
                        }
                    })
                    .catch(function (error) {
                        layer.msg('请求错误');
                    });
            }
        }
    });
</script>
