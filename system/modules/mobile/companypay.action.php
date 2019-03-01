<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class companypay extends base
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
      	$out_trade_no = $_SESSION['companypay']; 
      	//echo $out_trade_no;exit;
      	$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if(!$dingdaninfo){
          	$_SESSION['companypay'] = '';
          	echo "FAILED";exit;
        }
        if ( $dingdaninfo['status'] == '已付款' ) {
          	$_SESSION['companypay'] = '';
          	header("Location:/index.php/mobile/cart/paysuccess");exit;
        }else{
          	$_SESSION['companypay'] = '';
        	_messagemobile("支付失败",WEB_PATH."/mobile/home/recharge",2);exit;
        }
    }
  	private function pay_config(){
      	//$data['uid'] = '10000274';
      	//$data['key'] = 'e70387ce2a07eded4ge2b34785a730a8';
      
    	$data['uid'] = '172000244';
      	$data['key'] = 'fe31d4328d274d579bc431cf2c0dba0c';
      	$data['url'] = 'http://115.231.235.205:6060/api/createtrade';
      	return $data;
    }
  	//充值
    public function gopay(){
      	session_start();
      	
      	$_SESSION['companypay'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['companypay'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 20){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
      	
      	$userid = $user['uid'];
      	$time = time();
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$userid' order by time desc limit 1");
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
      
      	$_SESSION['companypay'] = $dingdancode;
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$userid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
      	
    	$price = $money;
      	$postUrl = "http://xc.csthsc.com/index.php/pay/xypay_web_url3/companypay/"; 
      	$backUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";
      	$orderNo = $dingdancode;
      	$body = '微信公众号';
        $type = 'wxpay';
      	$pay_config = $this->pay_config();
      	$uid = $pay_config['uid'];
      	$key = $pay_config['key'];
      	$tjurl = $pay_config['url'];
      
      	$sign = "backUrl=".$backUrl."&body=".$body."&orderNo=".$orderNo."&postUrl=".$postUrl."&price=".$price."&type=".$type."&uid=".$uid."&key=".$key;
      
      	$data['backUrl'] = $backUrl;
      	$data['body'] = $body;
      	$data['orderNo'] = $orderNo;
      	$data['postUrl'] = $postUrl;
      	$data['price'] = $price;
      	$data['type'] = $type;
      	$data['uid'] = $uid;
      	$data['sign'] = strtoupper(md5($sign));
      	
      	
      
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_exec($ch);
        $rs = curl_exec($ch);
      	//var_dump(json_decode($rs,true));exit;
      	$req = json_decode($rs,true);
      	
      	if($req['payUrl']){
        	header('Location:'.$req['payUrl']);exit;
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
      	$userid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$userid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	//_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
  		
      	$price = $_SESSION['total_fee'] / 100;
      	$postUrl = "http://xc.csthsc.com/index.php/pay/xypay_web_url3/companypay/"; 
      	$backUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";
      	$orderNo = $dingdancode;
      	$body = '微信公众号';
        $type = 'wxpay';
      	$pay_config = $this->pay_config();
      	$uid = $pay_config['uid'];
      	$key = $pay_config['key'];
      	$tjurl = $pay_config['url'];
      
      	$sign = "backUrl=".$backUrl."&body=".$body."&orderNo=".$orderNo."&postUrl=".$postUrl."&price=".$price."&type=".$type."&uid=".$uid."&key=".$key;
      
      	$data['backUrl'] = $backUrl;
      	$data['body'] = $body;
      	$data['orderNo'] = $orderNo;
      	$data['postUrl'] = $postUrl;
      	$data['price'] = $price;
      	$data['type'] = $type;
      	$data['uid'] = $uid;
      	$data['sign'] = strtoupper(md5($sign));
      	
      	
      
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_exec($ch);
        $rs = curl_exec($ch);
      	//var_dump(json_decode($rs,true));exit;
      	$req = json_decode($rs,true);
      	if($req['payUrl']){
        	header('Location:'.$req['payUrl']);exit;
        }else{
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
    }
}