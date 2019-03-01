<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class cnmobi extends base
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
          	//_messagemobile("请在微信端打开",WEB_PATH."/mobile/home",3);exit();
        }
    }
  	public function pay_status(){
    	session_start();
      	$out_trade_no = $_SESSION['cnmobi']; 
      	//echo $out_trade_no;exit;
      	$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if(!$dingdaninfo){
          	$_SESSION['cnmobi'] = '';
          	echo "FAILED";exit;
        }
        if ( $dingdaninfo['status'] == '已付款' ) {
          	$_SESSION['cnmobi'] = '';
          	header("Location:/index.php/mobile/cart/paysuccess");exit;
        }else{
          	$_SESSION['cnmobi'] = '';
        	_messagemobile("支付失败",WEB_PATH."/mobile/home/recharge",2);exit;
        }
    }
  	public function payfail(){
    	_messagemobile("支付失败",WEB_PATH."/mobile/home/recharge",2);exit;
    }
  	//充值
    public function gopay(){
      	
      	session_start();
      	$_SESSION['cnmobi'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            //header("Location:/index.php/mobile/user/login");exit();
        }
		    if(!empty($_SESSION['cnmobi'])){
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
      	$ddd = time() . substr(microtime(), 2, 6) . rand(0, 9);
        $dingdancode =   'C' . $ddd;
      	$_SESSION['cnmobi'] = $dingdancode;
      	$pay_type = '微信公众号';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai5/"; 
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
        //$this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		
      	error_reporting(0);
      	
      	$data['orderNo'] = $ddd;
        $data['name'] = $pay_type;
        $data['total'] = $money;
        $data['returnUrl'] = $returnUrl;
        $data['openID'] = 'ovM8R1bBh4gBTLM0XsFa0RvT1lDo';

        $rs = request_post('http://'.$_SERVER['HTTP_HOST'].'/cnmobi/request.php?method=wxGzh',$data);
        $rs1 = json_decode($rs,true);
      	$rs2 = $rs1['result']['formfield'];
      	$rs2 = json_decode($rs1['result']['formfield'],true);
      	//var_dump($rs2);
      	//header("Location:{$rs1['result']['payInfo']}");
      	include templates ( "mobile/cnmobi", "pay1" );
    }
    
  	
  	//充值支付
  	public function paygo(){
      	session_start();
        header("Content-type: text/html; charset=utf-8");
      	
        $money = $_SESSION["total_fee"];
      	$dingdancode = $_SESSION["out_trade_no"];
      
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(empty($dingdancode)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      
        $time = time();
      
      	error_reporting(0);
      	
      	$uid = $user['uid'];
      	
      	$totalAmount = $money;
      	$msgSrc = 'WWW.SZPCSY.COM';
      	$msgType = 'WXPay.jsPay';
        $requestTimestamp = date('Y-m-d H:i:s',$time);
        $merOrderId = '4400'.$dingdancode;
        
      	$account = $this->account();
        $mid = $account['mid'];
        $tid = $account['tid'];
      
        $instMid = 'YUEDANDEFAULT';
      	$key = 'wyTH3PswJR7yztCzf6jTEsb2ny2xy48mazfMdcTsEtWtb6wz';
      	
      	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai5/";   //服务端返回地址
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";  //页面跳转返回地址
        
      	$attachedData = '充值支付,uid:'.$uid.':time:'.$time.','.date('Y-m-d H:i:s',$time);
    	$string = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE'.$key;
      	$sign = md5($string);
      	$a = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE';
      	$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$a.'&sign='.$sign;
      	header("Location:{$url}");exit();
    }
    private function account(){//private
    	$array = array(
          	array('mid'=>'898445156911255','tid'=>'67110562'),
          	array('mid'=>'898445156911259','tid'=>'67110566'),
          	array('mid'=>'898445148160235','tid'=>'67110289'),
          	array('mid'=>'898445156911262','tid'=>'67110569')
        );
      	$n = mt_rand(0,3);
      	return $array[$n];
    }
  	private function account2($n){//private
    	$array = array(
          	array('mid'=>'898445156911255','tid'=>'67110562'),
          	array('mid'=>'898445156911259','tid'=>'67110566'),
          	array('mid'=>'898445148160235','tid'=>'67110289'),
          	array('mid'=>'898445156911262','tid'=>'67110569')
        );
      	$n = mt_rand(0,3);
      	return $array[$n];
    }
  	
  	
  	public function gopay2502501(){
      	$n = htmlspecialchars($this->segment(4));
      	$uid = '1';
      	$time = time();
      	$msgSrc = 'WWW.SZPCSY.COM';
      	$msgType = 'WXPay.jsPay';
        $requestTimestamp = date('Y-m-d H:i:s',time());
        $merOrderId = '4400'.'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
        $account = $this->account2($n);
        $mid = $account['mid'];
        $tid = $account['tid'];
        $instMid = 'YUEDANDEFAULT';
      	$key = 'wyTH3PswJR7yztCzf6jTEsb2ny2xy48mazfMdcTsEtWtb6wz';
      	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";   //服务端返回地址
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";  //页面跳转返回地址
      	$attachedData = '充值,uid:'.$uid.':time:'.$time.','.date('Y-m-d H:i:s',$time);
    	$string = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount=10&walletOption=SINGLE'.$key;
      	$sign = md5($string);
      	$a = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount=10&walletOption=SINGLE';
      	$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$a.'&sign='.$sign;
      	header("Location:{$url}");exit();  	
    }	
    public function gopay250(){
      	$n = htmlspecialchars($this->segment(4));
      	$money = $n * 100;
      	$uid = '1';
      	$time = time();
      	$msgSrc = 'WWW.SZPCSY.COM';
      	$msgType = 'WXPay.jsPay';
        $requestTimestamp = date('Y-m-d H:i:s',time());
        $merOrderId = '4400'.'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
        $account = $this->account();
        $mid = $account['mid'];
        $tid = $account['tid'];
        $instMid = 'YUEDANDEFAULT';
      	$key = 'wyTH3PswJR7yztCzf6jTEsb2ny2xy48mazfMdcTsEtWtb6wz';
      	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";   //服务端返回地址
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";  //页面跳转返回地址
      	$attachedData = '充值,uid:'.$uid.':time:'.$time.','.date('Y-m-d H:i:s',$time);
    	$string = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$money.'&walletOption=SINGLE'.$key;
      	$sign = md5($string);
      	$a = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$money.'&walletOption=SINGLE';
      	$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$a.'&sign='.$sign;
      	header("Location:{$url}");exit();  	
    }
  	//充值
    public function gopay250250(){
      	
      	session_start();
      	$_SESSION['unionpayy'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money != 1){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      
      	$uid = $user['uid'];
      
        $time = time();
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	$_SESSION['unionpayy'] = $dingdancode;
      	//echo $_SESSION['unionpay'];exit;
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		
      	
      	error_reporting(0);
      	
      	
      
      	$msgSrc = 'WWW.SZPCSY.COM';
      	$msgType = 'WXPay.jsPay';
        $requestTimestamp = date('Y-m-d H:i:s',$time);
        $merOrderId = '4400'.$dingdancode;
      	
      	$account = $this->account2();
        $mid = $account['mid'];
        $tid = $account['tid'];
      
        $instMid = 'YUEDANDEFAULT';
      	$key = 'wyTH3PswJR7yztCzf6jTEsb2ny2xy48mazfMdcTsEtWtb6wz';
      	
      	$totalAmount = $money * 100;
      	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai5/";   //服务端返回地址
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/unionpay/pay_status/";  //页面跳转返回地址
        
      	$attachedData = '充值,uid:'.$uid.':time:'.$time.','.date('Y-m-d H:i:s',$time);
    	$string = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE'.$key;
      	$sign = md5($string);
      	$a = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE';
      	$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$a.'&sign='.$sign;
      	//echo $url;exit();
      	$url = urldecode($url);
      	header("Location:{$url}");exit();
    }
	//充值
    public function abcd(){
      	
      	session_start();
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
      	//echo $user['uid'];exit;
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['unionpay'])){
        	header("Location:/index.php/mobile/abcd/html7/");exit();
        }
      	if(empty($money)){
        	header("Location:/");exit();
        }
      	if($money < 1000 || $money > 10000 ){
        	header("Location:/");exit();
        }
      	
      	
      
      	$uid = $user['uid'];
      
      	$uinfo = $this->db->GetOne("select * from `@#_vip_song` where `uid` = '$uid'");
      	if($uinfo['song_state']!=1){
        	header("Location:/index.php/mobile/abcd/html7/");exit();
        }
      	
        $time = time();
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	$pay_type = '微信公众号';
      
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		$_SESSION['unionpay'] = $dingdancode;
      	error_reporting(0);
      	
      	
      
      	$msgSrc = 'WWW.SZPCSY.COM';
      	$msgType = 'WXPay.jsPay';
        $requestTimestamp = date('Y-m-d H:i:s',$time);
        $merOrderId = '4400'.$dingdancode;
      	
      	$account = $this->account();
        $mid = $account['mid'];
        $tid = $account['tid'];
      
        $instMid = 'YUEDANDEFAULT';
      	$key = 'wyTH3PswJR7yztCzf6jTEsb2ny2xy48mazfMdcTsEtWtb6wz';
      	
      	$totalAmount = $money * 100;
      	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/houtai55/";   //服务端返回地址
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";  //页面跳转返回地址
        
      	$attachedData = '充值,uid:'.$uid.':time:'.$time.','.date('Y-m-d H:i:s',$time);
    	$string = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE'.$key;
      	$sign = md5($string);
      	$a = 'attachedData='.$attachedData.'&instMid='.$instMid.'&merOrderId='.$merOrderId.'&mid='.$mid.'&msgId=3194&msgSrc='.$msgSrc.'&msgType='.$msgType.'&notifyUrl='.$notifyUrl.'&requestTimestamp='.$requestTimestamp.'&returnUrl='.$returnUrl.'&tid='.$tid.'&totalAmount='.$totalAmount.'&walletOption=SINGLE';
      	$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$a.'&sign='.$sign;
      	header("Location:{$url}");exit();
    }
}