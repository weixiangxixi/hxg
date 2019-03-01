<?php
//header("Content-Type:text/html;charset=UTF-8");
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "weixin" . DIRECTORY_SEPARATOR . "WxPayPubHelper.php";
class weixin
{
	private $config;

	public function config($config = null)
	{
		$this->config = $config;
	}

	public function send_pay()
	{
		$unifiedOrder = new UnifiedOrder_pub();
		$amount = trim($this->config['money']) * 100;
		$notify_url = $this->config['NotifyUrl'];
		$unifiedOrder->setParameter("body", $this->config['title']);
		$out_trade_no = $this->config['code'];
		$unifiedOrder->setParameter("out_trade_no", $out_trade_no);
		$unifiedOrder->setParameter("total_fee", $amount);
		$unifiedOrder->setParameter("notify_url", $notify_url);
		$unifiedOrder->setParameter("trade_type", "NATIVE");
		$unifiedOrder->setParameter("attach", "111");
		$unifiedOrderResult = $unifiedOrder->getResult();

		$status = 0;
		$msg = '支付暂不可用';
		if ($unifiedOrderResult["return_code"] == "FAIL") {
			$status = 0;
			$msg = '支付暂不可用';
		} elseif ($unifiedOrderResult["result_code"] == "FAIL") {
			$status = 0;
			$msg = '支付暂不可用';
		} elseif ($unifiedOrderResult["code_url"] != NULL) {	
			$status = 1;
			$msg = $unifiedOrderResult["code_url"];
		}
		$res['status'] = $status;
		$res['msg'] = $msg;
		$res['code'] = $out_trade_no;
		$res['money'] = $this->config['money'];
		echo json_encode($res);
		exit();
		// if ($unifiedOrderResult["return_code"] == "FAIL") {
		// 	echo "通信出错：" . $unifiedOrderResult['return_msg'] . "<br>";
		// } elseif ($unifiedOrderResult["result_code"] == "FAIL") {
		// 	echo iconv("utf-8", "gb2312//IGNORE", "错误代码：" . $unifiedOrderResult['err_code'] . "<br>");
		// 	echo iconv("utf-8", "gb2312//IGNORE", "错误代码描述：" . $unifiedOrderResult['err_code_des'] . "<br>");
		// } elseif ($unifiedOrderResult["code_url"] != NULL) {
		// 	$qrcode = "/system/modules/pay/lib/qrcode.js";
		// 	$code_url = $unifiedOrderResult["code_url"];
		// 	if ($unifiedOrderResult["code_url"] != NULL) {
		// 		$hehe = 'var url = "' . $code_url . '";
		// 		var qr = qrcode(10, "M");
		// 		qr.addData(url);
		// 		qr.make();	
		// 		var code = qr.createImgTag();
		// 		console.log(code);
		// 		$("body").append(code);';
		// 	}
		// 	$def_url = '<body></body><script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
		// 	<script src="' . $qrcode . '"></script>
		// 				<script>' . $hehe . '</script>';
		// 	echo $code_url;
		// 	exit;
		// }
	}
}