/*自动推送功能*/
(function () {
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();

window.onload = function () {
    var oH2 = document.getElementsByTagName("h2")[0];
    var oUl = document.getElementsByTagName("ul")[0];
    oH2.onclick = function () {
        var style = oUl.style;
        style.display = style.display == "block" ? "none" : "block";
        oH2.className = style.display == "block" ? "open" : ""
    }

    //复制功能
    // function addLink() {
    //     var selection = window.getSelection();
    //     pagelink = "<br /><br />作者：Vijay<br />链接： " + document.location.href + "<br />来源：臭大佬<br />著作权归Vijay所有，任何形式的转载都请联系Vijay(1937832819@qq.com)获得授权并注明出处。";
    //     copytext = selection + pagelink;
    //     newdiv = document.createElement('div');
    //     newdiv.style.position = 'absolute';
    //     newdiv.style.left = '-99999px';
    //     document.body.appendChild(newdiv);
    //     newdiv.innerHTML = copytext;
    //     selection.selectAllChildren(newdiv);
    //     window.setTimeout(function () {
    //         document.body.removeChild(newdiv);
    //     }, 100);
    // }
    // document.oncopy = addLink;
};