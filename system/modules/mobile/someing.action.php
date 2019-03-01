<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class someing extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
      	$user = $this->userinfo;
      	if(!$user){
          	exit;
        	//header("Location:/index.php/mobile/user/login");exit();
        }
    }
  
    public function orderlist(){
		$psw = _getcookie('save_password');
		if(!$psw){
			header("Location:".WEB_PATH."/mobile/home/identification?type=1");exit;
		}
	    $webname=$this->_cfg['web_name'];

		$member=$this->userinfo;

		$title="获得的商品";
		$dizhi = $this->db->GetList("select * from `@#_member_dizhi` where `uid`='".$member['uid']."' ORDER BY id DESC");
		include templates("mobile/user","orderlist2");

	}
  	function do_set_place(){
      	$user = $this->userinfo;
      	if(!$user){
          	exit();
        }
    	if($_POST){
        	$rid = $_POST['rid'];
            $did = $_POST['did'];
            if(!$rid){
            	echo '{"code":"0","msg":"未选择商品"}';exit;
            }
            if(!$did){
            	echo '{"code":"0","msg":"未选择地址"}';exit;
            }
          	
            foreach($rid as $k=>$v){
            	$this->do_dizhi($v,$did);
            }
            echo '{"code":"1","msg":""}';exit;
        }
    }
  	private function do_add_place($rid,$did){
      	$user = $this->userinfo;
      	if(!$user){
          	return false;
        }
      	$uid = $user['uid'];
      	
      	$timed = time();
      	$p = $this->db->GetOne("select * from `@#_member_dizhi` where `id`='$did' and `uid`='$uid' LIMIT 1");
      	if(!$p){
        	return false;
        }
    	if($rid && $uid && $did){
        	$record = $this->db->GetOne("select * from `@#_member_go_record` where `id`='$rid' and `uid`='$uid' LIMIT 1");	
          	if($record['dizhi_id']){
            	return false;
            }else{
              	
            	$this->db->Query("UPDATE `@#_member_go_record` SET `dizhi_id` = '$did',`dizhi_time` = '$timed' WHERE `id` = '$rid' and `uid` = '$uid'");
              	return true;
            }
        }
      	return false;
    }
  	public function queren(){
      session_start();
      $id = $_SESSION['pl_addr_id'];
      if(empty($id)){
        _messagemobile("请选择商品","/index.php/mobile/someing/orderlist");
      }
      $uid = $this->userinfo['uid'];
      $addr = $this->db->GetList("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `is_delete` = '0'");
    	include templates("mobile/dizhi","queren");
    }
  	public function do_dizhi($sid,$did){
		if ($sid && $did) {
			$timed = time();

			$check = $this->db->Getone("select * from `@#_member_go_record` WHERE `id` = $sid");
			$shopid = $check['shopid'];

			$shop = $this->db->Getone("SELECT `cateid` FROM `@#_shoplist` WHERE `id` = '$shopid'");

			$data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$did);
			$data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);

			if (!empty($data2['bind_phone'])) {
				if (strpos($data2['bind_phone'],$data1['mobile']) !== false && $shop['cateid'] == '177') {
					return false;
				}
			}
			if (!empty($check['dizhi_id'])) {
				return false;
			}

			$q = $this->db->Query("UPDATE `@#_member_go_record` SET `dizhi_id` = $did,`dizhi_time` = $timed WHERE `id` = $sid");
			
			if (!empty($data2['bind_phone'])) {
				if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
					$time = explode ( " ", microtime () );
					$time = $time[0] * 1000;  
					$time2 = explode (".", $time );  
					$time = $time2[0];
					$company_code = date("YmdHis",time()).$time;
					$this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,未发货,未完成' ,`company` = '惠享配送',`company_code` = '$company_code' WHERE `id` = $sid");
				}
			}
			

			if ($q) {
				return true;
			}else{
				return false;
			}
		}
		
	}
  	function get_dizhi(){
    	$user = $this->userinfo;
      	$uid = $user['id'];
      	if(!$user){
        	//exit();
        }
      	$list = $this->db->GetList("select * from `@#_member_dizhi` where `uid`='$uid' order by id desc");
      	echo json_encode($list);
    }
  	public function ssss(){
      	$id = '';
    	$this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,未发货,未完成' ,`company` = '惠享配送',`company_code` = '$company_code' WHERE `id` = $id");
    }

    function post_addr_id(){
      $uid = $this->userinfo['uid'];
      $id = trim($_POST['id']);
      $arr = explode(',',$id);
      foreach ($arr as $key => $val) {
         $sid = $val;
         $data = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `id` = '$sid' AND `huode` != '0' AND `dizhi_id` = '0' AND `uid` = '$uid'");
         if(!$data){
            echo false;exit;
         }
      }
      session_start();
      $_SESSION['pl_addr_id'] = $id;
      echo true;
    }


    //批量完善地址
    function do_pl_addr(){
      session_start();
      $id = $_SESSION['pl_addr_id'];
      $arr = explode(',',$id);

      $did = intval($_POST['did']);
      $uid = $this->userinfo['uid'];
      $str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `id` = '$did'");
      if(empty($id)){
        $pl_data['flag'] = 2;
        $pl_data['status'] = "未选择商品！";
        echo json_encode($pl_data);
        exit;
      }
      if(empty($did)){
        $pl_data['flag'] = 3;
        $pl_data['status'] = "未选择地址！";
        echo json_encode($pl_data);
        exit;
      }
      if(!$str){
        $pl_data['flag'] = 0;
        $pl_data['status'] = "非法完善地址！";
        echo json_encode($pl_data);
        exit;
      }
      foreach ($arr as $key => $val) {
         $sid = $val;
         $data = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `id` = '$sid' AND `huode` != '0' AND `dizhi_id` = '0' AND `uid` = '$uid' AND `type` = '0'");
         if($data){
            $timed = time();

            $check = $this->db->Getone("select * from `@#_member_go_record` WHERE `id` = $sid");
            $shopid = $check['shopid'];

            $shop = $this->db->Getone("SELECT `cateid`,`str1` FROM `@#_shoplist` WHERE `id` = '$shopid'");

            $data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);

            if (!empty($data2['bind_phone'])) {
              if (strpos($data2['bind_phone'],$str['mobile']) !== false && $shop['cateid'] == '177') {
                continue;
              }
            }
            if (!empty($check['dizhi_id']) || $shop['str1'] != 0) {
              continue;
            }

            $q = $this->db->Query("UPDATE `@#_member_go_record` SET `dizhi_id` = $did,`dizhi_time` = $timed WHERE `id` = $sid");
            
            if (!empty($data2['bind_phone'])) {
              if (strpos($data2['bind_phone'],$str['mobile']) !== false) {
                $time = explode ( " ", microtime () );
                $time = $time[0] * 1000;  
                $time2 = explode (".", $time );  
                $time = $time2[0];
                $company_code = date("YmdHis",time()).$time;
                $this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,未发货,未完成' ,`company` = '惠享配送',`company_code` = '$company_code' WHERE `id` = $sid");
              }
            }
         }
      }
      $_SESSION['pl_addr_id'] = '';
      $pl_data['flag'] = 1;
      $pl_data['status'] = "地址完善成功,正在跳转！";
      echo json_encode($pl_data);
    }

    function add_addr(){
      $uid = $this->userinfo['uid'];
      $time = time();
      $name = trim($_POST['name']);
      $mobile = intval($_POST['mobile']);
      $sheng = trim($_POST['sheng']);
      $shi = trim($_POST['shi']);
      $xian = trim($_POST['xian']);
      $jiedao = trim($_POST['jiedao']);

      if($name == '' || $mobile == '' || $sheng == '' || $shi == '' || $xian == '' || $jiedao == ''){
        echo 0;exit;
      }
      if(!_checkmobile($mobile)){
        echo 1;exit;
      }
      $data = $this->db->Query("INSERT INTO `@#_member_dizhi` (`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`time`)VALUES('$uid','$sheng','$shi','$xian','$jiedao','$name','$mobile','$time')");
      if($data){
        echo 2;
      }else{
        echo 3;
      }
    }

    function del_addr(){
      $id = intval($_POST['id']);
      $uid = $this->userinfo['uid'];
      $str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `id` = '$id'");
      if($str){
        $str2 = $this->db->Query("UPDATE `@#_member_dizhi` SET `is_delete` = '1' WHERE `uid` = '$uid' AND `id` = '$id'");
        if($str2){
          $data['flag'] = 1;
          $data['status'] = "删除地址成功！";
        }else{
          $data['flag'] = 2;
          $data['status'] = "删除地址失败！";
        }
        
      }else{
        $data['flag'] = 3;
        $data['status'] = '非法删除地址！';
      }
      echo json_encode($data);
    }

    function edit_addr(){
      $id = intval($_POST['id']);
      $uid = $this->userinfo['uid'];
      $str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `id` = '$id' AND `is_delete` = '0'");
      if($str){
        $data['flag'] = 1;
        $data['name'] = $str['shouhuoren'];
        $data['mobile'] = $str['mobile'];
        $data['dq'] = $str['sheng'].$str['shi'].$str['xian'];
        $data['sheng'] = $str['sheng'];
        $data['shi'] = $str['shi'];
        $data['xian'] = $str['xian'];
        $data['jiedao'] = $str['jiedao'];
      }else{
        $data['flag'] = 2;
        $data['status'] = '非法修改地址！';
      }
      echo json_encode($data);
    }

    function do_edit_addr(){
      $id = intval($_POST['id']);
      $uid = $this->userinfo['uid'];
      $str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `id` = '$id' AND `is_delete` = '0'");

      $time = time();
      $name = trim($_POST['name']);
      $mobile = intval($_POST['mobile']);
      $sheng = trim($_POST['sheng']);
      $shi = trim($_POST['shi']);
      $xian = trim($_POST['xian']);
      $jiedao = trim($_POST['jiedao']);

      if($str){
        if($name == '' || $mobile == '' || $sheng == '' || $shi == '' || $xian == '' || $jiedao == ''){
          $data['flag'] = 2;
          $data['status'] = "地址填写有误，请重新填写！";
          echo json_encode($data);exit;
        }
        if(!_checkmobile($mobile)){
          $data['flag'] = 3;
          $data['status'] = "手机号码格式错误！";
          echo json_encode($data);exit;
        }
        
        $str2 = $this->db->Query("UPDATE `@#_member_dizhi` SET `is_delete` = '1' WHERE `uid` = '$uid' AND `id` = '$id'");
        if($str2){
          $data2 = $this->db->Query("INSERT INTO `@#_member_dizhi` (`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`time`)VALUES('$uid','$sheng','$shi','$xian','$jiedao','$name','$mobile','$time')");
          if($data2){
            $data['flag'] = 1;
            $data['status'] = "修改地址成功！";
            echo json_encode($data);exit;
          }else{
            $data['flag'] = 2;
            $data['status'] = "修改地址失败！";
            echo json_encode($data);exit;
          }
        }else{
          $data['flag'] = 2;
          $data['status'] = "修改地址失败！";
          echo json_encode($data);exit;
        }
        
      }else{
        $data['flag'] = 4;
        $data['status'] = '非法修改地址！';
        echo json_encode($data);exit;
      }
    }
}