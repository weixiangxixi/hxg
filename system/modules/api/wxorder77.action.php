<?php

defined('G_IN_SYSTEM')or exit("no");
System::load_app_class('base','member','no');

System::load_app_fun('my','go');

System::load_app_fun('user','go');

System::load_sys_fun('send');

System::load_sys_fun('user');

System::load_sys_fun('test');

class wxorder77 extends SystemAction {
	
	private $qc;
	private $db;
	private $conf;
    private $appid;
    private $secret;
	private $qq_openid;
	public function __construct(){	
		$this->conf = System::load_app_config("connect");
        $this->appid = 'wx406b64aecc8b832d';
        $this->secret = '9eef12877189003a54152113bb583fed';
      	$this->db = System::load_sys_class("model");
	}
	public function aaaaaaa(){
      	$key = 'tfzx123456'; 
		$code = MyEncrypt($str, 'E', $key);
    }
	//wexin登录
	public function init(){
      	//echo 1;exit();
      	session_start();
      	//$code = htmlspecialchars($this->segment(4));
      	/*
      	$allString = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$searchString = 'http://'.$_SERVER['HTTP_HOST']."/index.php/api/wxorder5/init/";
		$newString = strstr($allString, $searchString);
		$length = strlen($searchString);
		$code = substr($newString, $length);
      
      	if(empty($code)){
        	exit();
        }
      	//$code = urldecode($code);
      	$key = 'tfzx123456'; 
		$uid = MyEncrypt($code, 'D', $key);
      
      	$member = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid' LIMIT 1");
		if(empty($member)){
        	exit();
        }
		_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
      	_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
      
      	$_SESSION['uid'] = $uid;
      	*/
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.WEB_PATH.'/api/wxorder77/callback&response_type=code&scope=snsapi_userinfo&state=wechat123&connect_redirect=1#wechat_redirect';
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
		
		$_SESSION['openid']= $weixin_openid;
		
		//header('Location:/wepay/');
      	//exit('1');
      	$str = $uid; 
		$key = 'tfzx123456'; 
		$code = MyEncrypt($str, 'E', $key);
      	header('Location:/index.php/mobile/home/recharge1/');exit();
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