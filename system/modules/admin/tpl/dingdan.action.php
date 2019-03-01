<?php 
defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class dingdan extends admin {

	private $db;

	public function __construct(){		

		parent::__construct();		

		$this->db=System::load_sys_class('model');		

		$this->ment=array(

						array("lists","订单列表",ROUTE_M.'/'.ROUTE_C."/lists"),					

						array("lists","中奖订单",ROUTE_M.'/'.ROUTE_C."/zhongjiang"),					

						array("lists","已发货",ROUTE_M.'/'.ROUTE_C."/lists/sendok"),

						array("lists","未发货",ROUTE_M.'/'.ROUTE_C."/lists/notsend"),						

						array("insert","已完成",ROUTE_M.'/'.ROUTE_C."/lists/ok"),

						array("insert","已作废",ROUTE_M.'/'.ROUTE_C."/lists/del"),

						array("insert","待收货",ROUTE_M.'/'.ROUTE_C."/lists/shouhuo"),

						array("lists","未填地址",ROUTE_M.'/'.ROUTE_C."/weitian"),

						array("lists","已填地址",ROUTE_M.'/'.ROUTE_C."/yitian"),

						array("genzhong","<b>快递跟踪</b>",ROUTE_M.'/'.ROUTE_C."/genzhong"),

						array("lists","微信扫码充值订单",ROUTE_M.'/'.ROUTE_C."/wxpay"),

						array("lists","支付宝扫码充值订单",ROUTE_M."/dingdan1/alipay"),

						array("lists","打单设置",ROUTE_M."/test/shop_play_cord"),

						array("lists","打印订单",ROUTE_M."/test/dadan"),

						array("lists","不打印订单",ROUTE_M."/test/not_dadan"),

						array("lists","订单号查询",ROUTE_M."/dingdan/dd_cx"),

						array("lists","活动打单",ROUTE_M."/test/hd_dadan"),

						array("lists","兑换打单",ROUTE_M."/test/dh_dadan"),

						array("lists","闯三关打单",ROUTE_M."/dingdan/csg_dadan"),



						

		);

	}

	public function edit_qrcode(){
		//$codelist = $this->db->GetList("SELECT * FROM `@#_wxpay_locat_config` where `canshu`='paycode'");
        $str = $this->db->GetList("SELECT distinct `fenzu` FROM `@#_wxpay_locat_config` where `canshu`='paycode' order by `fenzu` asc");
      	$time_start = strtotime(date('Y-m-d',time()));
		$time_end = $time_start + 86400;
		$time_zt = $time_start - 86400;
      	foreach($str as $key=>$val){
        	$codelist[$val['fenzu']]['list'] = $this->db->GetList("SELECT * FROM `@#_wxpay_locat_config` where `canshu`='paycode' and `fenzu` = '$val[fenzu]'");
          	
          	foreach ($codelist[$val['fenzu']]['list'] as $k => $v) {
              $aid = $v['zhi'];
              $data_today = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_wxpay_locat` WHERE `aid` = '$aid' AND `status` = 1 AND `create_time` > '$time_start' AND `create_time` <= '$time_end'");
              $data_yest = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_wxpay_locat` WHERE `aid` = '$aid' AND `status` = 1 AND `create_time` > '$time_zt' AND `create_time` <= '$time_start'");
              $codelist[$val['fenzu']]['list'][$k]['today'] = $data_today['sum_money'];
              $codelist[$val['fenzu']]['list'][$k]['yesterday'] = $data_yest['sum_money'];
          }
        }
		
		
		include $this->tpl(ROUTE_M,'dingdan.editqrcode');
	}

	public function edit_qrcode_name(){
		$id = intval($_POST['id']);
		$name = trim($_POST['name']);
		$data = $this->db->Query("UPDATE `@#_wxpay_locat_config` SET `name` = '$name' WHERE `id` = '$id'");
		echo 1;
	}

  	public function edit_pay(){
		$codelist = $this->db->GetList("SELECT * FROM `@#_pay_config` ");	
		include $this->tpl(ROUTE_M,'dingdan.pay_config');
	}
  	public function change_payc(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$s = intval($_POST['s']);
	
			$this->db->Query("UPDATE `@#_pay_config` SET `status`='$s' where `id`=$id");
          	
          	//$c = $this->db->GetList("SELECT * FROM `@#_pay_config` where `status`= '1' ");
          	//if(count($c)>1){
            //	$this->db->Query("UPDATE `@#_pay_config` SET `status`='$s' where `id`=$id");
            //}
			
			echo 0;
		}
	}
	public function add_wxpay_user(){
		if (!empty($_POST)) {
			$data=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `canshu`='paycode' order by id desc limit 1");
			$money = $data['zhi'] + 1;
			$this->db->Query("INSERT INTO `@#_wxpay_locat_config` (`canshu`, `zhi`) VALUES ('paycode', '$money')");
			echo 0;
		}	
	}
	public function edit_qrcode_img(){
		$zhi=intval($this->segment(4));
		$money=intval($this->segment(5));

		$linkinfo=$this->db->GetOne("SELECT * FROM `@#_wxpay_qrcode` where `zhi`='$zhi' and `num`='$money' ");
		$id = $linkinfo['id'];

		if (empty($linkinfo)) {
			$this->db->Query("INSERT INTO `@#_wxpay_qrcode` (`zhi`, `num`, `img`) VALUES ('$zhi', '$money', ' ')");
			$id = $this->db->insert_id();
		}
		
		if(isset($_POST['submit'])){		
				$logo = $linkinfo['img'];
				if(!empty($_FILES['image']['tmp_name'])){
					System::load_sys_class('upload','sys','no');
					upload::upload_config(array('png','jpg','jpeg','gif'),500000,'linkimg');
					upload::go_upload($_FILES['image']);
					if(!upload::$ok){
						_message(upload::$error,WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/edit_qrcode");
					}
					$logo=upload::$filedir."/".upload::$filename;
				}			

				$this->db->Query("UPDATE `@#_wxpay_qrcode` SET `img`='$logo' WHERE `id`='$id'");
				if($this->db->affected_rows()){
						_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/edit_qrcode");
				}else{
						_message("修改失败");
				}		
		}
		include $this->tpl(ROUTE_M,'dingdan.wxpay_code1');	
	}
	public function open_qrcode(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$s = intval($_POST['s']);
		
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `open`='$s' where `id`=$id");
			
			echo 0;
		}
	}

	public function open_qrcode_status(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$s = intval($_POST['s']);
		
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `status`='$s' where `id`=$id");
			
			echo 0;
		}
	}

	// public function jqr(){
	// 	$data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `auto_user` = '1' AND `buy_record` = '0' AND `gain_shop` = '0' ORDER BY rand() limit 500");
	// 	$uid = '';
	// 	foreach ($data as $key => $val) {
	// 		if($uid == ''){
	// 			$uid = $val['uid'];
	// 		}else{
	// 			$uid = $uid.','.$val['uid'];
	// 		}
	// 	}
	// 	$str = $this->db->Query("UPDATE `@#_member` SET `buy_record` = '1',`gain_shop` = '1' WHERE `uid` in ($uid)");
	// 	var_dump($str);
	// }

	public function wxpay(){
		if(isset($_POST['sososubmit'])){

			$posttime1=isset($_POST['posttime1'])?$_POST['posttime1']:'';

			$posttime2=isset($_POST['posttime2'])?$_POST['posttime2']:'';

			$cate = $_POST['cate'];

			$sel = $_POST['sel'];

			$times = '';
			//if(empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空！");

			if(!empty($posttime1) && !empty($posttime2)){ //如果2个时间都不为空

				$posttime1=strtotime($posttime1);

				$posttime2=strtotime($posttime2);

				if($posttime1 > $posttime2){

					_message("前一个时间不能大于后一个时间");

				}

				$times= "`create_time`>='$posttime1' AND `create_time`<='$posttime2'";

			}

			if(!empty($sel) && !empty($times)){
				$times = $times." AND `$cate`='$sel'";
			}else if(!empty($sel)){
				$times = "`$cate`='$sel'";
			}

			$wheres=$times;
		}
		//var_dump($wheres);
		
		$total = 100;
		if (empty($wheres)) {
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_wxpay_locat`");
		}else{
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_wxpay_locat` where ".$wheres);
		}
		 
		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if (empty($wheres)) {
			$recordlist=$this->db->GetPage("select * from `@#_wxpay_locat` order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}else{
			//$recordlist=$this->db->GetPage("select * from `@#_wxpay_locat` where ".$wheres." order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
			$recordlist = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE ".$wheres." order by id DESC");
		}
		// $total_money = 0;
		// foreach ($recordlist as $key => $val) {
		// 	if($val['status'] == 1){
		// 		$total_money += $val['money'];
		// 	}
		// }
		
		$CheckId = _encrypt(_getcookie("AID"), 'DECODE');
		$set_open=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='7'");
		$song_open=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='18'");
		
		$a1 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='11'");	
		
		$a2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='12'");	
	
		$a3 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='13'");	
	
		$a4 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='14'");	
	
		$a5 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='15'");	
	
		$a6 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='16'");	
	
		$a7 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='17'");	

		include $this->tpl(ROUTE_M,'dingdan.list3');	
	}

	public function check_wxpay_open(){
		$open=intval($this->segment(4));
		if ($open == 0 || $open ==1) {
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `open`='$open' WHERE `id`='7'");
		}
		header("Location:/admin/dingdan/wxpay");
	}
	public function song_wxpay_open(){
		$open=intval($this->segment(4));
		if ($open == 0 || $open ==1) {
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `open`='$open' WHERE `id`='18'");
		}
		header("Location:/admin/dingdan/wxpay");
	}
	public function wxpay_code(){
		$id=intval($this->segment(4));
		$linkinfo=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='$id'");
		
		if(!$linkinfo)_message("参数不正确");	
		
		if(isset($_POST['submit'])){		
				$id = $linkinfo['id'];
				$logo = $linkinfo['img'];
				if(!empty($_FILES['image']['tmp_name'])){
					System::load_sys_class('upload','sys','no');
					upload::upload_config(array('png','jpg','jpeg','gif'),500000,'linkimg');
					upload::go_upload($_FILES['image']);
					if(!upload::$ok){
						_message(upload::$error,WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/wxpay");
					}
					$logo=upload::$filedir."/".upload::$filename;
				}			

				$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `img`='$logo' WHERE `id`='$id'");
				if($this->db->affected_rows()){
						_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/wxpay");
				}else{
						_message("修改失败");
				}
				
				
			
		}
		include $this->tpl(ROUTE_M,'dingdan.wxpay_code');	
	}

	public function check_wxpay(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$timed = time();

			$CheckId = _encrypt(_getcookie("AID"), 'DECODE');

			$data = $this->db->GetOne("select * from `@#_wxpay_locat` where `id`=".$id);
          
          	if($timed - $data['create_time'] > 3000){
            	echo 1;exit();
            }
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过线下扫码充值".$money."元";
			$ip = _get_ip();
			$aid_data = $this->db->GetOne("SELECT `zhi` FROM `@#_wxpay_locat_config` WHERE `canshu` = 'paycode' AND `open` = '1'");
			$aid = $aid_data['zhi'];
			$rs1 = $this->db->Query("UPDATE `@#_wxpay_locat` SET `status`='1',`update_time`=$timed,`aduser`=$CheckId,`ip`='$ip' where id='$id'");

			$rs2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$uid')");
  			$rs3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$money', '$timed')");

  			
  				
  					
  			

			if ($rs1 && $rs2 && $rs3) {
				$this->song_wxpay($id,$money);
				echo 0;
			}else{
				echo 1;
			}
		}
	}
	public function add_wxpay(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$timed = time();

			$CheckId = _encrypt(_getcookie("AID"), 'DECODE');

			$data = $this->db->GetOne("select * from `@#_wxpay_locat` where `id`=".$id);
			if ($data['status']==0) {
				echo 1;
				exit();
			}
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过线下扫码充值".$money."元";

			$rs1 = $this->db->Query("UPDATE `@#_wxpay_locat` SET `zhuijia`=`zhuijia` + '1' where id='$id'");

			$rs2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$uid')");
  			$rs3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$money', '$timed')");

			if ($rs1 && $rs2 && $rs3) {
				echo 0;
			}else{
				echo 1;
			}
		}
	}
	public function song_wxpay($id,$money){
		$open = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='18'");
		if ($open['open']==0) {
			return false;
		}

		$song = '';
		if ($money == 100) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='11'");	
		}elseif ($money == 200) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='12'");	
		}elseif ($money == 300) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='13'");	
		}elseif ($money == 500) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='14'");	
		}elseif ($money == 1000) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='15'");	
		}elseif ($money == 2000) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='16'");	
		}elseif ($money == 5000) {
			$song = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='17'");	
		}else{
			return true;
		}

		if ( empty($money) || empty($song) ) {
			return false;
		}

		$money = $song['zhi'];

		$timed = time();

		$CheckId = _encrypt(_getcookie("AID"), 'DECODE');

		$data = $this->db->GetOne("select * from `@#_wxpay_locat` where `id`=".$id);
		if ($data['status']==0) {
			echo 1;
			exit();
		}
		$uid = $data['uid'];
		$money1 = intval($data['money']);
		$content = "充值".$money1."元送".$money."元";

		$rs1 = $this->db->Query("UPDATE `@#_wxpay_locat` SET `song`='$money' where id='$id'");

		$rs2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$uid')");

			$rs3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$money', '$timed')");

		if ($rs1 && $rs2 && $rs3) {
			return true;
		}else{
			return false;
		}	
	}

	public function song_money(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$money = intval($_POST['money']);

			$rs = $this->db->Query("UPDATE `@#_wxpay_locat_config` SET `zhi`='$money' where id='$id'");

			if ($rs) {
				echo 0;
			}else{
				echo 1;
			}
		}
	}

	public function chehui_wxpay(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$timed = time();

			$CheckId = _encrypt(_getcookie("AID"), 'DECODE');

			$data = $this->db->GetOne("select * from `@#_wxpay_locat` where `id`=".$id);
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过线下扫码充值".$money."元";

			$rs1 = $this->db->Query("UPDATE `@#_wxpay_locat` SET `status`='0',`update_time`=0,`aduser`=$CheckId where id='$id'");

			$rs2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` - '$money' where (`uid` = '$uid')");
  			//$rs3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '$content', '$money', '$timed')");

			if ($rs1 && $rs2 ) {
				echo 0;
			}else{
				echo 1;
			}
		}
	}

	public function genzhong(){	

		include $this->tpl(ROUTE_M,'dingdan.genzhong');	

	}

	public function chang_redbag(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$s = intval($_POST['s']);

			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `redbag_status`='$s' where id='$id'");
			if ($ret) {
				echo 0;
			}else{
				echo 1;
			}
		}
	}
	public function chang_dadan(){
		if (!empty($_POST)) {
			$id = intval($_POST['id']);
			$s = intval($_POST['s']);

			$list = $this->db->GetOne("select * from `@#_dadan_setting` where `sid` = ".$id);
			if (empty($list)) {
				$this->db->Query("insert into `@#_dadan_setting`(`sid`,`status`) values('$id','$s') ");	
			}else{
				$this->db->Query("UPDATE `@#_dadan_setting` SET `status`='$s' where `sid`=$id");
			}
			echo 0;
		}
	}
	public function zhongjiang(){
		//$list = $this->db->GetList("select * from `@#_member_go_record` as u left join `@#_member_go_record` a on u.uid = a.uid where a.auto_user=0");

		//$list = $this->db->GetList("select * from `@#_member_go_record`,`@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.huode !='0'");
		//var_dump($list);
		if(!empty($_POST)){
			$posttime1 = strtotime($_POST['posttime1']);
			$posttime2 = strtotime($_POST['posttime2']);
			$user_type = $_POST['user_type'];
			$user = $_POST['user'];

			if($user_type == 'uid'){
				$uid = $user;
			}else if($user_type == 'mobile'){
				$arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$user'");
				$uid = $arr['uid'];
			}else{
				$arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `username` = '$user'");
				$uid = $arr['uid'];
			}

			if(!empty($_POST['posttime1']) && !empty($_POST['posttime2'])){
				if(empty($uid)){
					$recordlist=$this->db->GetList("select `@#_member_go_record`.code,`@#_member_go_record`.shopname,`@#_member_go_record`.username,`@#_member_go_record`.gonumber,`@#_member_go_record`.status,`@#_member_go_record`.redbag_status,`@#_member_go_record`.shopid,`@#_member_go_record`.huode,`@#_member_go_record`.id,`@#_member_go_record`.shopqishu,`@#_shoplist`.cateid from `@#_member_go_record`,`@#_member`,`@#_shoplist` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.huode !='0' and `@#_member_go_record`.time > '$posttime1' and `@#_member_go_record`.time < '$posttime2' and `@#_member_go_record`.shopid=`@#_shoplist`.id and `@#_member`.uid != '74447' order by `@#_shoplist`.q_end_time DESC ");
				}else{
					$recordlist=$this->db->GetList("select `@#_member_go_record`.code,`@#_member_go_record`.shopname,`@#_member_go_record`.username,`@#_member_go_record`.gonumber,`@#_member_go_record`.status,`@#_member_go_record`.redbag_status,`@#_member_go_record`.shopid,`@#_member_go_record`.huode,`@#_member_go_record`.id,`@#_member_go_record`.shopqishu,`@#_shoplist`.cateid from `@#_member_go_record`,`@#_member`,`@#_shoplist` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.huode !='0' and `@#_member_go_record`.time > '$posttime1' and `@#_member_go_record`.time < '$posttime2' and `@#_member_go_record`.uid='$uid' and `@#_member_go_record`.shopid=`@#_shoplist`.id and `@#_member`.uid != '74447' order by `@#_shoplist`.q_end_time DESC ");
				}
			}else{
				$recordlist=$this->db->GetList("select `@#_member_go_record`.code,`@#_member_go_record`.shopname,`@#_member_go_record`.username,`@#_member_go_record`.gonumber,`@#_member_go_record`.status,`@#_member_go_record`.redbag_status,`@#_member_go_record`.shopid,`@#_member_go_record`.huode,`@#_member_go_record`.id,`@#_member_go_record`.shopqishu,`@#_shoplist`.cateid from `@#_member_go_record`,`@#_member`,`@#_shoplist` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.huode !='0' and `@#_member_go_record`.uid='$uid' and `@#_member_go_record`.shopid=`@#_shoplist`.id and `@#_member`.uid != '74447' order by `@#_shoplist`.q_end_time DESC ");
			}
		}else{
			$order_cate = $this->segment(4);
			if($order_cate == 1){
				$num=20;

				$total=1000;

				$page=System::load_sys_class('page');

				if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

				$page->config($total,$num,$pagenum,"0");

				$tm = time()-172800;

				$recordlist=$this->db->GetPage("select m.code,m.shopname,m.username,m.gonumber,m.status,m.redbag_status,m.shopid,m.huode,m.id,m.shopqishu,k.cateid from `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE m.type=0 and m.huode !='0' and m.time>'$tm' and m.uid != '74447' order by k.q_end_time DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
			}else{
				$num=20;

				$total=1000;

				$page=System::load_sys_class('page');

				if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

				$page->config($total,$num,$pagenum,"0");

				$tm = time()-172800;

				$recordlist=$this->db->GetPage("select m.code,m.shopname,m.username,m.gonumber,m.status,m.redbag_status,m.shopid,m.huode,m.id,m.shopqishu,k.cateid from `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE m.type=0 and m.huode !='0' and m.time>'$tm' and m.uid != '74447' order by m.time DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
			}
		}

		foreach ($recordlist as $k => $v) {
			$sh = $this->db->GetOne("select `q_user_code` from `@#_shoplist` where `id` = ".$v['shopid']);
			if ($sh['q_user_code'] != $v['huode']) {
				unset($recordlist[$k]);
			}
		}
		array_values($recordlist);

		include $this->tpl(ROUTE_M,'dingdan.list1');	
	}
	public function weitian(){
		//$list = $this->db->GetList("select * from `@#_member_go_record` as u left join `@#_member_go_record` a on u.uid = a.uid where a.auto_user=0");

		//$list = $this->db->GetList("select * from `@#_member_go_record`,`@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.dizhi_id=0 and `@#_member_go_record`.huode !='0'");
		//var_dump($list);
		$num=20;

	

		// $total=count($list);
		$total=1000;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("select * from `@#_member_go_record`,`@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.dizhi_id=0 and `@#_member_go_record`.huode !='0' order by `@#_member_go_record`.time DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	

		include $this->tpl(ROUTE_M,'dingdan.list');	
	}
	public function yitian(){
		$num=20;
		$dd_number = $this->segment(4);
		if(!empty($dd_number)){
			$list = $this->db->GetList("select * from `@#_member_go_record` where `dizhi_id` != 0 and `company_code` = '$dd_number'");
		}else{
			$list = $this->db->GetList("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%'");
		}
		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if(!empty($dd_number)){
			$recordlist=$this->db->GetPage("select * from `@#_member_go_record` where `dizhi_id` != 0 and `company_code` = '$dd_number' ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
		}else{
			$recordlist=$this->db->GetPage("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%' ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
		}

		include $this->tpl(ROUTE_M,'dingdan.list2');	
	}
	public function lists(){	

		

		/*

			已付款,未发货,已完成

			未付款,已发货,已作废

			已付款,未发货,待收货

		*/

		$where = $this->segment(4);

		if(!$where){

			$list_where = "where `status` LIKE  '%已付款%'";

		}elseif($where == 'zj'){

			//中奖		

			//$list_where = "where `huode` != '0' and uid IN(SELECT `uid` FROM `@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `auto_user`=0)";
			$list_where = ",`@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0";

		}elseif($where == 'sendok'){

			//已发货订单

			$list_where = "where `status` =  '已付款,已发货,未完成' or `status` =  '已付款,已发货,已完成' ";

		}elseif($where == 'notsend'){

			//未发货订单

			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%'";

		}elseif($where == 'ok'){

			//已完成

			$list_where = "where `huode` != '0' and  `status` LIKE  '%已完成%'";

		}elseif($where == 'del'){

			//已作废		

			$list_where = "where `status` LIKE  '%已作废%'";

		}elseif($where == 'gaisend'){

			//该发货			

			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%'";

		}elseif($where == 'shouhuo'){

			//该发货			

			$list_where = "where `status` LIKE  '%待收货%'";

		}elseif($where == 'weitian'){

			//未填地址订单

			$list_where = "where `dizhi_id` = 0 and uid IN(SELECT `uid` FROM `@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `auto_user`=0)";

		}elseif($where == 'yitian'){

			//已填地址订单

			$list_where = "where `dizhi_id` != 0 and `status` LIKE  '%未发货%'";

		}

		

		if(isset($_GET['paixu_submit'])){

			$paixu = $_GET['paixu'];
			$shopname = $_GET['shopname'];

			if(!empty($shopname)){
				$list_where="where `status` =  '已付款,已发货,未完成' and `shopname` = '$shopname'";
			}

			if($paixu == 'time1'){

				$list_where.=" order by `time` DESC";

			}

			if($paixu == 'time2'){

				$list_where.=" order by `time` ASC";

			}

			if($paixu == 'num1'){

				$list_where.=" order by `gonumber` DESC";

			}

			if($paixu == 'num2'){

				$list_where.=" order by `gonumber` ASC";

			}

			if($paixu == 'money1'){

				$list_where.=" order by `moneycount` DESC";

			}

			if($paixu == 'money2'){

				$list_where.=" order by `moneycount` ASC";

			}

		

		}else if($where == 'sendok'){
			$list_where.=" order by `dizhi_time` DESC ";

			$paixu = 'time1';
		}else{

			$list_where.=" order by `time` DESC";

			$paixu = 'time1';

		}

			
		$num=20;

	

		//$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` $list_where");
		$total = 5000;
		
		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` $list_where",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	

		

		

		include $this->tpl(ROUTE_M,'dingdan.list');	

	}

	public function edit_dizhi(){
		//echo json_encode($_POST);
		$rid = $_POST['rid'];
		$pr = $_POST['province'];
		$co = $_POST['county'];
		$ci = $_POST['city'];
		$pl = $_POST['place'];
		$u = $_POST['user'];
		$ph = $_POST['phone'];
		$this->db->Query("insert into `@#_member_dizhi`(`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`) values(0,'$pr','$co','$ci','$pl','$u','$ph') ");
		$id = $this->db->insert_id();
		$this->db->Query("UPDATE `@#_member_go_record` SET `dizhi_id`='$id' where id=".$rid);
		echo 0;
	}

	public function edit_exchange_dizhi(){
		$rid = $_POST['rid'];
		$pr = $_POST['province'];
		$co = $_POST['county'];
		$ci = $_POST['city'];
		$pl = $_POST['place'];
		$u = $_POST['user'];
		$ph = $_POST['phone'];
		$this->db->Query("insert into `@#_member_dizhi`(`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`) values(0,'$pr','$co','$ci','$pl','$u','$ph') ");
		$id = $this->db->insert_id();
		$this->db->Query("UPDATE `@#_exchange_record` SET `addr_id`='$id' where id=".$rid);
		echo 0;
	}

	//活动订单详情

	public function get_hd_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT m.*,n.shopname FROM `@#_activity_yyy` AS m LEFT JOIN `@#_yyy_shop` AS n ON m.shiwu_id = n.id where m.id='$code'");

		if(!$record)_message("参数不正确!");

		

		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			switch ($company) {
				case '京东快递':
					$shipper_code = "JD";
					break;
				case '圆通快递':
					$shipper_code = "YTO";
					break;
				case '顺丰快递':
					$shipper_code = "SF";
					break;
				case '韵达快递':
					$shipper_code = "YD";
					break;
				default:
					$shipper_code = "";
					break;
			}
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
				
			$ret = $this->db->Query("UPDATE `@#_activity_yyy` SET `company` = '$company',`company_code` = '$company_code', `shipper_code` = '$shipper_code' where id='$code'");
			if($ret){
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}
		System::load_sys_fun("user");
		$uid= $record['user_id'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
		if (empty($record['dizhi_id'])) {
			//$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `uid` = '$uid' ORDER BY `default`  DESC LIMIT 1");
		}else{
			$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `id` = ".$record['dizhi_id']);
		}
		
		$go_time = $record['created_at'];
		include $this->tpl(ROUTE_M,'dingdan.hd_code');	
	}

	//兑换订单详情
	public function get_dh_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id where m.id='$code'");

		if(!$record)_message("参数不正确!");

		

		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			switch ($company) {
				case '京东快递':
					$shipper_code = "JD";
					break;
				case '圆通快递':
					$shipper_code = "YTO";
					break;
				case '顺丰快递':
					$shipper_code = "SF";
					break;
				case '韵达快递':
					$shipper_code = "YD";
					break;
				default:
					$shipper_code = "";
					break;
			}
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
				
			$ret = $this->db->Query("UPDATE `@#_exchange_record` SET `company` = '$company',`company_code` = '$company_code', `shipper_code` = '$shipper_code' where id='$code'");
			if($ret){
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
		if (empty($record['addr_id'])) {
			//$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `uid` = '$uid' ORDER BY `default`  DESC LIMIT 1");
		}else{
			$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `id` = ".$record['addr_id']);
		}
		
		$go_time = $record['create_time'];
		include $this->tpl(ROUTE_M,'dingdan.dh_code');	
	}

	//闯三关详情
	public function get_csg_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id where m.id='$code'");

		if(!$record)_message("参数不正确!");

		

		if(isset($_POST['submit'])){
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			switch ($company) {
				case '京东快递':
					$shipper_code = "JD";
					break;
				case '圆通快递':
					$shipper_code = "YTO";
					break;
				case '顺丰快递':
					$shipper_code = "SF";
					break;
				case '韵达快递':
					$shipper_code = "YD";
					break;
				default:
					$shipper_code = "";
					break;
			}
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
				
			$ret = $this->db->Query("UPDATE `@#_kh_record` SET `wuliu` = '已发货',`company` = '$company',`company_code` = '$company_code', `shipper_code` = '$shipper_code' where id='$code'");
			if($ret){
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
		if (empty($record['dizhi_id'])) {
			//$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `uid` = '$uid' ORDER BY `default`  DESC LIMIT 1");
		}else{
			$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `id` = ".$record['dizhi_id']);
		}
		
		$go_time = $record['create_time'];
		include $this->tpl(ROUTE_M,'dingdan.csg_code');	
	}

	//订单详细

	public function get_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where `id`='$code'");

		if(!$record)_message("参数不正确!");

		$uid = $record['uid'];
		//充值总金额
		$czje = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '1' and `pay` = '账户' and `content` not like '%不中全返%' and `content` != '商城卡充值'");

		$wx_data = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `uid`='$uid' order by `create_time` desc");
		$wx_money = 0;
		foreach ($wx_data as $key => $val) {
			if($val['status'] == 1){
				$wx_money += $val['money'];
				if($val['zhuijia'] != 0){
					$wx_money += $val['money']*$val['zhuijia'];
				}
			}
		}
		$zfb_data = $this->db->GetList("SELECT sum(`money`) sum_money FROM `@#_alipay_locat` WHERE `uid`='$uid' AND `status` = '1' order by `create_time` desc");
		$zfb_money = $zfb_data['sum_money'];
		
		$zz_data = $this->db->GetList("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid`='$uid' and (`content` LIKE '%获得用户%' or `content`='使用佣金充值到参与账户') order by `time` desc");
		$zz_money = $zz_data['sum_money'];

		$gzh_data = $this->db->GetList("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid`='$uid' and `content`='通过微信公众号充值' order by `time` desc");
		$gzh_money = $gzh_data['sum_money'];

		$wl_data = $this->db->GetList("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid`='$uid' and `content`='通过网络充值' order by `time` desc");
		$wl_money = $wl_data['sum_money'];

		$yhk_data = $this->db->GetList("SELECT * FROM `@#_bank_locat` WHERE `uid`='$uid' order by `create_time` desc");
		$yhk_money = 0;
		foreach ($yhk_data as $key => $val) {
			if($val['status'] == 1){
				$yhk_money += $val['money'];
				if($val['zhuijia'] != 0){
					$yhk_money += $val['money']*$val['zhuijia'];
				}
			}
		}
		//实际充值总金额
		$sj_money = $wx_money + $zfb_money + $zz_money + $gzh_money + $wl_money + $yhk_money;
		//购买金额
		$gm_money = $this->db->GetOne("SELECT sum(gonumber) as sum_money FROM `@#_member_go_record` WHERE `uid`='$uid'");

		//商城卡充值金额
		$sck_money = $this->db->GetList("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '1' and `pay` = '账户' and `content` = '商城卡充值'");

		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			switch ($company) {
				case '京东快递':
					$shipper_code = "JD";
					break;
				case '圆通快递':
					$shipper_code = "YTO";
					break;
				case '顺丰快递':
					$shipper_code = "SF";
					break;
				case '韵达快递':
					$shipper_code = "YD";
					break;
				default:
					$shipper_code = "";
					break;
			}
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
			if($status == '未完成'){
				$status = $record_code[0].','.$record_code[1].','.'未完成';		
			}
			if($status == '已发货'){
				$status = '已付款,已发货,待收货';
			}
			if($status == '未发货'){
				$status = '已付款,未发货,未完成';
			}
			if($status == '已完成'){
				$status = '已付款,已发货,已完成';	
			}
			if($status == '已作废'){
				$status = $record_code[0].','.$record_code[1].','.'已作废';				
			}			
			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status',`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money', `shipper_code` = '$shipper_code' where id='$code'");
			if($ret){
				//调用发货通知
				if(_cfg("sendmobile")){
					//如果没有中奖短信就强制在发送一遍--E
					$data = $this->send_wx_ship_code($record['shopid']);
					if($data){
						$wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");// 获取token
						$access_token= get_token($wechat['appid'],$wechat['appsecret']);
						$postUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
						$this->https_request($postUrl,$data);
					}
				}
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
		if (empty($record['dizhi_id'])) {
			//$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `uid` = '$uid' ORDER BY `default`  DESC LIMIT 1");
		}else{
			$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `id` = ".$record['dizhi_id']);
		}
		
		$go_time = $record['time'];
		include $this->tpl(ROUTE_M,'dingdan.code');	
	}
	//兑换商品
	public function exchange_shop(){

		if(!empty($_POST)){
			$posttime1 = strtotime($_POST['posttime1']);
			$posttime2 = strtotime($_POST['posttime2']);
			$user_type = $_POST['user_type'];
			$user = $_POST['user'];

			if($user_type == 'uid'){
				$uid = $user;
			}else if($user_type == 'mobile'){
				$arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$user'");
				$uid = $arr['uid'];
			}else{
				$arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `username` = '$user'");
				$uid = $arr['uid'];
			}

			if(!empty($_POST['posttime1']) && !empty($_POST['posttime2'])){
				if(empty($uid)){
					$recordlist=$this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid=n.id WHERE m.create_time>'$posttime1' AND m.create_time<'$posttime2' ORDER BY m.create_time DESC");
				}else{
					$recordlist=$this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid=n.id WHERE m.uid = '$uid' AND m.create_time>'$posttime1' AND m.create_time<'$posttime2' ORDER BY m.create_time DESC");
				}
			}else{
				$recordlist=$this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid=n.id WHERE m.uid = '$uid' ORDER BY m.create_time DESC");
			}
          $total = count($recordlist);
		}else{
			
			$num=20;

			$total=$this->db->GetCount("SELECT * FROM `@#_exchange_record` WHERE 1");

			$page=System::load_sys_class('page');

			if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

			$page->config($total,$num,$pagenum,"0");

			$recordlist=$this->db->GetPage("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid=n.id WHERE 1 ORDER BY m.create_time DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}
		include $this->tpl(ROUTE_M,'dingdan.exchange_shop');	
	}
	//兑换商品订单详情
	public function get_exchange_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT * FROM `@#_exchange_record` where `id`='$code'");

		$str = $this->db->GetOne("SELECT `title` FROM `@#_shoplist_exchange` WHERE `id` = '$record[shopid]'");

		if(!$record)_message("参数不正确!");

		

		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			if(!$company_money){
				$company_money = '0.01';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}
			if($status == '已发货'){
				$status = '已发货';
			}
			if($status == '未发货'){
				$status = '未发货';
			}
			if($status == '已完成'){
				$status = '已完成';	
			}
			if($status == '已作废'){
				$status = '已作废';				
			}			
			$ret = $this->db->Query("UPDATE `@#_exchange_record` SET `status`='$status',`company` = '$company',`company_code` = '$company_code' where id='$code'");
			if($ret){
				//调用发货通知
				if(_cfg("sendmobile")){
					//如果没有中奖短信就强制在发送一遍--E
					$data = $this->send_wx_ship_code($record['shopid']);
					if($data){
						$wechat= $this->db->GetOne("select * from `@#_wechat_config` where id = 1");// 获取token
						$access_token= get_token($wechat['appid'],$wechat['appsecret']);
						$postUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
						$this->https_request($postUrl,$data);
					}
				}
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}
		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
		if (empty($record['addr_id'])) {
			//$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `uid` = '$uid' ORDER BY `default`  DESC LIMIT 1");
		}else{
			$user_dizhi = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where `id` = ".$record['addr_id']);
		}
		
		$go_time = $record['time'];
		include $this->tpl(ROUTE_M,'dingdan.exchange');	
	}
	//订单搜索
	public function select(){
		$record = '';
		$cate = $_POST['cate'];
		if(isset($_POST['codesubmit'])){
			$code = htmlspecialchars($_POST['text']);
			if($cate == 'pt'){
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `company_code` = '$code' and `huode` != '0'");	
			}else if($cate == 'dh'){
				$record = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.company_code = '$code'");
			}else if($cate == 'csg'){
				$record = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.company_code = '$code' AND m.status = '1'");
			}
		}
		if(isset($_POST['usersubmit'])){	
			if($_POST['user'] == 'uid'){
				$uid = intval($_POST['text']);
				if($cate == 'pt'){
					$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `uid` = '$uid' and `huode` != '0'");	
				}else if($cate == 'dh'){
					$record = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.uid = '$uid'");
				}else if($cate == 'csg'){
					$record = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid = '$uid' AND m.status = '1'");
				}
			}
		}
		if(isset($_POST['shopsubmit'])){
			if($_POST['shop'] == 'sid'){
				$sid = intval($_POST['text']);
				if($cate == 'pt'){
					$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopid` = '$sid' and `huode` != '0'");
				}else if($cate == 'dh'){
					$record = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.shopid = '$sid'");
				}else if($cate == 'csg'){
					$record = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.shop_id = '$sid' AND m.status = '1'");
				}
			}
			if($_POST['shop'] == 'sname'){
				$sname= htmlspecialchars($_POST['text']);
				if($cate == 'pt'){
					$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopname` = '$sname' and `huode` != '0'");
				}else if($cate == 'dh'){
					$record = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE n.title = '$sname'");
				}else if($cate == 'csg'){
					$record = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE n.name = '$sname' AND m.status = '1'");
				}
			}
		}
		if(isset($_POST['timesubmit'])){
				$start_time = strtotime($_POST['posttime1']) ? strtotime($_POST['posttime1']) : time();				
				$end_time   = strtotime($_POST['posttime2']) ? strtotime($_POST['posttime2']) : time();
				if($cate == 'pt'){
					$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `time` > '$start_time' and `time` < '$end_time' and `huode` != '0'");
				}else if($cate == 'dh'){
					$record = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.f_time > '$start_time' and m.f_time < '$end_time'");
				}else if($cate == 'csg'){
					$record = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.f_time > '$start_time' and m.f_time < '$end_time' AND m.status = '1'");
				}
		}
		include $this->tpl(ROUTE_M,'dingdan.soso');	
	}
	//私有方法保存菜单
	private function https_request($url,$data = null){
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
	    return json_decode($output);
	}

	//发送发货通知
	private function send_wx_ship_code($gid=null){
		//查询模板消息id
		$template_id = $this->db->GetOne("SELECT * FROM `@#_wxch_cfg` WHERE `cfg_name` = 'template_fh'");
		if(empty($template_id['cfg_value'])){
			return false;
		}
		$info = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$gid'");
		$member_band = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '{$info['q_uid']}' AND `b_type` = 'weixin'");
		if(empty($member_band)){
			return false;
		}
		$orders = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `uid` = '{$info['q_uid']}' AND `shopid` = '{$info['id']}'");
		if(!empty($member_band['b_code'])){
			//发送数据组合
			$data = array(
				"touser" => $member_band['b_code'],
				"template_id"=>$template_id['cfg_value'],
				"url"=>WEB_PATH."/mobile/mobile/dataserver/".$info['id'], 
				"data" => array(
					'first' =>array(
						"value"=>"您好，您的中奖商品已经发货，请注意查收！",
						"color"=>"#173177",
						),
					"keyword1"=>array(
						"value"=>$orders['company'],
						"color"=>"#173177",
						),
					"keyword2"=>array(
						"value"=>$orders['company_code'],
						"color"=>"#173177",
						),
					"keyword3"=>array(
						"value"=>$info['title'],
						"color"=>"#173177",
						),
					"keyword4"=>array(
						"value"=>_cfg("web_name"),
						"color"=>"#173177",
						),
					"keyword5"=>array(
						"value"=>_cfg("cell"),
						"color"=>"#173177",
						),
					"remark"=>array(
						"value"=>"本订单由"._cfg("web_name")."提供发货及售后服务,感谢您的支持",
						"color"=>"#173177",
						),
				),
			);
		}
		return json_encode($data);
	}
	public function edit_zhuan_img(){
	
		$linkinfo=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='34' ");
		
		if(isset($_POST['submit'])){		
				$logo = $linkinfo['img'];
				if(!empty($_FILES['image']['tmp_name'])){
					System::load_sys_class('upload','sys','no');
					upload::upload_config(array('png','jpg','jpeg','gif'),500000,'linkimg');
					upload::go_upload($_FILES['image']);
					if(!upload::$ok){
						_message(upload::$error,WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/edit_zhuan_img");
					}
					$logo=upload::$filedir."/".upload::$filename;
				}			

				$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `img`='$logo' WHERE `id`='34'");
				if($this->db->affected_rows()){
						_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/edit_zhuan_img");
				}else{
						_message("修改失败");
				}		
		}
		include $this->tpl(ROUTE_M,'dingdan.wxpay_code1');	
	}

	//订单号查询
	public function dd_cx(){
		include $this->tpl(ROUTE_M,'dingdan.dd_cx');
	}

	//秒款号订单确认发货
	public function mk_dingdan(){
		$num=20;
	
		$list = $this->db->GetList("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%' and `company` = '惠享配送'");

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$record=$this->db->GetPage("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%' and `company` = '惠享配送' order by `time` ASC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	

		include $this->tpl(ROUTE_M,'dingdan.mk_dingdan');	
	}

	//秒款发货
	public function mk_fahuo(){
		$id = $this->segment(4);
		$data = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `dizhi_id` != 0 AND `status` LIKE '%未发货%' AND `company` = '惠享配送' AND `id` = '$id'");
		$time = time();
		if($data){
			$str = $this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,已发货,未完成',`s_time` = '$time' WHERE `id` = '$id'");
			if($str){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 0;
		}
	}

	//兑换秒款号订单确认发货
	public function dh_mk_dingdan(){
		$data =  $this->db->GetOne("SELECT `bind_phone` FROM `@#_member` where  `uid` = ".MK_UID);
		$mobile2 = explode('，',$data['bind_phone']);
		$mobile = implode(',', $mobile2);
		$str = $this->db->GetList("SELECT distinct `id` FROM `@#_member_dizhi` WHERE `mobile` in($mobile)");
		foreach ($str as $key => $val) {
			$id[] = $val['id'];
		}
		$id = implode(',', $id);

		$num=20;
	
		$list = $this->db->GetList("select * from `@#_exchange_record` where `addr_id` in($id) and `status` =  '未发货' and `company` = ''");

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$record=$this->db->GetPage("select * from `@#_exchange_record` where `addr_id` in($id) and `status` =  '未发货' and `company` = '' order by `create_time` ASC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	


		include $this->tpl(ROUTE_M,'dingdan.dh_mk_dingdan');	
	}

	//秒款发货
	public function dh_mk_fahuo(){
		$id = $this->segment(4);
		$data = $this->db->GetOne("SELECT * FROM `@#_exchange_record` WHERE `addr_id` != 0 AND `status` = '未发货' AND `id` = '$id'");
		$time = time();
		$time3 = explode ( " ", microtime () );
        $time3 = $time3[0] * 1000;  
        $time2 = explode (".", $time3 );  
        $time1 = $time2[0];
        $company_code = date("YmdHis",time()).$time1;
		if($data){
			$str = $this->db->Query("UPDATE `@#_exchange_record` SET `status` = '已发货',`company` = '惠享购配送',`f_time` = '$time',`company_code` = '$company_code' WHERE `id` = '$id'");
			if($str){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 0;
		}
	}

	//闯三关秒款号订单确认发货
	public function csg_mk_dingdan(){
		$data =  $this->db->GetOne("SELECT `bind_phone` FROM `@#_member` where  `uid` = ".MK_UID);
		$mobile2 = explode('，',$data['bind_phone']);
		$mobile = implode(',', $mobile2);
		$str = $this->db->GetList("SELECT distinct `id` FROM `@#_member_dizhi` WHERE `mobile` in($mobile)");
		foreach ($str as $key => $val) {
			$id[] = $val['id'];
		}
		$id = implode(',', $id);

		$num=20;
	
		$list = $this->db->GetList("select * from `@#_kh_record` where `dizhi_id` in($id) and `wuliu` =  '未发货' and `company` = '惠享购配送'");

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$record=$this->db->GetPage("select m.*,n.name from `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id where m.dizhi_id in($id) and m.wuliu =  '未发货' and m.company = '惠享购配送' order by m.dizhi_time ASC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	


		include $this->tpl(ROUTE_M,'dingdan.csg_mk_dingdan');	
	}

	//闯三关所有中奖商品
	public function csg_all_dingdan(){

		$num=20;

		$total= $this->db->GetCount("select COUNT(*) from `@#_kh_record` where `status` = '1'");

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$record=$this->db->GetPage("select m.*,n.name from `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id where m.status = '1' order by m.update_time DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	


		include $this->tpl(ROUTE_M,'dingdan.csg_all_dingdan');	
	}

	//闯三关秒款发货
	public function csg_mk_fahuo(){
		$id = $this->segment(4);
		$data = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `dizhi_id` != 0 AND `wuliu` = '未发货' AND `id` = '$id'");
		$time = time();
		
		if($data){
			$str = $this->db->Query("UPDATE `@#_kh_record` SET `wuliu` = '已发货',`f_time` = '$time' WHERE `id` = '$id'");
			if($str){
				echo 1;
			}else{
				echo 2;
			}
		}else{
			echo 0;
		}
	}

	public function csg_dadan(){
		$num=20;
        $data = $this->db->GetOne("SELECT `bind_phone` FROM `@#_member` WHERE `uid` = ".MK_UID);
      	$data2 = explode('，',$data['bind_phone']);
        $number = implode(',',$data2);
        $str = $this->db->GetList("SELECT `id` FROM `@#_member_dizhi` WHERE `mobile` in ($number)");
        $str = array_column($str,'id');
        $id2 = implode(',',$str);
      
		$list = $this->db->GetList("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE k.auto_user = '0' AND m.dizhi_id != '0' AND m.company_code = '' AND m.dizhi_id not in($id2) order by m.create_time desc");
		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("SELECT m.*,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE k.auto_user = '0' AND m.dizhi_id != '0' AND m.company_code = '' AND m.dizhi_id not in($id2) order by m.create_time desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	
		foreach ($list as $key => $val) {
			$str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$val[dizhi_id]' limit 1");
			$addr[$key]['name'] = $str['shouhuoren'];
			$addr[$key]['gh'] = '';
			$addr[$key]['mobile'] = $str['mobile'];
			$addr[$key]['dizhi'] = $str['sheng'].$str['shi'].$str['xian'].$str['jiedao'];
			$addr[$key]['bz'] = '';
			$addr[$key]['title'] = $val['name'];
			$addr[$key]['num'] = 1;
		}
		$time = date("Y-m-d",time())."活动";
		_setcookie('bt',$time,60*60);
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($addr));
      
		include $this->tpl(ROUTE_M,'dingdan.csg_dadan');	
	}
  
  	public function edit_qrcode_fenzu(){
    	$id = intval($_POST['id']);
      	$cateid = intval($_POST['cateid']);
      	$data = $this->db->Query("UPDATE `@#_wxpay_locat_config` SET `fenzu` = '$cateid' WHERE `id` = '$id'");
      	echo 1;
    }
  
    public function edit_qrcode_fenzu_name(){
    	$id = intval($_POST['id']);
		$name = trim($_POST['name']);
		$data = $this->db->Query("UPDATE `@#_wxpay_locat_config` SET `fenzu_bz` = '$name' WHERE `id` = '$id'");
		echo 1;
    }

}