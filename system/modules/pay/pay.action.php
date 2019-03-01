<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class pay extends admin {
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
	}

	public function mySend(){

		 sendWarnMsg('888', '999');
	}


	//支付列表
	public function pay_list(){
		$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where 1");
		include $this->tpl(ROUTE_M,'paylist');
	}


	public function pay_bank(){
		if(isset($_POST['dosubmit'])){
			$bank_type = htmlspecialchars($_POST['bank_type']);
			$q_ok = $this->db->Query("UPDATE `@#_caches` SET `value` = '$bank_type' where `key` = 'pay_bank_type'");
			if($q_ok){
				_message("操作成功!");
			}else{
				_message("操作失败!");
			}
		}

		$bank = $this->db->GetOne("select * from `@#_caches` where `key` = 'pay_bank_type'");
		if(!$bank)_message("查询失败");
		include $this->tpl(ROUTE_M,'paybank');
	}

	public function pay_set(){
		$payid = intval($this->segment(4));

		$pay = $this->db->GetOne("SELECT * FROM `@#_pay` where `pay_id` = '$payid'");
		if(!$pay)_message("参数错误");
		if($pay['pay_class'] == 'yeepay'){
			if(!file_exists(G_SYSTEM.'modules/'.ROUTE_M.'/lib/yeepay.class.php')){
				_message("开通易宝支付请联系官网");
			}
		}

		$pay['pay_key']  = @unserialize($pay['pay_key']);

		if(!is_array($pay['pay_key'])){
			$pay['pay_key'] = array("id"=>array("name"=>"商户号","val"=>""),"key"=>array("name"=>"密匙","val"=>""));
		}

		if(isset($_POST['dosubmit'])){
			$name = htmlspecialchars($_POST['pay_name']);
			$thumb = htmlspecialchars($_POST['pay_thumb']);
			$type = intval($_POST['pay_type']);
			$des = htmlspecialchars($_POST['pay_des']);
			$start = intval($_POST['pay_start']);

			$pay_key = $_POST['pay_key'];
			foreach($pay_key as $key=>$val){
				$pay_key[$key]=array("name"=>$pay['pay_key'][$key]['name'],"val"=>$pay_key[$key]);
			}
			$pay_key = serialize($pay_key);

			$this->db->Query("UPDATE `@#_pay` SET `pay_name` = '$name',`pay_thumb` = '$thumb',`pay_type` = '$type',`pay_des` = '$des',`pay_start` = '$start',`pay_key` = '$pay_key' where `pay_id` = '$payid'");
			_message("操作成功",WEB_PATH.'/pay/pay/pay_list');
		}

		$arr=array(
			"id" =>array("name"=>"支付宝商户号:","val"=>"12322313"),
			"key" =>array("name"=>"支付宝密钥:","val"=>"8934e7d15453e97507ef794cf7b0519d1"),
			"user" =>array("name"=>"支付宝账号:","val"=>"xx@qq.ccc"),
		);



		include $this->tpl(ROUTE_M,'payset');
	}

	public function wxpay_list(){
		$data = $this->db->GetList("SELECT `id`,`name`,`create_time`,`status` FROM `@#_wxpay_list` WHERE 1");
		include $this->tpl(ROUTE_M,'wxpay_list');
	}

	public function add_wxpay(){
		if(isset($_POST['dosubmit'])){
			$name = htmlspecialchars($_POST['name']);
			$appid = htmlspecialchars($_POST['appid']);
			$mchid = htmlspecialchars($_POST['mchid']);
			$key = htmlspecialchars($_POST['key']);
			$appsecret = htmlspecialchars($_POST['appsecret']);
			$create_time = time();
			
			$this->db->Query("INSERT INTO `@#_wxpay_list` (`name`,`appid`,`mchid`,`key`,`appsecret`,`create_time`)VALUES('$name','$appid','$mchid','$key','$appsecret','$create_time')");
			_message("添加成功",WEB_PATH.'/pay/pay/wxpay_list');
		}
		include $this->tpl(ROUTE_M,'add_wxpay');
	}

	public function open_wxpay(){
		$id = $this->segment(4);
		$data = $this->db->Query("UPDATE `@#_wxpay_list` SET `status` = '1' WHERE `id` = '$id'");
		$str = $this->db->Query("UPDATE `@#_wxpay_list` SET `status` = '0' WHERE `id` != '$id'");
		_message("开启成功",WEB_PATH.'/pay/pay/wxpay_list');
	}

	public function delete_wxpay(){
		$id = $this->segment(4);
		$data = $this->db->Query("DELETE FROM `@#_wxpay_list` WHERE `id` = '$id'");
		_message("删除成功",WEB_PATH.'/pay/pay/wxpay_list');
	}

	public function bankpay_list(){
		$data = $this->db->GetList("SELECT `id`,`name`,`card_number`,`bank_from`,`create_time`,`status` FROM `@#_bank_bind` WHERE 1");
		include $this->tpl(ROUTE_M,'bankpay_list');
	}

	public function add_bankpay(){
		if(isset($_POST['dosubmit'])){
			$name = htmlspecialchars($_POST['name']);
			$card_number = htmlspecialchars($_POST['card_number']);
			$bank_from = htmlspecialchars($_POST['bank_from']);
			$create_time = time();
			
			$this->db->Query("INSERT INTO `@#_bank_bind` (`name`,`card_number`,`bank_from`,`create_time`)VALUES('$name','$card_number','$bank_from','$create_time')");
			_message("添加成功",WEB_PATH.'/pay/pay/bankpay_list');
		}
		include $this->tpl(ROUTE_M,'add_bankpay');
	}

	public function open_bankpay(){
		$id = $this->segment(4);
		$data = $this->db->Query("UPDATE `@#_bank_bind` SET `status` = '1' WHERE `id` = '$id'");
		$str = $this->db->Query("UPDATE `@#_bank_bind` SET `status` = '0' WHERE `id` != '$id'");
		_message("开启成功",WEB_PATH.'/pay/pay/bankpay_list');
	}

	public function delete_bankpay(){
		$id = $this->segment(4);
		$data = $this->db->Query("DELETE FROM `@#_bank_bind` WHERE `id` = '$id'");
		_message("删除成功",WEB_PATH.'/pay/pay/bankpay_list');
	}
}
?>
