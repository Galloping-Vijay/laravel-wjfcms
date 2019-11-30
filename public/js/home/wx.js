wx.config({
    debug: false,
    appId: '',
    timestamp: '',
    nonceStr: '',
    signature: '',
    jsApiList: [
        'checkJsApi',
        'updateAppMessageShareData',
        'updateTimelineShareData'
    ]
});
wx.ready(function () {
    // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
    wx.checkJsApi({
        jsApiList: [
            'updateAppMessageShareData',
            'updateTimelineShareData'
        ],
        success: function (res) {
        }
    });
    // 2. 分享接口
    wx.updateAppMessageShareData({
        title: '臭大佬', // 分享标题
        desc: '每一次经历，每一段时光都值得被记录，它们将会是你未来的财富。', // 分享描述
        link: 'https://www.choudalao.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
    }, function (res) {
    });
    wx.updateTimelineShareData({
        title: '臭大佬', // 分享标题
        link: 'https://www.choudalao.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'https://www.choudalao.com/images/config/avatar.jpg', // 分享图标
    }, function (res) {
    });
})