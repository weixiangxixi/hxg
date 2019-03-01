<?php

defined('G_IN_SYSTEM')or exit("no");
class wxorder3 extends SystemAction {
	
	private $qc;
	private $db;
	private $conf;
	private $qq_openid;
	public function __construct(){	
		$this->conf = System::load_app_config("connect");
      	$this->db = System::load_sys_class("model");
	}
	
	//wexin登录
	public function init(){
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->conf['weixin']['id'].'&redirect_uri='.WEB_PATH.'/api/wxorder3/callback&response_type=code&scope=snsapi_userinfo&state=wechat123&connect_redirect=1#wechat_redirect';
		header("location:$url");
	}
  	public function aabbcc(){
    	echo intval(_encrypt(_getcookie("uid"),'DECODE'));
    }
	//wexin回调
	public function callback(){
		session_start();
		$code = $_GET['code'];
		$state = $_GET['state'];
		if (empty($code)) $this->error('授权失败');
		$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->conf['weixin']['id'].'&secret='.$this->conf['weixin']['key'].'&code='.$code.'&grant_type=authorization_code';
		$token = json_decode(getCurl($token_url));
		$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->conf['weixin']['id'].'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
		//转成对象
		$access_token = json_decode(getCurl($access_token_url));
		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
		//转成对象
		$user_info = json_decode(getCurl($user_info_url),true);
		$this->qc = $user_info;
		$weixin_openid = $user_info['openid'];
      	$nickname = $user_info['nickname'];
      
      	$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
		$this->db->Query("UPDATE `@#_member` SET `wxopenid1` = '$weixin_openid' WHERE `uid` = '$uid'");
      
      	
		if(empty($weixin_openid)){
			echo '信息获取失败，请返回刷新后重新操作';die;
		}
		$this->qq_openid = $weixin_openid;
		$this->db = System::load_sys_class("model");
		
		$img = $this->qq_add_member();
		$_SESSION['openid']= $weixin_openid;
		$_SESSION['img']= $img;
      	$_SESSION['nickname']= $nickname;
		//var_dump($_SESSION);exit();
      	
      	//$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
		
		//$this->db->Query("UPDATE `@#_member` SET `openid1` = '$_SESSION[openid]' WHERE `uid` = '$uid'");
 
		if (!empty($_SESSION['img'] )) {
			header("location:/mobile/home/userrecharge8");
		}else{
			header("location:/api/wxorder3/");
		}
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

	public function xx(){
		var_dump($this->conf);
	}


	
}

?>