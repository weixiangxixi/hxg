<?php
session_start();
if(!$_SESSION['openid']){
	header('Location:/index.php/api/wxorder55/init/');exit();
}
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid = "10060";   //商户ID
$pay_orderid = $_SESSION["out_trade_no"];    //订单号
$pay_amount = $_SESSION["total_fee"] / 100;    //交易金额
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$url = 'http://' . $_SERVER['HTTP_HOST'];
$pay_callbackurl = $url."/wepay/page.php"; //页面跳转返回地址
$pay_notifyurl = $url."/index.php/pay/xypay_web_url/houtai3/"; //服务端返回地址
$Md5key = "p42sg7dmhwwqc88r1qsvmqi7lgaifw2v";   //密钥
$tjurl = "http://zhifu.u8qgg.cn/Pay_Index.html";   //提交地址
$pay_bankcode = "916";   //银行编码
//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
$native['pay_attach'] = "1234|456";
$native['pay_productname'] ='VIP基础服务';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>微信支付</title>
  	<script src="/statics/templates/yungou/js/mobile/jquery.js"></script>
</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">
            <form class="form-inline" method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($native as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" style="background:none;border:none;" id="submitdemo1"></button>
            </form>
        </div>
    </div>
</div>
<center><img style="margin:0 auto;text-align:center;" src="/ext_pay2/image/loading.gif" /></center>
</body>
</html>
<script>
    $().ready(function(){
        
		
        $('#submitdemo1').click();
        
    });

</script>