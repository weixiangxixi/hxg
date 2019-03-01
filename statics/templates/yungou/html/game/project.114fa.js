require = function i(e, t, n) {
    function a(c, u) {
        if (!t[c]) {
            if (!e[c]) {
                var o = "function" == typeof require && require;
                if (!u && o)
                    return o(c, !0);
                if (s)
                    return s(c, !0);
                var h = new Error("Cannot find module '" + c + "'");
                throw h.code = "MODULE_NOT_FOUND",
                h
            }
            var l = t[c] = {
                exports: {}
            };
            e[c][0].call(l.exports, function(i) {
                var t = e[c][1][i];
                return a(t || i)
            }, l, l.exports, i, e, t, n)
        }
        return t[c].exports
    }
    for (var s = "function" == typeof require && require, c = 0; c < n.length; c++)
        a(n[c]);
    return a
}({
    gameFlyCutter: [function(i, e, t) {
        "use strict";
        cc._RF.push(e, "0b6b1wnf/xLuqitK+tGDszP", "gameFlyCutter"),
        cc.Class({
            extends: cc.Component,
            properties: {
                game: {
                    default: null,
                    serializable: !1
                },
                target: {
                    default: null,
                    serializable: !1
                },
                liZi: {
                    default: null,
                    type: cc.Prefab
                },
                audio2: {
                    url: cc.AudioClip,
                    default: null
                },
                audio1: {
                    url: cc.AudioClip,
                    default: null
                },
                audio3: {
                    url: cc.AudioClip,
                    default: null
                },
                falied: {
                    url: cc.AudioClip,
                    default: null
                }
            },
            onLoad: function() {
                var i = cc.director.getCollisionManager();
                i.enabled = !0,
                i.enabledDrawBoundingBox = !0
            },
            onCollisionEnter: function(i, e) {
                var t = i.node.group;
                if (!("flyCutterEnd" == t && e.node.y <= -51))
                    return "target" == t ? (this.createColliderLizi(),
                    void this.colliderTarget(i)) : void 0;
                this.colliderFlyCutter(e);
                $.ajax({
            		type:'post',
            		url:'/index.php/mobile/game/game_fail',
            		data:{mid: nowMid, oid: nowOid},
            		success:function(data){
            			data = JSON.parse(data);
            			console.log(data);
            		}
        		});
            },
            setNextFlyCutter: function() {
                var i = this
                  , e = this
                  , t = this.game;
                if (t.flyCutterAllNum > t.useFlyCutterNum) {
                    0 != t.yinliang && cc.audioEngine.play(this.audio1, !1, t.yinliang);
                    var n = function() {
                        t.createFlyCutter(),
                        t.isplay = !1
                    }
                      , a = function() {};
                    (u = []).push(cc.delayTime(.1)),
                    u.push(cc.callFunc(n, this.node)),
                    u.push(cc.callFunc(a, this.node));
                    var s = cc.sequence(u);
                    this.node.runAction(s)
                } else {
                    clearInterval(this.game.chuangguanId),
                    this.game.clock.active = !1,
                    this.scheduleOnce(function() {
                        i.target.removeAllChildren()
                    }, 1),
                    0 != t.yinliang && cc.audioEngine.play(this.audio2, !1, t.yinliang);
                    var c = cc.find("Canvas/successDragon");
                    if (c.active = !0,
                    c.getComponent(dragonBones.ArmatureDisplay).playAnimation("newAnimation", 1),
                    3 == this.game.level) {
                        (u = []).push(cc.delayTime(2.6));
                        n = function() {
                            cc.find("Canvas/successDragon").active = !1;
                            var i = cc.find("Canvas/gameover");
                            i.active = !0;
                            var e = i.getChildByName("whitebg");
                            e.getChildByName("time").getComponent(cc.Label).string = "好运连连",
                            e.getChildByName("ctbutton").active = !1,
                            e.getChildByName("kaishitiaozhan").active = !0
                        }
                        ,
                        a = function() {}
                        ;
                        u.push(cc.callFunc(n, e.node)),
                        u.push(cc.callFunc(a, e.node));
                        s = cc.sequence(u);
                        this.game.node.runAction(s);

                        $.ajax({
		            		type:'post',
		            		url:'/index.php/mobile/game/game_fuck_success',
		            		data:{mid: nowMid, oid: nowOid},
		            		success:function(data){
		            			data = JSON.parse(data);
		            			if(data.code == 2){
                                    alert(data.flag);
                                }
		            		}
		        		});

                    } else {
                        var u;
                        this.kaishisuiji = !1,
                        this.canbeginxuanzhuan = !1,
                        (u = []).push(cc.delayTime(2.6));
                        n = function() {
                            e.game.gameInit(),
                            cc.find("Canvas/successDragon").active = !1
                        }
                        ,
                        a = function() {}
                        ;
                        u.push(cc.callFunc(n, e.node)),
                        u.push(cc.callFunc(a, e.node));
                        s = cc.sequence(u);
                        this.game.node.runAction(s)
                        console.log('next');
                    }
                }
            },
            colliderTarget: function(i) {
                0 != this.game.yinliang && cc.audioEngine.play(this.audio1, !1, this.game.yinliang);
                var e = this.node;
                this.node.removeFromParent();
                var t = i.node.rotation
                  , n = 2 * t * Math.PI / 360
                  , a = 160 * Math.sin(n)
                  , s = 160 * -Math.cos(n);
                e.removeComponent(cc.Animation),
                e.group = "flyCutterEnd",
                this.target.addChild(e),
                e.setLocalZOrder(-2),
                e.y = s,
                e.x = a,
                e.rotation = -t,
                this.setNextFlyCutter()
            },
            colliderFlyCutter: function() {
            	
                var i = this
                  , e = this;
                this.node.removeComponent(cc.PolygonCollider),
                this.game.useFlyCutterNum = this.game.flyCutterAllNum;
                var t = this.game.node.height;
                0 != this.game.yinliang && cc.audioEngine.play(this.audio3, !1, this.game.yinliang),
                this.node.anchorX = .5,
                this.node.anchorY = .5;
                var n = cc.spawn(cc.moveBy(1, 30, -t / 2 - 300), cc.rotateBy(2, 400));
                this.node.runAction(n),
                this.scheduleOnce(function() {
                    i.target.removeAllChildren()
                }, 2);
                var a = [];
                a.push(cc.delayTime(.3)),
                a.push(cc.callFunc(function() {
                    0 != e.game.yinliang && cc.audioEngine.play(e.falied, !1, e.game.yinliang)
                }, this.node)),
                a.push(cc.delayTime(1.5)),
                a.push(cc.callFunc(function() {
                    clearInterval(e.game.chuangguanId),
                    cc.find("Canvas/gameover").active = !0,
                    e.game.chongxintiaozhanjishi()
                }, this.node)),
                a.push(cc.callFunc(function() {}, this.node));
                var s = cc.sequence(a);
                this.node.runAction(s);
                
            },
            createColliderLizi: function() {
                var i = cc.instantiate(this.liZi);
                this.game.node.addChild(i),
                i.x = 0,
                i.y = -56,
                i.getComponent(cc.Animation).play("liziR1")
            },
            liziRun: function(i) {
                this.game.node.height;
                var e = cc.bezierBy(2, [cc.p(0, -56), cc.p(200, -400), cc.p(300, -700)]);
                i.runAction(e)
            },
            start: function() {},
            update: function(i) {}
        }),
        cc._RF.pop()
    }
    , {}],
    gameTarget: [function(i, e, t) {
        "use strict";
        cc._RF.push(e, "30f5fZ9zTtK/6TG3KEX/DKF", "gameTarget"),
        cc.Class({
            extends: cc.Component,
            properties: {
                game: {
                    default: null,
                    serializable: !1
                },
                flyCutter: {
                    default: null,
                    type: cc.Prefab
                },
                sprite_frames: {
                    default: [],
                    type: cc.SpriteFrame
                }
            },
            onLoad: function() {
                var i = cc.director.getCollisionManager();
                i.enabled = !0,
                i.enabledDrawBoundingBox = !0,
                this.anim = this.node.getComponent(cc.Animation),
                this.kaishisuiji = !1,
                this.kaishixuanzhuanshijian = 0,
                this.yijingxuanzhuantime = 0,
                this.xuanzhuanshijian = 1,
                this.zhengfanzhuan = 0,
                this.xuanzhuansudu = 1,
                this.jiasudu = 0,
                this.fangxiang = -1,
                this.fangxiangcounts = 0
            },
            onCollisionEnter: function(i, e) {
                var t = cc.sequence(cc.moveBy(.02, 0, 20), cc.moveBy(.02, 0, -20));
                this.node.runAction(t),
                1 == this.game.useFlyCutterNum && this.beiginsuijixuanzhaun()
            },
            getxuanzhuanshijian: function() {
                return 1 == this.game.level ? Math.floor(2 * Math.random()) + 1 : 2 == this.game.level ? Math.floor(2 * Math.random()) + 1 : 3 == this.game.level ? Math.floor(3 * Math.random()) + 1 : void 0
            },
            getxuanzhuanfangxiang: function() {
                var i = Math.floor(2 * Math.random());
                return -1 == this.fangxiang ? this.fangxiang = i : this.fangxiang == i ? this.fangxiangcounts >= 2 ? i = 0 == i ? 1 : 0 : this.fangxiangcounts++ : this.fangxiangcounts = 0,
                i
            },
            getchushixuanzhuandushu: function() {
                return 1 == this.game.level ? Math.floor(3 * Math.random() + 1) : 2 == this.game.level ? Math.floor(4 * Math.random() + 1) : 3 == this.game.level ? Math.floor(4 * Math.random() + 1) : void 0
            },
            getjiasudu: function() {
                return (.1 * Math.random()).toFixed(2)
            },
            beiginsuijixuanzhaun: function() {
                this.game.kaishisuiji = !0,
                this.game.canbeginxuanzhuan = !1,
                this.kaishixuanzhuanshijian = this.game.chuangguantime,
                this.xuanzhuanshijian = this.getxuanzhuanshijian(),
                this.zhengfanzhuan = this.getxuanzhuanfangxiang(),
                this.xuanzhuansudu = this.getchushixuanzhuandushu(),
                this.jiasudu = this.getjiasudu()
            },
            update: function(i) {
                -1 != this.game.chuangguantime && this.game.kaishisuiji && !this.game.canbeginxuanzhuan && (this.kaishixuanzhuanshijian - this.game.chuangguantime == this.xuanzhuanshijian && (this.game.kaishisuiji = !1,
                this.game.canbeginxuanzhuan = !0,
                this.jiasudu = 0,
                this.xuanzhuansudu = 0,
                this.yijingxuanzhuantime = 0,
                this.xuanzhuanshijian = 1,
                this.zhengfanzhuan = 0,
                this.beiginsuijixuanzhaun()));
                0 == this.zhengfanzhuan ? this.node.rotation = this.node.rotation + this.xuanzhuansudu + parseFloat(this.jiasudu) : this.node.rotation = this.node.rotation - this.xuanzhuansudu - parseFloat(this.jiasudu)
            }
        }),
        cc._RF.pop()
    }
    , {}],
    game: [function(i, e, t) {
        "use strict";
        cc._RF.push(e, "bd1dcbl3I1IXrN2BZoGiCo+", "game"),
        cc.Class({
            extends: cc.Component,
            properties: {
                flyCutter: {
                    default: null,
                    type: cc.Prefab
                },
                lizi: {
                    default: null,
                    type: cc.Prefab
                },
                target: {
                    default: null,
                    type: cc.Node
                },
                topLabel: {
                    default: null,
                    type: cc.Label
                },
                bomLabel: {
                    default: null,
                    type: cc.Label
                },
                audio4: {
                    url: cc.AudioClip,
                    default: null
                },
                bgmusic: {
                    url: cc.AudioClip,
                    default: null
                },
                score: 0
            },
            onLoad: function() {
                this.shouyeurl = "/index.php/mobile/game/csg";
                var i = new Object;
                i = this.GetRequest(),
                this.firstNums = i.one,
                this.secondNums = i.two,
     
                this.firstNums ? isNaN(this.firstNums) && (this.firstNums = num1) : this.firstNums = num1,
                this.secondNums ? isNaN(this.secondNums) && (this.secondNums = num2) : this.secondNums = num2,
                this.thirdNums ? isNaN(this.thirdNums) && (this.thirdNums = num3) : this.thirdNums = num3,

                this.yinliang = 1,
                this.bgId = cc.audioEngine.play(this.bgmusic, !0, this.yinliang),
                this.kaishisuiji = !1,
                this.canbeginxuanzhuan = !1,
                this.chuangguantime = 59,
                this.chongxintiaozhantime = 9,
                this.level = 0,
                this.clock = this.node.getChildByName("clock"),
                this.seconds = this.clock.getChildByName("seconds"),
                this.clock.active = !0,
                this.introduceNode = cc.find("Canvas/Introduce"),
                this.guodu1 = this.introduceNode.getChildByName("level1"),
                this.guodu2 = this.introduceNode.getChildByName("level2"),
                this.guodu3 = this.introduceNode.getChildByName("level3"),
                this.overtimesNode = cc.find("Canvas/gameover/whitebg/time"),
                this.gameInit(),
                this.target.getComponent("gameTarget").game = this,
                this.onClickGame(),
                this.isplay = !1,
                this.open = this.node.getChildByName("yinxiao").getChildByName("open"),
                this.close = this.node.getChildByName("yinxiao").getChildByName("close");

            },
            GetRequest: function() {
                var i = location.search
                  , e = new Object;
                if (-1 != i.indexOf("?"))
                    for (var t = i.substr(1).split("&"), n = 0; n < t.length; n++)
                        e[t[n].split("=")[0]] = unescape(t[n].split("=")[1]);
                return e
            },
            createFlyCutter: function() {
                var i = cc.instantiate(this.flyCutter);
                this.node.addChild(i),
                i.getComponent("gameFlyCutter").game = this,
                i.getComponent("gameFlyCutter").target = this.target,
                this.flyCutterAnim = i.getComponent(cc.Animation)
            },
            gameInit: function() {
       
                for (var i = 0; i < this.node.childrenCount; i++)
                    "flyCutter" !== this.node.children[i].name && "lizi" !== this.node.children[i].name || this.node.removeChild(this.node.children[i]);
                this.isplay = !1,
                this.chuangguantime = 59,
                this.chongxintiaozhantime = 9,
                clearInterval(this.chuangguanId),
                clearInterval(this.chongxintiaozhanId),
                this.chuangguanId = null,
                this.kaishisuiji = !1,
                this.canbeginxuanzhuan = !0,
                this.level++,
                this.useFlyCutterNum = 0;
                var e = this.target.getComponent(cc.Sprite)
                  , t = this.node.getChildByName("starts");
                if(1 == this.level){
                    $.ajax({
                        type:'post',
                        url:'/index.php/mobile/game/game_fuck_level',
                        data:{mid: nowMid, oid: nowOid, leval: 1},
                        success:function(data){
                            data = JSON.parse(data);
    
                        }
                    });
                	this.chuangguantime = time1,
                	this.flyCutterAllNum = this.firstNums,
	                t.children[0].children[0].active = !0,
	                e.spriteFrame = this.target.getComponent("gameTarget").sprite_frames[0],
	                this.createFlyCutter(),
	                this.clock.active = !0,
	                this.chuangguanjishi(),
	                this.showGuodu(),
	                this.showFeidaoLie(),
	                this.showBomLabel()
                }else if(2 == this.level){
                    $.ajax({
                        type:'post',
                        url:'/index.php/mobile/game/game_fuck_level',
                        data:{mid: nowMid, oid: nowOid, level: 2},
                        success:function(data){
                            data = JSON.parse(data);
                        }
                    });
                	this.chuangguantime = time2,
                	this.flyCutterAllNum = this.secondNums,
	                t.children[0].children[0].active = !0,
	                t.children[1].children[0].active = !0,
	                e.spriteFrame = this.target.getComponent("gameTarget").sprite_frames[1],
	                this.createFlyCutter(),
	                this.clock.active = !0,
	                this.chuangguanjishi(),
	                this.showGuodu(),
	                this.showFeidaoLie(),
	                this.showBomLabel()
                }else if(3 == this.level){
                    $.ajax({
                        type:'post',
                        url:'/index.php/mobile/game/game_fuck_level',
                        data:{mid: nowMid, oid: nowOid, level: 3},
                        success:function(data){
                            data = JSON.parse(data);
                        }
                    });
                	this.chuangguantime = time3,
                	this.flyCutterAllNum = this.thirdNums,
	                t.children[0].children[0].active = !0,
	                t.children[1].children[0].active = !0,
	                t.children[2].children[0].active = !0,
	                e.spriteFrame = this.target.getComponent("gameTarget").sprite_frames[2],
	                this.createFlyCutter(),
	                this.clock.active = !0,
	                this.chuangguanjishi(),
	                this.showGuodu(),
	                this.showFeidaoLie(),
	                this.showBomLabel()
                }
          
                
            },
            chuangguanjishi: function() {
                var i = this;
                this.chuangguanId = setInterval(function() {
                    i.chuangguantime < 0 ? (clearInterval(i.chuangguanId),
                    i.chuangguanId = null,
                    cc.find("Canvas/gameover").active = !0,
                    i.chongxintiaozhanjishi()) : (i.seconds.getComponent(cc.Label).string = i.chuangguantime,
                    i.chuangguantime -= 1)
                }, 1e3)
            },
            chongxintiaozhanjishi: function() {
            	$.ajax({
            		type:'post',
            		url:'/index.php/mobile/game/game_fail',
            		data:{mid: nowMid, oid: nowOid},
            		success:function(data){
            			data = JSON.parse(data);
            			console.log(data);
            		}
        		});
                var i = this;
                this.chongxintiaozhanId = setInterval(function() {
                    i.chongxintiaozhantime < 0 ? (clearInterval(i.chongxintiaozhanId),
                    i.chongxintiaozhanId = null,
                    location.href = i.shouyeurl) : (i.overtimesNode.getComponent(cc.Label).string = i.chongxintiaozhantime,
                    i.chongxintiaozhantime -= 1)
                }, 1e3)
            },
            showGuodu: function() {
                this.introduceNode.opacity = 255,
                this.introduceNode.active = !0;
                var i = this;
                if(1 == this.level){
                	this.guodu1.active = !0,
	                this.guodu2.active = !1,
	                this.guodu3.active = !1;
                }else if(2 == this.level){
                	this.guodu1.active = !1,
	                this.guodu2.active = !0,
	                this.guodu3.active = !1;
                }else if(3 == this.level){
                	this.guodu1.active = !1,
	                this.guodu2.active = !1,
	                this.guodu3.active = !0;
                }
                
                var e = [];
                e.push(cc.delayTime(2)),
                e.push(cc.callFunc(function() {
                    i.introduceNode.runAction(cc.fadeOut(1))
                }, this.introduceNode)),
                e.push(cc.delayTime(1.5)),
                e.push(cc.callFunc(function() {
                    i.introduceNode.active = !1
                }, this.introduceNode));
                var t = cc.sequence(e);
                this.introduceNode.runAction(t)
            },
            showFeidaoLie: function() {
                if (this.level1Node = this.node.getChildByName("level1"),
                this.level2Node = this.node.getChildByName("level2"),
                this.level3Node = this.node.getChildByName("level3"),
                this.len = this.level1Node.childrenCount,
                1 == this.level) {
                    this.level1Node.active = !0,
                    this.level2Node.active = !1,
                    this.level3Node.active = !1;
                    for (var i = 0; i < this.len; i++) {
                        var e = "kouhong" + i
                          , t = this.level1Node.getChildByName(e);
                        i < this.flyCutterAllNum ? (t.active = !0,
                        t.getChildByName("zidan").active = !0) : t.active = !1
                    }
                } else if (2 == this.level) {
                    this.level1Node.active = !1,
                    this.level2Node.active = !0,
                    this.level3Node.active = !1;
                    for (i = 0; i < this.len; i++) {
                        e = "kouhong" + i,
                        t = this.level2Node.getChildByName(e);
                        i < this.flyCutterAllNum ? (t.active = !0,
                        t.getChildByName("zidan").active = !0) : t.active = !1
                    }
                } else if (3 == this.level){
                	this.level1Node.active = !1,
                    this.level2Node.active = !1,
                    this.level3Node.active = !0;
                    for (i = 0; i < this.len; i++) {
                        e = "kouhong" + i,
                        t = this.level3Node.getChildByName(e);
                        i < this.flyCutterAllNum ? (t.active = !0,
                        t.getChildByName("zidan").active = !0) : t.active = !1
                    }
                }
            },
            onClickGame: function() {
                var i = this;
                this.node.on("touchstart", function(e) {
                    if (!(i.useFlyCutterNum >= i.flyCutterAllNum || null == i.flyCutterAnim.node || i.isplay)) {
                        i.flyCutterAnim.play(),
                        i.isplay = !0,
                        0 != i.yinliang && cc.audioEngine.play(i.audio4, !1, i.yinliang),
                        i.useFlyCutterNum++,
                        i.showBomLabel();
                        var t = i.flyCutterAllNum - i.useFlyCutterNum;
                        if (1 == i.level)
                            for (var n = 0; n < i.len; n++) {
                                var a = "kouhong" + n
                                  , s = i.level1Node.getChildByName(a);
                                n < t ? s.active = !0 : s.getChildByName("zidan").active = !1
                            }
                        else if (2 == i.level)
                            for (n = 0; n < i.len; n++) {
                                a = "kouhong" + n,
                                s = i.level2Node.getChildByName(a);
                                n < t ? s.active = !0 : s.getChildByName("zidan").active = !1
                            }
                        else if (3 == i.level)
                        	for (n = 0; n < i.len; n++) {
                                a = "kouhong" + n,
                                s = i.level3Node.getChildByName(a);
                                n < t ? s.active = !0 : s.getChildByName("zidan").active = !1
                            }
                    }
                })
            },
            showBomLabel: function() {
                this.bomLabel.string = this.useFlyCutterNum + " / " + this.flyCutterAllNum
            },
            showScore: function() {
                this.topLabel.string = this.score
            },
            gameOver: function() {
            	console.log(33);
                cc.find("Canvas/gameover").active = !0
            },
            onBtnClick: function(i) {
                var e = i.target.name;
                "ctbutton" === e || "kaishitiaozhan" === e ? (clearInterval(this.chuangguanId),
                clearInterval(this.chongxintiaozhanId),
                location.href = this.shouyeurl) : "open" === e ? (this.yinliang = 0,
                cc.audioEngine.setVolume(this.bgId, this.yinliang),
                this.open.active = !1,
                this.close.active = !0) : "close" === e && (this.yinliang = 1,
                cc.audioEngine.setVolume(this.bgId, this.yinliang),
                this.open.active = !0,
                this.close.active = !1)
            },
            update: function(i) {}
        }),
        cc._RF.pop()
    }
    , {}],
    index: [function(i, e, t) {
        "use strict";
        cc._RF.push(e, "0a90bMr66JGe6UKOvy5jJg1", "index"),
        cc.Class({
            extends: cc.Component,
            properties: {
                btn: {
                    default: null,
                    type: cc.Node
                },
                share: {
                    default: null,
                    type: cc.Node
                }
            },
            onLoad: function() {
                this.btnAnim = this.btn.getComponent(cc.Animation),
                this.shareGroup()
            },
            start: function() {},
            toGameScene: function() {
                cc.director.loadScene("game")
            },
            shareGroup: function() {}
        }),
        cc._RF.pop()
    }
    , {}]
}, {}, ["game", "gameFlyCutter", "gameTarget", "index"]);
