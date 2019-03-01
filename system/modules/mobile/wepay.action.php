<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class wepay extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        //$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
        //$this->userinfo=$this->db->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");
        //_freshen();
    }
    
    public function gopay(){
      	session_start();
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/mobile/user/login");exit();
        }
		if ($money < 20) {
            _messagemobile("至少支付20元",WEB_PATH."/mobile/home/recharge",2);exit();
        }
        $pay_type = '微信公众号';
        $time = time();
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	
      	
      	
        //订单号
        $uid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      
      
     	$song = 0;
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`,`smoney`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money','$song', '$pay_type','未付款', '$time','0','0')");
        //echo 1;
		
      	error_reporting(0);
        $pay_memberid = "10060";   //商户ID
        $pay_orderid = $dingdancode;    //订单号
        $pay_amount = $money;    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        //$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/server.php";   //服务端返回地址
      	$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai3/";   //服务端返回地址
        
        $pay_callbackurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/page.php";  //页面跳转返回地址
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
        $native['pay_productname'] ='微信支付';
		
      	$pay_md5sign = $sign;
      	$pay_attach = "1234|456";
      	$pay_productname = '微信支付';
      	//var_dump($native);exit;
      	//$js = json_encode($native);
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$sign}:{$js}\n", FILE_APPEND);

      	//var_dump($native);exit();
      	//$tjurl = 'http://zhifu.u8qgg.cn/Pay_Index.html';

        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $tjurl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $native);
        //curl_exec($ch);
        
      	include templates("mobile/user","wepay");
    }
  	
	public function paygo(){
      	session_start();
        header("Content-type: text/html; charset=utf-8");
        
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/mobile/user/login");exit();
        }

        $pay_type = '微信公众号';
        $time = time();
        $dingdancode = $_SESSION["out_trade_no"];
      	if(empty($_SESSION["out_trade_no"])){
           header("Location:/mobile/home/recharge");exit();
        }
      	$uid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
  
      	error_reporting(0);
        $pay_memberid = "10060";   //商户ID
        $pay_orderid = $dingdancode;    //订单号
        $pay_amount = $_SESSION['total_fee'] / 100;    //交易金额
      
      	if($pay_amount < 20){
        	_messagemobile("至少支付20元",WEB_PATH."/mobile/home/recharge",2);exit();
        }
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        //$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/server.php";   //服务端返回地址
      	$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai3/";   //服务端返回地址
        
        $pay_callbackurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/page.php";  //页面跳转返回地址
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
        $native['pay_productname'] ='微信支付';
		
      	$pay_md5sign = $sign;
      	$pay_attach = "1234|456";
      	$pay_productname = '微信支付';
      	//var_dump($native);exit;
      	//$js = json_encode($native);
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$sign}:{$js}\n", FILE_APPEND);

      	//var_dump($native);exit();
      	//$tjurl = 'http://zhifu.u8qgg.cn/Pay_Index.html';

        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $tjurl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $native);
        //curl_exec($ch);
        
      	include templates("mobile/user","wepay");
    }
  	public function gopay123(){
      	session_start();
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/mobile/user/login");exit();
        }
		if ($money < 20) {
            //_messagemobile("至少支付20元",WEB_PATH."/mobile/home/recharge",2);exit();
        }
        $pay_type = '微信公众号';
        $time = time();
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	
      	
      	
        //订单号
        $uid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	//_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      
      
     	$song = 0;
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`,`smoney`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money','$song', '$pay_type','未付款', '$time','0','0')");
        //echo 1;
		
      	error_reporting(0);
        $pay_memberid = "10060";   //商户ID
        $pay_orderid = $dingdancode;    //订单号
        $pay_amount = $money;    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        //$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/server.php";   //服务端返回地址
      	$pay_notifyurl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai3/";   //服务端返回地址
        
        $pay_callbackurl = 'http://'.$_SERVER['HTTP_HOST']."/wepay/page.php";  //页面跳转返回地址
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
        $native['pay_productname'] ='微信支付';
		
      	$pay_md5sign = $sign;
      	$pay_attach = "1234|456";
      	$pay_productname = '微信支付';
      	//var_dump($native);exit;
      	//$js = json_encode($native);
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$sign}:{$js}\n", FILE_APPEND);

      	//var_dump($native);exit();
      	//$tjurl = 'http://zhifu.u8qgg.cn/Pay_Index.html';

        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $tjurl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $native);
        //curl_exec($ch);
        
      	include templates("mobile/user","wepay");
    }
}