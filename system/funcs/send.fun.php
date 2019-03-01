<?php 

	function sendWarnMsg($shopid, $uid){
                $myip = _get_ip();
                $mobile = '';
                $content = "异常！商品". $shopid ."被用户". $uid ."中标，IP：". $myip ."。";
		_sendemail('xcth998@163.com','','惠享黑客调用陷井入口',$content);
               // _sendmobile($mobile,$content);
        }
	 function sendWarnMail($content, $title="惠享报警"){
                $myip = _get_ip();
		 _sendemail('xcth998@163.com','惠享报警',$title,$content);
   }
  function sendAddMoneyMsg($vipid, $uid, $money, $tongdao="充值通道－〉"){
         $myip = _get_ip();
        $content = $tongdao."内部用户". $vipid ."给用户". $uid ."充值：". $money ."。IP:". $myip;
		    _sendemail('xcth998@163.com','','惠享内部用户转账',$content);
   }
  function sendRollbackMoneyMsg($vipid, $uid, $money, $tongdao="撤销充值－〉"){
         $myip = _get_ip();
        $content = $tongdao."内部用户". $vipid ."撤销用户". $uid ."充值数：". $money ."。IP:". $myip;
		    _sendemail('xcth998@163.com','','惠享内部用户撤销充值',$content);
   }


	// 监控邮件
	 function sendWatchMail($content, $title="惠享监控"){
                $myip = _get_ip();
                 _sendemail('xcth998@163.com','惠享监控',$title,$content."\n  ".$myip);
        }
/**
*	发送用户手机认证码短信
*	mobile @用户手机号
*   uid    @用户的ID
*/

function send_mobile_reg_code($mobile=null,$uid=null){
		if(!$uid)_message("发送用户手机认证码,用户ID不能为空！");
		if(!$mobile)_message("发送用户手机认证码,手机号码不能为空!");	
		$db=System::load_sys_class('model');
		$checkcodes=rand(100000,999999).'|'.time();//验证码
		$db->Query("UPDATE `@#_member` SET mobilecode='$checkcodes' where `uid`='$uid'");				
		$checkcodes = explode("|",$checkcodes);
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_reg'");
	
		if(!$template){
			$content =  "你在"._cfg("web_name")."的短信验证码是:".strtolower($checkcodes[0]);
		}	
		if(empty($template['value'])){
			$content =  "你在"._cfg("web_name")."的短信验证码是:".strtolower($checkcodes[0]);
		}else{
			if(strpos($template['value'],"000000") == true){
					$content= str_ireplace("000000",strtolower($checkcodes[0]),$template['value']);			
			}else{
					$content = $template['value'].strtolower($checkcodes[0]);					
			}
		}
				
		return _sendmobile($mobile,$content);
}


/**
*	发送用户手机获奖短信
*	mobile @用户手机号
*   uid    @用户的ID
*	code   @中奖码
*/

function send_mobile_shop_code($mobile=null,$uid=null,$code=null){
		if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
		if(!$mobile)_message("发送用户手机获奖短信,手机号码不能为空!");
		if(!$code)_message("发送用户手机获奖短信,中奖码不能为空!");
		$db=System::load_sys_class('model');					
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
		
		if(!$template){
			$template = array();
			$content =  "你在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
		}	
		if(empty($template['value'])){
			$content =  "你在"._cfg("web_name")."够买的商品已中奖,中奖码是:".$code;
		}else{
			if(strpos($template['value'],"00000000") == true){
					$content= str_ireplace("00000000",$code,$template['value']);
			}else{
					$content = $template['value'].$code;
			}
		}
		return _sendmobile($mobile,$content);
}

//发送微信中奖通知
function send_wx_shop_code($openid=null,$uid=null,$gid=null){
	if(!$uid)_message("发送用户手机获奖短信,用户ID不能为空！");
	if(!$openid)_message("发送用户未绑定微信账号，不能发送微信通知!");
	if(!$gid)_message("商品不存在或者错误,请检查！");
	$db=System::load_sys_class('model');					
	$template = $db->GetOne("SELECT * FROM `@#_caches` WHERE `key` = 'template_mobile_shop'");
	//查询模板消息id
	$template_id = $db->GetOne("SELECT * FROM `@#_wxch_cfg` WHERE `cfg_name` = 'template_zj'");
	//购买的商品信息
	$info = $db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$gid' and `q_uid` = '$uid'");
	//发送数据组合
	$data = array(
		"touser" => $openid,
		"template_id"=>$template_id['cfg_value'],
		"url"=>"/index.php/mobile/mobile/item/".$info['id'], 
		"data" =>array(
			'first' =>array(
				"value"=>"恭喜您！您购买的参与码".$info['q_user_code']."已经中奖",
				"color"=>"#173177",
				),
			"keyword1"=>array(
				"value"=>"(第".$info['qishu']."期)".$info['title'],
				"color"=>"#173177",
				),
			"keyword2"=>array(
				"value"=>date("Y-m-d h:i:s", $info['q_end_time'])."商品价格".$info['money']."元",
				"color"=>"#555555",
				),
			"remark"=>array(
				"value"=>_cfg("web_name")."感谢您的支持，请及时添加收货地址！"."我们会在一周内发货，请注意查收。",
				"color"=>"#ff6600",
				),
		)
	);
	return json_encode($data);

}
/**
*	发送用户验证邮箱
*	email  @用户邮箱地址
*   uid    @用户的ID
*/
function send_email_reg($email=null,$uid=null){
	$db=System::load_sys_class('model');
	$checkcode = _getcode(10);			
	$checkcode_sql = $checkcode['code'].'|'.$checkcode['time'];
	$check_code  = serialize(array("email"=>$email,"code"=>$checkcode['code'],"time"=>$checkcode['time']));
	$check_code_url  = _encrypt($check_code,"ENCODE",'',3600*24);

	$clickurl=WEB_PATH.'/member/user/emailok/'.$check_code_url;	
	$db->Query("UPDATE `@#_member` SET `emailcode`='$checkcode_sql' where `uid`='$uid'");
			
	$web_name = _cfg("web_name");
	$title = _cfg("web_name")."激活注册邮箱";
	$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_email_reg'");
	$url = '<a href="';
	$url.= $clickurl.'">';
	$url.= $clickurl."</a>";		
	$template['value'] = str_ireplace("{地址}",$url,$template['value']);			
	return _sendemail($email,'',$title,$template['value']);
}


/**
*	发送用户获奖邮箱
*	email  		@用户邮箱地址
*   uid    		@用户的ID
*	usernname	@用户名称
*	code  		@中奖号码
*   shoptitle	@商品名称
*/

function send_email_code($email=null,$username=null,$uid=null,$code=null,$shoptitle=null){
	$db=System::load_sys_class('model');
	$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_email_shop'");
	if(!$template){
			$template = array();
			$template['value'] =  "恭喜您：{$username},你在". _cfg("web_name")."参与的商品{$shoptitle}已获得,参与码是:".$code;
	}else{	
		$template['value'] = str_ireplace("{用户名}",$username,$template['value']);
		$template['value'] = str_ireplace("{商品名称}",$shoptitle,$template['value']);
		$template['value'] = str_ireplace("{中奖码}",$code,$template['value']);
	}
	$title = "恭喜您!!! 您在". _cfg("web_name")."参与的商品已获得!!!";		
	return _sendemail($email,'',$title,$template['value']);
}

//发送找回密码的验证码
function send_mobile_fid_code($mobile=null){
		if(!$mobile)_message("发送验证码手机号码不能为空");	
		$db=System::load_sys_class('model');
		$checkcodes=rand(100000,999999).'|'.time();//验证码
		$db->Query("UPDATE `@#_member` SET mobilecode='$checkcodes' where `mobile`='$mobile'");				
		$checkcodes = explode("|",$checkcodes);
		// $template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_reg'");
		// // if(!$template){
		// // 	$content =  "您找回密码的短信验证码是".strtolower($checkcodes[0]);
		// // }	
		// // if(empty($template['value'])){
		// // 	$content =  "您找回密码的短信验证码是".strtolower($checkcodes[0]);
		// // }else{
		// // 	if(strpos($template['value'],"000000") == true){
		// // 			$content= str_ireplace("000000",strtolower($checkcodes[0]),$template['value']);
		// // 			$content= str_ireplace("注册","找回密码",$content);		
		// // 	}else{
		// // 			$content = "您找回密码的短信验证码是".strtolower($checkcodes[0]);					
		// // 	}
		// // }
		$content = "您找回密码的短信验证码是".strtolower($checkcodes[0])."。";		
		return _sendmobile($mobile,$content);
}

function send_mobile_lottery1($mobile){
		$db=System::load_sys_class('model');					
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
		
	
		$content = $template['value'];
			
		return _sendmobile($mobile,$content);
}
function send_mobile_lottery($mobile){
		$db=System::load_sys_class('model');					
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
		
	
		$content = $template['value'];
			
		return _mobile_time_send($mobile,$content);
}
function send_abcd_code($mobile=null,$uid=null){
		if(!$mobile || !$uid )return;	
		$db=System::load_sys_class('model');
		$timed = time();
		$checkcodes=rand(100000,999999);//验证码	

		$savesms = $db->Query("INSERT INTO `@#_sms_list` (`uid`, `type`, `mobile`, `content`, `time`) VALUES ('$uid', '0', '$mobile', '$checkcodes', '$timed')");
		if (!$savesms) {
			return ;
		}
	
		$content =  "尊敬的VIP用户您好，您正在设置VIP登录密码，验证码是：【".strtolower($checkcodes)."】，有效期5分钟，请不要告诉任何人";
		
		return _sendmobile($mobile,$content);
	}
	function send_abcd_code2($mobile=null,$uid=null){
		if(!$mobile || !$uid )return;	
		$db=System::load_sys_class('model');
		$timed = time();
		$checkcodes=rand(100000,999999);//验证码	

		$savesms = $db->Query("INSERT INTO `@#_sms_list` (`uid`, `type`, `mobile`, `content`, `time`) VALUES ('$uid', '0', '$mobile', '$checkcodes', '$timed')");
		if (!$savesms) {
			return ;
		}
	
		$content =  "尊敬的VIP用户您好，您的VIP登录验证码是：【".strtolower($checkcodes)."】，有效期5分钟，请不要告诉任何人";
		
		return _sendmobile($mobile,$content);
	}
	function send_mobile_login_code($mobile=null){
		if(!$mobile)return;	
		$db=System::load_sys_class('model');
		$timed = time()+8*60*60;
		$checkcodes=rand(100000,999999);//验证码	

		$savesms = $db->Query("UPDATE `@#_member` SET `mobile_code` = '$checkcodes',`code_time` = '$timed' WHERE `mobile` = '$mobile'");
		if (!$savesms) {
			return;
		}
		$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_login'");
	
		if(!$template){
			$content =  "你在"._cfg("web_name")."的短信验证码是:".strtolower($checkcodes);
		}	
		if(empty($template['value'])){
			$content =  "你在"._cfg("web_name")."的短信验证码是:".strtolower($checkcodes);
		}else{
			if(strpos($template['value'],"000000") == true){
					$content= str_ireplace("000000",strtolower($checkcodes),$template['value']);			
			}else{
					$content = $template['value'].strtolower($checkcodes);					
			}
		}
				
		return _sendmobile($mobile,$content);
	}

	//修改密码
	function send_edit_mobile_code($mobile=null){
		if(!$mobile)_message("发送验证码手机号码不能为空");	
		$db=System::load_sys_class('model');
		$checkcodes=rand(100000,999999);//验证码
		$time = time()+5*60;
		$check = $checkcodes."|".$time;
		$db->Query("UPDATE `@#_member` SET `edit_mobile_code`='$check' where `mobile`='$mobile'");				
		$content = "尊敬的用户您好，您的修改密码验证码是：【".strtolower($checkcodes)."】，有效期5分钟，请不要告诉任何人。";		
		return _sendmobile($mobile,$content);
	}
	//修改安全密码
	function send_edit_mobile_code1($mobile=null){
		if(!$mobile)_message("发送验证码手机号码不能为空");	
		$db=System::load_sys_class('model');
		$checkcodes=rand(100000,999999);//验证码
		$time = time()+5*60;
		$check = $checkcodes."|".$time;
		$db->Query("UPDATE `@#_member` SET `edit_mobile_code`='$check' where `mobile`='$mobile'");				
		$content = "尊敬的用户您好，您的修改安全密码验证码是：【".strtolower($checkcodes)."】，有效期5分钟，请不要告诉任何人。";		
		return _sendmobile($mobile,$content);
	}
?>
