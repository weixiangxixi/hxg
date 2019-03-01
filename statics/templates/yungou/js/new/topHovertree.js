/*
jQuery返回顶部
使用方法：initTopHoverTree("id")
*/

function initTopHoverTree(hvtid) {
    var elm = $("#" + hvtid);

    elm.on("click", function () {
        $("html, body").animate({"scrollTop" : 0}, 200)
    });

    var timer;
    $(window).scroll(function () {
        clearTimeout(timer);
        timer = setTimeout( function () { refreshScrTop(elm) }, 150 ) // 即scrollEnd时才触发
    });
}

function refreshScrTop (elm) {
    if ( $(window).scrollTop() >= 250 && !elm.hasClass("show") )
        elm.addClass("show");

    if ( $(window).scrollTop() < 250 && elm.hasClass("show") )
        elm.removeClass("show");
}