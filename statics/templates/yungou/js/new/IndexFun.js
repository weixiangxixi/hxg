function getOSSImage_400(img) {
    return Path.remoteImg + '/' + img + "@!thumb_400_400";
}
function getOSSImage_300(img) {
    return Path.remoteImg + '/' + img + "@!thumb_300_300";
}
function getOSSImage_200(img) {
    return Path.remoteImg + '/' + img + "@!thumb_200_200";
}
function getOSSImage_110(img) {
    return Path.remoteImg + '/' + img + "@!thumb_110_110";
}
function getOSSImage_50(img) {
    return Path.remoteImg + '/' + img + "@!thumb_50_50";
}

function GetJPData(d, c, a, b) {

//alert(d + "/mobile/" + c + "/" + a );
//alert(b);
    //$.getJSON(d + "/JPData?action=" + c + "&" + a + "&fun=?", b)
    $.getJSON(d + "/mobile/" + c + "/" + a, b)
}
function loadImgFun(c) {
    var b = $("#loadingPicBlock");
    if (b.length > 0) {
        var i = "src2";
        Gobal.LoadImg = b.find("img[" + i + "]");
        var a = function () {
            return $(window).scrollTop()
        };
        var e = function () {
            return $(window).height() + a() + 50
        };
        var h = function () {
            Gobal.LoadImg.each(function (j) {
                if ($(this).offset().top <= e()) {
                    var k = $(this).attr(i);
                    if (k) {
                        $(this).attr("src", k).removeAttr(i).show()
                    }
                }
            })
        };
        var d = 0;
        var f = -100;
        var g = function () {
            d = a();
            if (d - f > 50) {
                f = d;
                h()
            }
        };
        if (c == 0) {
            $(window).bind("scroll", g)
        }
        g()
    }
}
(function(){
    var div = $('#divLottery');
    var update = function(info, key, listLen){
        info.thumb_style = '';
        var html = '<li class="m-lott-item" id="lott-' + info.id + '">' +
                       '<div class="m-lott-pic">' +
                           '<a class="' + info.thumb_style + '" href="' + Gobal.Webpath + '/mobile/mobile/dataserver/' + info.id + '">' +
                               '<img src="' + getOSSImage_300(info.thumb) + '" border="0" alt="' + info.title + '"/>' +
                           '</a>' +
                       '</div>' +
                       '<p>倒计时</p>' +
                       '<div class="m-lott-state">' +
                           '<span class="u-time orange">' +
                               '<em></em>' +
                               '<span class="minute">99</span>:<span class="second">99</span>:<span class="millisecond">99</span>' +
                           '</span>' +
                       '</div>' +
                   '</li>';

        var winwidth = $(window).width();//获取屏幕宽度
        var liWidth = winwidth / 4;//计算li的宽度
        var marginLen = -1 * (parseInt(key) + 1) * liWidth;//获取li的宽度
        var tmpWidth = (parseInt(key) + 5) * liWidth;
        div.prepend(html);//在divLottery 的头部插入新揭晓的li
        div.find("li").css("width", liWidth);
        div.css({
            "marginLeft" : marginLen,
            "width" : tmpWidth
        });

        loadImgFun();

        if (key >= (listLen - 4)) { // 只创建4个可见元素的setTimeout
            var mydiv = div.find('#lott-' + info.id);
            var minute = mydiv.find('span.minute');
            var second = mydiv.find('span.second');
            var millisecond = mydiv.find('span.millisecond');
            var times = (new Date().getTime()) + info.times * 1000;
            var timer = setInterval(function () {
                var elmExist = $('#divLottery').find('#lott-' + info.id).length; // 重新选择元素，不设变量缓存
                if (!elmExist) { // 元素被移除则释放倒计时
                    clearInterval(timer);
                    return;
                }

                var time = times - (new Date().getTime());
                if (!info.times || time < 1) {
                    clearInterval(timer);
                    if(info.extend_model==14){
                        minute.parent().html('正在获取');
                    }else{
                        minute.parent().html('正在计算');
                    }
                    var checker = setInterval(function () {
                        var elmExist = $('#divLottery').find('#lott-' + info.id).length;
                        if (!elmExist) {
                            clearInterval(checker);
                            return;
                        }

                        $.getJSON(Gobal.Webpath + "/mobile/mobile/lottery_huode_shop", {
                            'oid': info.id
                        }, function (user) {
                            if(!user.error) {
                                clearInterval(checker);
                                mydiv.removeClass();
                                //mydiv.find(".m-lott-text > p").remove();
                                mydiv.children("p").text("获奖者");
                                mydiv.find(".m-lott-state").html('<span class="u-user">' +
                                    '<a href="' + Gobal.Webpath + '/mobile/mobile/userindex/' + user.uid + '" class="orange">' +
                                    user.user + '</a></span>');
                            }
                        });
                    }, 3000);
                    return;
                }

                i = parseInt((time / 1000) / 60);
                s = parseInt((time / 1000) % 60);
                ms = String(Math.floor(time % 1000));
                ms = parseInt(ms.substr(0, 2));
                if (i < 10)i = '0' + i;
                if (s < 10)s = '0' + s;
                if (ms < 10)ms = '0' + ms;
                minute.html(i);
                second.html(s);
                millisecond.html(ms);
            }, 41);
        }

        if (key == (listLen - 1)) {
            div.animate({marginLeft:"0px"},2000,function(){//动画效果 并在回调函数中将第五个起的li移除
                div.css("width", liWidth * 4);
                div.children("li:gt(3)").remove();
                setTimeout(thread, 5000);
            });
        }
    };

    var oid = '';
    var thread = function() {
        $.getJSON("/index.php/mobile/mobile/lottery_going_shoplist",{'oid':oid},function(infos){

            if(!infos.error) {
                var list =  infos.listItems;
                oid = list[list.length-1].id;
                var infoLen = list.length;
                for (var key in list) {
                    var info = list[key];
                    update(info, key, infoLen);
                }
            } else {
                setTimeout(thread, 4000);
            }

        });
    };
    thread();
})();