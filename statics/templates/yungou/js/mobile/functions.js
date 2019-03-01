/**
 * Created by hbl on 2018/9/7.
 */
var webPath
$(function() {
     webPath = Path.Webpath;
})
var locationTimoOut = function(time,url){
    if(time > 0){
        time--
        setTimeout(function () {
            locationTimoOut(time,url)
        },1000)
    }else{
        if(url){
            window.location.href = url;
        }else{
            window.location.reload();
        }
    }
}
function toast(msg){
    $.PageDialog.fail(msg);
}
/**
 * 判断是否微信浏览器
 */
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }
}
//判断是否webApp
function isWebView(){
    console.log(navigator.userAgent);
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/Linux/i)){
        if(ua.match(/Version/i) && !ua.match(/MicroMessenger/i) && ua.match(/html5plus/i)){
            return true;
        }
    }else if(ua.match(/iPhone/i)){
        if(!ua.match(/Version/i) && !ua.match(/MicroMessenger/i) && !ua.match(/UCBrowser/i) && !ua.match(/qbwebviewtype/i) && !ua.match(/mqqbrowser/i) && !ua.match(/sogoumse/i) && !ua.match(/baidu/i) && !ua.match(/XiaoMi/i) && !ua.match(/360/i)){
            return true;
        }
    }
    return false;
}

/**
 * 获取url参数
 * @param {Object} name
 */
function getQueryString(name) {
    var reg = new RegExp("(^|&)"+name+"=([^&]*)(&|$)");
    var result = window.location.search.substr(1).match(reg);
    return result?decodeURIComponent(result[2]):null;
    return null;
}



/**
 * 配置微信jssdk
 * @param {Object} config
 * @param {Object} apilist
 * @param {Object} debug
 */
function configWxJSSDK(config,apilist,debug){
    wx.config({
        debug: debug,
        appId: config.appId,
        timestamp:config.timeStamp ,
        nonceStr: config.nonceStr,
        signature: config.signature,
        jsApiList: apilist // 需要使用的JS接口列表
    })
    wx.error(function(res){
        //toast("微信config失败")
    })
}



function getWxOpenid(){
    var wxopenid=localStorage.getItem('wxopenid');
    var key=localStorage.getItem('key');
    if (isWeiXin()){
        var access_code=getQueryString('code');
        if (!wxopenid){
            if (access_code==null)
            {
                var fromurl=location.href + '/';
                $.ajax({
                    url:webPath + "/?/app/ajax/get_wxappid/",
                    type:'GET',
                    dataType:'json',
                    success:function(data){
                        var url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='+data.data.appid+'&redirect_uri='+encodeURIComponent(fromurl)+'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect&connect_redirect=1#wechat_redirect';
                        console.log(url)
                        console.log((fromurl))
                        location.href=url;
                    }
                })
            }
            else
            {
                $.ajax({
                    url:webPath + "/?/app/ajax/getopenid/"+access_code,
                    type:'GET',
                    dataType:'json',
                    success:function(data){
                        localStorage.setItem('key',data.data.openid);
                    }
                })
            }
        }
    }
}


var chooseWXPay = function(data,successUrl,failUrl){
    if(data.error_code == 0){
        jsApiParameters =  JSON.parse(data.data.payinfo)   //后台返回支付参数
        wx.ready(function() {      						//调用jssdk支付接口
            wx.chooseWXPay({
                appId: jsApiParameters.appId,
                timestamp: jsApiParameters.timeStamp,
                nonceStr: jsApiParameters.nonceStr,
                package: jsApiParameters.package,
                signType: jsApiParameters.signType,
                paySign: jsApiParameters.paySign,
                success: function(res) {
                    if (res.errMsg == "chooseWXPay:ok") {
                        toast('支付成功')
                        if(successUrl){

                        }
                    } else {
                        toast(res.errMsg)
                    }
                },
                cancel: function(res) {
                    toast('支付取消')
                }
            });
        })

    }
}
