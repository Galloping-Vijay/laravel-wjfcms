<div class="cloud">
    <h2 class="hometitle">标签云</h2>
    <ul id="ajaxTags">
    </ul>
</div>
<script>
    $(function () {
        $('#ajaxTags').append('<li>加载中，请稍候</li>');
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {}, //传接收到的参数id
            url: '/ajaxTags',
            success: function (data) {
                var li = '';
                var res = data['data'];
                if (res.length > 0) {
                    for (var i = 0; i < res.length; i++) {
                        li += '<a href="/tag/'+res[i].id+'">'+res[i].name+'</a>';
                    }
                } else {
                    li += '<li>没有数据哦!</li>';
                }
                $('#ajaxTags').find('li').remove();
                $('#ajaxTags').append(li);
            }
        })
    })
</script>
