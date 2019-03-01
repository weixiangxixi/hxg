<?php 
defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class dingdan1 extends admin {

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

						array("lists","扫码充值订单",ROUTE_M.'/'.ROUTE_C."/wxpay")


						

		);

	}

	public function alipay(){
		if(isset($_POST['sososubmit'])){

			$posttime1=isset($_POST['posttime1'])?$_POST['posttime1']:'';

			$posttime2=isset($_POST['posttime2'])?$_POST['posttime2']:'';

			if(empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空！");

			if(!empty($posttime1) && !empty($posttime2)){ //如果2个时间都不为空

				$posttime1=strtotime($posttime1);

				$posttime2=strtotime($posttime2);

				if($posttime1 > $posttime2){

					_message("前一个时间不能大于后一个时间");

				}

				$times= "`create_time`>='$posttime1' AND `create_time`<='$posttime2'";

			}

			$wheres=$times;
		}
		//var_dump($wheres);
		
		$total = 100;
		if (empty($wheres)) {
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_alipay_locat`");
		}else{
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_alipay_locat where ".$wheres);
		}

		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if (empty($wheres)) {
			$recordlist=$this->db->GetPage("select * from `@#_alipay_locat` order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}else{
			$recordlist=$this->db->GetPage("select * from `@#_alipay_locat` where ".$wheres." order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}
		
		$CheckId = _encrypt(_getcookie("AID"), 'DECODE');
		$set_open=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='27'");
		$song_open=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='18'");
		
		$a1 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='11'");	
		
		$a2 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='12'");	
	
		$a3 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='13'");	
	
		$a4 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='14'");	
	
		$a5 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='15'");	
	
		$a6 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='16'");	
	
		$a7 = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `id`='17'");	

		include $this->tpl(ROUTE_M,'dingdan.list4');	
	}
	public function edit_qrcode(){
		$codelist = $this->db->GetList("SELECT * FROM `@#_wxpay_locat_config` where `canshu`='alipaycode'");	
		include $this->tpl(ROUTE_M,'dingdan.editqrcode1');
	}
	public function add_wxpay_user(){
		if (!empty($_POST)) {
			$data=$this->db->GetOne("SELECT * FROM `@#_wxpay_locat_config` where `canshu`='alipaycode' order by id desc limit 1");
			$money = $data['zhi'] + 1;
			$this->db->Query("INSERT INTO `@#_wxpay_locat_config` (`canshu`, `zhi`) VALUES ('alipaycode', '$money')");
			echo 0;
		}	
	}
	public function edit_qrcode_img(){
		$zhi=intval($this->segment(4));
		$money=intval($this->segment(5));

		$linkinfo=$this->db->GetOne("SELECT * FROM `@#_alipay_qrcode` where `zhi`='$zhi' and `num`='$money' ");
		$id = $linkinfo['id'];

		if (empty($linkinfo)) {
			$this->db->Query("INSERT INTO `@#_alipay_qrcode` (`zhi`, `num`, `img`) VALUES ('$zhi', '$money', ' ')");
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

				$this->db->Query("UPDATE `@#_alipay_qrcode` SET `img`='$logo' WHERE `id`='$id'");
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

	public function wxpay(){
		if(isset($_POST['sososubmit'])){

			$posttime1=isset($_POST['posttime1'])?$_POST['posttime1']:'';

			$posttime2=isset($_POST['posttime2'])?$_POST['posttime2']:'';

			if(empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空！");

			if(!empty($posttime1) && !empty($posttime2)){ //如果2个时间都不为空

				$posttime1=strtotime($posttime1);

				$posttime2=strtotime($posttime2);

				if($posttime1 > $posttime2){

					_message("前一个时间不能大于后一个时间");

				}

				$times= "`create_time`>='$posttime1' AND `create_time`<='$posttime2'";

			}

			$wheres=$times;
		}
		//var_dump($wheres);
		
		$total = 100;
		if (empty($wheres)) {
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_wxpay_locat`");
		}else{
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_wxpay_locat where ".$wheres);
		}

		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if (empty($wheres)) {
			$recordlist=$this->db->GetPage("select * from `@#_wxpay_locat` order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}else{
			$recordlist=$this->db->GetPage("select * from `@#_wxpay_locat` where ".$wheres." order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}
		
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
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `open`='$open' WHERE `id`='27'");
		}
		header("Location:/admin/dingdan1/alipay");
	}
	public function song_wxpay_open(){
		$open=intval($this->segment(4));
		if ($open == 0 || $open ==1) {
			$this->db->Query("UPDATE `@#_wxpay_locat_config` SET `open`='$open' WHERE `id`='18'");
		}
		header("Location:/admin/dingdan1/alipay");
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

			$data = $this->db->GetOne("select * from `@#_alipay_locat` where `id`=".$id);
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过支付宝线下扫码充值".$money."元";

			$rs1 = $this->db->Query("UPDATE `@#_alipay_locat` SET `status`='1',`update_time`=$timed,`aduser`=$CheckId where id='$id'");

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

			$data = $this->db->GetOne("select * from `@#_alipay_locat` where `id`=".$id);
			if ($data['status']==0) {
				echo 1;
				exit();
			}
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过支付宝线下扫码充值".$money."元";

			$rs1 = $this->db->Query("UPDATE `@#_alipay_locat` SET `zhuijia`=`zhuijia` + '1' where id='$id'");

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

			$data = $this->db->GetOne("select * from `@#_alipay_locat` where `id`=".$id);
			$uid = $data['uid'];
			$money = intval($data['money']);
			$content = "通过支付宝线下扫码充值".$money."元";

			$rs1 = $this->db->Query("UPDATE `@#_alipay_locat` SET `status`='0',`update_time`=0,`aduser`=$CheckId where id='$id'");

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
		$num=20;

	

		$total=1000;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("select `@#_member_go_record`.* from `@#_member_go_record`,`@#_member` where `@#_member_go_record`.uid=`@#_member`.uid and `@#_member`.auto_user=0 and `@#_member_go_record`.huode !='0' order by `@#_member_go_record`.time DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	
		foreach ($recordlist as $k => $v) {
			$sh = $this->db->GetOne("select * from `@#_shoplist` where `id` = ".$v['shopid']);
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
		$list = $this->db->GetList("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%'");
	

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("select * from `@#_member_go_record` where `dizhi_id` != 0 and `status` LIKE  '%未发货%' ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	

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

			$list_where = "where `huode` != '0' and  `status` LIKE  '%已发货%'";

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

		

		if(isset($_POST['paixu_submit'])){

			$paixu = $_POST['paixu'];

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

	//订单详细

	public function get_dingdan(){

		$code=abs(intval($this->segment(4)));

		$record=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where `id`='$code'");

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
			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status',`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money' where id='$code'");
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
	//订单搜索
	public function select(){
		$record = '';
		if(isset($_POST['codesubmit'])){
			$code = htmlspecialchars($_POST['text']);		
			$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `code` = '$code'");	
		}
		if(isset($_POST['usersubmit'])){	
			if($_POST['user'] == 'uid'){
				$uid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `uid` = '$uid'");	
			}
		}
		if(isset($_POST['shopsubmit'])){
			if($_POST['shop'] == 'sid'){
				$sid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopid` = '$sid'");
			}
			if($_POST['shop'] == 'sname'){
				$sname= htmlspecialchars($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopname` = '$sname'");
			}
		}
		if(isset($_POST['timesubmit'])){
				$start_time = strtotime($_POST['posttime1']) ? strtotime($_POST['posttime1']) : time();				
				$end_time   = strtotime($_POST['posttime2']) ? strtotime($_POST['posttime2']) : time();
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `time` > '$start_time' and `time` < '$end_time'");
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

}