!function(e) {
    var n = {};
    function t(a) {
        if (n[a])
            return n[a].exports;
        var i = n[a] = {
            i: a,
            l: !1,
            exports: {}
        };
        return e[a].call(i.exports, i, i.exports, t),
        i.l = !0,
        i.exports
    }
    t.m = e,
    t.c = n,
    t.d = function(e, n, a) {
        t.o(e, n) || Object.defineProperty(e, n, {
            enumerable: !0,
            get: a
        })
    }
    ,
    t.r = function(e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }),
        Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }
    ,
    t.t = function(e, n) {
        if (1 & n && (e = t(e)),
        8 & n)
            return e;
        if (4 & n && "object" == typeof e && e && e.__esModule)
            return e;
        var a = Object.create(null);
        if (t.r(a),
        Object.defineProperty(a, "default", {
            enumerable: !0,
            value: e
        }),
        2 & n && "string" != typeof e)
            for (var i in e)
                t.d(a, i, function(n) {
                    return e[n]
                }
                .bind(null, i));
        return a
    }
    ,
    t.n = function(e) {
        var n = e && e.__esModule ? function() {
            return e.default
        }
        : function() {
            return e
        }
        ;
        return t.d(n, "a", n),
        n
    }
    ,
    t.o = function(e, n) {
        return Object.prototype.hasOwnProperty.call(e, n)
    }
    ,
    t.p = "",
    t(t.s = 0)
}([function(e, n) {
    var t = null;
    window.onload = function() {
        if (document.getElementById("levelSwitchBox").addEventListener("webkitAnimationEnd", function() {
            $("#levelSwitchBox").css("display", "none"),
//            $("#levelSwitchBoxMain").attr("src", "./level_2Switch_main.png?version=1"),
            $("#levelSwitchBoxMain").attr("src", sysGame[1].switch),
            $("#levelSwitchBox").removeClass("hidden")
        }),
        $("#levelSwitchBox").addClass("hidden"),
        !t) {
            if (-1 == JSON.parse($.cookie("game_cookie")).game_pay)
                var e = !1;
            else
                e = !0;
            !function(e) {
                (t = new HardestGame(document.getElementById("gameStage"),e)).levelSuccessHandle = function() {
                    //“4”是否总关数，判断是否闯关成功
                	if (t.level < tmpTotalLevel) {
                        $("#success_audio").get(0).paused && $("#success_audio").get(0).play(),
                        document.getElementById("currentLevel").getElementsByTagName("span")[0].innerHTML = t.level;
                        var e = 4;
                        document.getElementById("gameTip").innerHTML = "完美通过第" + (t.level - 1) + "关, " + e + "秒后,开始第" + t.level + "关";
                        var n = setInterval(function() {
                            e--,
                            document.getElementById("gameTip").innerHTML = "完美通过第" + (t.level - 1) + "关, " + e + "秒后,开始<span>第" + t.level + "关</span>",
                            e <= 0 && (clearInterval(n),
                            document.getElementById("gameTip").innerHTML = "",
                            t.gameContinue(!0))
                        }, 1e3)
                    } else{
                        nowMid = 1;
                    	if(nowMid && nowMid>=0){
                            console.log("hah");
                            $("#gameSuccess_audio").get(0).play();
                    		if(wxTip && wxTip != '') alert(wxTip);
                    		$.ajax({
                        		type:'post',
                        		url:'/index.php/mobile/game/game_success',
                        		data:{mid: nowMid, oid: nowOid},
                        		success:function(data){
                        			data = JSON.parse(data);
                        			console.log(data);
                        		}
                    		}),
                    		$("#gameOverBox").css("display", "block"),
                			$("#gameOverBoxTitle").html('挑战成功'),
                			$("#gameOverBoxBtn").html('领取奖品'),
                			$("#gameOverBoxBtn").on("click", function() {
            					if(nowWindow==2){
            						location.href = appUrl;
             					}
            					else{
            						wx.miniProgram.redirectTo({
            							url: "/pages/order/order"
            						})
            					}
            				}),
            				$("#gameOverClose").on("click", function() {
            					if(nowWindow==2){
            						location.href = appUrl;
            					}
            					else{
            						wx.miniProgram.redirectTo({
            							url: "/pages/order/order"
            						})
            					}
                            });
                        }
                    	else{
                    		$("#gameSuccess_audio").get(0).paused && $("#gameSuccess_audio").get(0).play(),
                            $("#app").addClass("blur"),
                            $("#gameSuccessBox").css("display", "block"),
                            $("#gameSuccessBoxBtn").on("click", function() {
//                                wx.miniProgram.redirectTo({
//                                    url: "/pages/index/index"
//                                })
                            	if(nowWindow==2) history.back(-1);
                            	else wx.miniProgram.navigateBack()
                            }),
                            $("#gameSuccessClose").on("click", function() {
//                                wx.miniProgram.redirectTo({
//                                    url: "/pages/index/index"
//                                })
                            	if(nowWindow==2) history.back(-1);
                            	else wx.miniProgram.navigateBack()
                            })
                    	}
                    }
                }
                ,
                t.gameOverHandle = function() {
                    console.log("over");
                    $("#collision_audio").get(0).play();
                    setTimeout(function(){
                        $("#gameFail_audio").get(0).play();
                    },800);
                    clearInterval(timeboxInterval),
                    (t = null) && delete t,
                    $("#gameOverBox").css("display", "block"),
                    $("#app").addClass("blur");
                    var e = 5;
//                    document.getElementById("gameTip").innerHTML = "游戏结束, " + e + "秒后,重启游戏",
//                    document.getElementById("gameOverBoxTime").innerHTML = e + "s";
                    document.getElementById("gameTip").innerHTML = "游戏结束, " + e + "秒后,重启游戏";
                    var n = setInterval(function() {
                        e--,
                        document.getElementById("gameTip").innerHTML = "游戏结束, " + e + "秒后,重启游戏",
//                        document.getElementById("gameOverBoxTime").innerHTML = e + "s",
                        e <= 0 && (wx.miniProgram.navigateBack(),
                        clearInterval(n),
                        document.getElementById("gameTip").innerHTML = "")
                    }, 1e3);
                    $("#gameOverBoxBtn").on("click", function() {
                    	if(nowWindow==2) history.back(-1);
                    	else wx.miniProgram.navigateBack()
//                    	wx.miniProgram.redirectTo({
//                            url: "/pages/index/index"
//                        })
                    }),
                    $("#gameOverClose").on("click", function() {
//                    		wx.miniProgram.redirectTo({
//                    			url: "/pages/index/index"
//                    		})
                    	if(nowWindow==2) history.back(-1);
                    	else wx.miniProgram.navigateBack()
                    })
                }
                ,
                t.init(),
                t.canvas.parentNode.style.width = t.canvas.width + "px",
                t.canvas.parentNode.style.height = t.canvas.height + "px",
                t.gameStart(),
                document.getElementById("currentLevel").getElementsByTagName("span")[0].innerHTML = t.level
            }(e)
        }
    }
}
]);
