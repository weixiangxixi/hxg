<?php
defined('G_IN_SYSTEM')or exit('no');
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class fifa extends admin {

	private $db;

	public function __construct(){		

		parent::__construct();		

		$this->db=System::load_sys_class('model');	
	}
	public function aa(){
    	echo 1;
    }
  
	public function history(){

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

				$times= "`time`>='$posttime1' AND `time`<='$posttime2'";

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
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_fifa_guess_list`");
		}else{
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_fifa_guess_list` where ".$wheres);
		}
		 
		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if (empty($wheres)) {
			$recordlist=$this->db->GetPage("select * from `@#_fifa_guess_list` order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}else{
			$recordlist = $this->db->GetList("SELECT * FROM `@#_fifa_guess_list` WHERE ".$wheres." order by id DESC");
		}
		
		

		include $this->tpl(ROUTE_M,'fifa.list');	
	}
	
	public function setting(){
		$config = $this->db->GetOne("SELECT * FROM `@#_fifa_config` where `id`='1'");
		if(isset($_POST['dosubmit'])){

			$status = intval($_POST['status']);

			$money = $_POST['money'];
          	
          	$num1 = $_POST['num1'];
          	$num2 = $_POST['num2'];
			//echo $status;exit();
			$this->db->Query("UPDATE `@#_fifa_config` SET `money` = '$money',`num1` = '$num1',`num2` = '$num2',`status` = '$status' where `id`='1'");

			_message("配置更新成功!");

		}
		include $this->tpl(ROUTE_M,'fifa.config');
	}
	
  	public function user(){
      	$where = '';
      	$total = 100;
		if (empty($wheres)) {
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_fifa_user`");
		}else{
			$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_fifa_user` where ".$wheres);
		}
		 
		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		if (empty($wheres)) {
			$pay_list=$this->db->GetPage("select * from `@#_fifa_user` order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}else{
			$pay_list = $this->db->GetList("SELECT * FROM `@#_fifa_user` WHERE ".$wheres." order by id DESC");
		}
    	include $this->tpl(ROUTE_M,'fifa.user');
    }
	
  	public function yaoqing(){
      	$yaoqing = $this->segment(4);
      	$where = '';
      	$total = 100;
      	if(empty($yaoqing)){
        	_message("没有邀请人!",WEB_PATH.'/'.ROUTE_M.'/fifa/user');
        }
		$total = $this->db->GetCount("SELECT COUNT(*) FROM `@#_fifa_user` where `yaoqing`= '$yaoqing' ");
		
		//exit($yaoqing);
		$num=20;

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		
		$pay_list=$this->db->GetPage("select * from `@#_fifa_user` where `yaoqing`= '$yaoqing' order by id DESC ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		
		
    	include $this->tpl(ROUTE_M,'fifa.yaoqing');
    }

    public function money_times(){
    	$data = $this->db->GetList("SELECT * FROM `@#_fifa_list` WHERE `match_status` = 0");
  		include $this->tpl(ROUTE_M,'fifa.money_times');
    }

    public function do_money_times(){
    	$x = $_POST['hh'];
    	$match_id = $_POST['match_id'];
    	$val = $_POST['val'];

    	$data = $this->db->Query("UPDATE `@#_fifa_list` SET `$x` = '$val' WHERE `match_id` = '$match_id'");
    	if($data){
    		echo 1;
    	}else{
    		echo 0;
    	}
    }

    public function hmd(){

		if(isset($_POST['submit'])){

			$sousuo=htmlspecialchars(trim($_POST['sousuo']));

			$content=htmlspecialchars(trim($_POST['content']));

		

			if(empty($sousuo) || empty($content)){

				_message("参数错误");

			}

			$members = array();

			if($sousuo=='id'){			

				$members[0]=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$content'");				

			}

			if($sousuo=='nickname'){	

				$members=$this->db->GetList("SELECT * FROM `@#_member` WHERE `username` LIKE '%$content%'"); 

			}

			if($sousuo=='email'){				

				$members=$this->db->GetList("SELECT * FROM `@#_member` WHERE `email` LIKE '%$content%'");				

			}

			if($sousuo=='mobile'){

				$members=$this->db->GetList("SELECT * FROM `@#_member` WHERE `mobile` LIKE '%$content%'");			

			}			

			

		}

		include $this->tpl(ROUTE_M,'fifa.hmd');

		

	}

	public function add_hmd(){
		$uid = $this->segment(4);
		if(!empty($uid)){
			$str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
			$mobile = $str['mobile'];
			$time = time();
			$arr = $this->db->GetOne("SELECT * FROM `@#_fifa_hmd` WHERE `uid` = '$uid'");
			if($arr){
				$ccc = $this->db->Query("DELETE FROM `@#_fifa_hmd` WHERE `uid` = '$uid'");
				if($ccc){
					_message("撤销黑名单成功");exit;
				}else{
					_message("撤销黑名单失败");exit;
				}
			}
			$data = $this->db->Query("INSERT INTO `@#_fifa_hmd` (`uid`,`mobile`,`create_time`)VALUES('$uid','$mobile','$time')");
			if($data){
				_message("添加黑名单成功");
			}else{
				_message("添加黑名单失败");
			}
		}
	}
}


?>