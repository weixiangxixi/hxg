<?php
session_start();

error_reporting(E_ERROR);
ini_set("display_errors", "ON");

System::load_app_fun("pay","pay");
System::load_sys_fun("user");
System::load_sys_fun('global');
System::load_app_class("tocode","pay",'no');

class weixin_wft
{

    private $config;

    public function config($config = null)
    {
        $this->config = $config;
    }

    public function send_pay()
    {
        if($this->config['money'] < 10){
        	return false;
        }
        $_SESSION["out_trade_no"] = $this->config['code'];
        $_SESSION["total_fee"] = $this->config['money'] * 100;
        $_SESSION['notify_url'] = '/index.php/pay/wxpay_web_url/wtfCallback';
      	//echo $_SESSION["out_trade_no"];
        //header("Location:http://{$_SERVER['SERVER_NAME']}/ext_pay2/recharge1.php");
		//header("Location:/ext_pay2/recharge1.php");
      	//exit;
      	$model=System::load_sys_class('model');
        
      	$wepay1 = $model->GetOne("SELECT * FROM `@#_pay_config` where `id` = '3'");
      	$wepay2 = $model->GetOne("SELECT * FROM `@#_pay_config` where `id` = '4'");
      	
      	header("Location:/index.php/mobile/cnmobi/paygo/");exit;
          
      	if($wepay1['status']=='1'){
        	//header("Location:/index.php/mobile/unionpay/paygo/");
        }
      	if($wepay2['status']=='1'){
        	//header("Location:/index.php/mobile/cnmobi/paygo/");
        }
    }
}
