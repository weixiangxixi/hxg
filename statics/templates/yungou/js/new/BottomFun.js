var Gobal = new Object();

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
String.prototype.trim = function () {
    return this.replace(/(\s*$)|(^\s*)/g, "")
};
String.prototype.trims = function () {
    return this.replace(/\s/g, "")
};
var addNumToCartFun = null;
(function () {
    // Gobal.Skin = "http://mskin.1yyg.com";

    Gobal.Skin = Path.Skin;
    Gobal.Webpath = Path.Webpath;
    Gobal.imgpath = Path.imgpath;  //上传图片地址

    Gobal.LoadImg = null;
    Gobal.LoadHtml = '<div class="loadImg">正在加载</div>';
    Gobal.LoadPic = Gobal.Skin + "/images/loading.gif?171209";

    Gobal.NoneHtml = '<div class="haveNot z-minheight"><s></s><p>暂无记录</p></div>';
    Gobal.unlink = "javascript:void(0);";
    $("#tBtnReturn").click(function () {
        history.go(-1);
        return false
    });

    var d = Gobal.Webpath + "/login";
    loadImgFun(0);
/*    var a = $("#fLoginInfo");
    GetJPData(Gobal.Webpath, "ajax", "init",
        function (h) {
            var g = '<span><a href="' + Gobal.Webpath + '/mobile/mobile/init">首页</a><b></b></span><span><a href=' + Gobal.Webpath + '/mobile/mobile/about>新手指南</a><b></b></span>';
            if (h.code == 0) {
                g = g + '<span><a href="' + Gobal.Webpath + '/mobile/home" class="Member">' + h.username + '</a><a href="' + Gobal.Webpath + '/mobile/user/cook_end" class="Exit">退出</a></span>'
            } else {
                g = g + '<span><a href="' + Gobal.Webpath + '/mobile/user/login">登录</a><b></b></span><span><a href="' + Gobal.Webpath + '/mobile/register">注册</a></span>'
            }

            a.html(g)
        });*/
    //购物车网页顶部
    var c = $("#btnCart > i");
    if (c.length > 0) {
        GetJPData(Gobal.Webpath, "cart", "cartnum",
            function (g) {
                if (g.code == 0 && g.num > 0) {
                    c.html("<em>" + g.num + "</em>")
                }
            })
    }
    addNumToCartFun = function (g) {
        c.html("<em>" + g + "</em>")
    };
    var e = function (h) {
        var g = new Date();
        h.attr("src", h.attr("data")).removeAttr("id").removeAttr("data")
    };
    var f = $("#pageJS", "head");

    if (f.length > 0) {
        e(f)
    } else {
        f = $("#pageJS", "body");
        if (f.length > 0) {
            e(f)
        }
    }

})();

Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}

function unix_to_datetime(time) {
    return new Date(time * 1000).format('yyyy-MM-dd hh:mm:ss');
}

//添加到购物车
$(document).on("click", '.add[codeid]', function (o) {
    if (o && o.stopPropagation) {
        o.stopPropagation()
    } else {
        window.event.cancelBubble = true
    }

    var here = $(this);
    var codeid = here.attr('codeid');
    var cartnum=here.attr("cartnum");
    var qishu=here.attr("qishu");
    if(!cartnum) {
        cartnum=1;
    }
    var backcall_func =here.data("func");

    $.getJSON(Gobal.Webpath + '/mobile/cart/addShopCart/' + codeid + '/'+cartnum,{"qishu":qishu} ,function (data) {
        if (data.code == 1) {
            $.PageDialog.fail("本件商品您已达到限购上限！");
        } else if (data.code == 0) {
            if(backcall_func){
                eval(backcall_func)();
                return;
            }
            $("#btnCart > i").append('<em>' + data.num + '</em>');
            //$.PageDialog.fail("添加成功！");
            var img = $("ul#" + codeid).length ? $("ul#" + codeid + " img") : here.parents("li").find("img");
            var src = img.attr("src");
            var cart = $("#btnCart");
            var arr = [
                img.attr("src"),
                img.offset().left + "px",
                img.offset().top + "px",
                cart.offset().left + 20 + "px",
                cart.offset().top + 10 + "px"
            ];
            imgFun(arr);
        } else if (data.code == 2) {
            $.PageDialog.fail("添加失败，购物车已满！");
        }else if(data.code==3){
            content = '<div class="cfm-title" style="color: #f60;font-size: 14px;font-weight: bold;">本期'+qishu+'已经更新到'+data.qishu+'期</div><p style="line-height: 30px;font-size: 12px;">是否继续购买?</p>';
            $.PageDialog.confirm(content, function () {
                $.getJSON('/index.php/mobile/cart/addShopCart/' + codeid + '/'+cartnum,
                    function (data) {
                        if(data.code == 0){
                            eval(backcall_func)();
                            return;
                        }else {
                            $.PageDialog.fail("追加取消!");
                        }
                });
            });
        }
    });

    return false;
});

//添加到购物车动画函数
function imgFun(arr)
{
    var fImg = $("img#forFun");
    var fStyle = $("style#funStyle");
    var Fir = fImg.length;
    if (Fir) {
        fImg.remove();
        fStyle.remove();
    }
    var newImg = "<img id='forFun' src='" + arr[0] + "' style='left:" + arr[1] + ";top:" + arr[2] + "'>";
    var ifIndex = $("#ulRecommend").length; //判断是否首页
    if (ifIndex)
        var style = "<style id='funStyle'>@-webkit-keyframes forFun {0% {width: 120px;height: 120px}99.99% {width: 15px;height: 15px;left: " + arr[3] + ";top: " + arr[4] + ";border-radius: 50%;-webkit-transform: rotate(180deg)}100% {width: 0;height: 0}}</style>";
    else
        var style = "<style id='funStyle'>@-webkit-keyframes forFun {0% {width: 80px;height: 80px}99.99% {width: 15px;height: 15px;left: " + arr[3] + ";top: " + arr[4] + ";border-radius: 50%;-webkit-transform: rotate(180deg)}100% {width: 0;height: 0}}</style>";
    $("body").append(newImg).append(style);
}