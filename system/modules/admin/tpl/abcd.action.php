<?php 



defined('G_IN_SYSTEM')or exit('');

System::load_app_class('admin','','no');

System::load_app_fun('global');

class abcd extends admin {

	

	public function __construct(){

		parent::__construct();

		$this->db=System::load_sys_class('model');

	}

	public function goods(){
		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

						array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),

						array("xsjx","限时揭晓商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),

						array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),

						array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),

						array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),

						array("money","已揭晓",ROUTE_M.'/'.ROUTE_C."/goods_list/jiexiaook"),

						array("money","<font color='#f00'>期数已满商品</font>",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),

		);		

		$list_where = "`q_uid` is null  order by `id` DESC";

		if(isset($_POST['sososubmit'])){			

			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			

			$cateid = $_POST['cateid'];

			$sosotext = $_POST['sosotext'];	

			if($posttime1 && $posttime2){

				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

				$list_where = "`time` > '$posttime1' AND `time` < '$posttime2'";

			}

			if($posttime1 && empty($posttime2)){				

				$list_where = "`time` > '$posttime1'";

			}

			if($posttime2 && empty($posttime1)){				

				$list_where = "`time` < '$posttime2'";

			}

			if(empty($posttime1) && empty($posttime2)){				

				$list_where = false;

			}			

			$list_where = "`q_uid` is null ";

			if(!empty($cateid)){			

				$list_where .= " AND `cateid` = '$cateid'";

			}

			if(!empty($sosotext)){			

				$list_where .= " AND `title` like '%$sosotext%'";

			}		

			//var_dump($list_where);
		}		

	

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE $list_where"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE $list_where ");

		$catelist = $this->db->GetList("SELECT * FROM `@#_category` where `model` = 1 ");

		include $this->tpl(ROUTE_M,'abcd.lists');
	}

	public function add_goods(){
		if(!empty($_POST)){
			$shopid = intval($_POST['shopid']);
			$userid = intval($_POST['userid']);
			$time = time();
			$appoint = $this->db->GetOne("SELECT * from `@#_appoint` WHERE `shopid` ='$shopid' LIMIT 1");
			$goods =  $this->db->GetOne("SELECT * from `@#_shoplist` WHERE `id` ='$shopid' LIMIT 1");
			$user =  $this->db->GetOne("SELECT * from `@#_member` WHERE `uid` ='$userid' LIMIT 1");
			$ex_info=$this->db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shopid' and `uid`='{$userid}'");
			if(empty($user)){
				echo "指定中奖人不存在";
				exit();
			}
			if(empty($ex_info)){
				echo "指定的中奖人未参与购买，不能指定该用户！";
				exit();
			}
			if(!empty($appoint)){
				echo "该项目已经指定过中奖人，请删除后从新设置";
				exit();
			}
			if(!empty($goods['q_uid'])){
				echo "该项目已经开奖完毕不能设置中奖人，请从新设置";
				exit();
			}

			$res = $this->db->Query("INSERT INTO `@#_appoint` SET `shopid` = '$shopid',`userid` = '$userid',`time` = '$time'");
			//$res1 = $this->db->Query("UPDATE `@#_shoplist` SET `zhiding` = '$userid' WHERE `id`='$shopid'");

			if($res>0){
				echo "中奖人添加成功";
				exit();
			}else{
				echo "中奖人添加失败";
				exit();
			}

		}
	}
	public function pay_list(){

		$shopid=$this->segment(4);



		$page=System::load_sys_class('page');

		

		$pay_list=$this->db->GetList("SELECT * FROM `@#_member_go_record`  WHERE shopid = $shopid order by gonumber desc"); 


		$members=array();

		for($i=0;$i<count($pay_list);$i++){

			$uid=$pay_list[$i]['uid'];

			$member=$this->db->GetOne("select * from `@#_member` where `uid`='$uid'");

			$members[$i]=$member['username'];	

			if(empty($member['username'])){

				if(!empty($member['email'])){

					$members[$i]=$member['email'];

				}

				if(!empty($member['mobile'])){

					$members[$i]=$member['mobile'];

				}

			}

		}

		include $this->tpl(ROUTE_M,'abcd.pay_list');	

	}
	public function fundset(){

		

		$config = $this->db->GetOne("select * from `@#_fund` LIMIT 1");

		if(isset($_POST['dosubmit'])){

			$off = intval($_POST['fund_off']);

			$money = floatval(substr(sprintf("%.3f",$_POST['fund_money']), 0, -1));

			if(isset($_POST['fund_count_money'])){

				$count_money = floatval(substr(sprintf("%.3f",$_POST['fund_count_money']), 0, -1));

			}else{

				$count_money = $config['fund_count_money'];

			}

			if($money<=0){

				_message("基金出资金额不正确");

			}

			$this->db->Query("UPDATE `@#_fund` SET `fund_off` = '$off',`fund_money` = '$money',`fund_count_money` = '$count_money'");

			_message("修改成功");

		}		

		$config = $this->db->GetOne("select * from `@#_fund` LIMIT 1");

		include $this->tpl(ROUTE_M,'fundset');

	}

	public function specify(){
		if(isset($_POST['dosubmit'])){
			$shopid = intval($_POST['shopid']);
			$userid = intval($_POST['userid']);
			$time = time();
			$appoint = $this->db->GetOne("SELECT * from `@#_appoint` WHERE `shopid` ='$shopid' LIMIT 1");
			$goods =  $this->db->GetOne("SELECT * from `@#_shoplist` WHERE `id` ='$shopid' LIMIT 1");
			$user =  $this->db->GetOne("SELECT * from `@#_member` WHERE `uid` ='$userid' LIMIT 1");
			$ex_info=$this->db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shopid' and `uid`='{$userid}'");
			if(empty($user)){
				_message("指定中奖人不存在");
			}
			if(empty($ex_info)){
				_message("指定的中奖人未参与购买，不能指定该用户！");
			}
			if(!empty($appoint)){
				_message("该项目已经指定过中奖人，请删除后从新设置");
			}
			if(!empty($goods['q_uid'])){
				_message("该项目已经开奖完毕不能设置中奖人，请从新设置");
			}
			$res = $this->db->Query("INSERT INTO `@#_appoint` SET `shopid` = '$shopid',`userid` = '$userid',`time` = '$time'");
			$res1 = $this->db->Query("UPDATE `@#_shoplist` SET `zhiding` = '$userid' WHERE `id`='$shopid'");
			if($res>0 && $res1>0){
				_message("中奖人添加成功",G_ADMIN_PATH.'/abcd/specifylist/');
			}else{
				_message("中奖人添加失败");
			}

		}
		include $this->tpl(ROUTE_M,'specify');
	}

	public function specifylist(){

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_appoint`  WHERE 1"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");	

		$res=$this->db->GetPage("SELECT * FROM `@#_appoint` WHERE 1",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0)); 
		
		include $this->tpl(ROUTE_M,'specifylist');
	}


	//删除指定的人
	public function zddel(){
		$id=intval($this->segment(4));
		//$appoint = $this->db->GetOne("SELECT * from `@#_appoint` WHERE `id` ='$id' LIMIT 1");
		//查询商品的sid
		//$shopinfo = $this->db->GetOne("SELECT * from `@#_shoplist` WHERE `id` ='{$appoint['shopid']}' LIMIT 1");
		$res = $this->db->Query("DELETE FROM `@#_appoint` WHERE (`id`='$id') LIMIT 1");
		//$res1 = $this->db->Query("UPDATE `@#_shoplist` SET `zhiding` = 0 WHERE `sid`='{$shopinfo['sid']}' AND `q_uid` is Null");
			if($res>0){			
				_message("删除成功");
			}else{
				_message("删除失败");
			}
	}

}