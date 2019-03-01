<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class huodong2019 extends memberbase {
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");
	}

	public function recharge_1(){

		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$current_count = intval(date("Ymd",time()));
			$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK($wechat['appid'], $wechat['appsecret']);

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());
        if($hour > 19){
        	$current_count = intval(date("Ymd",time()));
        	$arr = $this->db->GetList("SELECT * FROM `@#_activity_yyy` WHERE `current_count`='$current_count' order by `id` desc");
        }else{
        	$current_count = intval(date("Ymd",time()))-1;
        	$arr = $this->db->GetList("SELECT * FROM `@#_activity_yyy` WHERE `current_count`='$current_count' order by `id` desc");
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
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$shopid'");
			$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data2[amount]'");
			if(!$data2 || !$str){
				_messagemobile("大爷,你走错地方了","/",3);
			}
			$uid = $this->userinfo['uid'];
			if(!$uid){
				header("location:".WEB_PATH."/mobile/user/login/");exit;
			}
			
			$hd_sid = $shopid;
	
			$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$hd_sid'");
			$hd_flag = strstr($hd_data['friends_id'], $uid);
			if(!$hd_flag && $hd_data['user_id'] != $uid && $hd_sid){
				if(empty($hd_data['friends_id'])){
					$friends_id = $uid;
				}else{
					$friends_id = $hd_data['friends_id'].",".$uid;
				}
				$hd_status = $this->db->Query("UPDATE `@#_activity_yyy` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
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
			$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count` = '$current_count'");
			$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$tt = date('Ymd',time());
			$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `uid` = '$user_id' AND `current_count` = '$tt'");
			if(!$arr3){
				$arr3 = 0;
			}
			$sycs = 1-$arr3;
		}else{
			$sycs = 1;
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK('wx4974d1b8daeca416', 'aef2cc68c515b6797420d5197e805677');

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());

        $current_count = intval(date("Ymd",time()));
        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_yyy` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

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
	// 		$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$shopid'");
	// 		$str = $this->db->GetOne("SELECT * FROM `@#_zhuanpan_shop` WHERE `id` = '$data2[amount]'");
	// 		if(!$data2 || !$str){
	// 			_messagemobile("大爷,你走错地方了","/",3);
	// 		}
	// 		$uid = $this->userinfo['uid'];
	// 		if(!$uid){
	// 			header("location:".WEB_PATH."/mobile/user/login/");exit;
	// 		}
			
	// 		$hd_sid = $shopid;
	
	// 		$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$hd_sid'");
	// 		$hd_flag = strstr($hd_data['friends_id'], $uid);
	// 		if(!$hd_flag && $hd_data['user_id'] != $uid && $hd_sid){
	// 			if(empty($hd_data['friends_id'])){
	// 				$friends_id = $uid;
	// 			}else{
	// 				$friends_id = $hd_data['friends_id'].",".$uid;
	// 			}
	// 			$hd_status = $this->db->Query("UPDATE `@#_activity_yyy` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
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
	// 		$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count` LIKE '%$current_count%'");
	// 		$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
	// 		$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
	// 		$tt = date('Ymd',time());
	// 		$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `user_id` = '$user_id' AND `current_count` LIKE '%$tt%'");
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
 //        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_yyy` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

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

	public function recharge(){
		$sid = $this->segment(4);
		$type = intval($_GET['type']);
		if(!empty($sid)){
			if($type == 1){
				$shopid = _encrypt($sid,"DECODE");
				$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$shopid'");
				$str = $this->db->GetOne("SELECT * FROM `@#_yyy_shop` WHERE `id` = '$data2[shiwu_id]'");
				if(!$data2 || !$str){
					_messagemobile("大爷,你走错地方了","/",3);
				}
				$uid = $this->userinfo['uid'];
				if(!$uid){
					header("location:".WEB_PATH."/mobile/user/login/");exit;
				}
				
				$hd_sid = $shopid;
		
				$hd_data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$hd_sid'");
				$hd_flag = strstr($hd_data['friends_id'], $uid);
				if(!$hd_flag && $hd_data['uid'] != $uid && $hd_sid){
					if(empty($hd_data['friends_id'])){
						$friends_id = $uid;
					}else{
						$friends_id = $hd_data['friends_id'].",".$uid;
					}
					$hd_status = $this->db->Query("UPDATE `@#_activity_yyy` SET `friends_id` = '$friends_id',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$hd_sid'");
					if($hd_status){
						$fz_status = 1;
					}else{
						$fz_status = 2;
					}
				}else if($hd_data['uid'] == $uid){
					$fz_status = 4;
				}else{
					
					$fz_status = 3;
				}
			}else{
				$user_id = _encrypt($sid,"DECODE");
				$day_time = date('Ymd',time());
				$data2 = $this->db->GetOne("SELECT * FROM `@#_yyy_fz` WHERE `uid` = '$user_id' AND `day_time` = '$day_time'");
				if(!$data2){
					_messagemobile("大爷,你走错地方了","/",3);
				}
				$uid = $this->userinfo['uid'];
				if(!$uid){
					header("location:".WEB_PATH."/mobile/user/login/");exit;
				}
		
				$hd_flag = strstr($data2['friends_union'], $uid);
				if(!$hd_flag && $user_id != $uid){
					if(empty($data2['friends_union'])){
						$friends_union = $uid;
					}else{
						$friends_union = $data2['friends_union'].",".$uid;
					}
					$id = $data2['id'];
					$hd_status = $this->db->Query("UPDATE `@#_yyy_fz` SET `friends_union` = '$friends_union',`friends_yes` = `friends_yes` + '1' WHERE `id` = '$id'");

					if($hd_status){
						$fz_status = 1;
					}else{
						$fz_status = 2;
					}
				}else if($data2['uid'] == $uid){
					$fz_status = 4;
				}else{
					
					$fz_status = 3;
				}
			}

		}
		$user = $this->userinfo;
		if($user){
			$user_id = $user['uid'];
			$current_count = intval(date("Ymd",time()));
			$data = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count` LIKE '%$current_count%'");
			$flag = $this->db->GetOne("SELECT * FROM `@#_alipay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$flag2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$user_id' AND `status` = '1' order by `create_time` desc");
			$tt = date('Ymd',time());
			$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `uid` = '$user_id' AND `current_count` = '$tt'");
			if(!$arr3){
				$arr3 = 0;
			}
			$sycs = 1-$arr3;
		}else{
			$sycs = 1;
		}

		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK('wx4974d1b8daeca416', 'aef2cc68c515b6797420d5197e805677');

        $signPackage = $jssdk->GetSignPackage();

        $hour = date("H",time());

        $current_count = intval(date("Ymd",time()));
        $arr = $this->db->GetList("SELECT m.*,n.name FROM `@#_activity_yyy` AS m LEFT JOIN `@#_zhuanpan_shop` AS n ON m.amount = n.id WHERE m.current_count LIKE '%$current_count%' order by m.id desc");

        $brr = array();
        foreach($arr as $key=>$val){
        	$uid = $val['user_id'];
        	$detail = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
        	$brr[$key]['username'] = $detail['username'];
        	$brr[$key]['phone'] = substr($detail['mobile'], 0, 3)."****".substr($detail['mobile'], 7);

        	$brr[$key]['shopname'] = $val['name'];
        }
		include templates("mobile/huodong","huafei4");
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
		$code = intval($_POST['code']);
		$flag = $_POST['flag'];

		$user = $this->userinfo;
		$uid = $user['uid'];
		if (!$user) {
			$data['flag'] = "您未登录！";
			$data['status'] = 0;
			echo json_encode($data);exit;
		}//未登录

		if($code != $uid){
			$data['flag'] = "访问出错！";
			$data['status'] = 2;
			echo json_encode($data);exit;
		}
		$day_time = date('Ymd',time());

		$xxx = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `uid` = '$uid' AND `current_count` = '$day_time'");
		//$bbb = $this->db->GetOne("SELECT * FROM `@#_yyy_fz` WHERE `uid` = '$uid' AND `day_time` = '$day_time' AND `friends_yes` >= '3' AND `status` = '0'");
	
		if($xxx > 1){
			$data['flag'] = "当天抽奖已达上线";
			$data['status'] = 3;
			echo json_encode($data);exit;
		}
	
		$yyy = $this->db->GetCount("SELECT count(*) FROM `@#_activity_yyy` WHERE `uid` = '$uid' AND `shiwu_id` != '0'");
		if(strpos($flag,'7') !== false || strpos($flag,'8') !== false || strpos($flag,'9') !== false){
			$yyy_2 = 1;
		}else{
			$yyy_2 = 0;
		}

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
		if(($yyy > 0 || $yyy_2 > 0) && $s > 6){
			$s = 6;
		}
		$str = $this->db->GetOne("SELECT `shopname` FROM `@#_yyy_shop` WHERE `id` = '$s'");
		$data['shopname'] = $str['shopname'];
		$data['status'] = 1;
		$data['money'] = $s;

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
			$time = strtotime("-5 days");
			$brr = $this->db->GetList("SELECT * FROM `@#_activity_yyy` WHERE `uid` = '$user_id' AND `created_at` > '$time' order by `id` desc");
			foreach ($brr as $key => $val) {
				$amount = explode(',', $val['fufen_union']);
				$brr[$key]['day_time'] = date('Y-m-d H:i:s',$val['created_at']);
				if($val['friends_num'] <= $val['friends_yes']){
					if($val['dizhi_id'] != 0){
						$brr[$key]['sw'] = 2;
					}else{
						$brr[$key]['sw'] = 1;
					}
				}else{
					$brr[$key]['sw'] = 0;
				}
				foreach ($amount as $k => $v) {
					$xxx = $this->db->GetOne("SELECT `shopname` FROM `@#_yyy_shop` WHERE `id` = '$v'");
					$brr[$key]['shopname'][$k] = $xxx['shopname'];
					$brr[$key]['type'][$k] = $k+1;
				}
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
		$user_id = $this->userinfo['uid'];
		if($uid != $user_id){
			//echo 0;exit;
		}
		$current_count = intval(date("Ymd",time()));
		$time = time();
		$this->db->Autocommit_start();

		$xxx = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `uid` = 'uid' AND `current_count` = '$current_count'");
		if($xxx <= 1){
		
			$total = explode(',',$amount);
			$shiwu_id = 0;	 //获得实物id
			$fufen_tj = 0;   //统计中奖福分总和
			$friends_num = 0;  //需好友辅助个数
			foreach ($total as $key => $val) {
				if($val > 6){
					$shiwu_id = $val;
					$friends = $this->db->GetOne("SELECT `friends` FROM `@#_yyy_shop` WHERE `id` = '$val'");
					$friends_num = $friends['friends'];
				}else{
					$fufen_flag = $this->db->GetOne("SELECT `fufen` FROM `@#_yyy_shop` WHERE `id` = '$val'");
					$fufen_tj += $fufen_flag['fufen'];
				}
			}

			$x = $this->db->Query("INSERT INTO `@#_activity_yyy` (fufen_union,shiwu_id,uid,current_count,created_at,friends_num) VALUE ('$amount','$shiwu_id','$uid','$current_count','$time','$friends_num')");

			$flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$uid','1','积分','摇一摇活动充值','$fufen_tj','$time')");
			$f = $this->db->Query("UPDATE `@#_member` SET `score` = `score` + '$fufen_tj' where (`uid` = '$uid')");
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
		//echo 6;exit;  //活动结束
		$user = $this->userinfo;
		if($user){
			$uid = $user['uid'];
			$time = time()-3*86400;
			$data = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = '$uid' AND `status` = '已付款' AND `pay_type` in ('微信公众号','通过网络充值') AND `time` > '$time' order by `time` desc");
			$flag = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `create_time`>'$time' order by `create_time` desc");
			$tt = date('Ymd',time());

	   		$arr3 = $this->db->GetCount("SELECT COUNT(*) FROM `@#_activity_yyy` WHERE `uid` = '$uid' AND `current_count` = '$tt'");
	   		if($arr3 >= 1){
	   			echo 7;exit; //当天抽奖已达上线
	   		}
			if($data || $flag){
				// if($h2 < 72000){ //72000
				// 	echo 1; //活动未开始
				// }else if($h2 >= 86399){  //86399
				// 	echo 2; //活动已结束
				// }else{			
				// 	echo 3;
				// }
				echo 3;
			}else{
				echo 4; //未进行充值;
			}
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
	// 		$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
	// 	}else{
	// 		$current_count = intval(date("Ymd",time()))-1;
	// 		$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `user_id`='$user_id' AND `current_count`='$current_count'");
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
	// 			$t = $this->db->Query("UPDATE `@#_activity_yyy` SET `state` = '1' WHERE `user_id` = '$user_id' AND `current_count`='$current_count'");
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
	   		$arr = $this->db->GetCount("SELECT * FROM `@#_activity_yyy` WHERE `user_id` in ($brr) AND `current_count` LIKE '%$tt%'");

	   		$wxpay_account = $flag['mobile'];
	   		$all_wx = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `mobile` = '$wxpay_account' AND `status`=1 AND `create_time`>'$time'");
	    
	   		foreach($all_wx as $key => $val){
	   			$brr2[] = $val['uid'];
	   		}
	   		$brr2 = array_unique($brr2);
	   		$brr2 = implode(',', $brr2);
	   		$arr2 = $this->db->GetCount("SELECT * FROM `@#_activity_yyy` WHERE `user_id` in ($brr2) AND `current_count` LIKE '%$tt%'");
	   	
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
		$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$shopid'");
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
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `user_id` = '$uid' AND `id` = '$id'");
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

	public function ewm_yyy(){
		$uid = $this->userinfo['uid'];
		if(!$uid){
			$data['status'] = 2;
			echo json_encode($data);exit;
		}else{
			$id = $this->segment(4);
			$data2 = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `uid` = '$uid' AND `id` = '$id'");
			if($data2['shiwu_id'] < 7){
				$data['status'] = 3;
				echo json_encode($data);exit;
			}
			if($data2){
				$shopid = _encrypt($id);
				$file = "statics/templates/yungou/images/mobile/yyy/".$id.".jpg";
		      	$host = "http://".$_SERVER['HTTP_HOST'];
		        if(!file_exists($file)){
		            $crr = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text={$host}/index.php/mobile/huodong2019/recharge/".$shopid."?type=1");
		            file_put_contents($file, $crr);
		     	}
		        $img = "/statics/templates/yungou/images/mobile/yyy/".$id.".jpg";
		        $data['status'] = 1;
		        $data['sid'] = $data2['shiwu_id'];
		        $data['uid'] = $id;
		        echo json_encode($data);exit;
		    }else{
		    	$data['status'] = 4;
				echo json_encode($data);exit;
		    } 

	    }
	}

	public function ewm2(){
		$uid = $this->userinfo['uid'];
		if(!$uid){
			$data['status'] = 2;
			echo json_encode($data);exit;
		}else{
			
			$shopid = _encrypt($uid);
			$file = "statics/templates/yungou/images/mobile/yao/".$uid.".jpg";
	      	$host = "http://".$_SERVER['HTTP_HOST'];
	        if(!file_exists($file)){
	            $crr = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text={$host}/index.php/mobile/huodong2019/recharge/".$shopid);
	            file_put_contents($file, $crr);
	     	}
	     	$day_time = date("Ymd",time());
	     	$fz = $this->db->GetOne("SELECT * FROM `@#_yyy_fz` WHERE `uid` = '$uid' AND `day_time` = '$day_time'");
	     	if(!$fz){
	     		$this->db->Query("INSERT INTO `@#_yyy_fz` SET `uid` = '$uid',`day_time` = '$day_time'");
	     	}
	        $img = "/statics/templates/yungou/images/mobile/yao/".$uid.".jpg";
	        $data['status'] = 1;
	        $data['sid'] = 7;
	        $data['uid'] = $uid;
	        echo json_encode($data);exit;

	    }
	}

	public function wsdz(){
		$id = $this->segment(4);
		$uid = $this->userinfo['uid'];
		$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$id' AND `uid` = '$uid' AND `shiwu_id` > '6'");
		if(!$data){
			_messagemobile("大爷,你走错地方了","/",3);
		}else{
			$arr = $this->db->GetOne("SELECT m.*,n.shopname,n.img FROM `@#_activity_yyy` AS m LEFT JOIN `@#_yyy_shop` AS n ON m.shiwu_id = n.id WHERE m.id='$id' AND m.dizhi_id = '0' order by m.id desc");
			if(!$arr){
				_messagemobile("地址已填写","/index.php/mobile/huodong2019/recharge",3);
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

		$data = $this->db->GetOne("SELECT * FROM `@#_activity_yyy` WHERE `id` = '$sid' AND `uid` = '$uid' AND `dizhi_id` = '0'");
		$data_s = $this->db->GetOne("SELECT * FROM `@#_yyy_shop` WHERE `id` = '$data[shiwu_id]'");

		if($data){
			$data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$did);
			$data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);
			if (!empty($data2['bind_phone'])) {
				if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
					echo 2;exit();
				}
			}

			$time = time();
			$code = $uid.time();
			$q = $this->db->Query("UPDATE `@#_activity_yyy` SET `dizhi_id` = '$did',`dizhi_time` = '$time',`code` = '$code',`wuliu` = '未发货' WHERE `id` = '$sid'");

			
			if ($q) {
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 3;
		}
	}

	public function swapOrderDetail(){
		$uid = $this->userinfo['uid'];
		$id = $this->segment(4);
		
		$flag = 'huodong';
		$str = $this->db->GetOne("SELECT m.*,n.img,n.shopname FROM `@#_activity_yyy` AS m LEFT JOIN `@#_yyy_shop` AS n ON m.shiwu_id = n.id WHERE m.id = '$id' AND m.uid = '$uid'");

		$str['thumb'] = $str['img'];
		$str['title'] = $str['shopname'];
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