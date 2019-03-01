$(function () {
    window.sta = 0; // 添加商品类标识
    var h = null;
    var a = 0;
    var j = 0;
    var d = $("#divLotteryLoading");
    var l = $("#btnLoadMore");
    var search = $("#search").val();
    var t=20;

    /*商品揭晓机制*/
    var tempn = 0; // 已处理待揭晓商品数量
    var RollingShop = function  () {
        $("ul.over").each(function () { // each避免了for循环下的异步数据重写
            tempn++;
            if (tempn >= 20) { // 每次更新20件等待开奖的商品
                tempn = 0;
                return false;
            }
            var elm = $(this);
            id = parseInt(elm.attr("id"));
            $.getJSON(Gobal.Webpath+"/mobile/ajax/lottery_huode_shop?oid="+id,function(info){
                if ( !info.error ) {
                    elm.removeClass("rNow").removeClass("over").removeClass("rFirst").find(".revConR").html('<dl><dd><img src="http://f.weimicm.com/' + info.img + '@!thumb_110_110"></dd><dd>获得者：<em class="blue">' + info.username + '</em><br>本期：<em class="orange arial">' + info.gonumber + '</em>人次</dd></dl><dt>幸运码：<em class="orange arial">' + info.q_user_code + '</em><br>揭晓时间：<em class="c9 arial">' + info.q_end_time + '</em></dt><b class="fr z-arrow"></b>');
                    elm.find(".rNowTitle").remove();
                }
            });
        });
    };

    var getingResult = function  () {
        var overLen = $("ul.over").length;
        if (overLen) { // 存在等待彩票开奖码的商品
            RollingShop(); // 获取商品揭晓结果
        }
        setTimeout(function () {
            getingResult()
        }, 3000);
    };
    getingResult();
    /*end*/

    var c = function (o) {
        if (o && o.stopPropagation) {
            o.stopPropagation()
        } else {
            window.event.cancelBubble = true
        }
    };
    var b = function () {
        var pro = {'noindex':1};
        if(search != ""){
            pro = {'s': search,"model":14,'noindex':1};
        }else{
            pro =
                {
                    "model": 14,'noindex':1
                };
        }
        var p = function () {
            d.show();
            $.getJSON(Gobal.Webpath , "/mobile/ajax/lottery_huode_shoplist/20/" + j, function (s) {
              
                if (s.error == 0) {
                    var r = s.listItems;

                    t = r.length;
                    for (var q = 0; q < t; q++) {
                        var v = '<ul id="' + r[q].id + '"><li class="revConL">' + '<img src="'+'http://f.weimicm.com/' + r[q].thumb + '@!thumb_300_300"><span>第' + r[q].qishu + '期</span></li><li class="revConR"><dl><dd><img name="uImg" uweb="' + r[q].q_uid + '" src="http://f.weimicm.com/' + r[q].img + '@!thumb_110_110"></dd><dd><span>获得者<strong>：</strong><a name="uName" uweb="' + r[q].uid + '" class="rUserName blue">' + r[q].username + '</a></span>本期<strong>：</strong><em class="orange arial">' + r[q].gonumber + '</em>人次</dd></dl><dt>幸运码：<em class="orange arial">' + r[q].q_user_code + '</em><br/>揭晓时间：<em class="c9 arial">' + r[q].q_end_time + '</em></dt><b class="fr z-arrow"></b></li></ul>';
                        var u = $(v);
                        u.click(function () {
                            location.href = Gobal.Webpath + "/mobile/mobile/dataserver/" + $(this).attr("id")
                        }).find('img[name="uImg"]').click(function (w) {
                            location.href = Gobal.Webpath + "/mobile/mobile/userindex/" + $(this).attr("uweb");
                            c(w)
                        });
                        u.find('a[name="uName"]').click(function (w) {
                            location.href = Gobal.Webpath + "/mobile/mobile/userindex/" + $(this).attr("uweb");
                            c(w)
                        });
                        d.before(u)
                    }

                        if(t>=20){
                            l.show();
                        }else{
                            $("#btnLoadMore").attr("is_data","no");
                            $(".tip").show(1500);

                        }

                   // $("#btnLoadMore").text("加载更多");
                    loadImgFun()
                }
                d.hide()
            })
        };
        this.getInitPage = function () {
            p()
        };
        this.getNextPage = function () {
            j++;
            p()
        }
    };

    l.click(function () {
        l.hide();
        var isload=$("#btnLoadMore").attr("is_data");
        if(t>=20&&isload=='yes'){
            h.getNextPage()
        }
    });




    h = new b();
    h.getInitPage();
    var e = 0;
    var n = false;
    var g = 0;
    var i = $("#divLottery");

    var k = function () {
        var pro = {'oid': e,'model':14,'noindex':1,'limit':20};
        if(search != ""){
            pro = {'oid': e, 's': search,'model':14,'noindex':1,'limit':20};
        }
        $.getJSON(Gobal.Webpath + "/mobile/ajax/lottery_going_shoplist/" + e, function (p) {
            if (p.error == 0) {
                o(p)
            }

            setTimeout(k, 5000) 
        });
        var o = function (q) {
            var p = function (t) {
                e = t[0].sid;
                for (var r = 0; r < t.length; r++) {
                    var s = t[r];
                    //Gobal.imgpath + '/uploads/'
                    var u = $('<ul class="rNow rFirst" id="' + s.id + '"><li class="revConL"><img src="'+'http://f.weimicm.com/' + s.thumb + '@!thumb_300_300"><span>第' + s.qishu + '期</span></li><li class="revConR"><h4>' + s.title + "</h4><h5>价值：￥" + s.money + '</h5><p name="pTime"><strong><em>00</em>:<em>00</em>:<em>00</em>:<em>0</em><em>0</em></strong></p><b class="fr z-arrow"></b></li><div class="rNowTitle">正在揭晓</div></ul>');
                    u.click(function () {
                        location.href = Gobal.Webpath + "/mobile/mobile/dataserver/" + $(this).attr("id");
                    });
                    i.prepend(u);
                    u.next().removeClass("rFirst");
                    var now = parseInt(new Date().getTime() / 1000);
                    u.addClass("over");
                    u.find("p[name='pTime']").html("获取时时彩开奖码...");
                    // if (s.q_end_time <= now) {
                    //     u.addClass("over");
                    //     u.find("p[name='pTime']").html("获取时时彩开奖码...");
                    // } else {
                    //     if (s.q_end_time > window.sta) {
                    //         window.sta = s.q_end_time;
                    //         // 将等待相同时时彩期数的商品放在同一个setTimeout里，以节省浏览器资源
                    //         $.StartTimeOut(window.sta, parseInt(s.times));
                    //     }
                    //     u.attr("lottime", window.sta);
                    // }
                }
                loadImgFun();
            };
            if (n) {
                p(q.listItems)
            } else {
                Base.getScript(Gobal.Skin + "/js/mobile/LotteryTimeFunssc.js?v=20170706", function () {
                    n = true;
                    p(q.listItems)
                })
            }
        }
    };
    Base.getScript(Gobal.Skin + "/js/mobile/Comm.js", k);

});