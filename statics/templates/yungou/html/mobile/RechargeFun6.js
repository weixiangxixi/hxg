$(function() {

    var d = 10;

    var g = false;

    var a = null;

    var f = null;

    var b = null;

    var c = 1;

	var banktype='wxpay_wap';

    var e = function() {

        var k = function(p, o, n, m) {

            $.PageDialog.fail(p, o, n, m)

        };

        function l(m) {

            m = Math.round(m * 1000) / 1000;

            m = Math.round(m * 100) / 100;

            if (/^\d+$/.test(m)) {

                return m + ".00"

            }

            if (/^\d+\.\d$/.test(m)) {

                return m + "0"

            }

            return m

        }

        var h = /^[1-9]{1}\d*$/;

        var j = "";

        var i = function() {

            var m = a.val();

            if (m != "") {

                if (j != m) {

                    if (!h.test(m)) {

                        a.val(j).focus()

                    } else {

                        j = m;

                        f.html('选择平台充值<em class="orange">' + l(m) + "</em>元")

                    }

                }

            } else {

                j = "";

                a.focus();

                f.html('选择平台充值<em class="orange">0.00</em>元')

            }

        };

        $("#ulOption > li").each(function(m) {

            var n = $(this);

            if (m < 11) {

                n.click(function() {

                    g = false;

                    d = n.attr("money");

                    n.children("a").addClass("z-sel");

                    n.siblings().children().removeClass("z-sel").removeClass("z-initsel");

                    f.html('充值<em class="orange">' + n.attr("money") + ".00</em>元")

                })

            } else {

                a = n.find("input");

                a.focus(function() {

                    g = true;

                    if (a.val() == "输入金额") {

                        a.val("")

                    }

                    a.parent().addClass("z-initsel").parent().siblings().children().removeClass("z-sel");

                    if (b == null) {

                        b = setInterval(i, 200)

                    }

                }).blur(function() {

                    clearInterval(b);

                    b = null

                })

            }

        });

        $("#ulBankList > li").each(function(m) {

            var n = $(this);

			if (m == 0) {

                f = n

            } else {

                n.click(function() {

                    c = m;

					banktype=n.attr('urm');

                    n.children("i").attr("class", "z-bank-Roundsel");

                    n.siblings().children("i").attr("class", "z-bank-Round");

                })

            }

        });

        $("#btnSubmit").click(function() {

            var aaaaaa = $('#ulOption li:eq(5) b').attr("class");
            
            d = g ? a.val() : d;

            if (d == "" || parseInt(d) == 0) {

                k("请输入充值金额")

            }else if($('.z-init').val()< 10 && aaaaaa == 'z-initsel'){
                k('输入的金额不能小于10元')
                //k(aaaaaa);
            } else {

                var m = /^[1-9]\d*\.?\d{0,2}$/;

                if (m.test(d)) {

                    if (c == 1 || c==2 ||c==3) {

                        //location.href = Gobal.Webpath+"/mobile/home/wxpay_locat/" + d+"/"
                        //location.href = 'http://baidu.com';
                        $("#mask").css("height",$(document).height());     
                        $("#mask").css("width",$(document).width());     
                        $("#mask").show();  
                        setTimeout(function () {
                            //code ..

                                $("#mask").hide();   
                                $.post("/index.php/mobile/home/wxpay_locat1",{ money : d },function(result){
                                    //alert(result);
                                    //var result = '/statics/templates/yungou/images/mobile/money_qrcode/10.jpg';
                                    //$("#mask1").css("height",$(document).height());     
                                    //$("#mask1").css("width",$(document).width());     
                                    //$("#mask1").css("background","url(" + result + ")  top center");
                                    $("#mask1").css("background-size","cover");
                                    $("#mask1").show(); 
                                    $("#qrcodeId").attr('src',result); 
                                });

                            //code ..
                        }, 1000);
                    }

                } else {

                    k("充值金额输入有误")

                }

            }

        });



		$("#ulBankList>li:eq(1)").click();

    };

    Base.getScript(Gobal.Skin + "/js/mobile/pageDialog.js", e)

});