<div class="toolbar-open"></div>
<div class="toolbar">
    <div class="toolbar-close">
        <span id="guanbi"></span>
    </div>
    <div class="toolbar-nav">
        <ul id="toolbar-menu">
            <li>
                <i class="side-icon-user"></i>
                <section>
                    @if (Auth::check())
                        <div class="login_herder">
                            <a href="/user">
                                <img src="{{ Auth::user()->avatar??'/images/config/avatar_min.jpg' }}" class="huiyuan-img" alt="臭大佬" title="臭大佬">
                            </a>
                        </div>
                        <div class="userinfo">
                            <div class="clear"></div>
                            <div class="logged">
                                <b>{{ Auth::user()->name }}，您好！</b>
                                <a href="/user">个人中心</a>
                                <a href="/auth/logout">退出</a>
                            </div>
                        </div>
                    @else
                        <div class="login_herder">
                            <img src="/images/config/avatar.jpg" class="huiyuan-img" alt="臭大佬" title="臭大佬">
                            <span>登录</span>
                        </div>
                        <div class="userinfo">
                            <form class="layui-form" name="login" method="post" action="">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input name="account" type="text" class="inputText" required lay-verify="account" size="16" placeholder="邮箱或账号">
                                <input name="password" required lay-verify="required" type="password" class="inputText" size="16" placeholder="密码">
                                <div class="captcha">
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <img class="captcha_src_ajax" src="{{captcha_src()}}" style="cursor: pointer;height: 32px;height: 32px;"
                                             onclick="this.src='{{captcha_src()}}'+Math.random()">
                                    </div>
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <input type="text"  required lay-verify="required" name="captcha" placeholder="验证码" class="layui-input" style="height: 32px;">
                                    </div>
                                </div>
                                <input type="button" lay-submit  lay-filter="ajaxLogin" value="登陆" class="inputsub-dl">
                                <a href="/register" class="inputsub-zc">注册</a>
                            </form>
                            <div class="auth-group">
                                <div class="auth-title">—— 社交账号登入 ——</div>
                                <div class="auth-apps">
                                    <a href="/auth/github" data-type="github">
                                        <img src="/images/home/app_logo/github.png">
                                    </a>
                                    <a href="/auth/qq" data-type="qq">
                                        <img src="/images/home/app_logo/qq.png">
                                    </a>
                                    <a href="/auth/weibo" data-type="weibo">
                                        <img src="/images/home/app_logo/weibo.png">
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endif
                </section>
            </li>
            <li>
                <i class="side-icon-qq"></i>
                <section class="qq-section">
                    <div class="qqinfo">
                        <a target="_blank"
                           href="http://wpa.qq.com/msgrd?v=3&amp;uin=1937832819&amp;site=qq&amp;menu=yes">站长QQ</a>
                    </div>
                </section>
            </li>
            <li>
                <i class="side-icon-weixin"></i>
                <section class="weixin-section">
                    <div class="weixin-info">
                        <div class="kf">
                            <ul class="kfdh">
                                <p class="kftext">微信</p>
                                <p class="kfnum"><img src="/images/config/wx.jpg" alt="臭大佬" title="臭大佬"></p>
                            </ul>
                        </div>
                    </div>
                </section>
            </li>
            <li>
                <i class="side-icon-dashang"></i>
                <section class="dashang-section">
                    <p>如果你觉得本站很棒，可以通过扫码支付打赏哦！</p>
                    <ul>
                        <li><img src="/images/config/weixin_pay.jpg" alt="臭大佬" title="臭大佬">微信收款码</li>
                        <li><img src="/images/config/ali_pay.jpg" alt="臭大佬" title="臭大佬">支付宝收款码</li>
                    </ul>
                </section>
            </li>
        </ul>
    </div>
</div>
<script>
    layui.config({
        base: "/static/layuiadmin/"
    }).use(['form'], function () {
        var layer = layui.layer;
        var form = layui.form;
        //提交
        form.on('submit(ajaxLogin)', function(data){
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: data.field, //传接收到的参数id
                url: '/auth/ajaxLogin',
                success: function (res) {
                    if(res.code === 0){
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        }, function () {
                            location.reload();
                        });
                    }else{
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 1000
                        }, function () {
                            $('.captcha_src_ajax').click();
                        });
                    }
                }
            });
            return false;
        });
    });
    (function(){
        //toolbar
        $("#guanbi").click(function () {
            $(".toolbar").addClass("guanbi");
            $(".toolbar-open").addClass("openviewd");
            $("#toolbar-menu li").removeClass("current");
        });
        $(".toolbar-open").click(function () {
            $(".toolbar-open").removeClass("openviewd");
            $(".toolbar").removeClass("guanbi");
        });
        //toolbar-menu
        $('#toolbar-menu li').click(function () {
            $(this).addClass('current').siblings().removeClass('current');
        });

    })();
</script>