<?php 

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_sys_fun("user");
class shaidan_admin extends admin {
	private $db;
	public function __construct(){		
		parent::__construct();	
		$this->ment=array(
			array("lists","晒单管理",ROUTE_M.'/'.ROUTE_C.""),
			array("addcate","晒单回复管理",ROUTE_M.'/'.ROUTE_C."/sd_hueifu"),
			array("addcate","待审核晒单管理",ROUTE_M.'/'.ROUTE_C."/shaidan_dsh"),
			array("addcate","待审核晒单回复管理",ROUTE_M.'/'.ROUTE_C."/sd_hueifu_dsh"),
		);
		$this->db=System::load_sys_class('model');		
	} 	
	public function init(){	
		$num=20;
		$total=$this->db->GetCount("SELECT * from `@#_shaidan` WHERE `status` = '1'"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$shaidan=$this->db->GetPage("SELECT * from `@#_shaidan` WHERE `status` = '1'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		
		include $this->tpl(ROUTE_M,'shaidan.list');
	}
	public function sd_del(){
		$id=intval($this->segment(4));
		$shaidanx=$this->db->getlist("select * from `@#_shaidan` where `sd_id`='$id' limit 1 ");
		if($shaidanx){
			$this->db->Query("DELETE FROM `@#_shaidan` where `sd_id`='$id' ");
			_message("删除成功");
		}else{
			_message("参数错误");
		}		
	}
	public function hf_del(){
		$id=intval($this->segment(4));
		$shaidanx=$this->db->getlist("select * from `@#_shaidan_hueifu` where `id`='$id' limit 1 ");
		if($shaidanx){
			$this->db->Query("DELETE FROM `@#_shaidan_hueifu` where `id`='$id' ");
			_message("删除成功");
		}else{
			_message("参数错误");
		}
	}
	public function sd_hueifu(){
		$num=20;
		$total=$this->db->GetCount("SELECT * from `@#_shaidan_hueifu` WHERE `status` = '1'"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$shaidan=$this->db->GetPage("SELECT * from `@#_shaidan_hueifu` WHERE `status` = '1'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		
		include $this->tpl(ROUTE_M,'shaidan.liuyan');
	}
	//待审核晒单
	public function shaidan_dsh(){	
		$num=20;
		$total=$this->db->GetCount("SELECT * from `@#_shaidan` WHERE `status` = '0'"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$shaidan=$this->db->GetPage("SELECT * from `@#_shaidan` WHERE `status` = '0'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		
		include $this->tpl(ROUTE_M,'shaidan.list_dsh');
	}
	//审核通过
	public function shtg(){
		$id = intval($_POST['id']);
		$score = intval($_POST['score']);
		$time = time();
		$this->db->Autocommit_start();
		$data = $this->db->Query("UPDATE `@#_shaidan` SET `sd_score`='$score',`status`='1' WHERE `sd_id` = '$id'");
		$brr = $this->db->GetOne("SELECT `sd_userid` FROM `@#_shaidan` WHERE `sd_id` = '$id'");
		$data2 = $this->db->Query("UPDATE `@#_member` SET `score` = `score` + '$score' WHERE `uid` = '$brr[sd_userid]'");
		$data3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$brr[sd_userid]','1','积分','晒单获得奖励福分','$score','$time')");
		if($data && $data2 && $data3){
			$this->db->Autocommit_commit();
			$str['success'] = 1;
		}else{
			$str['success'] = 0;
			$this->db->Autocommit_rollback();
		}
		echo json_encode($str);
	}
	//待审核回复
	public function sd_hueifu_dsh(){
		$num=20;
		$total=$this->db->GetCount("SELECT * from `@#_shaidan_hueifu` WHERE `status` = '0'"); 
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}		
		$page->config($total,$num,$pagenum,"0"); 
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}	
		$shaidan=$this->db->GetPage("SELECT * from `@#_shaidan_hueifu` WHERE `status` = '0'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		
		include $this->tpl(ROUTE_M,'shaidan.liuyan_dsh');
	}
	//审核通过回复
	public function shtg_hf(){
		$id = intval($_POST['id']);
		$score = intval($_POST['score']);
		$time = time();
		$this->db->Autocommit_start();
		$data = $this->db->Query("UPDATE `@#_shaidan_hueifu` SET `score`='$score',`status`='1' WHERE `id` = '$id'");
		$brr = $this->db->GetOne("SELECT `sdhf_userid` FROM `@#_shaidan_hueifu` WHERE `id` = '$id'");
		$data2 = $this->db->Query("UPDATE `@#_member` SET `score` = `score` + '$score' WHERE `uid` = '$brr[sdhf_userid]'");
		$data3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$brr[sdhf_userid]','1','积分','评论晒单获得奖励福分','$score','$time')");
		if($data && $data2 && $data3){
			$this->db->Autocommit_commit();
			$str['success'] = 1;
		}else{
			$str['success'] = 0;
			$this->db->Autocommit_rollback();
		}
		echo json_encode($str);
	}
}
?>