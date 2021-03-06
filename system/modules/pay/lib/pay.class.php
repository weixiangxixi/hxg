<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_fun("pay","pay");
System::load_sys_fun("user");
System::load_sys_fun('global');
System::load_app_class("tocode","pay",'no');
class pay {
	private $db;
	private $members;		//会员信息
	private $MoenyCount; 	//商品总金额
	private $shops; 		//商品信息
	private $pay_type;		//支付类型
	private $fukuan_type;	//付款类型 买商品 充值
	private $dingdan_query = true;	//订单的	mysql_qurey 结果
	public $pay_type_bank = false;

	public $scookie = null;
	public $fufen = 0;
	public $fufen_to_money = 0;
	public $dyq_to_money = 0;
	public $dhq_id = 0;


	//初始化类数据
	//$addmoney 充值金额
	public function init($uid=null,$pay_type=null,$fukuan_type='',$addmoney=''){
		$this->db=System::load_sys_class('model');
      	
      	$sb = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid'");
        if($sb['auto_user']==0){
          $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$sb[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
          if(empty($cc)){$cc['m'] = 0;}
          if($sb['money'] > $cc['m'] + 100){
            _setcookie("uid","",time()-3600);
            _setcookie("ushell","",time()-3600);
            session_start();
			unset($_SESSION['gwc']);
            return false;exit();
          } 	
        }
      
		$this->db->Autocommit_start();
		$this->members = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' for update");

		if($this->pay_type_bank){
			$pay_class = $this->pay_type_bank;
			$this->pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = '$pay_class' and `pay_start` = '1'");
			$this->pay_type['pay_bank'] = $pay_type;

		}
		if(is_numeric($pay_type)){
			$this->pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_id` = '$pay_type' and `pay_start` = '1'");
			$this->pay_type['pay_bank'] = 'DEFAULT';
		}

		$this->fukuan_type=$fukuan_type;
		if($fukuan_type=='go_record'){
			return $this->go_record();
		}
		if($fukuan_type=='addmoney_record'){
			return $this->addmoney_record($addmoney);
		}
		return false;
	}

	//初始化类数据
	//$addmoney 充值金额
	public function init_test($uid=null,$pay_type=null,$fukuan_type='',$addmoney=''){
		$this->db=System::load_sys_class('model');
      	
      	$sb = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid'");
        if($sb['auto_user']==0){
          $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$sb[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
          if(empty($cc)){$cc['m'] = 0;}
          if($sb['money'] > $cc['m'] + 100){
            _setcookie("uid","",time()-3600);
            _setcookie("ushell","",time()-3600);
            session_start();
			unset($_SESSION['gwc']);
            return false;exit();
          } 	
        }
      
		$this->db->Autocommit_start();
		$this->members = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' for update");

		if($this->pay_type_bank){
			$pay_class = $this->pay_type_bank;
			$this->pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = '$pay_class' and `pay_start` = '1'");
			$this->pay_type['pay_bank'] = $pay_type;

		}
		if(is_numeric($pay_type)){
			$this->pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_id` = '$pay_type' and `pay_start` = '1'");
			$this->pay_type['pay_bank'] = 'DEFAULT';
		}

		$this->fukuan_type=$fukuan_type;
		if($fukuan_type=='go_record'){
			return $this->go_record_test();
		}
		if($fukuan_type=='addmoney_record'){
			return $this->addmoney_record($addmoney);
		}
		return false;
	}

	//买商品
	private function go_record(){

		if(is_array($this->scookie)){
			$Cartlist = $this->scookie;
		}else{
			//$Cartlist=json_decode(stripslashes(_getcookie('Cartlist')),true);
			session_start();
			$Cartlist = json_decode($_SESSION['gwc'],true);
		}


		$shopids='';			//商品ID
		if(is_array($Cartlist)){
			foreach($Cartlist as $key => $val){
				$shopids.=intval($key).',';
			}
			$shopids=str_replace(',0','',$shopids);
			$shopids=trim($shopids,',');

		}

		$shoplist=array();		//商品信息
		if($shopids!=NULL){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
		}else{
			$this->db->Autocommit_rollback();
			return '购物车内没有商品!';
		}
		foreach ($shoplist as $key => $val) {
			if($val['cateid'] == '177'){
			    $uid = $this->members['uid'];
			    $shopid = $val['id'];

			    //用户关联号
		   		$cjcs = $this->db->GetOne("SELECT `wxopenid1` FROM `@#_member` WHERE `uid` = '$uid'");
		   		//微信1关联号
		   		if(!empty($cjcs['wxopenid1'])){
		   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
		   		}else{
		   			$wx1 = '';
		   		}

		   		foreach ($wx1 as $k => $v) {
		   			$wx1_str[] = $v['uid'];
		   		}
		   		
		   		$wx1_uid = implode(',', $wx1_str);

		   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$shopid'");

		   		if(count($wx1_data) >= 2){   //关联用户参与多余两个
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

		   		$sid = $val['sid'];
		   		$time_start = strtotime(date("Ymd",time()));
		   		$time_start2 = strtotime(date("Ymd",time()))-20000;
		   		$time_end = $time_start + 86400;
		   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");
		   		foreach ($xgqs as $k => $v) {
		   			$qs[] = $v['id'];
		   		}
		   		$qs_id = implode(',', $qs);
		   		unset($qs);
		   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
		   		$flag = false;
		   		foreach ($qs_bh as $k => $v) {
		   			if($shopid == $v['shopid']){
		   				$flag = true;
	
		   			}
		   		}
		   		if(count($qs_bh) >= 5 && $flag == false){
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

		   		$time = time() - strtotime(date("Ymd",time()));
		   		$time1 = 10*60*60;
		   		$time2 = 24*60*60;
		   		if($time < $time1 || $time > $time2){
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

			  	$xg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$shopid'");
			  	if(empty($xg['m'])){
			  		$xg['m'] = 0;
			  	}
			  	if($xg['m'] >= $val['str4']){
			  		$Cartlist[$shopid]['num'] = 0;
			  		unset($shoplist[$key]);
			  		continue;
			  	}
			  	if(($xg['m'] + $Cartlist[$shopid]['num']) > $val['str4']){
			  		if($xg['m'] < $val['str4']){
			  			$Cartlist[$shopid]['num'] = $val['str4'] - $xg['m'];
			  		}else{
			  			$Cartlist[$shopid]['num'] = 0;
			  			unset($shoplist[$key]);
			  			continue;
			  		}
			  	}
			}
		}
		$MoenyCount= 0;
		$shopguoqi = 0;
		if(count($shoplist)>=1){
			$scookies_arr = array();
			$scookies_arr['MoenyCount'] = 0;
			foreach($Cartlist as $key => $val){
						$key=intval($key);
						if(isset($shoplist[$key]) && $shoplist[$key]['shenyurenshu'] != 0){
							if(($shoplist[$key]['xsjx_time'] != '0') && $shoplist[$key]['xsjx_time'] < time()){
								unset($shoplist[$key]);
								$shopguoqi = 1;
								continue;
							}
							$shoplist[$key]['cart_gorenci']=$val['num'] ? $val['num'] : 1;
							if($shoplist[$key]['cart_gorenci'] >= $shoplist[$key]['shenyurenshu']){
								$shoplist[$key]['cart_gorenci'] = $shoplist[$key]['shenyurenshu'];
							}
							$MoenyCount+=$shoplist[$key]['yunjiage']*$shoplist[$key]['cart_gorenci'];
							$shoplist[$key]['cart_xiaoji']=substr(sprintf("%.3f",$shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci']),0,-1);
							$shoplist[$key]['cart_shenyu']=$shoplist[$key]['zongrenshu']-$shoplist[$key]['canyurenshu'];
							$scookies_arr[$key]['shenyu'] = $shoplist[$key]['cart_shenyu'];
							$scookies_arr[$key]['num'] = $shoplist[$key]['cart_gorenci'];
							$scookies_arr[$key]['money'] = intval($shoplist[$key]['yunjiage']);
							$scookies_arr['MoenyCount'] += intval($shoplist[$key]['cart_xiaoji']);
						}else{
							unset($shoplist[$key]);
						}
			}
			if(count($shoplist) < 1){
				$scookies_arr = '0';
				$this->db->Autocommit_rollback();
				if($shopguoqi){
					return '限时揭晓过期商品不能购买!';
				}else{
					return '购物车里没有商品!';
				}
			}
		}else{
			$scookies_arr = '0';
			$this->db->Autocommit_rollback();
			return '本期商品已销售完毕';
		}


		$this->MoenyCount=substr(sprintf("%.3f",$MoenyCount),0,-1);

		/**
		*	最多能抵扣多少钱
		**/
		if($this->fufen){
			if($this->fufen >= $this->members['score']){
				$this->fufen = $this->members['score'];
			}
			$fufen = System::load_app_config("user_fufen",'','member');
			if($fufen['fufen_yuan']){
				$this->fufen_to_money  = intval($this->fufen / $fufen['fufen_yuan']);
				if($this->fufen_to_money >= $this->MoenyCount){
					$this->fufen_to_money = $this->MoenyCount;
					$this->fufen = $this->fufen_to_money * $fufen['fufen_yuan'];
				}
			}else{
				$this->fufen_to_money = 0;
				$this->fufen = 0;
			}
		}else{
			$this->fufen_to_money = 0;
			$this->fufen = 0;
		}

		//总支付价格
		$this->MoenyCount = $this->MoenyCount - $this->fufen_to_money;
		$this->shoplist=$shoplist;
		$this->scookies_arr = $scookies_arr;
		return 'ok';
	}

	//买商品
	private function go_record_test(){

		if(is_array($this->scookie)){
			$Cartlist = $this->scookie;
		}else{
			//$Cartlist=json_decode(stripslashes(_getcookie('Cartlist')),true);
			session_start();
			$Cartlist = json_decode($_SESSION['gwc'],true);
		}


		$shopids='';			//商品ID
		if(is_array($Cartlist)){
			foreach($Cartlist as $key => $val){
				$shopids.=intval($key).',';
			}
			$shopids=str_replace(',0','',$shopids);
			$shopids=trim($shopids,',');

		}

		$shoplist=array();		//商品信息
		if($shopids!=NULL){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
		}else{
			$this->db->Autocommit_rollback();
			return '购物车内没有商品!';
		}
		foreach ($shoplist as $key => $val) {
			if($val['cateid'] == '177'){
			    $uid = $this->members['uid'];
			    $shopid = $val['id'];

			    //用户关联号
		   		$cjcs = $this->db->GetOne("SELECT `wxopenid1` FROM `@#_member` WHERE `uid` = '$uid'");
		   		//微信1关联号
		   		if(!empty($cjcs['wxopenid1'])){
		   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
		   		}else{
		   			$wx1 = '';
		   		}

		   		foreach ($wx1 as $k => $v) {
		   			$wx1_str[] = $v['uid'];
		   		}
		   		
		   		$wx1_uid = implode(',', $wx1_str);

		   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$shopid'");

		   		if(count($wx1_data) >= 2){   //关联用户参与多余两个
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

		   		$sid = $val['sid'];
		   		$time_start = strtotime(date("Ymd",time()));
		   		$time_start2 = strtotime(date("Ymd",time()))-20000;
		   		$time_end = $time_start + 86400;
		   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");
		   		foreach ($xgqs as $k => $v) {
		   			$qs[] = $v['id'];
		   		}
		   		$qs_id = implode(',', $qs);
		   		unset($qs);
		   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
		   		$flag = false;
		   		foreach ($qs_bh as $k => $v) {
		   			if($shopid == $v['shopid']){
		   				$flag = true;
	
		   			}
		   		}
		   		if(count($qs_bh) >= 5 && $flag == false){
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

		   		$time = time() - strtotime(date("Ymd",time()));
		   		$time1 = 10*60*60;
		   		$time2 = 24*60*60;
		   		if($time < $time1 || $time > $time2){
		   			$Cartlist[$shopid]['num'] = 0;
		   			unset($shoplist[$key]);
		  	  		continue;
		   		}

			  	$xg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$shopid'");
			  	if(empty($xg['m'])){
			  		$xg['m'] = 0;
			  	}
			  	if($xg['m'] >= $val['str4']){
			  		$Cartlist[$shopid]['num'] = 0;
			  		unset($shoplist[$key]);
			  		continue;
			  	}
			  	if(($xg['m'] + $Cartlist[$shopid]['num']) > $val['str4']){
			  		if($xg['m'] < $val['str4']){
			  			$Cartlist[$shopid]['num'] = $val['str4'] - $xg['m'];
			  		}else{
			  			$Cartlist[$shopid]['num'] = 0;
			  			unset($shoplist[$key]);
			  			continue;
			  		}
			  	}
			}
		}
		$MoenyCount= 0;
		$shopguoqi = 0;
		if(count($shoplist)>=1){
			$scookies_arr = array();
			$scookies_arr['MoenyCount'] = 0;
			foreach($Cartlist as $key => $val){
						$key=intval($key);
						if(isset($shoplist[$key]) && $shoplist[$key]['shenyurenshu'] != 0){
							if(($shoplist[$key]['xsjx_time'] != '0') && $shoplist[$key]['xsjx_time'] < time()){
								unset($shoplist[$key]);
								$shopguoqi = 1;
								continue;
							}
							$shoplist[$key]['cart_gorenci']=$val['num'] ? $val['num'] : 1;
							if($shoplist[$key]['cart_gorenci'] >= $shoplist[$key]['shenyurenshu']){
								$shoplist[$key]['cart_gorenci'] = $shoplist[$key]['shenyurenshu'];
							}
							$MoenyCount+=$shoplist[$key]['yunjiage']*$shoplist[$key]['cart_gorenci'];
							$shoplist[$key]['cart_xiaoji']=substr(sprintf("%.3f",$shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci']),0,-1);
							$shoplist[$key]['cart_shenyu']=$shoplist[$key]['zongrenshu']-$shoplist[$key]['canyurenshu'];
							$scookies_arr[$key]['shenyu'] = $shoplist[$key]['cart_shenyu'];
							$scookies_arr[$key]['num'] = $shoplist[$key]['cart_gorenci'];
							$scookies_arr[$key]['money'] = intval($shoplist[$key]['yunjiage']);
							$scookies_arr['MoenyCount'] += intval($shoplist[$key]['cart_xiaoji']);
						}else{
							unset($shoplist[$key]);
						}
			}
			if(count($shoplist) < 1){
				$scookies_arr = '0';
				$this->db->Autocommit_rollback();
				if($shopguoqi){
					return '限时揭晓过期商品不能购买!';
				}else{
					return '购物车里没有商品!';
				}
			}
		}else{
			$scookies_arr = '0';
			$this->db->Autocommit_rollback();
			return '本期商品已销售完毕';
		}


		$this->MoenyCount=substr(sprintf("%.3f",$MoenyCount),0,-1);

		/**
		*	最多能抵扣多少钱
		**/
		if($this->fufen){
			if($this->fufen >= $this->members['score']){
				$this->fufen = $this->members['score'];
			}
			$fufen = System::load_app_config("user_fufen",'','member');
			if($fufen['fufen_yuan']){
				$this->fufen_to_money  = intval($this->fufen / $fufen['fufen_yuan']);
				if($this->fufen_to_money >= $this->MoenyCount){
					$this->fufen_to_money = $this->MoenyCount;
					$this->fufen = $this->fufen_to_money * $fufen['fufen_yuan'];
				}
			}else{
				$this->fufen_to_money = 0;
				$this->fufen = 0;
			}
		}else{
			$this->fufen_to_money = 0;
			$this->fufen = 0;
		}

		$this->dyq_to_money = 10;
		//总支付价格
		$this->MoenyCount = $this->MoenyCount - $this->fufen_to_money;
		$this->shoplist=$shoplist;
		$this->scookies_arr = $scookies_arr;
		return 'ok';
	}

	
	/* 充值 data 其他数据 */
	private function addmoney_record($money=null,$data=null){

		$uid=$this->members['uid'];
      
      	$sb = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid'");
        if($sb['auto_user']==0){
            $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$sb[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100){
                _setcookie("uid","",time()-3600);
                _setcookie("ushell","",time()-3600);
              	session_start();
				unset($_SESSION['gwc']);
                return false;exit();
            } 	
        }
      
		$dingdancode = pay_get_dingdan_code('C');		//订单号
		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		$pay_type = $this->pay_type['pay_name'];
		$time = time();
		if(!empty($data)){
			$scookies = $data;
		}else{
			$scookies = '0';
		}
		$score = $this->fufen;
      	$song = rand(1,9) * 0.01;
		$query = $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`,`smoney`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money','$song', '$pay_type','未付款', '$time','$score','$scookies')");
		if($query){
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
			return false;
		}
		$_SESSION['out_trade_no'] = $dingdancode;
		//$_SESSION['openid'] = $openid;
		//$_SESSION['total_fee'] = ($money - $song) * 100;
      	$_SESSION['total_fee'] = $money * 100;
        
      	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { 
        	//header("Location:/index.php/mobile/wechat111/paygo/"); exit;
          
            //header("Location:/api/cnmobi/paygo");exit;
          
            $wepay1 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '3'");
            $wepay2 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '4'");
			$wepay3 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '5'");
            $wepay4 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '7'");
			
            if($wepay1['status']=='1'){
                header("Location:/index.php/mobile/unionpay/paygo/");exit;
            }
          	if($wepay4['status']=='1'){
                header("Location:/index.php/mobile/wechat111/paygo/");exit;
            }
          	if($wepay3['status']=='1'){
                header("Location:/index.php/mobile/companypay/paygo/");exit;
            }
            if($wepay2['status']=='1'){
                header("Location:/index.php/mobile/yaoqianshu/paygo/");exit;
            }
          	
    	}else{
        	//header("Location:/mobile/alipay/paygo");
        }
		
      
		$pay_type = $this->pay_type;
		$paydb = System::load_app_class($pay_type['pay_class'],'pay');
		$pay_type['pay_key'] = unserialize($pay_type['pay_key']);

		$config=array();
		$config['id'] = $pay_type['pay_key']['id']['val'];			//支付合作ID
		$config['key'] = $pay_type['pay_key']['key']['val'];		//支付KEY

		$config['shouname'] = _cfg('web_name');						//收款方
		$config['title'] = _cfg('web_name');						//付款项目
		$config['money'] = $money;									//付款金额$money
		$config['type']  = $pay_type['pay_type'];					//支付方式：	即时到帐1   中介担保2


		$config['ReturnUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay_type['pay_class'].'_url/qiantai/';	//前台回调
		$config['NotifyUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay_type['pay_class'].'_url/houtai/';		//后台回调


		$config['pay_bank'] = $this->pay_type['pay_bank'];

		$config['code'] = $dingdancode;
		$config['pay_type_data'] = $pay_type['pay_key'];

		$paydb->config($config);
		$paydb->send_pay();


		return true;
	}
	/* 充值 data 其他数据 */
	private function addmoney_record1($money=null,$data=null){
		session_start();
		$uid=$this->members['uid'];
      
      	$sb = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid'");
        if($sb['auto_user']==0){
            $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$sb[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100){
                _setcookie("uid","",time()-3600);
                _setcookie("ushell","",time()-3600);
              	session_start();
				unset($_SESSION['gwc']);
                return false;exit();
            } 	
        }
      
		$dingdancode = pay_get_dingdan_code('C');		//订单号

		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		$pay_type = $this->pay_type['pay_name'];
		$time = time();
		if(!empty($data)){
			$scookies = $data;
		}else{
			$scookies = '0';
		}
		$score = $this->fufen;
		$query = $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`uid`, `code`, `money`, `pay_type`, `status`,`time`,`score`,`scookies`) VALUES ('$uid', '$dingdancode', '$money', '$pay_type','未付款', '$time','$score','$scookies')");

		if($query){
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
			return false;
		}
		$_SESSION['out_trade_no'] = $dingdancode;
		//$_SESSION['openid'] = $openid;
		$_SESSION['total_fee'] = $money * 100;
		header("Location:/ext_pay/openid.php");
		exit();
		// $pay_type = $this->pay_type;
		// $paydb = System::load_app_class($pay_type['pay_class'],'pay');
		// $pay_type['pay_key'] = unserialize($pay_type['pay_key']);

		// $config=array();
		// $config['id'] = $pay_type['pay_key']['id']['val'];			//支付合作ID
		// $config['key'] = $pay_type['pay_key']['key']['val'];		//支付KEY

		// $config['shouname'] = _cfg('web_name');						//收款方
		// $config['title'] = _cfg('web_name');						//付款项目
		// $config['money'] = $money;									//付款金额$money
		// $config['type']  = $pay_type['pay_type'];					//支付方式：	即时到帐1   中介担保2


		// $config['ReturnUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay_type['pay_class'].'_url/qiantai/';	//前台回调
		// $config['NotifyUrl']  = G_WEB_PATH.'/index.php/pay/'.$pay_type['pay_class'].'_url/houtai/';		//后台回调


		// $config['pay_bank'] = $this->pay_type['pay_bank'];

		// $config['code'] = $dingdancode;
		// $config['pay_type_data'] = $pay_type['pay_key'];

		// $paydb->config($config);
		// $paydb->send_pay();


		return true;
	}
	//生成订单
	private function set_dingdan($pay_type='',$dingdanzhui=''){
			$uid=$this->members['uid'];
			$uphoto = $this->members['img'];
			$username = get_user_name($this->members);
	
			$insert_html='';
			$this->dingdancode = $dingdancode= pay_get_dingdan_code($dingdanzhui);		//订单号

			if(count($this->shoplist)>1){
					$dingdancode_tmp = 1;	//多个商品相同订单
			}else{
					$dingdancode_tmp = 0;	//单独商品订单
			}

			$ip = _get_ip();

			$user_type = 0;
			$user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid' LIMIT 1");
			$user_type = $user['auto_user'];
			
			//订单时间
			
			$this->MoenyCount=0;
			foreach($this->shoplist as $key=>$shop){
					$time=sprintf("%.3f",microtime(true));
					$ret_data = array();
					pay_get_shop_codes($shop['cart_gorenci'],$shop,$ret_data);
					$this->dingdan_query = $ret_data['query'];
					if(!$ret_data['query'])$this->dingdan_query = false;
					$codes = $ret_data['user_code'];									//得到的购买码
					$codes_len= intval($ret_data['user_code_len']);						//得到购买码个数
					$money=$codes_len * $shop['yunjiage'];								//单条商品的总价格
					$this->MoenyCount += $money;										//总价格
					$status='未付款,未发货,未完成';
					$shop['canyurenshu'] = intval($shop['canyurenshu']) + $codes_len;
					$shop['goods_count_num'] = $codes_len;
					$this->shoplist[$key] = $shop;
					if($codes_len){
						$insert_html.="('$dingdancode','$dingdancode_tmp','$uid','$username','$uphoto','$shop[id]','$shop[title]','$shop[qishu]','$codes_len','$money','$codes','$pay_type','$ip','$status','$time','$user_type'),";
					}
			}
			
			// if (!empty($this->members['mobile'])) {
			// 	System::load_sys_fun("send");
			// 	send_mobile_lottery($this->members['mobile']);
			// }
			
			$sql="INSERT INTO `@#_member_go_record` (`code`,`code_tmp`,`uid`,`username`,`uphoto`,`shopid`,`shopname`,`shopqishu`,`gonumber`,`moneycount`,`goucode`,`pay_type`,`ip`,`status`,`time`,`type`) VALUES ";
			$sql.=trim($insert_html,',');
			if(empty($insert_html)){
				return false;
			}
			//$this->db->Query("set global max_allowed_packet = 2*1024*1024*10");
			return $this->db->Query($sql);
	}

	
	
	/**
	*	开始支付
	**/
	public function go_pay($pay_checkbox,$pay_type_flag){
		if(is_array($this->scookies_arr)){
			$scookie = serialize($this->scookies_arr);
		}else{
			$scookie= '0';
		}
		if(!$pay_type_flag){
			if($this->members['money'] >= $this->MoenyCount){
				$uid=$this->members['uid'];
				$pay_1 =  $this->pay_bag();
				if(!$pay_1){return $pay_1;}
				$dingdancode=$this->dingdancode;
				$pay_2 = pay_go_fund($this->goods_count_num);
				$pay_3 = pay_go_yongjin($uid,$dingdancode);
				return $pay_1;
			}
		}else{
			$this->MoenyCount;
			return $this->addmoney_record($this->MoenyCount,$scookie);
		}
		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		
		if($pay_checkbox){
			$money = $this->MoenyCount - $this->members['money'];
			return $this->addmoney_record($money,$scookie);
		}else{
			//全额支付
			$this->MoenyCount;
			return $this->addmoney_record($this->MoenyCount,$scookie);
		}
		exit;
	}

	/**
	*	开始支付
	**/
	public function go_pay_test($pay_checkbox,$pay_type_flag,$dyq_id){
		$this->dyq_id = $dyq_id;

		if(is_array($this->scookies_arr)){
			$scookie = serialize($this->scookies_arr);
		}else{
			$scookie= '0';
		}
		if(!$pay_type_flag){
			if(($this->members['money'] + $this->dyq_to_money) >= $this->MoenyCount){
				$uid=$this->members['uid'];

				$pay_1 =  $this->pay_bag_test();
		
				if(!$pay_1){return $pay_1;}
				$dingdancode=$this->dingdancode;
				$pay_2 = pay_go_fund($this->goods_count_num);
				$pay_3 = pay_go_yongjin($uid,$dingdancode);
				return $pay_1;
			}
		}else{
			$this->MoenyCount;
			return $this->addmoney_record($this->MoenyCount,$scookie);
		}
		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		
		if($pay_checkbox){
			$money = $this->MoenyCount - $this->members['money'];
			return $this->addmoney_record($money,$scookie);
		}else{
			//全额支付
			$this->MoenyCount;
			return $this->addmoney_record($this->MoenyCount,$scookie);
		}
		exit;
	}

	/**
	*	开始支付
	**/
	public function go_pay1($pay_checkbox){
		if($this->members['money'] >= $this->MoenyCount){
			$uid=$this->members['uid'];
			$pay_1 =  $this->pay_bag();
			if(!$pay_1){return $pay_1;}
			$dingdancode=$this->dingdancode;
			$pay_2 = pay_go_fund($this->goods_count_num);
			$pay_3 = pay_go_yongjin($uid,$dingdancode);
			return $pay_1;
		}
		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		if(is_array($this->scookies_arr)){
			$scookie = serialize($this->scookies_arr);
		}else{
			$scookie= '0';
		} 

		if($pay_checkbox){
			$money = $this->MoenyCount - $this->members['money'];
			return $this->addmoney_record1($money,$scookie);
		}else{
			//全额支付
			$this->MoenyCount;
			return $this->addmoney_record1($this->MoenyCount,$scookie);
		}
		exit;
	}

	//账户里支付
	private function pay_bag(){
		$time=time();
		$uid=$this->members['uid'];
		$fufen = System::load_app_config("user_fufen",'','member');

		$query_1 = $this->set_dingdan('账户','A');
		/*会员购买过账户剩余金额*/
		$Money = $this->members['money'] - $this->MoenyCount + $this->fufen_to_money;
		$query_fufen = true;
		$pay_zhifu_name = '账户';
		if($this->fufen_to_money){
			$myfufen = $this->members['score'] - $this->fufen;
			$query_fufen = $this->db->Query("UPDATE `@#_member` SET `score`='$myfufen' WHERE (`uid`='$uid')");
			$pay_zhifu_name = '积分';
			$this->MoenyCount = $this->fufen;
		}

		//添加用户经验
		if($this->members['auto_user'] == "1"){
			$jingyan = $this->members['jingyan'];
		}else{
			$jingyan = $this->members['jingyan'] + $fufen['z_shoppay'] * $this->MoenyCount;
		}
		$query_jingyan = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值

		//更新用户账户金额
		$query_2 = $this->db->Query("UPDATE `@#_member` SET `money`='$Money' WHERE (`uid`='$uid')");			//金额
		$query_3 = $info = $this->db->GetOne("SELECT * FROM  `@#_member` WHERE (`uid`='$uid') LIMIT 1");
		$query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '$pay_zhifu_name', '购买了商品', '{$this->MoenyCount}', '$time')");
		$query_5 = true;
		$query_insert = true;


		$goods_count_num = 0;
		foreach($this->shoplist as $shop):
			if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
					$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
			}else{
				$sellnum = $this->db->GetOne("select sum(gonumber) as sellnum from `@#_member_go_record` where `shopid` = '$shop[id]'");
				$sellnum = $sellnum['sellnum'];
				$shenyurenshu = $shop['zongrenshu'] - $sellnum;
				$query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$sellnum',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");

				// $shenyurenshu = $shop['zongrenshu'] - $shop['canyurenshu'];
				// $query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$shop[canyurenshu]',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");
				if(!$query)$query_5=false;
			}
			$goods_count_num += $shop['goods_count_num'];
		endforeach;

		//添加积分
		if(!$this->fufen_to_money){
			$mygoscore = $fufen['f_shoppay']*$goods_count_num;
			$mygoscore_text =  "购买了{$goods_count_num}人次商品";
			$myscore = $this->members['score'] + $this->MoenyCount;
			$query_add_fufen_1 = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
			$query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '{$this->MoenyCount}', '$time')");
			$query_fufen = ($query_add_fufen_1 && $query_add_fufen_2);
		}

		$dingdancode=$this->dingdancode;
		$query_6 = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='已付款,未发货,未完成' WHERE `code`='$dingdancode' and `uid` = '$uid'");
		$query_7 = $this->dingdan_query;
		$query_8 = $this->db->Query("UPDATE `@#_caches` SET `value`=`value` + $goods_count_num WHERE `key`='goods_count_num'");
		$this->goods_count_num = $goods_count_num;

		if($query_fufen && $query_jingyan && $query_1 && $query_4 && $query_5 && $query_6 && $query_7 && $query_insert && $query_8){
			if($info['money'] == $Money){
				$this->db->Autocommit_commit();
					foreach($this->shoplist as $shop):
						if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
								$this->db->Autocommit_start();
								$query_insert = pay_insert_shop($shop,'add');
								if(!$query_insert){
									$this->db->Autocommit_rollback();
								}else{
									$this->db->Autocommit_commit();
								}
								$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
						}
					endforeach;
				return true;
			}else{
				$this->db->Autocommit_rollback();
				return false;
			}
		}else{
			$this->db->Autocommit_rollback();
			return $query_1;
		}

	}


	//账户里支付_测试
	private function pay_bag_test(){
		$time=time();
		$uid=$this->members['uid'];
		$fufen = System::load_app_config("user_fufen",'','member');

		$query_1 = $this->set_dingdan('账户','A');

		$dyq_id = $this->dyq_id;

		if($dyq_id = 0){
			$query_dyq = true;
			$query_dyq_up = true;
			$this->dyq_to_money = 0;
		}else{
			$dyq_data = $this->db->GetOne("SELECT m.*,n.money,n.use_condition FROM `@#_voucher_details` AS m LEFT JOIN `@#_shop_voucher` AS n ON m.v_id = n.id WHERE m.uid = '$uid' AND m.id = '{$this->dyq_id}' AND m.valid_time > '$time' AND m.use_time = '0' AND n.is_delete = '0'");
		
			if($dyq_data && ($dyq_data['uid'] == $uid) && ($this->MoenyCount >= $dyq_data['use_condition'])){ 
				$this->dyq_to_money = $dyq_data['money'];
				$query_dyq = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '抵用券', '购买了商品', '{$this->dyq_to_money}', '$time')");
				$query_dyq_up = $this->db->Query("UPDATE `@#_voucher_details` SET `use_time` = '$time' WHERE `id` = '{$this->dyq_id}'");
			}else{
				$query_dyq = true;
				$query_dyq_up = true;
				$this->dyq_to_money = 0;
			}
		}
		/*会员购买过账户剩余金额*/
		$Money = $this->members['money'] - $this->MoenyCount + $this->fufen_to_money + $this->dyq_to_money;
		$query_fufen = true;
		$pay_zhifu_name = '账户';
		if($this->fufen_to_money){
			$myfufen = $this->members['score'] - $this->fufen;
			$query_fufen = $this->db->Query("UPDATE `@#_member` SET `score`='$myfufen' WHERE (`uid`='$uid')");
			$pay_zhifu_name = '积分';
			$this->MoenyCount = $this->fufen;
		}
		if($this->dyq_to_money > 0){
			$this->MoenyCount = $this->MoenyCount - $this->dyq_to_money;
		}
		//添加用户经验
		if($this->members['auto_user'] == "1"){
			$jingyan = $this->members['jingyan'];
		}else{
			$jingyan = $this->members['jingyan'] + $fufen['z_shoppay'] * $this->MoenyCount;
		}
		$query_jingyan = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值

		//更新用户账户金额
		$query_2 = $this->db->Query("UPDATE `@#_member` SET `money`='$Money' WHERE (`uid`='$uid')");			//金额
		$query_3 = $info = $this->db->GetOne("SELECT * FROM  `@#_member` WHERE (`uid`='$uid') LIMIT 1");
		$query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '$pay_zhifu_name', '购买了商品', '{$this->MoenyCount}', '$time')");
		$query_5 = true;
		$query_insert = true;

		$goods_count_num = 0;
		foreach($this->shoplist as $shop):
			if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
					$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
			}else{
				$sellnum = $this->db->GetOne("select sum(gonumber) as sellnum from `@#_member_go_record` where `shopid` = '$shop[id]'");
				$sellnum = $sellnum['sellnum'];
				$shenyurenshu = $shop['zongrenshu'] - $sellnum;
				$query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$sellnum',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");

				// $shenyurenshu = $shop['zongrenshu'] - $shop['canyurenshu'];
				// $query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$shop[canyurenshu]',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");
				if(!$query)$query_5=false;
			}
			$goods_count_num += $shop['goods_count_num'];
		endforeach;

		//添加积分
		if(!$this->fufen_to_money){
			$mygoscore = $fufen['f_shoppay']*$goods_count_num;
			$mygoscore_text =  "购买了{$goods_count_num}人次商品";
			$myscore = $this->members['score'] + $this->MoenyCount;
			$query_add_fufen_1 = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
			$query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '{$this->MoenyCount}', '$time')");
			$query_fufen = ($query_add_fufen_1 && $query_add_fufen_2);
		}

		$dingdancode=$this->dingdancode;
		$query_6 = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='已付款,未发货,未完成' WHERE `code`='$dingdancode' and `uid` = '$uid'");
		$query_7 = $this->dingdan_query;
		$query_8 = $this->db->Query("UPDATE `@#_caches` SET `value`=`value` + $goods_count_num WHERE `key`='goods_count_num'");
		$this->goods_count_num = $goods_count_num;
		if($query_fufen && $query_jingyan && $query_1 && $query_4 && $query_5 && $query_6 && $query_7 && $query_insert && $query_8 && $query_dyq && $query_dyq_up){
			if($info['money'] == $Money){
				$this->db->Autocommit_commit();
					foreach($this->shoplist as $shop):
						if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
								$this->db->Autocommit_start();
								$query_insert = pay_insert_shop($shop,'add');
								if(!$query_insert){
									$this->db->Autocommit_rollback();
								}else{
									$this->db->Autocommit_commit();
								}
								$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
						}
					endforeach;
				return true;
			}else{
				$this->db->Autocommit_rollback();
				return false;
			}
		}else{
			$this->db->Autocommit_rollback();
			return $query_1;
		}

	}

	/**
	*	开始支付--全返余额支付
	**/
	public function go_pay2($pay_checkbox){

		if($this->members['qf_money'] >= $this->MoenyCount){
			$uid=$this->members['uid'];
			$pay_1 =  $this->pay_bag2();
			if(!$pay_1){return $pay_1;}
			$dingdancode=$this->dingdancode;
			$pay_2 = pay_go_fund($this->goods_count_num);
			//$pay_3 = pay_go_yongjin($uid,$dingdancode);
			return $pay_1;
		}
		if(!is_array($this->pay_type)){
			return 'not_pay';
		}
		if(is_array($this->scookies_arr)){
			$scookie = serialize($this->scookies_arr);
		}else{
			$scookie= '0';
		}
		if($pay_checkbox){
			$money = $this->MoenyCount - $this->members['qf_money'];
			return $this->addmoney_record($money,$scookie);
		}else{
			//全额支付
			$this->MoenyCount;
			return $this->addmoney_record($this->MoenyCount,$scookie);
		}
		exit;
	}

	//账户里支付--全返余额
	private function pay_bag2(){
		$time=time();
		$uid=$this->members['uid'];
		$fufen = System::load_app_config("user_fufen",'','member');

		$query_1 = $this->set_dingdan('账户','A');
		/*会员购买过账户剩余金额*/
		$Money = $this->members['qf_money'] - $this->MoenyCount + $this->fufen_to_money;
		$query_fufen = true;
		$pay_zhifu_name = '账户';
		if($this->fufen_to_money){
			$myfufen = $this->members['score'] - $this->fufen;
			$query_fufen = $this->db->Query("UPDATE `@#_member` SET `score`='$myfufen' WHERE (`uid`='$uid')");
			$pay_zhifu_name = '积分';
			$this->MoenyCount = $this->fufen;
		}

		//添加用户经验
		$jingyan = $this->members['jingyan'] + $fufen['z_shoppay'] * $this->MoenyCount;
		$query_jingyan = $this->db->Query("UPDATE `@#_member` SET `jingyan`='$jingyan' WHERE (`uid`='$uid')");	//经验值

		//更新用户账户金额
		$query_2 = $this->db->Query("UPDATE `@#_member` SET `qf_money`='$Money' WHERE (`uid`='$uid')");			//金额
		$query_3 = $info = $this->db->GetOne("SELECT * FROM  `@#_member` WHERE (`uid`='$uid') LIMIT 1");
		$query_4 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '$pay_zhifu_name', '购买了商品', '{$this->MoenyCount}', '$time')");
		$query_5 = true;
		$query_insert = true;


		$goods_count_num = 0;
		foreach($this->shoplist as $shop):
			if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
					$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
			}else{
				$sellnum = $this->db->GetOne("select sum(gonumber) as sellnum from `@#_member_go_record` where `shopid` = '$shop[id]'");
				$sellnum = $sellnum['sellnum'];
				$shenyurenshu = $shop['zongrenshu'] - $sellnum;
				$query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$sellnum',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");

				// $shenyurenshu = $shop['zongrenshu'] - $shop['canyurenshu'];
				// $query = $this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu` = '$shop[canyurenshu]',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'");
				if(!$query)$query_5=false;
			}
			$goods_count_num += $shop['goods_count_num'];
		endforeach;

		//添加积分
		if(!$this->fufen_to_money){
			$mygoscore = $fufen['f_shoppay']*$goods_count_num;
			$mygoscore_text =  "购买了{$goods_count_num}人次商品";
			$myscore = $this->members['score'] + $mygoscore;
			$query_add_fufen_1 = $this->db->Query("UPDATE `@#_member` SET `score`= '$myscore' WHERE (`uid`='$uid')");
			$query_add_fufen_2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '积分', '$mygoscore_text', '$mygoscore', '$time')");
			$query_fufen = ($query_add_fufen_1 && $query_add_fufen_2);
		}

		$dingdancode=$this->dingdancode;
		$query_6 = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='已付款,未发货,未完成' WHERE `code`='$dingdancode' and `uid` = '$uid'");
		$query_7 = $this->dingdan_query;
		$query_8 = $this->db->Query("UPDATE `@#_caches` SET `value`=`value` + $goods_count_num WHERE `key`='goods_count_num'");
		$this->goods_count_num = $goods_count_num;

		if($query_fufen && $query_jingyan && $query_1 && $query_4 && $query_5 && $query_6 && $query_7 && $query_insert && $query_8){
			if($info['qf_money'] == $Money){
				$this->db->Autocommit_commit();
					foreach($this->shoplist as $shop):
						if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
								$this->db->Autocommit_start();
								$query_insert = pay_insert_shop($shop,'add');
								if(!$query_insert){
									$this->db->Autocommit_rollback();
								}else{
									$this->db->Autocommit_commit();
								}
								$this->db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'");
						}
					endforeach;
				return true;
			}else{
				$this->db->Autocommit_rollback();
				return false;
			}
		}else{
			$this->db->Autocommit_rollback();
			return $query_1;
		}

	}

	public function pay_user_go_shop($uid=null,$gid=null,&$num=null){
		if(empty($uid) || empty($gid) || empty($num)){
			return false;
		}
		$uid = intval($uid);$gid = intval($gid);$num = intval($num);
		$this->db=System::load_sys_class('model');
      	
      	$sb = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid'");
        if($sb['auto_user']==0){
            $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$sb[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100){
                _setcookie("uid","",time()-3600);
                _setcookie("ushell","",time()-3600);
              	session_start();
				unset($_SESSION['gwc']);
                return false;exit();
            } 	
        }
      
		$this->db->Autocommit_start();
		$member = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid' for update");
		$goodinfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' and `shenyurenshu` != '0' for update");
		if(!$goodinfo['shenyurenshu']){
			$this->db->Autocommit_rollback();
			return false;
		}
		if($goodinfo['shenyurenshu'] < $num){
			$num = $goodinfo['shenyurenshu'];
		}
		$if_money = $goodinfo['yunjiage'] * $num;
		$this->members = $member;
		$this->MoenyCount = $if_money;
		$goodinfo['goods_count_num'] = $num;
		$goodinfo['cart_gorenci'] = $num;

		$this->shoplist = array();
		$this->shoplist[0] = $goodinfo;

		if($member && $goodinfo && $member['money'] >= $if_money){

			$uid=$member['uid'];
			$pay_1 =  $this->pay_bag();
			if(!$pay_1){return $pay_1;}
			$dingdancode=$this->dingdancode;
			$pay_2 = pay_go_fund($this->goods_count_num);
			$pay_3 = pay_go_yongjin($uid,$dingdancode);
			return $pay_1;

		}else{
			$this->db->Autocommit_rollback();
			return false;
		}
	}

	//透明购
	public function tmg_jx(){
		$this->db=System::load_sys_class('model');
		$src = 'http://wd.apiplus.net/newly.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&rows=1';
        $src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
        $html = file_get_contents($src);
        $json = json_decode($html,true);

        if (isset($json['rows'])){
            foreach($json['data'] as $r){
                $expect = $r['expect'];
                $opencode = str_replace(',','',$r['opencode']);
                $opentimestamp = $r['opentimestamp'];
            }
        }
        $shop_id = $this->db->GetList("SELECT * FROM `@#_tmg` WHERE `ssc_times` = '$expect' AND `ssc_number` = ''");
       
        $shopids='';
        $ids='';
        foreach ($shop_id as $key => $val) {
        	$shopids .= intval($val['shopid']).',';
        	$ids .= intval($val['id']).',';
        }

        $shopids=str_replace(',0','',$shopids);
		$shopids=trim($shopids,',');

		$ids = str_replace(',0','',$ids);
		$ids = trim($ids,',');
		
		$str = $this->db->Query("UPDATE `@#_tmg` SET `ssc_time` = '$opentimestamp',`ssc_number` = '$opencode' WHERE `id` in($ids)");

		if($str){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
			foreach ($shoplist as $shop) {
				$arr = tmg_pay_shop($shop,$opencode);
			}
		}
		return $arr;
	}

	//透明购往期揭晓
	public function tmg_wq_jx($expect,$opencode,$opentimestamp){
		$this->db=System::load_sys_class('model');
		
        $shop_id = $this->db->GetList("SELECT * FROM `@#_tmg` WHERE `ssc_times` = '$expect' AND `ssc_number` = ''");
       
        $shopids='';
        $ids='';
        foreach ($shop_id as $key => $val) {
        	$shopids .= intval($val['shopid']).',';
        	$ids .= intval($val['id']).',';
        }

        $shopids=str_replace(',0','',$shopids);
		$shopids=trim($shopids,',');

		$ids = str_replace(',0','',$ids);
		$ids = trim($ids,',');
		
		$str = $this->db->Query("UPDATE `@#_tmg` SET `ssc_time` = '$opentimestamp',`ssc_number` = '$opencode' WHERE `id` in($ids)");

		if($str){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
			foreach ($shoplist as $shop) {
				$arr = tmg_pay_shop($shop,$opencode);
			}
		}
		return $arr;
	}

	//透明购开奖失败，重新开奖
	public function tmg_lose($expect,$opencode,$opentimestamp){
		$this->db=System::load_sys_class('model');
		
        $shop_id = $this->db->GetList("SELECT * FROM `@#_tmg` WHERE `ssc_times` = '$expect' AND `ssc_time` != '0'");
       
        $shopids='';
        $ids='';
        foreach ($shop_id as $key => $val) {
        	$shopids .= intval($val['shopid']).',';
        	$ids .= intval($val['id']).',';
        }

        $shopids=str_replace(',0','',$shopids);
		$shopids=trim($shopids,',');

		$ids = str_replace(',0','',$ids);
		$ids = trim($ids,',');
		
		$str = $this->db->Query("UPDATE `@#_tmg` SET `ssc_time` = '$opentimestamp',`ssc_number` = '$opencode' WHERE `id` in($ids)");

		if($str){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
			foreach ($shoplist as $shop) {
				$arr = tmg_pay_shop($shop,$opencode);
			}
		}
		return $arr;
	}

	//透明购手动输入
	public function tmg_jx_sd($expect,$opencode,$opentimestamp){
		$this->db=System::load_sys_class('model');

		$shop_id = $this->db->GetList("SELECT * FROM `@#_tmg` WHERE `ssc_times` = '$expect' AND `ssc_number` = ''");
       
        $shopids='';
        $ids='';
        foreach ($shop_id as $key => $val) {
        	$shopids .= intval($val['shopid']).',';
        	$ids .= intval($val['id']).',';
        }

        $shopids=str_replace(',0','',$shopids);
		$shopids=trim($shopids,',');

		$ids = str_replace(',0','',$ids);
		$ids = trim($ids,',');
		
		$str = $this->db->Query("UPDATE `@#_tmg` SET `ssc_time` = '$opentimestamp',`ssc_number` = '$opencode' WHERE `id` in($ids)");

		if($str){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` where `id` in($shopids) and `q_uid` is null for update",array("key"=>"id"));
			foreach ($shoplist as $shop) {
				$arr = tmg_pay_shop($shop,$opencode);
			}
		}
		return $arr;
	}

}
?>