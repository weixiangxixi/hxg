<?php 



defined('G_IN_SYSTEM')or exit('no');

System::load_app_class('admin',G_ADMIN_DIR,'no');

class chaxun extends admin {

	private $db;

	protected $ment;

	public function __construct(){		

		parent::__construct();

		$this->db=System::load_sys_class("model");
		

	}

	public function login(){

		if(isset($_POST['ajax'])){			

			$location=WEB_PATH.'/'.ROUTE_M.'/chaxun';

			$message=array("error"=>false,'text'=>$location);

			$username=$_POST['username'];

			$password=$_POST['password'];

			$code=strtolower($_POST['code']);			

			if(empty($username)){$message['error']=true;$message['text']="请输入用户名!";echo json_encode($message);exit;}

			if(empty($password)){$message['error']=true;$message['text']="请输入密码!";echo json_encode($message);exit;}			

			

			if(_cfg("web_verify")){

				if(empty($code)){$message['error']=true;$message['text']="请输入验证码!";echo json_encode($message);exit;}

				if(md5($code)!=_getcookie('checkcode')){$message['error']=true;$message['text']="验证码输入错误";echo json_encode($message);exit;}

			}

			

			$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `username` = '$username' AND `uid` = '6' LIMIT 1");		

			if(!$info){$message['error']=true;$message['text']="登录失败,请检查用户名或密码!";echo json_encode($message);exit;}

			if($info['userpass']!=md5($password)){$message['error']=true;$message['text']="登陆失败!";echo json_encode($message);exit;}

			

			if(!$message['error']){				

				_setcookie("AID",_encrypt($info['uid'],'ENCODE'));

				_setcookie("ASHELL",_encrypt(md5($info['username'].$info['userpass'])));

				$this->AdminInfo=$info;

				$time=time();$ip=_get_ip();

				$this->db->Query("UPDATE `@#_admin` SET `logintime`='$time' WHERE (`uid`='$info[uid]')");

				//$this->db->Query("UPDATE `@#_admin` SET `loginip`='$ip' WHERE (`uid`='$info[uid]')");

			}

			echo json_encode($message);

			exit;			

		}else{

			include $this->tpl(ROUTE_M,'chaxun.login');

		}

	}

	public function init(){
   
    	if(empty($time1) || empty($time2) || $CheckId == 6){
    		$time_1 = date("Ymd",time());
    		$time_2 = date("Ymd",strtotime("+1 days"));
    	}

    	if($time_1 >= $time_2){
    		_message("前一个时间不能大于等于后一个时间(以天计)");
    	}

    	$tt = (strtotime($time_2)-strtotime($time_1))/86400;
    	for ($i=0; $i < $tt; $i++) { 
    		$arr[] = date("Ymd",strtotime($time_2)-($i+1)*86400);
    	}
  
    	// $arr = array();
    	// for($i = $time_2 - 1; $i >= $time_1; $i--){
    	// 	$arr[] = intval($i);
    	// }
    	$tt = array();
    	$total_yjtx = 0;
    	foreach ($arr as $key => $val) {
    	
    		$time_start = strtotime($val);
    		$time_end = $time_start+86400;
    		$str = $this->db->GetOne("SELECT sum(`money`) sum_money $money FROM `@#_wxpay_locat` WHERE `create_time` >= '$time_start' AND `create_time` < '$time_end' AND `status` = '1'");
    		$str2 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '微信公众号' AND `status` = '已付款'");
    		$str3 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '使用佣金充值到参与账户' AND `time` >= '$time_start' AND `time` < '$time_end'");
    		$str4 = $this->db->GetList("SELECT `amount` FROM `@#_red_pachet` WHERE `current_count` = '$val'");
    		$str5 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.q_uid != '74447'");
    		$str5_2 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.cateid = '177' AND m.q_uid != '74447'");

    		$crr = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `zhuan_status` = '1' AND `uid` != ".MK_UID);
			foreach ($crr as $k => $v) {
				$drr[] = $v['uid'];
			}
			$krr = implode(',', $drr);
			$str6 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` in ($krr) AND `time` > '$time_start' AND `time` < '$time_end' AND `type`='-1' AND `content` LIKE '%给用户%' order by `time` desc");

			$str7 = $this->db->GetOne("SELECT sum(m.moneycount) sum_money FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end' AND m.uid != '74447'");

			$str7_2 = $this->db->GetOne("SELECT sum(m.moneycount) sum_money FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid LEFT JOIN `@#_shoplist` AS k ON k.id = m.shopid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end' AND k.cateid = '177' AND m.uid != '74447'");

			$str8 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '通过网络充值' AND `status` = '已付款'");
            
            $str9 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '新注册用户首次充值送8元' AND `time` >= '$time_start' AND `time` < '$time_end'");

            $str10 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '商城卡充值' AND `time` >= '$time_start' AND `time` < '$time_end'");

            $str11 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.str1 != '0' AND m.q_uid != '74447'");
		
    		$money4 = 0;
    		$total = array();
    		
    		foreach ($str4 as $k => $v) {
    			$drr = explode(',', $v['amount']);
    			$m = 0;
    			foreach ($drr as $ke => $va) {
    				$m += $va;
    			}
    			$money4 += $m;
    		}
    		$login_num = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member_login` WHERE `day` = '$val'");
    		$pay_num = $this->db->GetList("SELECT distinct m.uid FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end'");

    		$csg = $this->db->GetOne("SELECT sum(n.money) sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.shop_id != 4 AND m.shop_id != 5 AND m.free = 0 AND m.update_time >= '$time_start' AND m.update_time < '$time_end'");
    		$csg_zj = $this->db->GetOne("SELECT sum(n.price) sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.status = 1 AND m.update_time >= '$time_start' AND m.update_time < '$time_end'");
    		$yjtx = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_cashout` WHERE `time` >= '$time_start' AND `time` < '$time_end'");
    		$total_yjtx += $yjtx['sum_money'];
  
    		$total['sm'] = intval($str['sum_money']);
    		$total['gzh'] = intval($str2['sum_money']);
    		$total['xx'] = intval($str6['sum_money']);
    		$total['yj'] = intval($str3['sum_money']);
    		$total['hd'] = $money4;
    		$total['zj'] = intval($str5['sum_money']) + intval($str5_2['sum_money']);
    		$total['xf'] = intval($str7['sum_money']);
    		$total['zfb'] = intval($str8['sum_money']);
    		$total['sj'] = $val;
            $total['xyh'] = intval($str9['sum_money']);
            $total['bj'] = intval($str5_2['sum_money']*2);
            $total['bj_gm'] = intval($str7_2['sum_money']);
            $total['login_num'] = intval($login_num);
            $total['pay_num'] = count($pay_num);
            $total['sck_cz'] = intval($str10['sum_money']);
            $total['sck_zj'] = intval($str11['sum_money']);
            $total['csg'] = intval($csg['sum_money']);
            $total['csg_zj'] = intval($csg_zj['sum_money']);
    		$tt[] = $total;
    	}
    	include $this->tpl(ROUTE_M,'test.in_out');
	}
}
?>