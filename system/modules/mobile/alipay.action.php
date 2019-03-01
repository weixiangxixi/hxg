<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class alipay extends base
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
            header("Location:/index.php/mobile/user/login");exit();
        }
      	
      	if($money < 20){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
        $pay_type = '通过网络充值';
        $time = time();
      	$uid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      	
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	
        //订单号
        
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        //echo 1;
		$_SESSION['alipay_code'] = $dingdancode;
      	error_reporting(0);
        
        //从网页传入price:支付价格， type:支付渠道：1-微信支付；2-支付宝
        $price = $money;
        $istype = '1';
        $order_uid = $user['uid'];       //此处传入您网站用户的用户id，方便在平台后台查看是谁付的款，强烈建议加上。可忽略。

        //校验传入的表单，确保价格为正常价格（整数，1位小数，2位小数都可以），支付渠道只能是1或者2，orderuid长度不要超过33个中英文字。

       

		$orderuid = $user['uid'];       
        //此处传入您网站用户的用户名，方便在平台后台查看是谁付的款，强烈建议加上。可忽略。
        //此处就在您服务器生成新订单，并把创建的订单号传入到下面的orderid中。
        $goodsname = "支付宝支付";
        $orderid = $dingdancode;    //每次有任何参数变化，订单号就变一个吧。
        $uid = "100003";				//"此处填写平台的uid";
        $token = "78567f66d42927712a9c69bf66084053f3804ddc";			//"此处填写平台的Token";
      	$return_url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/pay/xypay_web_url/alipay_callback/';	//同步返回地址
        $notify_url = 'http://xc.csthsc.com/index.php/pay/xypay_web_url/houtai444/';	//回调地址

        $key = md5($goodsname. $istype . $notify_url . $orderid . $orderuid . $price . $return_url . $token . $uid);

       
        $data['goodsname'] = $goodsname;
        $data['istype'] = $istype;
        $data['key'] = $key;
        $data['notify_url'] = $notify_url;
        $data['orderid'] = $orderid;
        $data['orderuid'] = $orderuid;
        $data['price'] = $price;
        $data['return_url'] = $return_url;
        $data['uid'] = $uid;
		
      	//$tjurl = 'http://mianqian.u8qgg.cn/Home/Pay/payFor';
      	$tjurl = 'http://1120.weapp.pw/pay';
      
      	//var_dump($data);exit;
        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $tjurl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_exec($ch);
      	include templates("mobile/user","alipay");
    }
	public function gopay123(){
      	session_start();
        header("Content-type: text/html; charset=utf-8");
        $money = htmlspecialchars($this->segment(4));
        $user = $this->userinfo;
      	
        if (empty($user)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
      	
      	if($money != 1){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	
        $pay_type = '通过网络充值';
        $time = time();
      	$uid = $user['uid'];
      
      	$ddd = $this->db->GetOne("select * from `@#_member_addmoney_record` where `uid` = '$uid' order by time desc limit 1");
      	if($ddd){
        	if($time - $ddd['time'] < 20){
            	//_messagemobile("支付操作太快",WEB_PATH."/mobile/home/recharge",2);exit;
            }
        }
      	
        $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
      	
        //订单号
        
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        //echo 1;
		$_SESSION['alipay_code'] = $dingdancode;
      	error_reporting(0);
        
        //从网页传入price:支付价格， type:支付渠道：1-微信支付；2-支付宝
        $price = $money;
        $istype = '1';
        $order_uid = $user['uid'];       //此处传入您网站用户的用户id，方便在平台后台查看是谁付的款，强烈建议加上。可忽略。

        //校验传入的表单，确保价格为正常价格（整数，1位小数，2位小数都可以），支付渠道只能是1或者2，orderuid长度不要超过33个中英文字。

       

		$orderuid = $user['uid'];       
        //此处传入您网站用户的用户名，方便在平台后台查看是谁付的款，强烈建议加上。可忽略。
        //此处就在您服务器生成新订单，并把创建的订单号传入到下面的orderid中。
        $goodsname = "支付宝支付";
        $orderid = $dingdancode;    //每次有任何参数变化，订单号就变一个吧。
        $uid = "100003";				//"此处填写平台的uid";
        $token = "78567f66d42927712a9c69bf66084053f3804ddc";			//"此处填写平台的Token";
      	$return_url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/pay/xypay_web_url/alipay_callback/';	//同步返回地址
        $notify_url = 'http://xc.csthsc.com/index.php/pay/xypay_web_url/houtai444/';	//回调地址

        $key = md5($goodsname. $istype . $notify_url . $orderid . $orderuid . $price . $return_url . $token . $uid);

       
        $data['goodsname'] = $goodsname;
        $data['istype'] = $istype;
        $data['key'] = $key;
        $data['notify_url'] = $notify_url;
        $data['orderid'] = $orderid;
        $data['orderuid'] = $orderuid;
        $data['price'] = $price;
        $data['return_url'] = $return_url;
        $data['uid'] = $uid;
		
      	//$tjurl = 'http://mianqian.u8qgg.cn/Home/Pay/payFor';
      	$tjurl = 'http://1120.weapp.pw/pay';
      
      	//var_dump($data);exit;
        //$ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, $tjurl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_exec($ch);
      	include templates("mobile/user","alipay");
    }

    public function go_alipay(){
        header("Content-type: text/html; charset=utf-8");
        ini_set ( "display_errors", "On" );
        error_reporting ( E_ALL );
        
        header ( "Access-Control-Allow-Origin: *" );
        $return = '';
        
        $para ['out_trade_no'] = '10001_' . $this->create_password ();
        $para['pay_type'] = '010';
        $para ['goods_name'] = 'vip';
        $para ['total_fee'] = '110';
        $para ['callback_url'] = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess/";
        $para ['notify_url'] = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url/alipay/";
        $para ['attach'] = 'svip';
        $para ['nonce_str'] = mt_rand(time(),time()+rand());
        $para ['bank_id'] = '01000000';
        $para ['player_id'] = '66666';
        $para ['player_ip'] = $_SERVER["REMOTE_ADDR"]; 


        $para ['mer_id'] = '1211';
        $key = "sloxpe3ar3c1v4aulv1fooigun9pnca6";

        $sign_str = 'mer_id='.$para['mer_id'].'&nonce_str='.$para['nonce_str'].'&out_trade_no='.$para['out_trade_no'].'&total_fee='.$para['total_fee'].'&key='.$key;
        
        $para['sign'] = md5($sign_str); 

        $url = 'http://www.payucloud.com:7002/pay_server/tran_pay?' . http_build_query ( $para );

        // user_agent一定要带
        $user_agent = $_SERVER ['HTTP_USER_AGENT'];
        $header = array (
            "User-Agent: $user_agent" 
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        $response = curl_exec ( $ch );
        if ($error = curl_error ( $ch )) {
          die ( $error );
        }
        curl_close ( $ch );
        
        $arr = json_decode($response, true);

        if('0'  == $arr["status"]){
          header ( 'Location: ' . $arr["code_url"]);
        }else{
          echo $arr["msg"];
        }
    }

    public function create_password($pw_length = 16) {
      $randpwd = '';
      for($i = 0; $i < $pw_length; $i ++) {
        $randpwd .= chr ( mt_rand ( 97, 122 ) );
      }
      return $randpwd;
    }
}