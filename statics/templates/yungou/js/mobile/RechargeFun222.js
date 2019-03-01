$(function() {
    var d = Path.PayMinMoney;
    var g = false;
    var a = null;
    var f = null;
    var b = null;
    var c = 1;
	var banktype='nobank';
	var bankName = '';
    var enable=false;
    var money = 0;
    money = $(".z-sel").parent().attr('money');
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
                        money = l(m)
                        j = m;
                        f.html(bankName+'充值<em class="orange">' + money + "</em>元")
                    }
                }
            } else {
                j = "";
                a.focus();
                f.html(bankName+'充值<em class="orange">0.00</em>元')
            }
        };
        $("#ulOption > li").each(function(m) {
            var n = $(this);
            if (m < 9) {
                n.click(function() {
                    g = false;
                    d = n.attr("money");
                    n.children("a").addClass("z-sel");
                    n.siblings().children().removeClass("z-sel").removeClass("z-initsel");
                    money =  n.attr("money")
                    f.html(bankName+'充值<em class="orange">' + money + ".00</em>元")
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
                    if(n.is('[urm]')) {
                        $("#ulOption li a").removeClass('z-sel');
                        $("#ulOption li b").removeClass('z-initsel');
                        $("#ulOption li:eq(0) a").addClass('z-sel');
                        money = $(".z-sel").parent().attr('money');
                        c = m;
                        banktype = n.attr('urm');
                        enable = n.is('[enable]');
                        n.children("i").attr("class", "z-bank-Roundsel");
                        bankName = $(this).attr('bankName')
                        f.html(bankName+'充值<em class="orange">' + money + ".00</em>元")
                        n.siblings().children("i").attr("class", "z-bank-Round")
                        if(n.attr('urm') == '20'){
                            $('#ulOption').children("li:last-child").hide();
                            $('#ulOption').children("li:nth-child(9)").show();
                        }else{
                            $('#ulOption').children("li:nth-child(9)").hide();
                            $('#ulOption').children("li:last-child").show();
                        }
                        
                    }
                })
            }
        });

        $("#btnSubmit").click(function() {
/*            var pw = $('#card_pwd').val();
            if(pw != '') {
                $('form.card_form').submit();
                return;
            }*/
            if (money == "" || parseInt(money) == 0) {
                k("请输入充值金额")
            } else if(banktype == "nobank") {
                k("请选择一种支付方式")
            } else {
                var m = /^[1-9]\d*\.?\d{0,2}$/;
                if (m.test(money)) {
                    if(parseInt(d) < Path.PayMinMoney) {
                        k("充值金额不能少于" + Path.PayMinMoney + "元");
                    }
                    else if (c >= 1 ) {
                        //if(true === enable)
                        //location.href = Path.Webpath+"/?/app/cart/addmoney/" + money +"/"+banktype
                        addmoney(banktype)
                    } 
                } else {
                    k("充值金额输入有误")
                }
            }
        });

        var addmoney = function(payid){
            payid = parseInt(payid);
            if(money < 20 && (payid == 23 || payid == 24 || payid == 25 || payid == 9 | payid == 99) ){
                toast('该支付充值最小金额20元')
                return false;
            }
            //var money = $('.recharge-value').html()
            var addmoneyCallback = '';
            switch(payid){
                case 9:
                    addmoneyCallback = wxPayAddmoneyCallback     //微信
                    break;
                case 99:
                    addmoneyCallback = wxPayAddmoneyCallback1     //微信2
                    break;
                case 20:
                    addmoneyCallback = aliWapAddmoneyCallback    //支付宝WAP
                    break;
                case 19:
                    addmoneyCallback = tonglianAddmoneyCallback  //通联网银
                    break;
                case 21:
                case 22:
                    addmoneyCallback = saomaAddmoneyCallback  //扫码
                    break;
                case 23:
                case 24:
                case 25:
                case 26:
                    addmoneyCallback = cpnpAddmoneyCallback  //CPNP支付
                    break;
            }
            console.log(addmoneyCallback)
            addmoneyCallback(money);
            //ajaxRequest(API.ADDMONEY+money+'/'+payid+'/', {}, 'GET', 'JSON', addmoneyCallback)
            // $.ajax({
            //     url:Path.Webpath+"/?/app/cart/addmoney/" + money +"/"+payid,
            //     type:'get',
            //     dataType:'json',
            //     success:function(data){
            //         if(data.error_code == '0'){
            //             console.log(data.data)
            //             addmoneyCallback(data)
            //         }else{
            //             $.PageDialog.fail(data.error_msg)
            //         }
            //     }

            // })

        }
        var wxPayAddmoneyCallback1 = function(data){
          	//window.location.href = '/api/cnmobi/gopay?total='+data;
          	window.location.href = '/index.php/mobile/wepay/gopay/'+data;
        }
        var wxPayAddmoneyCallback = function(data){
            window.location.href = '/index.php/mobile/unionpay/gopay/'+data;
          	//window.location.href = '/index.php/mobile/cart/addmoney1/'+data + '/18';
            
            // if(data.error_code == 0){
            //     jsApiParameters =  JSON.parse(data.data.payinfo)   //后台返回支付参数
            //     wx.ready(function() {      						  //调用jssdk支付接口
            //         wx.chooseWXPay({
            //             appId: jsApiParameters.appId,
            //             timestamp: jsApiParameters.timeStamp,
            //             nonceStr: jsApiParameters.nonceStr,
            //             package: jsApiParameters.package,
            //             signType: jsApiParameters.signType,
            //             paySign: jsApiParameters.paySign,
            //             success: function(res) {
            //                 if (res.errMsg == "chooseWXPay:ok") {
            //                     $.PageDialog.fail('支付完成')
            //                     locationTimoOut(2,Path.Webpath + '/?/app/cart/rechargesuccess/');
            //                 } else {
            //                     toast(res.errMsg)
            //                 }
            //             },
            //             cancel: function(res) {
            //                 $.PageDialog.fail('支付取消')
            //             }
            //         });
            //     })
            // }
        }
//支付宝跳转支付
        var aliWapAddmoneyCallback = function(data){
            window.location.href = '/index.php/mobile/alipay/gopay/'+data;
            // if(data.error_code == 0){
            //     localStorage.setItem('paySubing',1);
            //     if(isWeiXin()){
            //         var wordArray = CryptoJS.enc.Utf8.parse(data.data.pay_form);
            //         var payinfo = CryptoJS.enc.Base64.stringify(wordArray)
            //         var mobile = data.data.user.mobile
            //         var user = data.data.user.user
            //         //PAGE.toPage('pay_location','api_url='+payinfo+'&mobile='+mobile+'&user='+user)
            //         //var parsedWordArray = CryptoJS.enc.Base64.parse(payinfo);
            //         //var depayinfo = parsedWordArray.toString(CryptoJS.enc.Utf8);
            //     }else{
            //         $("body").append(data.data.pay_form);
            //     }

            // }
            // layer.closeAll()
        }


        $("#ulBankList").find("li[urm]:first").click();
    };
    Base.getScript("/statics/templates/yungou/js/mobile/pageDialog.js", e)
});

var cpnpAddmoneyCallback = function(data){
    if(data.data.message){
        toast(data.data.message)
    }else{
        var mobile = data.data.user.uid
        var user = data.data.user.user
        if(data.data.type == 'alipay' && isWeiXin()){
            //PAGE.toPage('pay_location','api_url='+data.data.pay_url+'&mobile='+mobile+'&user='+user+'&server=cpnp&type=recharge&code='+data.data.code)
            var url = '/?/app/ajax/pay_location/&api_url='+data.data.pay_url+'&mobile='+mobile+'&user='+user+'&server=cpnp&type=recharge&code='+data.data.code
            window.location.href= Path.Webpath + url

        }else if(data.data.pay_qrcode != '' && data.data.type == 'wxpay'){
            //var api_url = 'api_url='+data.data.pay_qrcode+'&mobile='+mobile+'&user='+user+'&server=cpnp&type=recharge&code='+data.data.code+'&pay_url='+data.data.pay_url
            var uri = 'mobile='+mobile+'&user='+user+'&server=cpnp&type=recharge&code='+data.data.code+'&pay_url='+data.data.pay_url+'&pay_qrcode='+data.data.pay_qrcode+'&price='+data.data.price;
            //  PAGE.toPage('cpnp_wxpay_location','mobile='+mobile+'&user='+user+'&server=cpnp&type=recharge&code='+data.data.code+'&pay_url='+data.data.pay_url+'&qr_code='+data.data.pay_qrcode+'&price='+data.data.price)
            window.location.href= Path.Webpath + '/?/app/ajax/cpnp_wxpay_location/&'+uri
        }else{
            window.location.href = data.data.pay_url;
        }

    }
}

var tonglianAddmoneyCallback = function(data){
    if(data.error_code == 0){
        //alert(data.data.pay_form);
        localStorage.setItem('paySubing',1);
        $("body").append(data.data.pay_form);

    }
    //window.location.reload()

}

var saomaAddmoneyCallback = function(data){
    if(data.error_code == 0){
        //alert(data.data.pay_form);
        localStorage.setItem('paySubing',1);
        if(isWeiXin() && data.data.user){
            var wordArray = CryptoJS.enc.Utf8.parse(data.data.pay_form);
            var payinfo = CryptoJS.enc.Base64.stringify(wordArray)
            console.log(data);
            var mobile = data.data.user.uid
            var user = data.data.user.user
            //PAGE.toPage('pay_location','api_url='+payinfo+'&mobile='+mobile+'&user='+user+'&type=recharge')
            //var uri = 'api_url='+payinfo+'&mobile='+mobile+'&user='+user+'&type=recharge&code='+data.data.code+'&server=1';
            //window.location.href= Path.Webpath + '/?/app/ajax/pay_location/&'+uri
            //var parsedWordArray = CryptoJS.enc.Base64.parse(payinfo);
            //var depayinfo = parsedWordArray.toString(CryptoJS.enc.Utf8);
            $("body").append(data.data.pay_form);
        }else{
            $("body").append(data.data.pay_form);
        }
    }
    //window.location.reload()
    layer.closeAll()
}