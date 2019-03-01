<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class yaoqianshu extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        $user = $this->userinfo;
      	if(!$user){
        	header("Location:/index.php/mobile/user/login");exit();
        }
      	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
      	if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        	//echo " Sorry！非微信浏览器不能访问";
          	_messagemobile("请在微信端打开",WEB_PATH."/mobile/home",3);exit();
        }
    }
  	public function pay_status(){
    	session_start();
      	$out_trade_no = $_SESSION['yaoqingshu']; 
      	//echo $out_trade_no;exit;
      	$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if(!$dingdaninfo){
          	$_SESSION['yaoqingshu'] = '';
          	echo "FAILED";exit;
        }
        if ( $dingdaninfo['status'] == '已付款' ) {
          	$_SESSION['yaoqingshu'] = '';
          	header("Location:/index.php/mobile/cart/paysuccess");exit;
        }else{
          	$_SESSION['yaoqingshu'] = '';
        	_messagemobile("支付失败",WEB_PATH."/mobile/home/recharge",2);exit;
        }
    }
  	private function pay_config(){
      	$data['mch_id'] = '10000342';
      	$data['key'] = '0211d1b0e24e133c4ea6f620g1a3ga6b';
      
    	//$data['mch_id'] = '10000274';
      	//$data['key'] = 'e70387ce2a07eded4ge2b34785a730a8';
      	return $data;
    }
  	//充值
    public function gopay(){
      	session_start();
      	$_SESSION['yaoqianshu'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));

        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['yaoqianshu'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 20){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
      	
      	$uid = $user['uid'];
      	$time = time();
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      
        
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      
      	$ccc = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$dingdancode' order by time desc limit 1");
      	if($ccc['status']=='已付款'){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      
      	$_SESSION['yaoqianshu'] = $dingdancode;
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
      	$pay_config = $this->pay_config();
    	$amount = $money;
      	$bank_code = 'ICBC_NET_B2C';
      	$goods = '微信支付';
      	$mch_id = $pay_config['mch_id'];
      	//$notify_url = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/yaoqianshu/"; 
      	$notify_url = "http://m.weimicm.com/index.php/pay/xypay_web_url2/yaoqianshu1/";
      	$order_no = $dingdancode;
        $service = 'wx.js.pay';
		$key = $pay_config['key'];
      	
      	
      	$tjurl = 'https://opay.arsomon.com:28443/vipay/reqctl.do';
      
      	$sign = "amount=".$amount."&bank_code=".$bank_code."&goods=".$goods."&mch_id=".$mch_id."&notify_url=".$notify_url."&order_no=".$order_no."&service=".$service."&key=".$key;
        
      	$data['amount'] = $amount;
      	$data['bank_code'] = $bank_code;
      	$data['goods'] = $goods;
      	$data['mch_id'] = $mch_id;
      	$data['notify_url'] = $notify_url;
      	$data['order_no'] = $order_no;
      	$data['service'] = $service;
      	$data['sign'] = md5($sign);
      	//var_dump($sign);exit;
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arr2xml($data));
        $rs = curl_exec($ch);
      	curl_close($ch);
      	//var_dump($rs);exit;	
      	$xml = simplexml_load_string($rs);
        $xmljson= json_encode($xml);
        $xml=json_decode($xmljson,true);
        //var_dump($xml['url']);
      	if($xml['url']){
        	header('Location:'.$xml['url']);exit;
        }else{
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
    }
  	//充值
    public function gopay123(){
      	session_start();
      	$_SESSION['yaoqianshu'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['yaoqianshu'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money != 1){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
      	
      	$uid = $user['uid'];
      	$time = time();
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      
        
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      
      	$ccc = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$dingdancode' order by time desc limit 1");
      	if($ccc['status']=='已付款'){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      
      	$_SESSION['yaoqianshu'] = $dingdancode;
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
      	$pay_config = $this->pay_config();
    	$amount = $money;
      	$bank_code = 'ICBC_NET_B2C';
      	$goods = '微信支付';
      	$mch_id = $pay_config['mch_id'];
      	//$notify_url = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/yaoqianshu/"; 
      	$notify_url = "http://m.weimicm.com/index.php/pay/xypay_web_url2/yaoqianshu1/";
      	$order_no = $dingdancode;
        $service = 'wx.js.pay';
		$key = $pay_config['key'];
      	
      	
      	$tjurl = 'https://opay.arsomon.com:28443/vipay/reqctl.do';
      
      	$sign = "amount=".$amount."&bank_code=".$bank_code."&goods=".$goods."&mch_id=".$mch_id."&notify_url=".$notify_url."&order_no=".$order_no."&service=".$service."&key=".$key;
        
      	$data['amount'] = $amount;
      	$data['bank_code'] = $bank_code;
      	$data['goods'] = $goods;
      	$data['mch_id'] = $mch_id;
      	$data['notify_url'] = $notify_url;
      	$data['order_no'] = $order_no;
      	$data['service'] = $service;
      	$data['sign'] = md5($sign);
      	//var_dump($sign);exit;
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arr2xml($data));
        $rs = curl_exec($ch);
      	curl_close($ch);
      	//var_dump($rs);exit;	
      	$xml = simplexml_load_string($rs);
        $xmljson= json_encode($xml);
        $xml=json_decode($xmljson,true);
        //var_dump($xml['url']);
      	if($xml['url']){
        	header('Location:'.$xml['url']);exit;
        }else{
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
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
            	//_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
  		
      	$pay_config = $this->pay_config();
      
      	$amount = $_SESSION['total_fee'] / 100;
      	$bank_code = 'ICBC_NET_B2C';
      	$goods = '微信支付';
      	$mch_id = $pay_config['mch_id'];
      	//$notify_url = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/yaoqianshu/"; 
      	$notify_url = "http://m.weimicm.com/index.php/pay/xypay_web_url2/yaoqianshu1/";
      	$order_no = $dingdancode;
        $service = 'wx.js.pay';
		$key = $pay_config['key'];
      	
      	
      	$tjurl = 'https://opay.arsomon.com:28443/vipay/reqctl.do';
      
      	$sign = "amount=".$amount."&bank_code=".$bank_code."&goods=".$goods."&mch_id=".$mch_id."&notify_url=".$notify_url."&order_no=".$order_no."&service=".$service."&key=".$key;
        
      	$data['amount'] = $amount;
      	$data['bank_code'] = $bank_code;
      	$data['goods'] = $goods;
      	$data['mch_id'] = $mch_id;
      	$data['notify_url'] = $notify_url;
      	$data['order_no'] = $order_no;
      	$data['service'] = $service;
      	$data['sign'] = md5($sign);
      	//var_dump($data);exit;
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arr2xml($data));
        $rs = curl_exec($ch);
      	curl_close($ch);
      	//var_dump($rs);exit;	
      	$xml = simplexml_load_string($rs);
        $xmljson= json_encode($xml);
        $xml=json_decode($xmljson,true);
        //var_dump($xml['url']);
      	if($xml['url']){
        	header('Location:'.$xml['url']);exit;
        }else{
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
    }
  	function arr2xml($data, $root = true){
        $str="";
        if($root)$str .= "<xml>";
        foreach($data as $key => $val){
            if(is_array($val)){
                $child = arr2xml($val, false);
                $str .= "<$key>$child</$key>";
            }else{
                $str.= "<$key><![CDATA[$val]]></$key>";
            }
        }
        if($root)$str .= "</xml>";
        return $str;
    }
}
