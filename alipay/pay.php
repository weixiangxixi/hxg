<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */

header("Content-type: text/html; charset=utf-8");


require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'aop/AopClient.php';
$private_path = dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'aop/secret/rsa_private_key.pem';
$content = array();    
$content['subject'] = "商品名";    
$content['out_trade_no'] = "70501111111S001111119";    
$content['seller_id'] = '2088102147948060';    
$content['total_amount'] = "9.00";    
$content['product_code'] = "QUICK_MSECURITY_PAY";//销售产品码,固定值    
$con = json_encode($content);//$content是biz_content的值,将之转化成json字符串


$Client = new AopClient();//实例化支付宝sdk里面的AopClient类,下单时需要的操作,都在这个类里面

$param['app_id'] = '2016092300580523';    

$param['method'] = 'alipay.trade.app.pay';//接口名称，固定值

$param['charset'] = 'utf-8';//请求使用的编码格式

$param['sign_type'] = 'RSA2';//商户生成签名字符串所使用的签名算法类型

$param['version'] = '1.0';//调用的接口版本，固定为：1.0

$param['notify_url'] = '';    

$param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式，即前面一步得到的

$param['timestamp'] = "2019-02-18 14:35:02";//发送请求的时间
 

$paramStr = $Client->getSignContent($param);//组装请求签名参数

$sign = $Client->alonersaSign($paramStr, $private_path, 'RSA2', true);//生成签名

$param['sign'] = $sign;   

//$str = $Client->getSignContentUrlencode($param);//最终请求参数
$str = $sign;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>确认订单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"
    />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <style>
        body {
            margin: 0
        }

        a:active,
        a:hover {
            outline: 0
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            color: inherit;
            font: inherit;
            margin: 0
        }

        button,
        html input[type=button],
        input[type=reset],
        input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        td,
        th {
            padding: 0
        }

        img {
            vertical-align: middle;
            border: 0
        }

        @-ms-viewport {
            width: device-width
        }

        html {
            font-size: 312.5%;
            -webkit-tap-highlight-color: transparent;
            height: 100%;
            min-width: 320px;
            overflow-x: hidden
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: .28em;
            line-height: 1;
            color: #333;
            background-color: #F0EFED
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-weight: 500;
            line-height: 1.2;
            line-height: 1.1
        }

        h1 small,
        h1 .small,
        h2 small,
        h2 .small,
        h3 small,
        h3 .small,
        h4 small,
        h4 .small,
        h5 small,
        h5 .small,
        h6 small,
        h6 .small,
        .h1 small,
        .h1 .small,
        .h2 small,
        .h2 .small,
        .h3 small,
        .h3 .small,
        .h4 small,
        .h4 .small,
        .h5 small,
        .h5 .small,
        .h6 small,
        .h6 .small {
            font-weight: 400;
            line-height: 1
        }

        h1,
        .h1,
        h2,
        .h2,
        h3,
        .h3 {
            margin-top: .28rem;
            margin-bottom: .14rem
        }

        h1 small,
        h1 .small,
        .h1 small,
        .h1 .small,
        h2 small,
        h2 .small,
        .h2 small,
        .h2 .small,
        h3 small,
        h3 .small,
        .h3 small,
        .h3 .small {
            font-size: 65%
        }

        h4,
        .h4,
        h5,
        .h5,
        h6,
        .h6 {
            margin-top: .14rem;
            margin-bottom: .14rem
        }

        h4 small,
        h4 .small,
        .h4 small,
        .h4 .small,
        h5 small,
        h5 .small,
        .h5 small,
        .h5 .small,
        h6 small,
        h6 .small,
        .h6 small,
        .h6 .small {
            font-size: 75%
        }

        h1,
        .h1 {
            font-size: .364rem
        }

        h2,
        .h2 {
            font-size: .2996rem
        }

        h3,
        .h3 {
            font-size: .238rem
        }

        h4,
        .h4 {
            font-size: .175rem
        }

        h5,
        .h5 {
            font-size: .14rem
        }

        h6,
        .h6 {
            font-size: .119rem
        }

        h6 {
            margin-top: 0;
            margin-bottom: 0
        }

        input,
        button,
        select,
        textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit
        }

        a {
            color: #2BB2A3;
            text-decoration: none;
            outline: 0
        }

        a:focus {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px
        }

        ul {
            margin: 0;
            padding: 0;
            list-style-type: none
        }

        hr {
            margin-top: .28rem;
            margin-bottom: .28rem;
            border: 0;
            border-top: 1px solid #DDD8CE
        }

        p,
        h6 {
            line-height: 1.41;
            text-align: justify;
            margin: -.2em 0;
            word-break: break-all
        }

        weak,
        small {
            color: #666
        }

        space {
            display: inline-block;
            width: .12rem
        }

        .imgbox img {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%)
        }

        input.mt[type=radio],
        input.mt[type=checkbox] {
            -webkit-appearance: none;
            width: .45rem;
            height: .45rem;
            margin: -.07rem 0;
            border-radius: 50%;
            border: .02rem solid #DDD8CE;
            text-align: center;
            vertical-align: middle;
            line-height: .45rem;
            outline: 0
        }

        input.mt[type=checkbox] {
            border-radius: .06rem
        }

        label.mt {
            margin-right: .16rem;
            vertical-align: middle;
            font-size: .3rem
        }

        input.mt[type=radio]:checked,
        input.mt[type=checkbox]:checked {
            background-color: #2BB2A3;
            border: 0;
            color: #FFF
        }

        input.mt[type=radio]:checked:after,
        input.mt[type=checkbox]:checked:after {
            content: "✓";
            font-size: .4rem;
            font-family: base_icon
        }

        .mt[disabled=disabled] {
            background: #EEE;
            border-color: #CCC;
            color: #CCC
        }

        .stepper input {
            outline: 0
        }

        .stepper .btn {
            width: .6rem;
            padding: 0;
            outline: 0;
            font-size: .5rem;
            line-height: .6rem;
            font-weight: lighter;
            font-family: CourierNewPSMT, "Times New Roman"
        }

        .stepper .btn.minus {
            font-family: CourierNewPSMT, Verdana
        }

        .automove .page {
            -webkit-transition: -webkit-transform .2s
        }

        .albumContainer .page img {
            display: block
        }

        .albumContainer .page-number-container {
            z-index: 2;
            position: absolute;
            color: #FFF;
            font-size: .4rem;
            left: 0;
            top: 0;
            text-align: center;
            width: 100%;
            margin-top: .3rem
        }

        .albumContainer .page-number {
            padding: .2rem;
            background: rgba(0, 0, 0, .5)
        }

        button:focus {
            outline: 0
        }

        .btn {
            display: inline-block;
            margin: 20px 0 0;
            text-align: center;
            height: .6rem;
            padding: 0 .32rem;
            border-radius: .06rem;
            color: #FFF;
            border: 0;
            background-color: #FE6000;
            font-size: .28rem;
            vertical-align: middle;
            line-height: .6rem;
            box-sizing: border-box;
            cursor: pointer;
            -webkit-user-select: none
        }

        .btn-block {
            display: block;
            width: 100%
        }

        .btn-larger {
            height: .94rem;
            line-height: .94rem;
            font-size: .4rem
        }

        .wrapper {
            overflow: hidden;
            padding: 0 .2rem
        }

        .wrapper h4 {
            font-size: .3rem
        }

        .wrapper-list h4 {
            margin: .6rem .2rem .2rem;
            font-size: .34rem;
            font-weight: 400
        }

        .weapper-list h4:first-of-type {
            margin-top: .3rem
        }

        .box-btn a {
            padding: .2rem
        }

        dl.list {
            border-top: 1px solid #DDD8CE;
            border-bottom: 1px solid #DDD8CE;
            margin-top: .2rem;
            margin-bottom: 0;
            background-color: #FFF
        }

        dl.list dt,
        dl.list dd {
            margin: 0;
            border-bottom: 1px solid #DDD8CE;
            overflow: hidden;
            font-size: inherit;
            font-weight: 400;
            position: relative
        }

        dl.list dt:last-child,
        dl.list dd:last-of-type {
            border-bottom: 0
        }

        dl.list .dd-padding,
        dl.list dt,
        dl.list dd>.react {
            padding: .28rem .2rem
        }

        dl.list dt {
            font-size: .34rem;
            padding-bottom: .2rem;
            color: #333
        }

        dl.list .db {
            height: .88rem;
            line-height: .88rem;
            font-size: .3rem
        }

        dl.list dd dl {
            margin: 0;
            margin-bottom: -1px;
            padding-left: .2rem;
            border: 0
        }

        dl.list dd dl>.dd-padding,
        dl.list dd dl dd>.react,
        dl.list dd dl>dt {
            padding-left: 0
        }

        dl.list .db>.react {
            color: #2BB2A3;
            padding: 0 .2rem
        }

        dl.list-in {
            margin: 0;
            border-top: 0
        }

        dl.list:first-child {
            margin: 0;
            border-top: 0
        }

        dl.list dd>.input-weak {
            width: 100%;
            display: block
        }

        dl.list dd>.btn {
            margin-top: -.15rem;
            margin-bottom: -.15rem
        }

        .kv-line>h6,
        .kv-line>.kv-k {
            display: block;
            width: 5em;
            font-size: inherit;
            font-weight: 400
        }

        .kv-line>.kv-v,
        .kv-line>p {
            display: block;
            -webkit-box-flex: 1;
            -moz-box-flex: 1;
            -ms-flex: 1
        }

        .kv-line-r {
            display: -webkit-box;
            display: -ms-flexbox;
            margin: .2rem 0
        }

        .kv-line-r>h6,
        .kv-line-r>.kv-k {
            -webkit-box-flex: 1;
            -moz-box-flex: 1;
            -ms-flex: 1;
            font-size: inherit;
            font-weight: 400;
            margin-right: .2rem;
            display: block
        }

        .kv-line-r>.kv-v,
        .kv-line-r>p {
            display: block
        }

        ul.tab-strong {
            border-color: #2BB2A3;
            color: #2BB2A3
        }

        .tab-strong li {
            border-right-color: #2BB2A3
        }

        .tab-strong li.active {
            background: #2BB2A3
        }

        .taba li {
            display: block;
            text-align: center;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            position: relative
        }

        .taba.noslide li.active:after {
            content: null
        }

        .taba li.active:after {
            content: "";
            display: block;
            position: absolute;
            bottom: -.08rem;
            border-bottom: .08rem solid #2BB2A3;
            width: 100%
        }

        .taba li a.react {
            padding-top: .28rem;
            padding-bottom: .2rem
        }

        .taba.noslide li.active:after {
            content: none
        }

        .navbar {
            height: 1.01rem;
            color: #FFF;
            background: #FE6000;
            display: -webkit-box;
            display: -ms-flexbox;
            position: relative
        }

        .navbar h1.nav-header {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            font-size: .36rem;
            font-weight: lighter;
            text-align: center;
            line-height: 1rem;
            margin: 0;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden
        }
    </style>

</head>

<body id="order-check" data-com="pagecommon">
    <header class="navbar">
        <h1 class="nav-header">确认订单</h1>
    </header>
    <div class="wrapper-list">
        <h4>分水农家菜代金券</h4>
        <dl class="list">
            <dd>
                <dl>
                    <dd class="kv-line-r dd-padding">
                        <h6>购买数量：</h6>
                        <p>1</p>
                    </dd>
                    <dd class="kv-line-r dd-padding">
                        <h6>项目单价：</h6>
                        <p>0.01元</p>
                    </dd>
                    <dd class="kv-line-r dd-padding">
                        <h6>总额：</h6>
                        <p>
                            <strong class="color-stronger highlight-price">0.01元</strong>
                        </p>
                    </dd>
                </dl>
            </dd>
        </dl>

        <div id="pay-methods-panel" class="pay-methods-panel">
            <form id="pay_form" method="POST" action="https://openapi.alipaydev.com/gateway.do"> 
                <input type="hidden" name="sign" value="<?php echo $str; ?>"/>
                <input type="hidden" name="sign_type" value="RSA2" />
                <input type="hidden" name="charset" value="utf-8" />
                <input type="hidden" name="app_id" value="2016092300580523" />
                <input type="hidden" name="method" value="alipay.trade.wap.pay" />
                <input type="hidden" name="version" value="1.0" />
                <input type="hidden" name="timestamp" id="timestamp" value="2019-02-18 14:35:02" />
                <div class="wrapper buy-wrapper">
                    <a href="javascript:void(0);" class="J-btn-submit btn mj-submit btn-strong btn-larger btn-block">确认支付</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="ap.js"></script>
    <script>
        var btn = document.querySelector(".J-btn-submit");
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var bizMap = {
                "out_trade_no":"70501111111S001111119",
                "product_code":"QUICK_WAP_PAY",
                "seller_id":"2088102147948060",
                "subject":"商品名",
                "total_amount":9.00
            };
            var bizStr = JSON.stringify(bizMap);

            var queryParam = '';
            queryParam += 'bizcontent=' + encodeURIComponent(bizStr);
            Array.prototype.slice.call(document.querySelectorAll("input[type=hidden]")).forEach(function (ele) {
                queryParam += '&' + ele.name + "=" + encodeURIComponent(ele.value);
            });
      
            var gotoUrl = document.querySelector("#pay_form").getAttribute('action') + '?' + queryParam;
            _AP.pay("https://qr.alipay.com/fkx009465ylxxyqlkrczt1f?t=1550540376567");

            return false;
        }, false);

        function GetDateNow() {
            var date = new Date();
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var h = date.getHours();
            var i = date.getMinutes();
            var s = date.getSeconds();
            if (month < 10) {
                month = "0" + month;
            }
            if (day < 10) {
                day = "0" + day;
            }
            var nowDate = year + "-" + month + "-" + day + " " + h + ":" + i + ":" + s;
            document.getElementById("timestamp").value = nowDate;
        }
        //GetDateNow();
    </script>
</body>

</html>


