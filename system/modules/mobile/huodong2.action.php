<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class huodong2 extends memberbase {
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");
	}

	public function recharge(){

		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$current_count = intval(date("Ymd",time()));
			$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK($wechat['appid'], $wechat['appsecret']);

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());
        if($hour > 19){
        	$current_count = intval(date("Ymd",time()));
        	$arr = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `current_count`='$current_count' order by `id` desc");
        }else{
        	$current_count = intval(date("Ymd",time()))-1;
        	$arr = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `current_count`='$current_count' order by `id` desc");
        }
        $brr = array();
        foreach($arr as $key=>$val){
        	$crr = explode(',', $val['amount']);
        	$money[$key] = 0;
        	foreach ($crr as $k => $value) {
        		$money[$key] += $value;
        	}
        	$brr[$key]['money'] = $money[$key];

        	$uid = $val['user_id'];
        	$detail = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
        	$brr[$key]['username'] = $detail['username'];
        	$brr[$key]['phone'] = substr($detail['mobile'], 0, 3)."****".substr($detail['mobile'], 7);
        }

		//include templates("mobile/huodong","huafei2");
	}

	public function recharge010(){

		$sid = $this->segment(4);
		if(!empty($sid)){
			$shopid = _encrypt($sid,"DECODE");
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$shopid'");
			$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data2[amount]'");
			if(!$data2 || !$str){
				_messagemobile("大爷,你走错地方了","/",3);
			}
			$uid = $this->userinfo['uid'];
			if(!$uid){
				header("location:".WEB_PATH."/mobile/user/login/");exit;
			}
			
			$hd_sid = $shopid;
	
			$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$hd_sid'");
			$hd_flag = strstr($hd_data['friends_id'], $uid);
			if(!$hd_flag && $hd_data['user_id'] != $uid && $hd_sid){
				if(empty($hd_data['friends_id'])){
					$friends_id = $uid;
				}else{
					$friends_id = $hd_data['friends_id'].",".$uid;
				}
				$hd_status = $this->db->Query("UPDATE `@#_activity_lottery` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
				if($hd_status){
					$fz_status = 1;
				}else{
					$fz_status = 2;
				}
			}else{
				
				$fz_status = 3;
			}

		}
		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$current_count = intval(date("Ymd",time()));
			$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count` LIKE '%$current_count%'");
			$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$tt = date('Ymd',time());
			$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = '$user_id' AND `current_count` LIKE '%$tt%'");
			if(!$arr3){
				$arr3 = 0;
			}
			$sycs = 2-$arr3;
		}else{
			$sycs = 2;
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK('wx4974d1b8daeca416', 'aef2cc68c515b6797420d5197e805677');

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());

        $current_count = intval(date("Ymd",time()));
        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

        $brr = array();
        foreach($arr as $key=>$val){
        	$uid = $val['user_id'];
        	$detail = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
        	$brr[$key]['username'] = $detail['username'];
        	$brr[$key]['phone'] = substr($detail['mobile'], 0, 3)."****".substr($detail['mobile'], 7);

        	$brr[$key]['shopname'] = $val['name'];
        }
		//include templates("mobile/huodong","huafei3");
	}

	// public function recharge111(){
	// 	$sid = $this->segment(4);
	// 	if(!empty($sid)){
	// 		$shopid = _encrypt($sid,"DECODE");
	// 		$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$shopid'");
	// 		$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data2[amount]'");
	// 		if(!$data2 || !$str){
	// 			_messagemobile("大爷,你走错地方了","/",3);
	// 		}
	// 		$uid = $this->userinfo['uid'];
	// 		if(!$uid){
	// 			header("location:".WEB_PATH."/mobile/user/login/");exit;
	// 		}
			
	// 		$hd_sid = $shopid;
	
	// 		$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$hd_sid'");
	// 		$hd_flag = strstr($hd_data['friends_id'], $uid);
	// 		if(!$hd_flag && $hd_data['user_id'] != $uid && $hd_sid){
	// 			if(empty($hd_data['friends_id'])){
	// 				$friends_id = $uid;
	// 			}else{
	// 				$friends_id = $hd_data['friends_id'].",".$uid;
	// 			}
	// 			$hd_status = $this->db->Query("UPDATE `@#_activity_lottery` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
	// 			if($hd_status){
	// 				$fz_status = 1;
	// 			}else{
	// 				$fz_status = 2;
	// 			}
	// 		}else{
				
	// 			$fz_status = 3;
	// 		}

	// 	}
	// 	$user = $this->userinfo;
	// 	if($user){
	// 		$user_id = $user['uid'];
	// 		$current_count = intval(date("Ymd",time()));
	// 		$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count` LIKE '%$current_count%'");
	// 		$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
	// 		$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
	// 		$tt = date('Ymd',time());
	// 		$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = '$user_id' AND `current_count` LIKE '%$tt%'");
	// 		if(!$arr3){
	// 			$arr3 = 0;
	// 		}
	// 		$sycs = 2-$arr3;
	// 	}else{
	// 		$sycs = 2;
	// 	}

	// 	//分享部分代码
 //        require_once("system/modules/mobile/jssdk.php");

 //        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

 //        $jssdk = new JSSDK('wx4974d1b8daeca416', 'aef2cc68c515b6797420d5197e805677');

 //        $signPackage = $jssdk->GetSignPackage();

 //        $hour = date("H",time());

 //        $current_count = intval(date("Ymd",time()));
 //        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

 //        $brr = array();
 //        foreach($arr as $key=>$val){
 //        	$uid = $val['user_id'];
 //        	$detail = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
 //        	$brr[$key]['username'] = $detail['username'];
 //        	$brr[$key]['phone'] = substr($detail['mobile'], 0, 3)."****".substr($detail['mobile'], 7);

 //        	$brr[$key]['shopname'] = $val['name'];
 //        }
	// 	include templates("mobile/huodong","huafei3");
	// }

	public function recharge2019(){
		$sid = $this->segment(4);
		if(!empty($sid)){
			$shopid = _encrypt($sid,"DECODE");
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$shopid'");
			$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data2[amount]'");
			if(!$data2 || !$str){
				_messagemobile("大爷,你走错地方了","/",3);
			}
			$uid = $this->userinfo['uid'];
			if(!$uid){
				header("location:".WEB_PATH."/mobile/user/login/");exit;
			}
			
			$hd_sid = $shopid;
	
			$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$hd_sid'");
			$hd_flag = strstr($hd_data['friends_id'], $uid);
			if(!$hd_flag && $hd_data['user_id'] != $uid && $hd_sid){
				if(empty($hd_data['friends_id'])){
					$friends_id = $uid;
				}else{
					$friends_id = $hd_data['friends_id'].",".$uid;
				}
				$hd_status = $this->db->Query("UPDATE `@#_activity_lottery` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
				if($hd_status){
					$fz_status = 1;
				}else{
					$fz_status = 2;
				}
			}else{
				
				$fz_status = 3;
			}

		}
		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$current_count = intval(date("Ymd",time()));
			$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count` LIKE '%$current_count%'");
			$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$tt = date('Ymd',time());
			$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = '$user_id' AND `current_count` LIKE '%$tt%'");
			if(!$arr3){
				$arr3 = 0;
			}
			$sycs = 2-$arr3;
		}else{
			$sycs = 2;
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK('wx4974d1b8daeca416', 'aef2cc68c515b6797420d5197e805677');

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());

        $current_count = intval(date("Ymd",time()));
        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

        $brr = array();
        foreach($arr as $key=>$val){
        	$uid = $val['user_id'];
        	$detail = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
        	$brr[$key]['username'] = $detail['username'];
        	$brr[$key]['phone'] = substr($detail['mobile'], 0, 3)."****".substr($detail['mobile'], 7);

        	$brr[$key]['shopname'] = $val['name'];
        }
		include templates("mobile/huodong","huafei3");
	}
	/*
	status :
		0,活动结束
		1,只抽中话费
		2,都抽中话费,现金红包
		3,活动未开始
		4,需登录
		5,需去充值才能参加活动
	*/

	//抢红包
	public function inrecharge(){

		$status = 3;

		$user = $this->userinfo;
		$uid = $user['uid'];
		if (!$user) {
			$status = 4;
		}//未登录

		$yyy = $this->db->GetCount("SELECT count(*) FROM `@#_activity_yyy` WHERE `uid` = '$uid' AND `shiwu_id` != '0'");

		if ($status==3) {
			$x = rand(1,1000);
			if($x == 111 || $x == 333 || $x == 666 || $x == 888 || $x == 999){
				$s = 9;
			}else if($x < 9){
				$s = 8;
			}else if($x <19){
				$s = 7;
			}else if($x < 36){
				$s = 6;
			}else if($x < 86){
				$s = 5;
			}else if($x < 146){
				$s = 4;
			}else if($x < 316){
				$s = 3;
			}else if($x < 566){
				$s = 2;
			}else{
				$s = 1;
			}
			if($yyy > 0 && $s > 6){
				$s = 6;
			}
		}elseif ($status==4) {
			$resultMessage = '请先登录';
		}

		$str = $this->db->GetOne("SELECT * FROM `@#_yyy_shop` WHERE `id` = '$s'");

		$current_count = intval(date("Ymd",time()));
		$time = time();
		$created_at = date("Y-m-d H:i:s",time());
		$this->db->Autocommit_start();

		$xxx = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = '$uid' AND `current_count` LIKE '%$current_count%'");
	
		if($xxx <= 1){
			if($xxx == 0){
				$current_count = $current_count."1";
				$x = $this->db->Query("INSERT INTO `@#_activity_lottery`(amount,user_id,current_count,created_at,state) VALUE ('$s','$uid','$current_count','$time','1')");
			}else{
				$current_count = $current_count."2";
				$x = $this->db->Query("INSERT INTO `@#_activity_lottery`(amount,user_id,current_count,created_at,state) VALUE ('$s','$uid','$current_count','$time','1')");
			}
			
			if($s >= 1 && $s <= 4){
				$flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$uid','1','积分','摇一摇奖励','$str[fufen]','$time')");

				$f = $this->db->Query("UPDATE `@#_member` SET `score` = `score` + '$str[fufen]' where (`uid` = '$uid')");

				if($x && $flag && $f){
					$this->db->Autocommit_commit();
					$data['money'] = $str['name'];
					$data['img'] = $str['img'];
					$data['status'] = 3;
					
				}else{
					$this->db->Autocommit_rollback();
					$data['money'] = "获取商品失败".";".$flag.";".$f.";".$x.";".$xxx;
					$data['status'] = 0;
				}
			}else{
				$this->db->Autocommit_commit();
                $data['money'] = $str['name'];
                $data['img'] = $str['img'];
				$data['status'] = 3;
				$hd_sid = _getcookie("hd_sid");
				$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$hd_sid' AND `user_id` != '$uid'");
				$hd_flag = strstr($hd_data['friends_id'], $uid);
				if(!$hd_flag){
					if(empty($hd_data['friends_id'])){
						$friends_id = $uid;
					}else{
						$friends_id = $hd_data['friends_id'].",".$uid;
					}
					$xx = 1;
					$hd_status = $this->db->Query("UPDATE `@#_activity_lottery` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
					if($hd_status){
						$data['hd'] = 1;
						_setcookie("hd_sid","",time()-3600);
					}else{
						$data['hd'] = 2;
					}
				}
			}
		}else{
			$data['money'] = "当天抽奖已达上线";
			$data['status'] = 0;
		}

		echo json_encode($data);
	}

	//获取红包数量
	public function GetNumber(){
		$data['number'] = 1888;
		$data['resultMessage'] = '获取成功';
		$data['status'] = 1;
		echo json_encode($data);
	}

	//中奖记录
	public function BuyLog(){
		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$time = strtotime("-15 days");
			$brr = $this->db->GetList("SELECT m.*,n.name,n.img,n.friends,n.fufen FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.user_id = '$user_id' AND m.created_at > '$time' order by m.id desc");
			foreach ($brr as $key => $val) {
				$brr[$key]['updated_at'] = date("Y-m-d H:i:s",$val['created_at']);
			}
		}
		$data['status'] = 1;
		$data['total'] = count($brr);
		$data['log'] = $brr;
		echo json_encode($data);
	}

	public function SaveTotal(){
		$amount = $_POST['total'];
		$uid = $_POST['uid'];
		$current_count = intval(date("Ymd",time()));
		$time = time();
		$this->db->Autocommit_start();

		$xxx = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = 'uid' AND `current_count` = '$current_count'");
		if($xxx <= 2){
			$x = $this->db->Query("INSERT INTO `@#_activity_lottery`(amount,user_id,current_count,state) VALUE ('$amount','$uid','$current_count','1')");
		
			$total = explode(',',$amount);
			$money = 0;
			foreach ($total as $key => $val) {
				$money += $val;
			}

			//$t = $this->db->Query("UPDATE `@#_activity_lottery` SET `state` = '1' WHERE `user_id` = '$user_id' AND `current_count`='$current_count'");
			$flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$uid','1','账户','活动红包充值','$money','$time')");
			$f = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$uid')");
			if($x && $flag && $f){
				$this->db->Autocommit_commit();
				echo 1;  //充值成功
			}else{
				$this->db->Autocommit_rollback();
				echo 2;  //充值失败
			}
		}else{
			echo 2;
		}
	}

	public function share(){
		$data['status'] = 3;
		echo json_encode($data);
	}

	public function user(){
		$user = $this->userinfo;
		
		if($user){
			if(time()<1519995600){   //1519995600
				echo 1; //活动未开始
			}else{
				echo 2;
			}
		}else{
			echo 0;
		}
	}

	public function activity_time(){
		echo 6;exit;
		$user = $this->userinfo;
		if($user){
			$uid = $user['uid'];
			$time = time()-3*86400;
			//$data = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `create_time`>'$time' order by `create_time` desc");
			$data = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = '$uid' AND `status` = '已付款' AND `pay_type` in ('微信公众号','通过网络充值') AND `time` > '$time' order by `time` desc");
			$flag = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `create_time`>'$time' order by `create_time` desc");
			
	    	$tt = date('Ymd',time());
	    	$alipay_account = $data['alipay_account'];
	    	$all_ali = $this->db->GetList("SELECT * FROM `@#_alipay_locat` WHERE `alipay_account` = '$alipay_account' AND `status`=1 AND `create_time`>'$time'");
	    
	   		foreach($all_ali as $key => $val){
	   			$brr[] = $val['uid'];
	   		}
	   		$brr = array_unique($brr);
	   		$brr = implode(',', $brr);
	   		$arr = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `user_id` in ($brr) AND `current_count` LIKE '%$tt%'");
	   		foreach ($arr as $key => $val) {
	   			$drr[] = $val['user_id'];
	   		}
	   		$drr = array_unique($drr);
	   		if(!empty($drr)){
		   		$drr_s = in_array($drr);
		   	}

	   		$openid = $flag['img'];

	   		$all_wx = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `img` = '$openid' AND `status`=1 AND `create_time`>'$time'");
	    
	   		foreach($all_wx as $key => $val){
	   			$brr2[] = $val['uid'];
	   		}
	   		$brr2 = array_unique($brr2);
	   		$brr2 = implode(',', $brr2);
	   		$arr2 = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `user_id` in ($brr2) AND `current_count` LIKE '%$tt%'");
	   		foreach ($arr2 as $key => $val) {
	   			$drr2[] = $val['user_id'];
	   		}
	   		$drr2 = array_unique($drr2);
	   		if(!empty($drr2)){
		   		$drr2_s = in_array($uid,$drr2);
		   	}

		   	/****同个充值用户抽奖当天抽奖个数****/
		   	/**start**/

		   	//用户关联号
	   		$cjcs = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$uid'");
	   		//微信1、微信2关联号
	   		if(!empty($cjcs['wxopenid1'])){
	   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
	   		}else{
	   			$wx1 = '';
	   		}

	   		if(!empty($cjcs['wxopenid2'])){
	   			$wx2 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid2` = '$cjcs[wxopenid2]' AND `uid` != '$uid'");
	   		}else{
	   			$wx2 = '';
	   		}

	   		foreach ($wx1 as $key => $val) {
	   			$wx1_str[] = $val['uid'];
	   		}
	   		foreach ($wx2 as $key => $val) {
	   			$wx2_str[] = $val['uid'];
	   		}
	   		$wx1_uid = implode(',', $wx1_str);
	   		$wx2_uid = implode(',', $wx2_str);

	   		$wx1_data = $this->db->GetList("SELECT distinct `user_id` FROM `@#_activity_lottery` WHERE `user_id` in ($wx1_uid) AND `current_count` LIKE '%$tt%'");
	   		$wx2_data = $this->db->GetList("SELECT distinct `user_id` FROM `@#_activity_lottery` WHERE `user_id` in ($wx2_uid) AND `current_count` LIKE '%$tt%'");

	   		if(count($wx1_data) >= 2 || count($wx2_data) >= 2){
	   			echo 5;exit; //该充值账号参与抽奖已达上线
	   		}

	 		/**end**/

	   		$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_lottery` WHERE `user_id` = '$uid' AND `current_count` LIKE '%$tt%'");
	   		if($arr3 >= 2){
	   			echo 7;exit; //当天抽奖已达上线
	   		}

	   		if(!$data && !$flag){
	   			echo 4;exit;//未进行充值;
	   		}
			// if($data || $flag){
			// 	if($h2 < 72000){ //72000
			// 		echo 1; //活动未开始
			// 	}else if($h2 >= 86399){  //86399
			// 		echo 2; //活动已结束
			// 	}else{
			// 		if($arr > 2 || $arr2 > 2){
			// 			echo 5;
			// 		}else{
			// 			echo 3;
			// 		}
			// 	}
			// }else{
			// 	echo 4; //未进行充值;
			// }
			//|| (count($drr) == 2 && !$drr_s) || (count($drr2) == 2 && !$drr2_s)
			// if(count($drr) > 2 || count($drr2) > 2 || (count($drr) == 2 && !$drr_s) || (count($drr2) == 2 && !$drr2_s)){
	  //  			echo 5;exit; //该充值账号参与抽奖已达上线
	  //  		}
			echo 3;

			
		}else{
			echo 0;
		}
	}

	// public function sure_money(){
	// 	$user = $this->userinfo;
	// 	$user_id = $user['uid'];
	// 	$hour = date("H",time());
	// 	if($hour > 19){
	// 		$current_count = intval(date("Ymd",time()));
	// 		$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
	// 	}else{
	// 		$current_count = intval(date("Ymd",time()))-1;
	// 		$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
	// 	}
	// 	$total = explode(',',$data['amount']);
	// 	$money = 0;
	// 	foreach ($total as $key => $val) {
	// 		$money += $val;
	// 	}
	// 	$time = time();
	// 	if(empty($data)){
	// 		echo 3;  //没有红包
	// 	}else{
	// 		if($data['state'] == 0){
	// 			$this->db->Autocommit_start();
	// 			$t = $this->db->Query("UPDATE `@#_activity_lottery` SET `state` = '1' WHERE `user_id` = '$user_id' AND `current_count`='$current_count'");
	// 			$flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$user_id','1','账户','元宵红包充值','$money','$time')");
	// 			$f = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$user_id')");
	// 			if($t && $flag && $f){
	// 				$this->db->Autocommit_commit();
	// 				echo 1;  //充值成功
	// 			}else{
	// 				$this->db->Autocommit_rollback();
	// 				echo 0;  //充值失败
	// 			}
	// 		}else{
	// 			echo 2; //已经将红包充值
	// 		}
	// 	}
		
	// }


	function test(){
		$user = $this->userinfo;
		if($user){
			$uid = $user['uid'];
			$time = time()-7*86400;
			$data = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `create_time`>'$time'");
			$flag = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `create_time`>'$time'");
			$t_t = time()-1519833600;
	    	$h1 = intval($t_t/86400);
	    	$h2 = $t_t-$h1*86400;

	    	$tt = date('Ymd',time());
	    	$alipay_account = $data['alipay_account'];
	    	$all_ali = $this->db->GetList("SELECT * FROM `@#_alipay_locat` WHERE `alipay_account` = '$alipay_account' AND `status`=1 AND `create_time`>'$time'");
	    
	   		foreach($all_ali as $key => $val){
	   			$brr[] = $val['uid'];
	   		}
	   		$brr = array_unique($brr);
	   		$brr = implode(',', $brr);
	   		$arr = $this->db->GetCount("SELECT * FROM `@#_activity_lottery` WHERE `user_id` in ($brr) AND `current_count` LIKE '%$tt%'");

	   		$wxpay_account = $flag['mobile'];
	   		$all_wx = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `mobile` = '$wxpay_account' AND `status`=1 AND `create_time`>'$time'");
	    
	   		foreach($all_wx as $key => $val){
	   			$brr2[] = $val['uid'];
	   		}
	   		$brr2 = array_unique($brr2);
	   		$brr2 = implode(',', $brr2);
	   		$arr2 = $this->db->GetCount("SELECT * FROM `@#_activity_lottery` WHERE `user_id` in ($brr2) AND `current_count` LIKE '%$tt%'");
	   	
			if($data || $flag){
				if($h2 < 72000){ //72000
					echo 1; //活动未开始
				}else if($h2 >= 86399){  //86399
					echo 2; //活动已结束
				}else{
					if($arr > 2 || $arr2 > 2){
						echo 5;
					}else{
						echo 3;
					}
				}
			}else{
				echo 4; //未进行充值;
			}
			
		}else{
			echo 0;
		}
		//include templates("mobile/huodong","test");
	}

	public function xxxx(){
		$data = $this->db->GetList("select `sid` from `@#_shoplist` where `time` > '1525042800'");
		foreach ($data as $key => $val) {
			$arr[] = $val['sid'];
		}
		$arr = array_unique($arr);
		$data2 = $this->db->GetList("select `sid` from `@#_shoplist` where `time` > '1525042800' and `q_uid` is null");
		foreach ($data2 as $key => $val) {
			$brr[] = $val['sid'];
		}
		$brr[] = '2510269';
		$brr[] = '2510272';
		$brr[] = '2510265';
		$brr[] = '2510270';
		$brr = array_unique($brr);
		$brr = implode(',', $brr);
		$data3 = $this->db->GetList("select `id`,`sid` from `@#_shoplist` where `time` > '1525042800' and `sid` not in ($brr)");
		foreach ($data3 as $key => $val) {
			$crr[$val['id']] = $val['sid'];
		}
		$crr = array_unique($crr);
		foreach ($crr as $key => $val) {
			$drr[] = $key;
		}
		$drr = implode(',', $drr);

		// $data4 = $this->db->GetList("select `sid`,`title` from `@#_shoplist` where `time` > '1525042800' and `time` < '1525057200' and `title` like '%香烟%'");
		// foreach ($data4 as $key => $val) {
		// 	$drr[$val['sid']] = $val['title'];
		// }
		// $drr = array_unique($drr);
		//var_dump(count($brr));
		
		var_dump($drr);
	}
	//好友辅助
	public function renzheng(){
		$uid = $this->userinfo['uid'];
		if(!$uid){
			header("location:".WEB_PATH."/mobile/user/login/");exit;
		}
		$sid = $this->segment(4);
		$shopid = _encrypt($sid,"DECODE");
		$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$shopid'");
		$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data[amount]'");
		if(!$data || !$str){
			_messagemobile("大爷,你走错地方了","/",3);
		}
		$name = get_user_name($data['user_id']);
		include templates("mobile/huodong2","renzheng");
	}


	public function ewm(){
		$uid = $this->userinfo['uid'];
		if(!$uid){
			$data['status'] = 2;
			echo json_encode($data);exit;
		}else{
			$id = $this->segment(4);
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `user_id` = '$uid' AND `id` = '$id'");
			if($data2['amount'] < 5){
				$data['status'] = 3;
				echo json_encode($data);exit;
			}
			if($data2){
				$shopid = _encrypt($id);
				$file = "statics/templates/yungou/images/mobile/yyy/".$id.".jpg";
		      	$host = "http://".$_SERVER['HTTP_HOST'];
		        if(!file_exists($file)){
		            $crr = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text={$host}/index.php/mobile/huodong2/recharge111/".$shopid);
		            file_put_contents($file, $crr);
		     	}
		        $img = "/statics/templates/yungou/images/mobile/yyy/".$id.".jpg";
		        $data['status'] = 1;
		        $data['sid'] = $data2['amount'];
		        $data['uid'] = $id;
		        echo json_encode($data);exit;
		    }else{
		    	$data['status'] = 4;
				echo json_encode($data);exit;
		    } 

	    }
	}

	public function wsdz(){
		$id = $this->segment(4);
		$uid = $this->userinfo['uid'];
		$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$id' AND `user_id` = '$uid' AND `amount` > '4'");
		if(!$data){
			_messagemobile("大爷,你走错地方了","/",3);
		}else{
			$arr = $this->db->GetOne("SELECT m.*,n.name,n.logo FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.id='$id' AND m.dizhi_id = '0' order by m.id desc");
			if(!$arr){
				_messagemobile("地址已填写","/index.php/mobile/huodong2/recharge111",3);
			}else{
				$dizhi = $this->db->GetList("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid'");
			}
		}
		include templates("mobile/huodong2","wsdz");
	}

	public function do_wsdz(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_POST['sid']);
		$did = intval($_POST['did']);
		$data = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$sid' AND `user_id` = '$uid' AND `dizhi_id` = '0'");
		if($data){
			$str = $this->db->Query("UPDATE `@#_activity_lottery` SET `dizhi_id` = '$did' WHERE `id` = '$sid'");
			if($str){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 3;
		}
	}

	public function swapOrderDetail(){
		//$KdApi = System::load_app_class('kdapisearch','api');
		$uid = $this->userinfo['uid'];
		$id = $this->segment(4);
		// $str = $this->db->GetOne("SELECT * FROM `@#_activity_lottery` WHERE `id` = '$id' AND `user_id` = '$uid'");
		// if (!$str) {
		// 	_messagemobile("无此订单号","/index.php/mobile/huodong2/recharge111");exit;
		// }
		// $flag = true;
		// if(empty($str['shipper_code']) || empty($str['company_code'])){
		// 	$flag = false;
		// }else{
		// 	$ShipperCode = $str['shipper_code'];
		// 	$LogisticCode = $str['company_code'];
		// 	$data = json_decode($KdApi->getResult($ShipperCode,$LogisticCode),true);
		// 	$length = count($data['Traces']);
		// }
		$flag = 'huodong';
		$str = $this->db->GetOne("SELECT m.*,n.logo,n.name FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.id = '$id' AND m.user_id = '$uid'");
		$str['thumb'] = $str['logo'];
		$str['title'] = $str['name'];
		$str['create_time'] = $str['created_at'];
		$str['addr_id'] = $str['dizhi_id'];

		if (!$str) {
			_messagemobile("无此订单号","/index.php/mobile/home/exchange?info");exit;
		}
		//判断是否为秒款号
		// $data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$str['dizhi_id']);
		// $data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);
		// $mk = false;
		// if (!empty($data2['bind_phone'])) {
		// 	if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
		// 		$mk = true;
		// 		$status = $str['status'];
		// 		if($status == '已完成'){
		// 			$flag_status = 3;
		// 		}else if($status == '已发货'){
		// 			$flag_status = 2;
		// 		}else{
		// 			$flag_status = 1;
		// 		}
		// 	}
		// }

		if($str['惠享配送']){
			$mk = true;
		}

		if(!empty($str['company_code']) && $str['company'] != '惠享配送'){
			if ($str['company'] == '京东快递') {	
			    $wuliu = $this->express('JD',trim($str['company_code']));
			}else{
			  	$wuliu = $this->express($str['shipper_code'],trim($str['company_code']));
			}
		}
		$place =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$str['dizhi_id']);
		
		include templates("mobile/user","swapOrderDetail");
	}

	public function express($type,$code){
    	$key = 'af2334b002a52daee3ed77d99fd44d06';
    	$code = $code;
  
    	$api = 'https://way.jd.com/fegine/gbexp?n='.$code.'&t='.$type.'&appkey='.$key;
    	$data = $this->send_get($api);
    	$rs = json_decode($data,true);
    	
    	$info = $rs['result']['Traces'];
 		$info = array_reverse($info);
    	return $info;
    }

    //get请求数据
    public function send_get($url) {  
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出,参数为0表示不带头文件，为1表示带头文件
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;
    }

}