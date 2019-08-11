<link href="{{ asset('css/home/history_icon.css') }}" rel="stylesheet">
<link href="{{ asset('css/home/history_day.css') }}" rel="stylesheet">
<div class="paihang">
    <h2 class="hometitle">历史上的今天</h2>
    <ul class="events" id="events">

    </ul>
</div>
<script>
    $(function () {
        getHistoryDay();

        function getHistoryDay() {
            $('#events').append('<li>加载中，请稍候</li>');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {}, //传接收到的参数id
                url: '/history',
                success: function (data) {
                    var li = '';
                    var res = data['data'];
                    if (res.length > 0) {
                        for (var i = 0; i < res.length; i++) {
                            if(i>4){
                                break;
                            }
                            if (res[i].pic) {
                                li += '<li>' +
                                    '<div class="icon">' +
                                    '<em class="cmn-icon wiki-home-eventsOnHistory-icons wiki-home-eventsOnHistory-icons_event"></em>' +
                                    '</div>'
                                    + '<div class="event">' +
                                    '<div class="event_tit-wrapper">' +
                                    '<div class="event_tit">' + res[i].year + '年 '+res[i].lunar+'<br />' + res[i].title + '</div>' +
                                    '</div>' +
                                    '<div class="event_cnt">'
                                    + '<img src="' + res[i].pic + '" width="80%"/></div> </div> </li>';
                            } else {
                                li += '<li><div class="icon">' +
                                    '<em class="cmn-icon wiki-home-eventsOnHistory-icons wiki-home-eventsOnHistory-icons_death"></em>' +
                                    '</div>'
                                    + '<div class="event">' +
                                    '<div class="event_tit-wrapper">' +
                                    '<div class="event_tit">' + res[i]['year'] + '年<br />' + res[i].title + '</div>' +
                                    '</div>' +
                                    '</div> ' +
                                    '</li>';
                            }
                        }
                    } else {
                        li += '<li>网络异常，获取数据失败</li>';
                    }
                    $('#events').find('li').remove();
                    $('#events').append(li);
                }
            })
        }
    })
</script>