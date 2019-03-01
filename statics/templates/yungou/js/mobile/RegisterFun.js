$(function() {

    var c = $("#userMobile");

    var ps = $("#txtPassword");

    var a = $("#btnNext");

    var b = $("#isCheck");

    var fg;

    var d = function() {

        var k = 0;

        var h = "";

        var psv = "";

        var q = function(u) {

            var t = /^\d+$/;

            return t.test(u)

        };

        var m = function(u) {

            var t = /^1\d{10}$/;

            return t.test(u)

        };

        var l = {

            txtStr: "请输入您的手机号码",

            ishad: "已被注册，请更换手机号码",

            error: "请输入正确的手机号码",

            many: "验证码请求次数过多，请稍后再试",

            retry: "验证码发送失败，请重试",

            msgerror: "系统短息配置不正确",

            code_no: "请输入验证码",

            code_fal: "验证码输入错误",

            psw: "请输入密码",

            psw_no: "密码长度6~12位",

            ok: "该号码可以注册"

        };

        var f = {

            txtStr: "下一步",

            checkNO: "正在验证手机号",

            sendCode: "正在发送验证码"

        };

        var i = function(t) {

            $.PageDialog.fail(t)

        };

        var n = function() {

            if (!isLoaded || k != 2) {

                return

            }

            var u = h;

            var pass = psv;

            var t = function(v) {

            //alert(v.state);

                if (v.state == 0) {

                    location.replace(Gobal.Webpath+"/mobile/user/mobilecheck/" + u);

                    return

                } else {

                    if (v.state == 2) {

                        i(l.many)

                    } else{

                        i(l.retry)

                    }

                }

                isLoaded = true;

                a.html(f.txtStr).removeClass("grayBtn").bind("click", g)

            };

            isLoaded = false;

            a.html(f.sendCode).addClass("grayBtn").unbind("click");

            GetJPData(Gobal.Webpath, "ajax", "userMobile/"+u+"/"+base64encode(utf16to8(pass)), t)

        };

        var o = function() {

            if (!isLoaded) {

                return

            }

            var u = h;

            var pass = psv;

            var t = function(v) {

             //alert(v.state);

                if (u == h) {

                    if (v.state == 1) {

                        k = 1;

                        i(l.ishad)

                    }else if(v.state == 2){

                       k = 1;

                       i(l.msgerror)

                    } else {

                        if (v.state == 0) {

                            k = 2;

                            isLoaded = true;

                            n()

                        } else {

                            k = 0

                        }

                    }

                }

            };

            GetJPData(Gobal.Webpath, "ajax", "checkname/"+ u, t)

        };

        var g = function() {

            h = c.val();

            psv = ps.val();
    
            // checkcode = $("#yanzhengs").val();
            // if(checkcode != ''){
            //     $.ajax({
            //         data: {'param':checkcode},
            //         type: 'POST',
            //         url: '/index.php/member/user/codeCheck',
            //         async: false,
            //         success:function(data){
            //             var fg2 = JSON.parse(data);
            //             fg = fg2.status;
            //         }
            //     })
            // }
        
            if (j) {

                return

            }

            if (h == "" || h == l.txtStr) {

                i(l.txtStr)

            }else if(psv == ''){
                i(l.psw);
            }else if(psv.length < 6 || psv.length > 12){
                i(l.psw_no);
            }else if(fg == 'x'){
                i(l.code_fal);
            }else {

                if ((h.length < 11 || h.length >= 11) && !m(h)) {

                    i(l.error)

                } else {

                    if (m(h)) {
                         var validata = captchaObj.getValidate();
                        if (!validata) {
                            captchaObj.verify();
                            return;
                        }else{
                            o();
                        }

                    }

                }

            }

        };

        var r = "";

        var s = function() {

            if (r != c.val()) {

                if (q(c.val()) || c.val() == "") {

                    r = c.val()

                } else {

                    c.val(r)

                }

            }

            if (checkSwitch) {

                setTimeout(s, 200)

            }

        };

        c.bind("focus",

        function() {

            $(this).attr("style", "color:#666666");

            checkSwitch = true;

            s()

        }).bind("blur",

        function() {

            checkSwitch = false

        });

        var j = false;

        var p = function() {

            if (!j) {

                b.addClass("noCheck");

                a.addClass("grayBtn").unbind("click")

            } else {

                b.removeClass("noCheck");

                var t = c.val();

                a.removeClass("grayBtn").bind("click", g)

            }

            j = !j

        };
        var xx = b.attr('class');
        if(xx != 'noCheck'){
            a.bind("click", g);
        }

        b.bind("click", p);

        isLoaded = true;

        var handlerEmbed = function (captchaObj) {

            window.captchaObj=captchaObj;
            captchaObj.onSuccess(function () {
                var validata = captchaObj.getValidate();
                if (!validata) {
                    captchaObj.reset();
                    $.PageDialog.fail("安全验证失败，请重新验证")
                } else {
                    o();
                }
            });
            
            captchaObj.appendTo('#btnNext');
        };

        $.ajax({
            // 获取id，challenge，success（是否启用failback）
            url: "/geetest/web/StartCaptchaServlet.php?t=" + (new Date()).getTime(), // 加随机数防止缓存
            type: "get",
            dataType: "json",
            success: function (data) {
                // 使用initGeetest接口
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: "bind", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注

                    // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
                }, handlerEmbed);
            }
        });

    };

    Base.getScript(Gobal.Skin + "/js/mobile/pageDialog.js", d);



    var base64encodechars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

    var base64decodechars = new Array(

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,

    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,

    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,

    -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,

    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,

    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,

    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

    function base64encode(str) {

        var out, i, len;

        var c1, c2, c3;

        len = str.length;

        i = 0;

        out = "";

        while (i < len) {

            c1 = str.charCodeAt(i++) & 0xff;

            if (i == len) {

                out += base64encodechars.charAt(c1 >> 2);

                out += base64encodechars.charAt((c1 & 0x3) << 4);

                out += "==";

                break;

            }

            c2 = str.charCodeAt(i++);

            if (i == len) {

                out += base64encodechars.charAt(c1 >> 2);

                out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));

                out += base64encodechars.charAt((c2 & 0xf) << 2);

                out += "=";

                break;

            }

            c3 = str.charCodeAt(i++);

            out += base64encodechars.charAt(c1 >> 2);

            out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));

            out += base64encodechars.charAt(((c2 & 0xf) << 2) | ((c3 & 0xc0) >> 6));

            out += base64encodechars.charAt(c3 & 0x3f);

        }

        return out;

    }

    function base64decode(str) {

        var c1, c2, c3, c4;

        var i, len, out;

        len = str.length;

        i = 0;

        out = "";

        while (i < len) {



            do {

                c1 = base64decodechars[str.charCodeAt(i++) & 0xff];

            } while (i < len && c1 == -1);

            if (c1 == -1)

                break;



            do {

                c2 = base64decodechars[str.charCodeAt(i++) & 0xff];

            } while (i < len && c2 == -1);

            if (c2 == -1)

                break;

            out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));



            do {

                c3 = str.charCodeAt(i++) & 0xff;

                if (c3 == 61)

                    return out;

                c3 = base64decodechars[c3];

            } while (i < len && c3 == -1);

            if (c3 == -1)

                break;

            out += String.fromCharCode(((c2 & 0xf) << 4) | ((c3 & 0x3c) >> 2));



            do {

                c4 = str.charCodeAt(i++) & 0xff;

                if (c4 == 61)

                    return out;

                c4 = base64decodechars[c4];

            } while (i < len && c4 == -1);

            if (c4 == -1)

                break;

            out += String.fromCharCode(((c3 & 0x03) << 6) | c4);

        }

        return out;

    }

    function utf16to8(str) {

        var out, i, len, c;

        out = "";

        len = str.length;

        for (i = 0; i < len; i++) {

            c = str.charCodeAt(i);

            if ((c >= 0x0001) && (c <= 0x007f)) {

                out += str.charAt(i);

            } else if (c > 0x07ff) {

                out += String.fromCharCode(0xe0 | ((c >> 12) & 0x0f));

                out += String.fromCharCode(0x80 | ((c >> 6) & 0x3f));

                out += String.fromCharCode(0x80 | ((c >> 0) & 0x3f));

            } else {

                out += String.fromCharCode(0xc0 | ((c >> 6) & 0x1f));



                out += String.fromCharCode(0x80 | ((c >> 0) & 0x3f));

            }

        }

        return out;

    }

    function utf8to16(str) {

        var out, i, len, c;

        var char2, char3;

        out = "";

        len = str.length;

        i = 0;

        while (i < len) {

            c = str.charCodeAt(i++);

            switch (c >> 4) {

                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:

                    // 0xxxxxxx

                    out += str.charAt(i - 1);

                    break;

                case 12: case 13:

                    // 110x xxxx   10xx xxxx

                    char2 = str.charCodeAt(i++);

                    out += String.fromCharCode(((c & 0x1f) << 6) | (char2 & 0x3f));

                    break;

                case 14:

                    // 1110 xxxx  10xx xxxx  10xx xxxx

                    char2 = str.charCodeAt(i++);

                    char3 = str.charCodeAt(i++);

                    out += String.fromCharCode(((c & 0x0f) << 12) |

                       ((char2 & 0x3f) << 6) |

                       ((char3 & 0x3f) << 0));

                    break;

            }

        }

        return out;

    }

    //base64 加密base64encode(utf16to8(value));

    //base64 解密utf8to16(base64decode(value));

});