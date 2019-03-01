<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class wechatpay extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        $user = $this->userinfo;
      	if(!$user){
        	header("Location:/index.php/mobile/user/login");exit();
        }
      	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
      	if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        	//echo " Sorry！非微信浏览器不能访问";
          	_messagemobile("请在微信端打开",WEB_PATH."/mobile/home",3);exit();
        }
    }
    public function payfail(){
        _messagemobile("支付失败",WEB_PATH."/mobile/home/recharge",2);exit();
    }
}