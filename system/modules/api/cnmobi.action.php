<?php

defined('G_IN_SYSTEM')or exit("no");
System::load_app_class('base','member','no');

System::load_app_fun('my','go');

System::load_app_fun('user','go');

System::load_sys_fun('send');

System::load_sys_fun('user');

System::load_sys_fun('test');

class cnmobi extends SystemAction {
	
	private $qc;
	private $db;
	private $conf;
    private $appid;
    private $secret;
	private $qq_openid;
	public function __construct(){	
		$this->conf = System::load_app_config("connect");
        $this->appid = 'wxde669b50c9c78ca7';
        $this->secret = '96d94e8af3427acfbfdcee18cf7dfcce';
      	$this->db = System::load_sys_class("model");
      	_messagemobile("支付已下线",WEB_PATH."/mobile/home/recharge",2);exit();
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
        $money = $_GET['total'];
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
      	//var_dump($uid);exit;
        if (empty($uid)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['cnmobi'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($_SESSION['openID'])){
        	header("Location:/index.php/api/cnmobi");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 20){
        	_messagemobile("至少支付20元",WEB_PATH."/mobile/home/recharge",3);exit();
        }
      
        $time = time();
      	$ddd = time() . substr(microtime(), 2, 6) . rand(0, 9);
        $dingdancode =   'C' . $ddd;
      	$_SESSION['cnmobi'] = $dingdancode;
      	$pay_type = '微信公众号';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/wxnofity/"; 
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		
      	error_reporting(0);
      	
      	$data['orderNo'] = $ddd;
        $data['name'] = $pay_type;
        $data['total'] = $money * 100;;
        $data['returnUrl'] = $returnUrl;
        $data['openID'] = $_SESSION['openID'];

        $rs = request_post('http://'.$_SERVER['HTTP_HOST'].'/cnmobi/request.php?method=wxGzh',$data);
        $rs1 = json_decode($rs,true);
      	$rs2 = $rs1['result']['formfield'];
      	$rs2 = json_decode($rs1['result']['formfield'],true);
      	//var_dump($rs2);
      	//header("Location:{$rs1['result']['payInfo']}");
      	include templates ( "mobile/cnmobi", "pay1" );
    }
  	//购买
  	public function paygo(){
      	session_start();
      	$_SESSION['cnmobi'] = '';
        header("Content-type: text/html; charset=utf-8");
        
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
      	//var_dump($uid);exit;
        if (empty($uid)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['cnmobi'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($_SESSION['openID'])){
        	//header("Location:/index.php/api/cnmobi");exit();
        }
      	if(empty($_SESSION["out_trade_no"])){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 20){
        	_messagemobile("至少支付20元",WEB_PATH."/mobile/home/recharge",3);exit();
        }
      
        $time = time();
      	
        $dingdancode = $_SESSION["out_trade_no"];
      	$ddd = trim(str_replace("C",'',$dingdancode));
      	$_SESSION['cnmobi'] = $dingdancode;
      	$pay_type = '微信公众号';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/wxnofity/"; 
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
		
      	error_reporting(0);
      	
      	$data['orderNo'] = $ddd;
        $data['name'] = $pay_type;
        $data['total'] = $_SESSION["total_fee"];
        $data['returnUrl'] = $returnUrl;
        $data['openID'] = $_SESSION['openID'];

        $rs = request_post('http://'.$_SERVER['HTTP_HOST'].'/cnmobi/request.php?method=wxGzh',$data);
        $rs1 = json_decode($rs,true);
      	$rs2 = $rs1['result']['formfield'];
      	$rs2 = json_decode($rs1['result']['formfield'],true);
      	//var_dump($rs2);
      	//header("Location:{$rs1['result']['payInfo']}");
      	include templates ( "mobile/cnmobi", "pay1" );
    }
  	//充值
    public function gopay123(){
      	
      	session_start();
      	$_SESSION['cnmobi'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = $_GET['total'];
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
      	//var_dump($uid);exit;
        if (empty($uid)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['cnmobi'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($_SESSION['openID'])){
        	header("Location:/index.php/api/cnmobi");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money == 20){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      
        $time = time();
      	$ddd = time() . substr(microtime(), 2, 6) . rand(0, 9);
        $dingdancode =   'C' . $ddd;
      	$_SESSION['cnmobi'] = $dingdancode;
      	$pay_type = '微信公众号';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
        //$this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		
      	error_reporting(0);
      	
      	$data['orderNo'] = $ddd;
        $data['name'] = $pay_type;
        $data['total'] = $money * 100;;
        $data['returnUrl'] = $returnUrl;
        $data['openID'] = $_SESSION['openID'];

        $rs = request_post('http://'.$_SERVER['HTTP_HOST'].'/cnmobi/request.php?method=wxGzh',$data);
        $rs1 = json_decode($rs,true);
      	$rs2 = $rs1['result']['formfield'];
      	$rs2 = json_decode($rs1['result']['formfield'],true);
      	//var_dump($rs2);
      	//header("Location:{$rs1['result']['payInfo']}");
      	include templates ( "mobile/cnmobi", "pay1" );
    }
  	//充值
    public function abcd(){
      	
      	session_start();
      	$_SESSION['cnmobi'] = '';
        header("Content-type: text/html; charset=utf-8");
        $money = $_GET['total'];
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
      	//var_dump($uid);exit;
        if (empty($uid)) {
            header("Location:/index.php/mobile/user/login");exit();
        }
		if(!empty($_SESSION['cnmobi'])){
        	//header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if(empty($_SESSION['openID'])){
        	//header("Location:/index.php/api/cnmobi");exit();
        }
      	if(empty($money)){
        	header("Location:/index.php/mobile/home/recharge");exit();
        }
      	if($money < 1000 || $money > 10000 ){
        	header("Location:/index.php/mobile/abcd/html7/");exit();
        }
      	
      	$uinfo = $this->db->GetOne("select * from `@#_vip_song` where `uid` = '$uid'");
      	if($uinfo['song_state']!=1){
        	header("Location:/index.php/mobile/abcd/html7/");exit();
        }
      
        $time = time();
      	$ddd = time() . substr(microtime(), 2, 6) . rand(0, 9);
        $dingdancode =   'C' . $ddd;
      	$_SESSION['cnmobi'] = $dingdancode;
      	$pay_type = '微信公众号';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/pay/xypay_web_url2/wxnofity2/"; 
        $returnUrl = 'http://'.$_SERVER['HTTP_HOST']."/index.php/mobile/cart/paysuccess";
        $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','0','0')");
        
		
      	error_reporting(0);
      	
      	$data['orderNo'] = $ddd;
        $data['name'] = $pay_type;
        $data['total'] = $money * 100;;
        $data['returnUrl'] = $returnUrl;
        $data['openID'] = $_SESSION['openID'];

        $rs = request_post('http://'.$_SERVER['HTTP_HOST'].'/cnmobi/request.php?method=wxGzh',$data);
        $rs1 = json_decode($rs,true);
      	$rs2 = $rs1['result']['formfield'];
      	$rs2 = json_decode($rs1['result']['formfield'],true);
      	//var_dump($rs2);
      	//header("Location:{$rs1['result']['payInfo']}");
      	include templates ( "mobile/cnmobi", "pay1" );
    }
	//wexin登录
	public function init(){
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.WEB_PATH.'/api/cnmobi/callback&response_type=code&scope=snsapi_userinfo&state=wechat123&connect_redirect=1#wechat_redirect';
		header("location:$url");
	}
	//wexin回调
	public function callback(){
		session_start();
		$code = $_GET['code'];
		$state = $_GET['state'];
		if (empty($code)) $this->error('授权失败');
		$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$code.'&grant_type=authorization_code';
		$token = json_decode(getCurl($token_url));
		$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
		//转成对象
		$access_token = json_decode(getCurl($access_token_url));
		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
		//转成对象
		$user_info = json_decode(getCurl($user_info_url),true);
		$this->qc = $user_info;
		$weixin_openid = $user_info['openid'];
      
      	$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
		$this->db->Query("UPDATE `@#_member` SET `wxopenid2` = '$weixin_openid' WHERE `uid` = '$uid'");
      
		if(empty($weixin_openid)){
			echo '信息获取失败，请返回刷新后重新操作';die;
		}
		//echo $weixin_openid;exit;
		$_SESSION['openID'] = $weixin_openid;
		
		header('Location:/mobile/home/recharge');
	}
	//wexin回调
	public function callback1(){
		session_start();
		$code = $_GET['code'];
		$state = $_GET['state'];
		if (empty($code)) $this->error('授权失败');
		$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$code.'&grant_type=authorization_code';
		$token = json_decode(getCurl($token_url));
		//var_dump($token);exit();

		$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
		//转成对象
		$access_token = json_decode(getCurl($access_token_url));

		//var_dump($access_token->access_token);exit();

		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
		//转成对象
		$user_info = json_decode(getCurl($user_info_url));
		
		$this->qc = $user_info;
		$weixin_openid = $user_info['openid'];
		if(empty($weixin_openid)){
			echo '信息获取失败，请返回刷新后重新操作';die;
		}
		$this->qq_openid = $weixin_openid;
		$this->db = System::load_sys_class("model");
		
		$img = $this->qq_add_member();
		
	}
	public function test(){
		$a = getCurl('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0c82f1a1ec759364&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect');
		var_dump($a);
	}
	private function qq_add_member(){
		$go_user_info = $this->qc;

		$go_user_time = time();
		if(!$go_user_info)$go_user_info=array('nickname'=>'QU'.$go_user_time.rand(0,9));
		// $go_y_user = $this->db->GetOne("select * from `@#_member` where `username` = '$go_user_info[nickname]' LIMIT 1");
		// if($go_y_user)$go_user_info['nickname'] .= rand(0,9);
		$go_user_name = $go_user_info['nickname'];
		$go_user_img  = 'photo/member.jpg';
		$go_user_himg  = $go_user_info['headimgurl'];
		$go_user_pass = md5('123456');
		$qq_openid    = $this->qq_openid;
		$this->db->Autocommit_start();
		return $go_user_himg;
		// $decode = 0;
			
		// $q1 = $this->db->Query("INSERT INTO `@#_member` (`username`,`password`,`img`,`yaoqing`,`headimg`,`wxid`,`time`) VALUES ('$go_user_name','$go_user_pass','$go_user_img','$decode','$go_user_himg','$qq_openid','$go_user_time')");
		// $go_user_id = $this->db->insert_id();
		// $q2 = $this->db->Query("INSERT INTO `@#_member_band` (`b_uid`, `b_type`, `b_code`, `b_time`) VALUES ('$go_user_id', 'weixin', '$qq_openid', '$go_user_time')");
		// // 查询用户注册
		// if($q1 && $q2){
		// 	$this->db->Autocommit_commit();
		// 	$this->qq_set_member($go_user_id,'add');

		// }else{
		// 	$this->db->Autocommit_rollback();
		// 	_message("登录失败!",G_WEB_PATH);
		// }
		
	}

	function https_request($url, $data = null)  {  
	    $curl = curl_init();  
	    curl_setopt($curl, CURLOPT_URL, $url);  
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  
	    if (!empty($data)){  
	        curl_setopt($curl, CURLOPT_POST, 1);  
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
	    }  
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	    $output = curl_exec($curl);  
	    curl_close($curl);  
	    return $output;  
	}  

	
}

?>