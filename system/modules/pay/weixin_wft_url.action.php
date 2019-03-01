<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');


class weixin_wft_url extends SystemAction {
	public function __construct(){
		$this->db=System::load_sys_class('model');
	}

	public function payinfo(){
		$msg = $this->segment(4);
		if ( $msg == "cancel" ){
			$msg = '交易取消！';
			_messagemobile($msg,'/');
		}else if ( $msg == "fail" ){
			$msg = '交易失败！';
		}else if ( $msg == "nowechat" ){
			$msg = '请关注微信公众号在微信中登录后进行支付操作！';
		} else {
			$msg = '交易错误：'.urldecode($msg);
		}

		_messagemobile($msg);

	}

	public function init() {
		if ( empty($_GET['money']) || empty($_GET['out_trade_no']) ) {
			header('Location: '.WEB_PATH.'/pay/weixin_wft_url/payinfo/fail2');
			die;
		}

		$config=array();
		$config['money'] = $_GET['money'];
		$config['code'] = $_GET['out_trade_no'];
		$config['NotifyUrl']  = G_WEB_PATH.'/index.php/pay/'.__CLASS__.'/houtai/';

		$pay = System::load_app_class('weixin_wft','pay');
		$pay->config($config);
		$pay->send_pay();
	}

    public function houtai(){
    	require('/www/wwwroot/csthsc/ext_pay2/Utils.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/config/config.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/RequestHandler.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/ClientResponseHandler.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/PayHttpClient.class.php');

		$resHandler = new ClientResponseHandler();
        $reqHandler = new RequestHandler();
        $pay = new PayHttpClient();
        $cfg = new Config();
        echo $cfg->C('key');exit();
		//$pay = $this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'weixin_wft'");
		//$config = array();
		//$config['pay_type_data'] = unserialize($pay['pay_key']);
		$resHandler->setKey($cfg->C('key'));
		$xml = file_get_contents('php://input');
		$resHandler->setContent($xml);
		//var_dump($this->resHandler->setContent($xml));
        if($resHandler->isTenpaySign()){
			
            if($resHandler->getParameter('status') == 0 && $resHandler->getParameter('result_code') == 0){
				
				//更改订单状态
				  $total_fee_t = $resHandler->getParameter('total_fee')/100;
		          $out_trade_no=$resHandler->getParameter('out_trade_no');

		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo){
			mylog('weixin_wft','f1');
			echo "fail";exit;
		}
		if ( $dingdaninfo['status'] == '已付款' ) {
			mylog('weixin_wft','s1');
			echo "success";exit;
		}

		$this->db->Autocommit_start();
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
		if(!$dingdaninfo){
			mylog('weixin_wft','f2');
			echo "fail";exit;
		}
		$uid = $dingdaninfo['uid'];
		$time = time();
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
		$up_q4=true;$up_q5=true;
		  if(empty($dingdaninfo['scookies'])){
				$marketing=$this->db->GetOne("select * from `@#_marketing_recharge` where `status` = 1 and `start_time`<='$time' and `end_time`>='$time' order by id desc");
				if($marketing){
					$marketing_count=$this->db->GetCount("select * from `@#_member_account` where `uid` = '$dingdaninfo[uid]' and `m_r_id`='$marketing[id]' ");
					//增加优惠次数设为0时，可无限参加优惠
					if($marketing_count<$marketing['num'] || !$marketing['num']){
						if($total_fee_t>=$marketing['recharge'] && $marketing['gift']>0){
							$time2=intval($time)+1;
							$gift_money=floor($total_fee_t*$marketing['gift']/100);
							if($gift_money>0){
								$up_q4 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $gift_money where (`uid` = '$dingdaninfo[uid]')");
								$up_q5 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`,`m_r_id`) VALUES ('$dingdaninfo[uid]', '1', '账户', '$marketing[name]', '$gift_money', '$time2','$marketing[id]')");
								$up_q6 = $this->db->Query("UPDATE `@#_marketing_recharge` SET `count` = `count` + 1 where (`id` = '$marketing[id]')");
							}
						}
					}
				}
		  }
		
		
		
		
		if($up_q1 && $up_q2 && $up_q3 && $up_q4 && $up_q5){
			$this->db->Autocommit_commit();
			 isRecWheel($dingdaninfo['uid'],$dingdaninfo['code']);
		}else{
			$this->db->Autocommit_rollback();
			mylog('weixin_wft','f3');
			echo "fail";exit;
		}
		if(empty($dingdaninfo['scookies'])){
			mylog('weixin_wft','s2');
			echo "success";exit;
		}
		$scookies = unserialize($dingdaninfo['scookies']);
		$pay = System::load_app_class('pay','pay');
		$pay->scookie = $scookies;
		$pay->fufen=$dingdaninfo['score'];

		$ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
		if($ok != 'ok'){
			echo "fail";exit;
		}
		$check = $pay->go_pay(1);
		if ( $check ) {
			$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
			echo "1";exit;
		} else {
			echo "fail";exit;
		}

		$pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
				
				//更改订单状态
               // Utils::dataRecodes('接口回调收到通知参数',$resHandler->getAllParameters());
                echo 'success';
                exit();
            }else{
                echo 'failure';
                exit();
            }
        }else{
            echo 'failure';
        }


		//=======================//

		

	}


}
