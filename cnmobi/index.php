<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>华移支付DEMO-php版</title>
    <link href="./static/css/pay.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="./static/js/jquery-2.1.0.min.js"></script>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Author" content="cnmobi ecitlm">
    <meta name="copyright" name="Copyright Cnmobi">
    <link rel="icon" href="https://pay.cnmobi.cn/icon.png" type="image/x-icon" />
   
</head>
<body>
<div id="pay_platform">
 <!-- header -->
			<header class="header">
		<div class="auto_center"><p>客服电话: 0755-3288-3288 ( 工作日09:00--18:00 )</p></div>
	</header>
	<div class="content">
        <div class="menu">
            <div class="item">
                <h5>接口测试</h5>
                <div class="">
                    <ul>
                        <li  class="cur" data-href="./tpl/wx_order">微信公众号支付测试 </li>
                        <li  data-href="./tpl/wx_scancode"> 微信扫码支付测试 </li>
                        <li data-href="./tpl/wx_bscancode">微信刷卡支付测试 </li>
                        <li data-href="./tpl/alipay_scancode">支付宝扫码支付测试</li>
                        <li data-href="./tpl/alipay_bscancode">支付宝刷卡支付测试 </li>
                        <li data-href="./tpl/query_order">订单查询测试</li>
                        <li data-href="./tpl/refund_order">订单退款测试</li>
                        <li data-href="./tpl/cancel_order">订单撤销测试</li>
                    </ul>
                </div>
            </div>
        </div>
		<div class="auto_center" id="auto_center">
			
		</div>
	</div><!-- content end -->
	
	
</div>

<footer class="footer legal">
    <p>深圳市华移科技股份有限公司</p>
    <p>深圳市南山区高新科技园中区特发信息港A栋南楼三层</p>
    <p>客服热线：<em>0755-3288-3288</em></p>
</footer>
 <script type="text/javascript" src="./static/js/cnpay.js"></script>
</body>
</html>