$(function() {

    var a = false;

    var b = function() {

        var x = parseInt($("#hidShopMoney").val());

        var ffdk = parseInt($("#pointsbl").val());

        var d = $("#hidBalance").val();

        var t = parseInt($("#hidPoints").val());

        var c = $("#spPoints");

        var p = $("#spBalance");

        var h = $("#btnPay");

        var m = $("#bankList");

        var shopnum = parseInt($("#shopnum").val());

        var r = "支付方式";


        var g = ffdk > x ? x: ffdk;

        var w = 0;

        var e = 0;

		var checkpay='money';//选择支付方式

		var banktype='nobank';

        var xx = $('#bankList').css('display');

        var k = function(y) {
            e = y;

            if (y > 0) {
                $('<a style="height: 45px;line-height: 45px;" id="btnPay" href="javascript:;" class="orgBtn">余额不足,微信支付剩余:<em class="orange">' + e + ".00</em>元</a>").replaceAll("#btnPay");

                checkpay='money';
                banktype='nobank';

            }

        };

        var y = parseInt(d);
        // if(y < x && x >= 20){
        //     $('<a style="height: 45px;line-height: 45px;" id="btnPay" href="javascript:;" class="orgBtn">微信支付</a>').replaceAll("#btnPay");
        //     $('#spBalance').attr('class','z-pay-ment');
        //     $('#spBalance').attr('sel','0');
        //     $('#spBalance').parent().addClass("z-pay-grayC");
        //     $('#spBalance').parent().css('color','#ccc');
        //     $("#bankList li:eq(1)").children().attr('class','z-pay-mentsel');
        //     $("#bankList li:eq(1)").children().attr('sel','1');
        // }

        $("#bankList li").click(function(){
            var index = $(this).index();
            var z = $(this).children().attr('class');
            if(index != 0){
                if(z == 'z-pay-ment'){
                    if(x < 20){
                        $('<a href="/index.php/mobile/mobile/glist" style="height: 45px;line-height: 45px;" id="btnPay" class="orgBtn noMoney">满20使用微信支付,前往选购</a>').replaceAll("#btnPay");
                    }else{
                        k(x-parseInt(d));
                        //$('<a style="height: 45px;line-height: 45px;" id="btnPay" href="javascript:;" class="orgBtn">微信支付</a>').replaceAll("#btnPay");
                    }
                    $('#spBalance').attr('class','z-pay-ment');
                    $('#spBalance').attr('sel','0');
                    $('#spBalance').parent().addClass("z-pay-grayC");
                    $('#spBalance').parent().css('color','#ccc');
                    $(this).children().attr('class','z-pay-mentsel');
                    $(this).children().attr('sel','1');
                    
                }else{
                    $(this).children().attr('class','z-pay-ment');
                    $(this).children().attr('sel','0');
                    if(parseInt(d) < x ){
                        $('<a href="/index.php/mobile/home/recharge" style="height: 45px;line-height: 45px;" id="btnPay" class="orgBtn noMoney">余额不足,请前往充值</a>').replaceAll("#btnPay");
                    }else{
                        $('<a style="height: 45px;line-height: 45px;" id="btnPay" href="javascript:;" class="orgBtn">确认支付</a>').replaceAll("#btnPay");
                    }
                }
            }

        })

        if (g < x) {

            var j = parseInt(d);

            if (j > 0) {

                var i = x - g;

                if (j >= i) {

                    w = i

                } else {

                    w = j;

                    e = i - j

                }

            } else {

                e = x - g

            }

        }


        var f = function(y) {



            w = y;

            if (y > 0) {

                p.parent().removeClass("z-pay-grayC");
                p.parent().css('color','#22AAFF');
                p.attr("sel", "1").attr("class", "z-pay-mentsel").next("span").html('余额支付<em class="orange">' + y + ".00</em>元（账户余额：" + d + " 元）")

				checkpay='money';

				banktype='nobank';

                $("#bankList ul li i").attr("class","z-pay-ment").attr('sel','0');
                $('<a style="height: 45px;line-height: 45px;" id="btnPay" href="javascript:;" class="orgBtn">确认支付</a>').replaceAll("#btnPay");

            } else {
                p.parent().addClass("z-pay-grayC");
                p.parent().css('color','#CCC');
                p.attr("sel", "0").attr("class", "z-pay-ment").next("span").html('余额支付<em class="orange">0.00</em>元（账户余额：' + d + " 元）")

            }

        };


        if (ffdk > 0) {

            c.parent().click(function() {

                if (c.attr("sel") == 1) {

                    q(0);

                    n(x)

                } else {

                    var y = ffdk;

                    if (y > 0) {

                        q(y >= x ? x: y);

                        n(y >= x ? 0 : x - y)

                    } else {

                        n(x)

                    }

                }

            });

            var n = function(z) {

                if (p.attr("sel") == 1) {

                    var y = parseInt(d) - z;

                    if (y > 0) {

                        f(z)

                    } else {

                        f(parseInt(d));

                    }

                }

            }

        }

        if (parseInt(d) > 0) {
            
            p.parent().click(function() {

                if (p.attr("sel") == 1) {

                    f(0);

                } else {

                    var y = parseInt(d);

                    if (y > 0) {

                        f(y >= x ? x: y);

                        k(y >= x ? 0 : x - y)

                    } else {

                        k(x)

                    }

                }

            });

            

            $("#bankList li:eq(1)").click(function(){
                console.log(2);
            })

        }

        var o = false;

        var v = 1;

        var s = $("#btnPay");

        var u = function() {

        	var payment = $('.z-bank-Roundsel').length;

        	if (payment) {

        		banktype = $('.z-bank-Roundsel').parent('li').attr('urm');
        	}



			var submitcode = Path.submitcode

			if(!this.cc){

				this.cc = 1;

			}else{

				alert("不可以重复提交订单!")

				return false;

			}



            if(checkpay=='nosel' && banktype=='nobank'){

			  alert("请选择一种支付方式！");

			  if(this.cc){

				this.cc = false;

			  }

			  return

			}

            if (!a) {

                return

            }


            if (w + g >= x) {
                var wx = $('#bankList ul li i').attr('sel');

                a = false;

                s.unbind("click").addClass("dis");

			    if (shopnum != -1) {

					if (shopnum == 0) {
                        if(wx == 1){

                            location.replace(Gobal.Webpath+"/api/wxorder55/init/")
                        }else{

                            location.replace(Gobal.Webpath+"/mobile/cart/paysubmit/"+checkpay+"/"+banktype+"/"+x+"/"+t+"/"+submitcode)
                        }
	
					} else {

						if (shopnum == 1) {

							alert("亲，您的购物车中没有商品哦，去选购一些吧。");

							location.replace(Gobal.Webpath+"/mobile/cart/cartlist")

						} else {

							if (shopnum == 10) {

								location.reload()

							}

						}

					}

				}

				//s.bind("click", u).removeClass("dis");
                $(".g-Total-bt").on("click","#btnPay", u).removeClass("dis");
				a = true

            } else {

                if (e > 0) {

                    if (v == 1 || v == 2 || v == 3) {

                           location.href = Gobal.Webpath+"/mobile/cart/paysubmit/"+checkpay+"/"+banktype+"/"+x+"/"+t

                    }

                }

            }

        };
        if(xx == 'none'){
            qf.parent().trigger('click');
        }
        //s.bind("click", u);
        $(".g-Total-bt").on("click","#btnPay", u);

        a = true

    };

    Base.getScript(Gobal.Skin + "/js/mobile/pageDialog.js", b)

});