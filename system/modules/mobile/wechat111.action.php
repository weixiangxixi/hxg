<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class wechat111 extends base
{
	
  	private $key;
    private $code;
  	private $mchid;
  	private $shost;
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
  	public function clean(){
      	session_start();
    	unset($_SESSION['wechat111_openid']);
      	unset($_SESSION['wechat111_money']);
      	echo $_SESSION['wechat111_money'];
    }
  	//充值
    public function gopay(){
      	session_start();
      	require 'wechat111.php';
        header("Content-type: text/html; charset=utf-8");
      	
      	$money = htmlspecialchars($this->segment(4));
     	
        
      	if(empty($money)){
        	$money = $_SESSION['wechat111_money'];
        }
      
      	$openid = $_SESSION['wechat111_openid'];
      	if(empty($openid)){
            if($_GET['code']){
              	$p = new PP($_GET['code']);
            	$p->getOpenid();
            }
        }else{
        	$_SESSION['wechat111_money'] = '';
        }
      
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
      	if(empty($money) && empty($_SESSION['wechat111_money'])){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 20){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
      	
      	$userid = $user['uid'];
      	$time = time();
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$userid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 8){
            	//_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      
        
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	
      	$ccc = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$dingdancode' order by time desc limit 1");
      	if($ccc['status']=='已付款'){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$userid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
      
      	if(empty($openid)){
          	$_SESSION['wechat111_money'] = $money;
          	$_SESSION['wechat111_code'] = $dingdancode;
            
            $p = new PP();
        	$p->getCode();
            
        }
      
      	$p = new PP();
		$req = $p->payMoney($money * 100,$dingdancode);
      	$rs = $req['pay_params'];
      	
      	if(!$rs){
        	_messagemobile("支付不可用",WEB_PATH."/mobile/home/recharge",2);exit;
        }
      	
      	$rs['mchntnm'] = urlencode('惠享购');
      	$rs['txamt'] = $money * 100;
      	$rs['goods_name'] = urlencode("微信支付");
      	$rs['redirect_url'] = urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php/mobile/cart/paysuccess/');
      	
      	foreach($rs as $k=>$v){
        	$data[] = "{$k}={$v}";
        }
      	$url = join('&',$data);
      	
      	header('Location:https://o2.qfpay.com/q/direct?'.$url);
    }
  	
  	public function paygo(){
      	session_start();
      	require 'wechat111.php';
        header("Content-type: text/html; charset=utf-8");
        
        $user = $this->userinfo;
        
      	$openid = $_SESSION['wechat111_openid'];
      	if(empty($openid)){
            if($_GET['code']){
              	$p = new PP($_GET['code']);
            	$p->getOpenid();
            }else{
              	$p = new PP();
        		$p->getCode2();
            }
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
  		
      	$price = $_SESSION['total_fee'];
      	
      	$p = new PP();
		$req = $p->payMoney($price,$dingdancode);
      	$rs = $req['pay_params'];
      	
      	if(!$rs){
        	_messagemobile("支付不可用",WEB_PATH."/mobile/home/recharge",2);exit;
        }
      	
      	$rs['mchntnm'] = urlencode('惠享购');
      	$rs['txamt'] = $price;
      	$rs['goods_name'] = urlencode("微信支付");
      	$rs['redirect_url'] = urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php/mobile/cart/paysuccess/');
      	
      	foreach($rs as $k=>$v){
        	$data[] = "{$k}={$v}";
        }
      	$url = join('&',$data);
      	
      	header('Location:https://o2.qfpay.com/q/direct?'.$url);
    }
}