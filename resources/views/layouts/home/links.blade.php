<div class="links">
    <h2 class="hometitle">友情链接
        <a href="javascript:;" title="申请友链" class="add_links">申请友链</a>
    </h2>
    <p style="padding: 10px;word-wrap: break-word;word-break: normal;word-break:break-all;">
        本站信息如下：<br>
        网站名称：<a href="/" title="Vijay个人博客" target="_blank">Vijay</a> <br>
        网站链接:<a href="/" title="Vijay个人博客">https://www.choudalao.com</a><br>
        网站logo:<a href="https://www.choudalao.com/images/config/avatar.jpg" title="Vijay个人博客" target="_blank">https://www.choudalao.com/images/config/avatar.jpg</a><br>
        <b><span style="color: red;">注：</span>申请友链之前，请务必先将本站添置友链，Vijay收到后会立马处理，处理结果会以邮件形式通知您~</b>
    </p>
    <ul style="padding: 10px 20px;" id="friendLinks">
    </ul>
</div>
<script>
    $(function () {
        $('#friendLinks').append('<li>加载中，请稍候</li>');
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {}, //传接收到的参数id
            url: '/friendLinks',
            success: function (data) {
                var li = '';
                var res = data['data'];
                if (res.length > 0) {
                    for (var i = 0; i < res.length; i++) {
                        li += ' <li>\n' +
                            '                <a href="'+res[i].url+'" title="'+res[i].name+'" target="_blank">'+res[i].name+'</a>\n' +
                            '            </li>';
                    }
                } else {
                    li += '<li>网络异常，获取数据失败</li>';
                }
                $('#friendLinks').find('li').remove();
                $('#friendLinks').append(li);
            }
        });
        $('.add_links').click(function () {
            layer.open({
                title: '友链提交',
                content: '网站名称:<input type="text" name="title" class="layui-layer-input" placeholder="请输入网站名称" value=""></p ><p>网站链接<input type="text" name="link" class="layui-layer-input" placeholder="请输入以http或https开头的url" value=""></p >联系邮箱:<input type="text" name="email" class="layui-layer-input" placeholder="联系邮箱" value=""></p >',
                yes: function () {
                    if ($("input[name='title']").val() != "" && $("input[name='link']").val() != "" && $("input[name='email']").val() != "") {
                        $.ajax({
                            type: "POST",
                            url: "/index/linkshandle.html",
                            data: {
                                title: $("input[name='title']").val(),
                                link: $("input[name='link']").val(),
                                email: $("input[name='email']").val()
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.status == 0) {
                                    layer.msg(data.msg, {icon: 1});
                                    window.location.reload();
                                } else {
                                    layer.msg(data.msg, {icon: 0});
                                    return false;
                                }
                            }
                        });
                    } else {
                        layer.msg('网站名称或网站链接以及联系邮箱不能为空', {icon: 0});
                    }
                }
            });
        })
    })

</script>