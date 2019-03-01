<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>充值支付</title>
    <link href="/ext_pay/css/pay.css" rel="stylesheet" type="text/css"/>
    <link href="/ext_pay/css/sprite.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ext_pay/js/jquery-2.1.0.min.js"></script>
</head>
<body>
<input type="hidden" id="out_trade_no" value="<?php echo $_SESSION["out_trade_no"]; ?>">
<input type="hidden" id="sub_openid" value="<?php echo $_SESSION["openid"]; ?>">
<input type="hidden" id="total_fee" value="<?php echo $_SESSION["total_fee"]; ?>">
<input type="hidden" id="pay_callback" value="<?php echo $_SESSION["pay_callback"][$_SESSION["out_trade_no"]]; ?>">
<script type="text/javascript" src="/ext_pay/js/pay.js?v=1.31"></script>
</body>
</html>