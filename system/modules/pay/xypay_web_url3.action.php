<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
if ( !function_exists('mylog') ) {
	function mylog() {
	}
}
if ( !function_exists('nolog') ) {
	function nolog() {
	}
}

require ('cnmobi/config/config.php');
require ('cnmobi/class/RequestHandler.class.php');

class xypay_web_url3 extends SystemAction {
	
	public $cfg ;
    private $RequestHandler;

	public function __construct(){
		$this->db=System::load_sys_class('model');

		$this->cfg = new Config();
        $this->RequestHandler = new RequestHandler();
	}
	
  	public function companypay()
    {
        
        $req = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml=json_decode($req,true);
      	
      	$resultCode = $xml['resultCode'];
      	$message = $xml['message'];
      	$orderNo = $xml['orderNo'];
      	$sysOrderNo = $xml['sysOrderNo'];
      	$threeOrderNo = $xml['threeOrderNo'];
      	$price = $xml['price'];
        $payPrice = $xml['payPrice'];
      	$sign = $xml['sign'];
      	
      	$key = 'fe31d4328d274d579bc431cf2c0dba0c';
      	
      	$str = 'message='.$message.'&orderNo='.$orderNo.'&payPrice='.$payPrice.'&price='.$price.'&resultCode='.$resultCode.'&sysOrderNo='.$sysOrderNo.'&threeOrderNo='.$threeOrderNo.'&key='.$key;
      	
      	$rs_sign = strtoupper(md5($str));
       	
      	file_put_contents("/www/wwwroot/csthsc/sql_log/yinlian.log", json_encode($xml)."--{$rs_sign}\n", FILE_APPEND);
      	//exit('1');
        if($resultCode == "success"){
          	if($sign != $rs_sign){
            	echo "error";exit;
            }
          	//$rs = json_encode($data);
            //file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$rs}\n", FILE_APPEND);
            $total_fee_t = $payPrice;
            $out_trade_no = $orderNo;
			//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_call.log", "{$total_fee_t}:{$out_trade_no}\n", FILE_APPEND);exit;
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "error";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "success";exit;
            }
			
            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "error";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        	
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `wx` = '4', `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "error";exit;
            }
          	
          	if(empty($dingdaninfo['scookies'])){
                echo "success";exit;
            }
            $scookies = unserialize($dingdaninfo['scookies']);
            $pay = System::load_app_class('pay','pay');
            $pay->scookie = $scookies;
            $pay->fufen=$dingdaninfo['score'];

            $ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
            if($ok != 'ok'){
                echo "error";exit;
            }
            $check = $pay->go_pay(1);
            if ( $check ) {
                $this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
                echo "success";exit;
            } else {
                echo "error";exit;
            }

            $pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
		
			echo 'success';$_SESSION["out_trade_no"] = '';$_SESSION["total_fee"] = '';
            exit();
        }else{
			echo "error";exit;
        }
    }
  	public function alipay1()
    {
        session_start();
		$platform_trade_no = $_POST["platform_trade_no"];
        $orderid = $_POST["orderid"];
        $price = $_POST["price"];
        $realprice = $_POST["realprice"];
        $orderuid = $_POST["orderuid"];
        $key = $_POST["key"];

        //校验传入的参数是否格式正确，略

        $token = "78567f66d42927712a9c69bf66084053f3804ddc";
    	$temps = md5($orderid . $orderuid . $platform_trade_no . $price . $realprice . $token);

    
        if ($temps == $key) {
          	$total_fee_t = $price;
            $out_trade_no = $orderid;
          
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "error";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "success";exit;
            }
			
            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "error";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        	
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `wx` = '3', `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "error";exit;
            }
          	
          	if(empty($dingdaninfo['scookies'])){
                echo "success";exit;
            }
            $scookies = unserialize($dingdaninfo['scookies']);
            $pay = System::load_app_class('pay','pay');
            $pay->scookie = $scookies;
            $pay->fufen=$dingdaninfo['score'];

            $ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
            if($ok != 'ok'){
                echo "error";exit;
            }
            $check = $pay->go_pay(1);
            if ( $check ) {
                $this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
                echo "success";exit;
            } else {
                echo "error";exit;
            }

            $pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
		
			echo 'success';$_SESSION["out_trade_no"] = '';$_SESSION["total_fee"] = '';
            exit();
        }else{
			echo "error";exit;
        }
    }
	public function wxnofity2()
    {
        $response = $_REQUEST;
		$resSign=$response['sign'];

		if(!$response){
			echo "error";
			die();
		}
        $data = $_REQUEST;
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_call.log", json_encode($data)."\n", FILE_APPEND);exit;
        unset($response['sign']);
        $sign=$this->RequestHandler->createSign($response);
        if($sign==$resSign){
          	//$rs = json_encode($data);
            //file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$rs}\n", FILE_APPEND);
            $total_fee_t = $data['total'] / 100;
            $out_trade_no = 'C'.$data['orderNo'];
			//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_call.log", "{$total_fee_t}:{$out_trade_no}\n", FILE_APPEND);exit;
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "error";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "SUCCESS";exit;
            }
			
            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "error";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        	
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			
          	$ufo = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
          	if($total_fee_t >= 1000 && $ufo['vip']==1){
            	$mmp = round($total_fee_t * 0.02 ) + $total_fee_t;
              	$total_fee_t = $mmp;
            }
          
          	$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "error";exit;
            }
          	
          	if(empty($dingdaninfo['scookies'])){
                echo "SUCCESS";exit;
            }
            $scookies = unserialize($dingdaninfo['scookies']);
            $pay = System::load_app_class('pay','pay');
            $pay->scookie = $scookies;
            $pay->fufen=$dingdaninfo['score'];

            $ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
            if($ok != 'ok'){
                echo "error";exit;
            }
            $check = $pay->go_pay(1);
            if ( $check ) {
                $this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
                echo "SUCCESS";exit;
            } else {
                echo "error";exit;
            }

            $pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
		
			echo 'SUCCESS';$_SESSION["out_trade_no"] = '';$_SESSION["total_fee"] = '';
            exit();
        }else{

        }
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
			header('Location: '.WEB_PATH.'/pay/xypay_web_url/payinfo/fail2');
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

		$resHandler->setKey($cfg->C('key'));
		$xml = file_get_contents('php://input');
		$resHandler->setContent($xml);
		//var_dump($this->resHandler->setContent($xml));

		
		$out_trade_no = $this->segment ( 4 );
		$total_fee_t = $this->segment ( 5 );
		//echo $total_fee_t;exit();
        if(!empty($out_trade_no)){
			
            
				
				//更改订单状态
				  //$total_fee_t = $resHandler->getParameter('total_fee')/100;
		          //$out_trade_no=$resHandler->getParameter('out_trade_no');

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
		

		
		
		
		
		if($up_q1 && $up_q2 &&$up_q3){
			$this->db->Autocommit_commit();
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
        }


		//=======================//

		

	}
  	public function houtai3(){
		$returnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "transaction_id" =>  $_REQUEST["transaction_id"], // 支付流水号
            "returncode" => $_REQUEST["returncode"],
        );
        $md5key = "p42sg7dmhwwqc88r1qsvmqi7lgaifw2v";
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
			
            
				
		//更改订单状态
		$total_fee_t = $_REQUEST["amount"];
		$out_trade_no = $_REQUEST["orderid"];

		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo){
			//mylog('weixin_wft','f1');
			echo "fail";exit;
		}
		if ( $dingdaninfo['status'] == '已付款' ) {
			//mylog('weixin_wft','s1');
			echo "ok";exit;
		}

		$this->db->Autocommit_start();
        if(!empty($dingdaninfo['smoney'])){
        	$total_fee_t = $dingdaninfo['smoney'] + $total_fee_t;
        }
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
		if(!$dingdaninfo){
			//mylog('weixin_wft','f2');
			echo "fail";exit;
		}
		$uid = $dingdaninfo['uid'];
		$time = time();
        
        
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		

		
		
		
		
		if($up_q1 && $up_q2 &&$up_q3){
			$this->db->Autocommit_commit();
		}else{
			$this->db->Autocommit_rollback();
			//mylog('weixin_wft','f3');
			echo "fail";exit;
		}
              
        $_SESSION["out_trade_no"] = '';
		$_SESSION["total_fee"] = '';
              
		if(empty($dingdaninfo['scookies'])){
			//mylog('weixin_wft','s2');
			echo "ok";exit;
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
			echo "ok";exit;
		} else {
			echo "fail";exit;
		}

		$pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
				
				//更改订单状态
               // Utils::dataRecodes('接口回调收到通知参数',$resHandler->getAllParameters());
                echo "ok";exit;
                //exit();
            } 
        }else{
            echo "fail";exit;
        }


		//=======================//

		

	}
	    public function houtai2(){


		require('/www/wwwroot/csthsc/ext_pay2/Utils.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/config/config.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/RequestHandler.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/ClientResponseHandler.class.php');
		require('/www/wwwroot/csthsc/ext_pay2/class/PayHttpClient.class.php');

		$resHandler = new ClientResponseHandler();
        $reqHandler = new RequestHandler();
        $pay = new PayHttpClient();
        $cfg = new Config();

		$resHandler->setKey($cfg->C('key'));
		$xml = file_get_contents('php://input');
		$resHandler->setContent($xml);
		//var_dump($this->resHandler->setContent($xml));

		
		$out_trade_no = $this->segment ( 4 );
		$total_fee_t = $this->segment ( 5 );
		//echo $total_fee_t;exit();
        if(!empty($out_trade_no)){
			
            
				
				//更改订单状态
				  //$total_fee_t = $resHandler->getParameter('total_fee')/100;
		          //$out_trade_no=$resHandler->getParameter('out_trade_no');

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
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '通过网络充值', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过网络充值', '$total_fee_t', '$time')");
		

		
		
		
		
		if($up_q1 && $up_q2 && $up_q3){
			$this->db->Autocommit_commit();
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
        }


		//=======================//

		

	}
  
  	public function alipay_callback(){
    	$order_id = $_GET["order_id"];

        //此处在您数据库中查询：此笔订单号是否已经异步通知给您付款成功了。如成功了，就给他返回一个支付成功的展示。
        echo "恭喜，支付成功!，订单号：".$order_id;
        header("Location:/index.php/mobile/cart/paysuccess");exit();
    }
  	public function houtai4(){
      	session_start();
		$code = $_POST['code'];				// 0000为支付成功
        //$code = '0000';
        $order_id = $_POST['order_id'];		//传入的订单号
        $order_uid = $_POST['order_uid'];	//传入的order_uid
        $price = $_POST['price'];			//支付金额
        $transaction_id = $_POST['transaction_id'];			//渠道流水号
        if ($code=='0000') {
            //更改订单状态
            $total_fee_t = $price;
            $out_trade_no = $order_id;
			
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "fail";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "success";exit;
            }

            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "fail";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '通过网络充值', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过网络充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "fail";exit;
            }
          	
			echo 'success';$_SESSION['alipay_code'] = '';
            exit();
         }else{
           echo "fail";exit;
        }


	  }
	public function houtai5(){
      	session_start();
      
		$status = $_POST['status'];		 
      
        $order_id = $_POST['merOrderId'];		//传入的订单号
        
        $price = $_POST['totalAmount'];			//支付金额
      	
      	file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$order_id}:{$price}:{$status}\n", FILE_APPEND);
        //echo "SUCCESS";exit;
        if ($status == 'TRADE_SUCCESS') {
            //更改订单状态
            $total_fee_t = $price / 100;
            $out_trade_no = substr($order_id,4);
			
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "FAILED";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "SUCCESS";exit;
            }
			//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$order_id}:{$price}:{$out_trade_no}\n", FILE_APPEND);
            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "FAILED";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        	
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "FAILED";exit;
            }
          	
          	if(empty($dingdaninfo['scookies'])){
                echo "SUCCESS";exit;
            }
            $scookies = unserialize($dingdaninfo['scookies']);
            $pay = System::load_app_class('pay','pay');
            $pay->scookie = $scookies;
            $pay->fufen=$dingdaninfo['score'];

            $ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
            if($ok != 'ok'){
                echo "FAILED";exit;
            }
            $check = $pay->go_pay(1);
            if ( $check ) {
                $this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
                echo "SUCCESS";exit;
            } else {
                echo "FAILED";exit;
            }

            $pay->init($uid,$pay_type['pay_id'],'go_record');	//淘购商品
		
			echo 'SUCCESS';$_SESSION["out_trade_no"] = '';$_SESSION["total_fee"] = '';
            exit();
         }else{
           echo "FAILED";exit;
        }


	  }
  public function houtai55(){
      	session_start();
      
		$status = $_POST['status'];		 
      
        $order_id = $_POST['merOrderId'];		//传入的订单号
        
        $price = $_POST['totalAmount'];			//支付金额
      	
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$order_id}:{$price}:{$status}\n", FILE_APPEND);
        //echo "SUCCESS";exit;
        if ($status == 'TRADE_SUCCESS') {
            //更改订单状态
            $total_fee_t = $price / 100;
            $out_trade_no = substr($order_id,4);
			
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
            if(!$dingdaninfo){
                echo "FAILED";exit;
            }
            if ( $dingdaninfo['status'] == '已付款' ) {
                echo "SUCCESS";exit;
            }
			//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$order_id}:{$price}:{$out_trade_no}\n", FILE_APPEND);
            $this->db->Autocommit_start();
            
            $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `money` = '$total_fee_t' and `status` = '未付款' for update");
            if(!$dingdaninfo){
                echo "FAILED";exit;
            }
			$uid = $dingdaninfo['uid'];
			$time = time();
        	
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '微信公众号', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			
          	$ufo = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");
          	if($total_fee_t >= 1000 && $ufo['vip']==1){
            	$mmp = round($total_fee_t * 0.02 ) + $total_fee_t;
              	$total_fee_t = $mmp;
            }
          	$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $total_fee_t where (`uid` = '$dingdaninfo[uid]')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$dingdaninfo[uid]', '1', '账户', '通过微信公众号充值', '$total_fee_t', '$time')");
		
		
            if($up_q1 && $up_q2 &&$up_q3){
                $this->db->Autocommit_commit();
            }else{
                $this->db->Autocommit_rollback();
                echo "FAILED";exit;
            }
          	
          	
			echo 'SUCCESS';
            exit();
         }else{
           echo "FAILED";exit;
        }


	  }
}
