<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun("test");

class paytest extends base
{
	
  	private $key;
    private $code;
  	private $mchid;
  	private $shost;
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
          	//_messagemobile("请在微信端打开",WEB_PATH."/mobile/home",3);exit();
        }
      
    }
 
  
    public function gopay(){
      	session_start();
      	require 'wechat222.php';
        header("Content-type: text/html; charset=utf-8");
      	
      	
      	
      	$array = array(
          'g05ABI7W8Q',
          'dMQVJsQ2Mw',
          'VRM1PU5kP4',
          'e67QqUP2vA',
          'wNrDVhl5DA',
          '8K5YdHjA9Q',
          '2k1YxI5RaQ',
          'plDkYt1Z6w',
          'Ym6qPSlWP0',
          '8K5YdHjAkV',
          'QxaYmfZ54J',
          '0ngZ6hgqlW',
          'aQeBPcJQ0r',
          'KxjYJfKdJJ',
          '5x7Ypf2DAQ',
          'lk54LIl8D3',
          'RvAYwTZxmL',
          'zBxv4h86mG',
          '4jKYBfv3KP',
          'jn4WBhKZd6',
          'g05ABI7WQ4',
          'QxaYmfZ5D2',
          'MxBYRf3V6r',
          'xb4ZQT5PKe',
          '8K5YdHjA3w',
          '0ngZ6hgqJg',
          'KxjYJfKd36',
          'lk54LIl8P1',
          'RvAYwTZxd4',
          'maAe3cNqG3',
          'KxjYJfKdRn',
          'lk54LIl864',
          'GxOYAfg3Vj',
          'PxLYmfrK96',
          '4jKYBfv3bx',
          'Z1PRKsgPgV',
          'Ox9YrfGWGB',
          'BxN5AfdKd0',
          'WOVl6TxZxe',
          'krb04tAR6Z'
        );
      	//$array = array('krb04tAR6Z');
      	foreach($array as $v){
        	$p = new PP($v);
          	$money = $this->test1();
          	$code = $this->test2();
            $req = $p->payMoney($money,$code);
          	echo $req['resperr'];
          	echo '-';
          	echo $v;
           /*
          	$rs = $req['pay_params'];
          	$rs['mchntnm'] = urlencode('惠享购');
      		$rs['txamt'] = $money;
      		$rs['goods_name'] = urlencode("微信支付");
      		$rs['redirect_url'] = urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php/mobile/cart/paysuccess/');
      	
            foreach($rs as $k=>$v){
                $data[] = "{$k}={$v}";
            }
      		$url = join('&',$data);
          	$go = file_get_contents('Location:https://o2.qfpay.com/q/direct?'.$url);
          	echo $go;
            */
          	echo '<br>';
          	
        }
    }
  	
  	function test1(){
    	return rand(1,9);
    }
  	function test2(){
    	return $dingdancode =   'C' . time() . substr(microtime(), 2, 6) . rand(0, 9);
    }
}