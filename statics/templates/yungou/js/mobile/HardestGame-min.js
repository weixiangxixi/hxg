!function(t) {
    var e = {};
    function i(s) {
        if (e[s])
            return e[s].exports;
        var o = e[s] = {
            i: s,
            l: !1,
            exports: {}
        };
        return t[s].call(o.exports, o, o.exports, i),
        o.l = !0,
        o.exports
    }
    i.m = t,
    i.c = e,
    i.d = function(t, e, s) {
        i.o(t, e) || Object.defineProperty(t, e, {
            enumerable: !0,
            get: s
        })
    }
    ,
    i.r = function(t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
            value: "Module"
        }),
        Object.defineProperty(t, "__esModule", {
            value: !0
        })
    }
    ,
    i.t = function(t, e) {
        if (1 & e && (t = i(t)),
        8 & e)
            return t;
        if (4 & e && "object" == typeof t && t && t.__esModule)
            return t;
        var s = Object.create(null);
        if (i.r(s),
        Object.defineProperty(s, "default", {
            enumerable: !0,
            value: t
        }),
        2 & e && "string" != typeof t)
            for (var o in t)
                i.d(s, o, function(e) {
                    return t[e]
                }
                .bind(null, o));
        return s
    }
    ,
    i.n = function(t) {
        var e = t && t.__esModule ? function() {
            return t.default
        }
        : function() {
            return t
        }
        ;
        return i.d(e, "a", e),
        e
    }
    ,
    i.o = function(t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }
    ,
    i.p = "",
    i(i.s = 0)
}([function(t, e) {
    !function() {
        var t = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
          , e = window.innerHeight
          , i = (t = t / e > .636 ? .636 * e : t) / 750;
        document.getElementsByTagName("html")[0].style.fontSize = 100 * i + "px";
        var s, o = 204 * i, l = [{
            left: {
//            	url: "./CircleCenter_1_split_left.png?version=1",
                url: sysGame[0].lbg,
                w: "238",
                h: "407",
                rs: 2 * Math.PI
            },
            right: {
//            	url: "./CircleCenter_1_split_right.png?version=1",
                url: sysGame[0].rbg,
                w: "230",
                h: "407",
                cutx: "26",
                rs: 2 * Math.PI
            }
        }, {
            left: {
//            	url: "./CircleCenter_2_split_left.png?version=1",
            	url: sysGame[1].lbg,
                w: "238",
                h: "407",
                rs: 2 * Math.PI
            },
            right: {
//            	url: "./CircleCenter_2_split_right.png?version=1",
            	url: sysGame[1].rbg,
                w: "230",
                h: "407",
                cutx: "26",
                rs: 2 * Math.PI
            }
        }, {
            left: {
//            	url: "./CircleCenter_3_split_left.png?version=1",
            	url: sysGame[2].lbg,
                w: "238",
                h: "407",
                rs: 2 * Math.PI
            },
            right: {
//            	url: "./CircleCenter_3_split_right.png?version=1",
            	url: sysGame[2].rbg,
                w: "230",
                h: "407",
                cutx: "26",
                rs: 2 * Math.PI
            }
        }], n = 0, a = 42 * i, r = [-.1, -.05, -.01, .01, .05, .1], h = 8, c = !0, u = [sysGame[0].Lip1, sysGame[1].Lip1, sysGame[2].Lip1], p = [sysGame[0].bg, sysGame[1].bg, sysGame[2].bg], g = [{
//        	nomal: "./level_icon_1_active.png?version=1",
//        	active: "./level_icon_1_active.png?version=1"
            nomal: sysGame[0].level,
            active: sysGame[0].level
        }, {
//        	nomal: "./level_icon_2.png?version=1",
//        	active: "./level_icon_2_active.png?version=1"
        	nomal: sysGame[1].level1,
            active: sysGame[1].level
        }, {
//        	nomal: "./level_icon_3.png?version=1",
//        	active: "./level_icon_3_active.png?version=1"
        	nomal: sysGame[2].level1,
            active: sysGame[2].level
        }], m = Math.max(.25 * e, .5 * t - 40), d = {
            x: .5 * t,
            y: 494 * i
        }, v = {
            w: 43 * i,
            h: 168 * i
        }, y = e - 58 * i - v.h + a, A = {
        	//西瓜旋转速度设置
            ROTAION_SPEED_ARRAY: [-.07, -.05, -.01, .01, .05, .07],
            //旋转加速度
            rotationAccelerationSpeed: 0.001,
            //TODO 参数：已插入口红数，总口红数，旋转初始速度，总游戏时间
//            levelArray: [0, 6, -.02, 30]
            levelArray: [0, parseFloat(goodGame[0].num), parseFloat(goodGame[0].speed), parseInt(goodGame[0].time)]
        }, f = {
            ROTAION_SPEED_ARRAY: [-.07, -.05, -.01, .01, .05, .07],
            rotationAccelerationSpeed: 0.002,
//            levelArray: [0, 10, .04, 40]
            levelArray: [0, parseFloat(goodGame[1].num), parseFloat(goodGame[1].speed), parseInt(goodGame[1].time)]
        }, S = {
            ROTAION_SPEED_ARRAY: [-.07, -.05, -.01, .01, .05, .07],
            rotationAccelerationSpeed: 0.004,
//            levelArray: [0, 13, .06, 60]
            levelArray: [0, parseFloat(goodGame[2].num), parseFloat(goodGame[2].speed), parseInt(goodGame[2].time)]
        }, C = {
            ROTAION_SPEED_ARRAY: [-.07, -.05, -.03, .03, .05, .07],
            rotationAccelerationSpeed: 0.004,
//            levelArray: [0, 13, .06, 60]
            levelArray: [0, parseFloat(goodGame[2].num), parseFloat(goodGame[2].speed), parseInt(goodGame[2].time)]
        }, x = {
            ROTAION_SPEED_ARRAY: [-.07, -.06, -.03, .03, .06, .07],
            rotationAccelerationSpeed: 0.004,
//            levelArray: [0, 13, .06, 60]
            levelArray: [0, parseFloat(goodGame[2].num), parseFloat(goodGame[2].speed), parseInt(goodGame[2].time)]
        }, w = {
            ROTAION_SPEED_ARRAY: [-.08, -.06, -.03, .03, .06, .08],
            rotationAccelerationSpeed: 0.004,
//            levelArray: [0, 13, .06, 60]
            levelArray: [0, parseFloat(goodGame[2].num), parseFloat(goodGame[2].speed), parseInt(goodGame[2].time)]
        }, _ = {
            cheatDistance: .6 * v.w,
            startJudgeCheatDistanceNum: 3
        }, b = {
            w: 89 * i,
            h: 21 * i,
            space: 41 * i,
            bottom: 60 * i
        };
        function k(t, e, i, s, o) {
            this.x = t,
            this.y = e,
            this.radius = i,
            this.imgObj = s,
            this.angle = o
        }
        function P(t, e, i, s, o) {
            this.x = t,
            this.y = e,
            this.w = i,
            this.h = s,
            this.imgObj = o
        }
        function R(t, e, i, s, o, l, n) {
            this.x = t,
            this.y = e,
            this.w = i,
            this.h = s,
            this.z = o,
            this.imgObj = l,
            this.angle = n,
            R.prototype.paint = function(t) {
                t.save(),
                t.translate(this.x, this.y),
                t.rotate(this.angle + 1.5 * Math.PI),
                t.beginPath(),
                t.moveTo(-this.w / 2, -this.z),
                t.lineTo(this.w / 2, -this.z),
                t.lineTo(this.w / 2, this.h - this.z),
                t.lineTo(-this.w / 2, this.h - this.z),
                t.closePath(),
                this.imgObj && t.drawImage(this.imgObj, -1 * i / 2, -this.z, this.w, this.h),
                t.restore()
            }
            ,
            R.prototype.update = function() {}
        }
        function I(t, e, i, s, l, n, a) {
            this.restNum = i,
            this.y = s,
            this.isQuake = l,
            this.isSplit = n,
            this.level = a,
            this.super.call(this, d.x, this.y, o, t, e)
        }
        function O(t, e, i, s, o, l) {
            this.super.call(this, 0, 0, t, e, a, s, i),
            this.angle = i,
            this.resetPosition()
        }
        function M(t, e, i, s, o, l, n, r, h) {
            this.super.call(this, t, e, i, s, a, o, l),
            this.newY = this.y,
            this.isFail = n,
            this.failDirection = r,
            this.failSpeed = h
        }
        function N(t, e) {
            if (N.prototype.levelLength = 3,
            !t || !t.getContext)
                throw new Error("参数canvas不能为空, 且必须为canvas元素");
            this.canvas = t;
            var i = JSON.parse($.cookie("game_cookie"));
            i.game_result || (i = {
                game_result: 0
            })
        }
        window.extendClass || (window.extendClass = function(t, e) {
            "function" == typeof e && "function" == typeof t && (t.prototype = new (function() {}
            .prototype = e.prototype).constructor,
            t.prototype.constructor = t,
            t.prototype.super = e)
        }
        ),
        k.prototype = {
            paint: function(t) {
                t.save(),
                t.fillStyle = this.fillStyle,
                t.beginPath(),
                t.arc(this.x, this.y, this.radius, 0, 2 * Math.PI, !0),
                t.restore()
            }
        },
        P.prototype = {
            paint: function(t) {
                t.save(),
                t.translate(this.x, this.y),
                t.beginPath(),
                t.moveTo(0, 0),
                t.lineTo(0, this.w),
                t.lineTo(this.w, this.h),
                t.lineTo(0, this.h),
                t.closePath(),
                this.imgObj && t.drawImage(this.imgObj, 0, 0, this.w, this.h),
                t.restore()
            }
        },
        extendClass(I, k),
        I.prototype.paint = function(t) {
            this.super.prototype.paint.call(this, t)
        }
        ,
        I.prototype.update = function(t, e, i, o) {
            this.isQuake && (c ? (this.y = this.y - 2,
            s - this.y >= 4 && (c = !1)) : (this.y = this.y + 2,
            s <= this.y && (this.y = s,
            this.isQuake = !1,
            c = !0,
            s = null))),
            //TODO
            this.angle += parseFloat(t),
            this.angle %= 2 * Math.PI,
            this.isSplit ? this.resetCircleCenterSplit(e, i, o, t) : this.resetCircleCenterRotate(e)
        }
        ,
        I.prototype.resetCircleCenterRotate = function(t) {
            t.save(),
            t.translate(d.x, this.y);
            var e = this.imgObj
              , i = (this.x,
            this.y,
            this.radius);
            t.rotate(this.angle),
            t.drawImage(e, -1 * i, -1 * i, 2 * i, 2 * i),
            t.restore(),
            t.save(),
            this.restNum >= 10 ? ($("#bulletsNum2").css("display", "block"),
            tmpNumPic = Math.floor(this.restNum / 10),
            tmpNumPic1 = this.restNum % 10,
//            $("#bulletsNum1").attr("src", "./" + Math.floor(this.restNum / 10) + ".png?version=1"),
//            $("#bulletsNum2").attr("src", "./" + this.restNum % 10 + ".png?version=1")) : ($("#bulletsNum2").attr("src", ""),
            $("#bulletsNum1").attr("src", sysGame['time'+tmpNumPic]),
            $("#bulletsNum2").attr("src", sysGame['time'+tmpNumPic1])) : ($("#bulletsNum2").attr("src", ""),
            $("#bulletsNum2").css("display", "none"),
//            $("#bulletsNum1").attr("src", "./" + this.restNum + ".png?version=1"))
            $("#bulletsNum1").attr("src", sysGame['time'+this.restNum]))
        }
        ,
        I.prototype.resetCircleCenterSplit = function(t, e, s, o) {
            s.left.x -= 2.5,
            s.left.y -= h,
            h -= .2,
            s.right.x += 2.5,
            s.right.y -= h;
            var n = l[this.level].right.cutx * i
              , a = l[this.level].left.w * i
              , r = l[this.level].right.w * i
              , c = {
                left: {
                    x: d.x + s.left.x - .5 * a,
                    y: this.y + s.left.y
                },
                right: {
                    x: d.x - n + s.right.x + .5 * r,
                    y: this.y + s.right.y
                }
            }
              , u = this.radius;
            t.save(),
            t.translate(c.left.x, c.left.y);
            var p = new Image;
            p.src = l[this.level].left.url;
            a = l[this.level].left.w * i;
            t.arc(0, 0, 5, 0, 2 * Math.PI, !0),
            l[this.level].left.rs -= o,
            t.rotate(l[this.level].left.rs),
            t.drawImage(p, -1 * u + .5 * a, -1 * u, a, 2 * u),
            t.restore(),
            t.save(),
            t.translate(c.right.x, c.right.y);
            var g = new Image;
            g.src = l[this.level].right.url;
            r = l[this.level].right.w * i;
            l[this.level].right.rs += o,
            t.rotate(l[this.level].right.rs),
            t.drawImage(g, .5 * -r, -1 * u, a, 2 * u),
            t.restore(),
            c.left.x < -1 * a && (this.isSplit = !1,
            e.nextLevel.call(e))
        }
        ,
        extendClass(O, R),
        O.prototype.update = function(t, e) {
        	this.angle += t,
            this.isSplit ? ($(".bulletsNumBox").css("display", "none"),
            clearInterval(timeboxInterval),
            this.resetCircleRotationEnd(e)) : ($(".bulletsNumBox").css("display", "none"),
            this.resetPosition())
        }
        ,
        O.prototype.resetPosition = function() {
            this.x = d.x + o * Math.cos(this.angle),
            this.y = d.y + o * Math.sin(this.angle)
        }
        ,
        O.prototype.paint = function(t) {
            this.super.prototype.paint.call(this, t)
        }
        ,
        O.prototype.resetCircleRotationEnd = function(t) {
            this.y -= h > 0 ? .2 * h : 1.4 * h
        }
        ,
        extendClass(M, R),
        M.prototype.update = function(t) {
            this.isFail ? (this.y += 2 * h,
            h += .24,
            this.x -= 100 * this.failSpeed,
            this.y >= e + a && t.gameOver.call(t)) : this.y > this.newY && (this.y += -30,
            this.y = this.y < this.newY ? this.newY : this.y)
        }
        ,
        M.prototype.paint = function(t) {
            this.super.prototype.paint.call(this, t)
        }
        ,
        N.prototype = {
            level: 1,
            isPause: !1,
            isOver: !0,
            isCanTap: !0,
            centerCircle: null,
            rotationCircles: [],
            bulletCircles: [],
            nowSpeed: null,
            AimBullet: null,
            levelLength: null,
            rotationSpeed: .03,
            rotaionTimes: 0,
            oRandom: 0,
            isFastChangeRotationAccelerationSpeed: !1,
            mobileTouch: null,
            levelSpaceTime: 1,
            centerCircleSplitReposition: {
                left: {
                    x: 0,
                    y: 0
                },
                right: {
                    x: 0,
                    y: 0
                }
            },
            saveCenterCircleSplitReposition: {
                left: {
                    x: 0,
                    y: 0
                },
                right: {
                    x: 0,
                    y: 0
                }
            },
            levelA: 0,
            getParams: function(t) {
                return 0 == t ? A : 1 == t ? f : 2 == t ? S : 3 == t ? C : 4 == t ? x : 5 == t ? w : void 0
            },
            init: function() {
                var i = this.getParams(this.levelA);
                n = 0,
                this.canvas.width = t,
                this.canvas.height = e,
                this.context = this.canvas.getContext("2d");
                var s = new Image;
//                s.src = "./CircleCenter_1.png?version=1",
                s.src = sysGame[0].bg,
                lens = i.levelArray[1],
                this.centerCircle || (this.centerCircle = new I(s,.5 * Math.PI,lens,d.y,!1,!1,1));
                var o = this
                  , l = "ontouchend"in document ? "touchend" : "click";
                $("#game").on(l, function(t) {
                    t.preventDefault(),
                    o.tapHandle.call(o, t)
                }),
                $("#game").on("click", function(t) {
                    o.tapHandle.call(o, t)
                })
            },
            gameStart: function() {
            	var that = this;
                if(nowMid && nowMid>0){
                	$.ajax({
                		type:'post',
                		url:'/index.php/mobile/game/do_play',
                		data:{mid: nowMid, oid: nowOid},
                		success:function(data){
                			data = JSON.parse(data);
                			console.log(data);
                			if(data.code==1){
                				logId = data.logid;
                				//挑战
                				that.level = 1,
                				that.isPause = !1,
                				that.isOver = !1,
                				that.isCanTap = !0,
                				that.levelChange.call(that),
                				that.update.call(that);
                			}else{
                                $("#falseBox").css("display", "block");
                                $("#falseBoxTitle").html(data.msg);
                                $("#falseBoxBtn").html(data.btn);
                            }
                		}
                	});
                }
                else{
                	//体验模式
                	this.level = 1,
                    this.isPause = !1,
                    this.isOver = !1,
                    this.isCanTap = !0,
                    this.levelChange.call(this),
                    this.update.call(this);
                }
            },
            gamePause: function() {
                this.isCanTap = !1,
                this.isPause = !0
            },
            gameContinue: function(t) {
                this.isPause = !1,
                this.isCanTap = !0,
                (this.isOver || t) && (this.levelChange.call(this),
                this.isOver = !1),
                this.update.call(this)
            },
            gameOver: function() {
            	var that = this;
            	var tmpLevel = that.level;
        		//闯关失败
            	$.ajax({
            		type:'post',
            		url:'/index.php/mobile/game/game_fail',
            		data:{mid: nowMid, oid: nowOid},
            		success:function(data){
            			data = JSON.parse(data);
            		}
        		}),
            	that.isOver = !0,
    			that.isCanTap = !1,
                that.gameOverHandle && that.gameOverHandle.call(that, that.level);	
            },
            update: function() {
                if (this.levelA = parseInt(this.level - 1),
                this.rotaionTimes++,
                this.context.clearRect(0, 0, t, e),
                !this.isPause && !this.isOver) {
                    var i, s;
                    for (s = this.rotationCircles.length,
                    i = 0; i < s; i++)
                        this.rotationCircles[i].update(this.rotationSpeed, this.context);
                    if (s = this.bulletCircles.length,
                    this.AimBullet.update(this),
                    !this.isCanTap) {
                        var l = this.AimBullet;
                        if (l.y <= d.y + m + 20) {
                            var n = this.checkCollision.call(this, l);
                            if (n.isCollision && (this.postGameResult(!1),
                            audioAutoPlay1("collision_audio"),
                            this.AimBullet.isFail = !0,
                            this.AimBullet.failDirection = n.failDirection,
                            this.AimBullet.failSpeed = this.rotationSpeed),
                            l.y == d.y + o) {
                                var a = JSON.parse($.cookie("game_cookie"));
                                a.game_result || (a = {
                                    game_result: 0
                                }),
                                this.normalResults.call(this)
                            }
                        }
                    }
                    this.paint.call(this)
                }
                this.centerCircle.level = this.levelA,
                this.changeSpeed.call(this)
            },
            changeSpeed: function() {
                var t = this.getParams(this.levelA);
                this.nowSpeed = t.ROTAION_SPEED_ARRAY[this.oRandom];
                var e = parseInt(t.levelArray[1] - n);
                3 == this.level && e <= 3 ? this.last_3_changeSpeed.call(this, e) : this.rotationSpeed >= this.nowSpeed ? this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed -= 2 * t.rotationAccelerationSpeed : this.rotationSpeed -= t.rotationAccelerationSpeed : this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed += 2 * t.rotationAccelerationSpeed : this.rotationSpeed += t.rotationAccelerationSpeed,
                this.centerCircle.update(this.rotationSpeed, this.context, this, this.centerCircleSplitReposition)
            },
            last_3_changeSpeed: function(t) {
                if (3 == t) {
                    var e = this.getParams(3);
                    this.nowSpeed = e.ROTAION_SPEED_ARRAY[this.oRandom];
                    t = parseInt(e.levelArray[1] - n);
                    this.rotationSpeed >= this.nowSpeed ? this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed -= 2 * e.rotationAccelerationSpeed : this.rotationSpeed -= e.rotationAccelerationSpeed : this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed += 2 * e.rotationAccelerationSpeed : this.rotationSpeed += e.rotationAccelerationSpeed
                } else if (2 == t) {
                    e = this.getParams(1);
                    this.nowSpeed = e.ROTAION_SPEED_ARRAY[this.oRandom];
                    t = parseInt(e.levelArray[1] - n);
                    this.rotationSpeed >= this.nowSpeed ? this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed -= 2 * e.rotationAccelerationSpeed : this.rotationSpeed -= e.rotationAccelerationSpeed : this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed += 2 * e.rotationAccelerationSpeed : this.rotationSpeed += e.rotationAccelerationSpeed
                } else {
                    e = this.getParams(1);
                    this.nowSpeed = e.ROTAION_SPEED_ARRAY[this.oRandom];
                    t = parseInt(e.levelArray[1] - n);
                    this.rotationSpeed >= this.nowSpeed ? (this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed -= 2 * e.rotationAccelerationSpeed : this.rotationSpeed -= e.rotationAccelerationSpeed,
                    this.rotationSpeed -= e.rotationAccelerationSpeed) : this.isFastChangeRotationAccelerationSpeed ? this.rotationSpeed += 2 * e.rotationAccelerationSpeed : this.rotationSpeed += e.rotationAccelerationSpeed
                }
            },
            normalResults: function() {
//            	this.bulletCircles[n] && (this.bulletCircles[n].imgObj.src = "./Sword_small_gray.png?version=1",
                this.bulletCircles[n] && (this.bulletCircles[n].imgObj.src = sysGame.Lip,
                n++,
                this.centerCircle.restNum = parseInt(this.getParams(this.levelA).levelArray[1] - n)),
                this.isCanTap = !0;
                var t = new Image;
                if (t.src = u[this.level - 1],
                this.rotationCircles.push(new O(v.w,v.h,.5 * Math.PI,t,!1)),
                lens = parseInt(this.getParams(this.levelA).levelArray[1]),
                n == lens) {
                    $("#split_audio").get(0).play();
                    $("#Countdown_10s_audio").get(0).pause();
                    p[this.levelA + 1] && (this.centerCircle.imgObj.src = p[this.levelA + 1],
                    this.centerCircle.restNum = this.getParams(this.levelA + 1).levelArray[1]),
                    this.centerCircle.isSplit = !0,
                    this.postGameResult(!0);
                    for (var e = 0; e < this.rotationCircles.length; e++)
                        this.rotationCircles[e].isSplit = !0;
                    for (var i in this.AimBullet)
                        delete this.AimBullet[i]
                } else {
                    $("#insert_audio").get(0).play();
                    for (var i in s = this.centerCircle.y,
                    audioAutoPlay1("insert_audio"),
                    this.centerCircle.isQuake = !0,
                    this.AimBullet)
                        delete this.AimBullet[i];
                    play();
                    var o = new Image;
                    o.src = u[this.levelA],
                    this.AimBullet = new M(d.x,y,v.w,v.h,o,.5 * Math.PI,!1,null,null)
                }
            },
            forceFailResults: function() {
                console.log(22);
                var t;
                this.rotationSpeed > 0 ? t = "clockwise" : this.rotationSpeed <= 0 && (t = "antiClockwise");
                for (var e = 0; e < this.rotationCircles.length; e++)
                    if (this.rotationCircles[e].y > d.y)
                        if ("clockwise" == t && this.rotationCircles[e].x > this.AimBullet.x && this.rotationCircles[e].x <= this.AimBullet.x + .5 * this.AimBullet.w + _.cheatDistance + .5 * this.AimBullet.w) {
                            console.log("isMustFail = true");
                            var i = this.getParams(this.levelA);
                            this.nowSpeed = i.ROTAION_SPEED_ARRAY[this.oRandom],
                            console.log("this.rotationSpeed:" + this.rotationSpeed),
                            this.rotationSpeed <= .3 && (console.log("------"),
                            this.rotationSpeed += 10 * i.rotationAccelerationSpeed),
                            !0
                        } else if ("antiClockwise" == t && this.rotationCircles[e].x < this.AimBullet.x && this.rotationCircles[e].x >= this.AimBullet.x - .5 * this.AimBullet.w - _.cheatDistance - .5 * this.AimBullet.w) {
                            console.log("isMustFailq = true");
                            i = this.getParams(this.levelA);
                            this.nowSpeed = i.ROTAION_SPEED_ARRAY[this.oRandom],
                            this.rotationSpeed >= -.3 && (console.log("++++++"),
                            this.rotationSpeed -= 10 * i.rotationAccelerationSpeed),
                            !0
                        } else
                            this.isFastChangeRotationAccelerationSpeed = !1;
                if (this.bulletCircles[n])
                    parseInt(this.getParams(this.levelA).levelArray[1] - n);
                JSON.parse($.cookie("game_cookie"));
                this.normalResults.call(this)
            },
            audioPlay: function(t) {
                t.paused && t.play()
            },
            postGameResult: function(t) {
                this.levelA;
                var e = JSON.parse($.cookie("game_cookie"));
                e.user_id,
                e.randomNum,
                e.game_id,
                e.product_id
            },
            paint: function() {
                var t, e;
                for (t = this.rotationCircles.length,
                e = 0; e < t; e++)
                    this.rotationCircles[e].paint(this.context);
                for (t = this.bulletCircles.length,
                e = 0; e < t; e++)
                    this.bulletCircles[e].paint(this.context);
                this.AimBullet.paint(this.context),
                this.centerCircle.paint(this.context);
                var i = this;
                window.requestAnimationFrame(function() {
                    i.update.call(i)
                })
            },
            nextLevel: function() {
                $("#Countdown_10s_audio").get(0).pause();
                $(".bulletsNumBox").css("display", "none"),
                clearInterval(timeboxInterval),
                n = 0;
                console.log(nowMid);
                // if((!nowMid && nowMid==0)){
                //     console.log("haha");
                // 	//体验模式更改关卡数为2
                // 	this.levelLength = 3;
                // }
                if (this.level++,
                this.level <= this.levelLength) {
                    JSON.parse($.cookie("game_cookie"));
//                    $("#levelSwitchBoxMain").attr("src", "./level_" + this.level + "Switch_main.png?version=1"),
                    var tmpLevel = parseInt(this.level) - 1
                    $("#levelSwitchBoxMain").attr("src", sysGame[tmpLevel].switch),
                    $("#levelSwitchBox").css("display", "block"),
                    $("#levelSwitchBox").addClass("hidden")
                }
                this.gamePause.call(this),
                this.levelSuccessHandle(this.level)
            },
            levelChange: function() {
            	console.log(this.level);
                var t, i, s;
                this.levelA = this.level - 1,
                this.rotationCircles = [],
                this.bulletCircles = [],
                this.centerCircleSplitReposition = {
                    left: {
                        x: 0,
                        y: 0
                    },
                    right: {
                        x: 0,
                        y: 0
                    }
                },
                h = 8;
                for (var o = 0; o < this.levelLength; o++)
                    document.getElementById("levelbox").children[o].children[0].src = g[o].nomal,
                    o <= this.levelA && (document.getElementById("levelbox").children[o].children[0].src = g[o].active);
                t = parseInt(this.getParams(this.levelA).levelArray[0]);
                var l = parseInt(this.getParams(this.levelA).levelArray[3])
                  , n = 0;
                for ($("#timebox").html(l),
                timeboxInterval = setInterval(function() {
                    if (l<=11) {
                        $("#Countdown_10s_audio").get(0).play();
                    }
                    l <= 11 && audioAutoPlay1("Countdown_10s_audio"),
                    ++n % 2 == 0 && (this.oRandom = Math.floor(Math.random() * r.length)),
                    0 == --l && this.gameOver.call(this),
                    $("#timebox").html(l)
                }
                .bind(this), 1e3),
                this.centerCircle.restNum = parseInt(this.getParams(this.levelA).levelArray[1]),
                s = 2 * Math.PI / t,
                o = 0; o < t; o++) {
                    var a = new Image;
                    a.src = u[this.level - 1],
                    i = new O(v.w,v.h,s * o,a,!1),
                    this.rotationCircles.push(i)
                }
                for (t = parseInt(this.getParams(this.levelA).levelArray[1]),
                o = 0; o < t; o++) {
                    var c = new Image;
//                    c.src = "./Sword_small_" + this.level + ".png?version=2",
                    var tmpLevel = parseInt(this.level) - 1;
                    c.src = sysGame[tmpLevel].Lip2,
                    tempClip = new P(10,e - b.bottom - b.space * t + o * b.space,b.w,b.h,c),
                    this.bulletCircles.push(tempClip)
                }
                var p = new Image;
                p.src = u[this.level - 1],
                this.AimBullet && delete this.AimBullet,
                this.AimBullet || (this.AimBullet = new M(d.x,y,v.w,v.h,p,.5 * Math.PI,!1)),
                this.getParams(this.levelA).levelArray.length >= 3 ? this.rotationSpeed = this.getParams(this.levelA).levelArray[2] : this.rotationSpeed = .03
            },
            crossMul: function(t, e) {
                return t.x * e.y - t.y * e.x
            },
            checkCross: function(t, e, i, s) {
                var o = {
                    x: t.x - i.x,
                    y: t.y - i.y
                }
                  , l = {
                    x: e.x - i.x,
                    y: e.y - i.y
                }
                  , n = {
                    x: s.x - i.x,
                    y: s.y - i.y
                }
                  , a = this.crossMul(o, n) * this.crossMul(l, n);
                return o = {
                    x: i.x - t.x,
                    y: i.y - t.y
                },
                l = {
                    x: s.x - t.x,
                    y: s.y - t.y
                },
                n = {
                    x: e.x - t.x,
                    y: e.y - t.y
                },
                a <= 5e4 && (this.crossMul(o, n),
                this.crossMul(l, n)),
                a <= 0 && this.crossMul(o, n) * this.crossMul(l, n) <= 0
            },
            checkCollision: function(t) {
                for (var e = t.x - .5 * t.w, i = t.y - t.z, s = t.w, o = t.h, l = {
                    x: e,
                    y: i
                }, n = {
                    x: e + s,
                    y: i
                }, a = {
                    x: e + s,
                    y: i + o
                }, r = {
                    x: e,
                    y: i + o
                }, h = 0; h < this.rotationCircles.length; h++) {
                    var c = this.rotationCircles[h].angle;
                    c %= 2 * Math.PI;
                    var u = this.rotationCircles[h].x
                      , p = this.rotationCircles[h].y
                      , g = this.rotationCircles[h].w
                      , m = this.rotationCircles[h].h
                      , v = this.rotationCircles[h].z
                      , y = {
                        x: u - g / 2 * Math.sin(c),
                        y: p + g / 2 * Math.cos(c)
                    }
                      , A = {
                        x: u + g / 2 * Math.sin(c),
                        y: p - g / 2 * Math.cos(c)
                    }
                      , f = {
                        x: u + (m - v) * Math.cos(c) + g / 2 * Math.sin(c),
                        y: p + (m - v) * Math.sin(c) - g / 2 * Math.cos(c)
                    }
                      , S = {
                        x: u + (m - v) * Math.cos(c) - g / 2 * Math.sin(c),
                        y: p + (m - v) * Math.sin(c) + g / 2 * Math.cos(c)
                    };
                    if (this.checkCross(l, n, S, y) || this.checkCross(l, r, S, y) || this.checkCross(n, a, S, y))
                        return {
                            isCollision: !0,
                            failDirection: "left"
                        };
                    if (this.checkCross(l, n, S, f) || this.checkCross(l, r, S, f) || this.checkCross(n, a, S, f))
                        return {
                            isCollision: !0,
                            failDirection: "bottom"
                        };
                    if (this.checkCross(l, n, f, A) || this.checkCross(l, r, f, A) || this.checkCross(n, a, f, A))
                        return {
                            isCollision: !0,
                            failDirection: "right"
                        };
                    t.y,
                    d.y
                }
                return !1
            },
            tapHandle: function(t) {
                if (t.stopPropagation(),
                t.preventDefault(),
                this.isCanTap) {
                    this.isCanTap = !1;
                    this.bulletCircles.length;
                    var e = this.getParams(this.levelA);
                    parseInt(e.levelArray[1] - n) <= _.startJudgeCheatDistanceNum && (this.nowSpeed > 0 ? this.nowSpeed = .1 : this.nowSpeed = -.1),
                    this.AimBullet.newY = d.y + o
                }
            },
            levelSuccessHandle: null,
            gameOverHandle: null
        },
        window.HardestGame = N
    }()
}
]);
