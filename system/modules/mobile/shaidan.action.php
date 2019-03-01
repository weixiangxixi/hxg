<?php

defined('G_IN_SYSTEM')or exit('no');
System::load_app_fun('global',G_ADMIN_DIR);
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_app_class("base","member","no");
System::load_sys_fun('user');
class shaidan extends base {
	public $db;
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');

	}

	//晒单分享
	public function init(){
	    $webname=$this->_cfg['web_name'];
		$key="晒单";
		include templates("mobile/index","shaidan");
	}
	public function shaidanajax(){
		$parm=htmlspecialchars($this->segment(4));
		$p=htmlspecialchars($this->segment(5)) ? htmlspecialchars($this->segment(5)) :1;
		//分页
		$end=10;
		$star=($p-1)*$end;

		if($parm=='new'){
			$sel='`sd_time`';
		}else if($parm=='renqi'){
			$sel='`sd_zhan`';
		}else if($parm=='pinglun'){
			$sel='`sd_ping`';
		}
		$count=$this->db->GetList("select * from `@#_shaidan` WHERE `status` = '1' order by $sel DESC");
		$shaidan=$this->db->GetList("select * from `@#_shaidan` WHERE `status` = '1' order by $sel DESC limit $star,$end");

		foreach($shaidan as $sd){
			$user[]=get_user_name($sd['sd_userid']);
			$time[]=date("Y-m-d H:i",$sd['sd_time']);
			$member=$this->db->GetOne("select * from `@#_member` where `uid`='$sd[sd_userid]'");
			if ($member['img']!='photo/member.jpg') {
				$pic[]="/statics/uploads/".$member['img'];
			}elseif ($member['headimg']!=''){
				$pic[]=$member['headimg'];
			}else{
				$pic[]="/statics/uploads/".$member['img'];
			}
			
		}
		for($i=0;$i<count($shaidan);$i++){
			$shaidan[$i]['user']=$user[$i];
			$shaidan[$i]['time']=$time[$i];
			$shaidan[$i]['pic']=$pic[$i];
		}
		$pagex=ceil(count($count)/$end);
		if($p<=$pagex){
			$shaidan[0]['page']=$p+1;
		}
		if($pagex>0){
			$shaidan[0]['sum']=$pagex;
		}else if($pagex==0){
			$shaidan[0]['sum']=$pagex;
		}
		echo json_encode($shaidan);
	}

	public function detail(){
	    $webname=$this->_cfg['web_name'];
		$key="晒单分享";
		$member=$this->userinfo;
		$sd_id=intval($this->segment(4));
		$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id' AND `status` = '1'");
		$shaidanname=$this->db->GetOne("select * from `@#_member` where `uid`='$shaidan[sd_userid]'");
		if(!empty($shaidan['sd_shopid'])){
			$goods = $this->db->GetOne("select * from `@#_shoplist` where `sid` = '$shaidan[sd_shopid]' order by `qishu` DESC");
		}else{
			$goods = $this->db->GetOne("select * from `@#_jf_shoplist` where `sid` = '$shaidan[sd_shopid]' order by `qishu` DESC");
		}
		$shaidannew=$this->db->GetList("select * from `@#_shaidan` order by `sd_id` DESC limit 5");
		$shaidan_hueifu=$this->db->GetList("select m.*,n.img from `@#_shaidan_hueifu` as m left join `@#_member` as n on m.sdhf_userid = n.uid where `sdhf_id`='$sd_id' AND `status` = '1'");
		if(!$shaidan){
			_messagemobile('页面错误!');
		}
		$substr=substr($shaidan['sd_photolist'],0,-1);
		$sd_photolist=explode(";",$substr);

		$data = $this->db->GetOne("SELECT * FROM `@#_shaidan_zhan` WHERE `sd_id` = '$sd_id' AND `uid` = '$member[uid]'");
		if($data){
			$ydz = 1;
		}
		if(!empty($shaidan['sd_shopid'])){
			include templates("mobile/index","detail");
		}else{
			include templates("mobile/index","jf_detail");
		}
	}
	public function plajax(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		if(!is_array($member)){
			echo "页面错误";exit;
		}
		$sdhf_id=$_POST['sd_id'];
		$sdhf_userid=$member['uid'];
		$sdhf_content=$_POST['count'];
		$sdhf_time=time();
		if($sdhf_content==null){
			echo "页面错误";exit;
		}
		$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sdhf_id'");
		$this->db->Query("INSERT INTO `@#_shaidan_hueifu`(`sdhf_id`,`sdhf_userid`,`sdhf_content`,`sdhf_time`)VALUES
		('$sdhf_id','$sdhf_userid','$sdhf_content','$sdhf_time')");

		$sd_ping=$shaidan['sd_ping']+1;
		$this->db->Query("UPDATE `@#_shaidan` SET sd_ping='$sd_ping' where sd_id='$shaidan[sd_id]'");
		echo "1";
	}
	//羡慕嫉妒恨
	public function xianmu(){
	    $webname=$this->_cfg['web_name'];
	    $member=$this->userinfo;
		$sd_id=$_POST['id'];
		if(!$member){
			$data['status'] = 3;
			$data['msg'] = "/index.php/mobile/user/login";
		}else{
			$this->db->Autocommit_start();
			$str = $this->db->Query("UPDATE `@#_shaidan` SET `sd_zhan` = `sd_zhan` + 1 where sd_id='".$sd_id."'");
			$brr = $this->db->GetOne("SELECT `sd_zhan` FROM `@#_shaidan` WHERE `sd_id` = '$sd_id'");
			$time = time();
			$crr = $this->db->Query("INSERT INTO `@#_shaidan_zhan` (`uid`,`sd_id`,`create_time`)VALUES('$member[uid]','$sd_id','$time')");
			if($str && $crr){
				$data['status'] = 1;
				$data['msg'] = "点赞成功";
				$data['num'] = $brr['sd_zhan'];
				$this->db->Autocommit_commit();
			}else{
				$data['status'] = 2;
				$data['msg'] = "您已经参与过点赞了";
				$this->db->Autocommit_rollback();
			}
		}
		
		echo json_encode($data);
	}
}
?>