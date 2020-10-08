<div class="paihang" style="margin-top:20px;">
    <h2 class="hometitle">热度榜</h2>
    <ul  id="clickArticle">
    </ul>
</div>

<script>
    $(function () {
        $('#clickArticle').append('<li>加载中，请稍候</li>');
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {}, //传接收到的参数id
            url: '/clickArticle',
            success: function (data) {
                var li = '';
                var res = data['data'];
                if (res.length > 0) {
                    for (var i = 0; i < res.length; i++) {
                        li += '<li><b><a href="/article/'+res[i].id+'" title="'+res[i].title+'">'+res[i].title+'</a></b><p><i><img src="'+res[i].cover+'" alt="'+res[i].title+'" title="'+res[i].title+'"></i>'+res[i].description+'</p></li>';
                    }
                } else {
                    li += '<li>没有数据哦!</li>';
                }
                $('#clickArticle').find('li').remove();
                $('#clickArticle').append(li);
            }
        })
    })
</script>
