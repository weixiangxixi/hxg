<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
define("MEMBER",true);

class base extends SystemAction {
	protected $userinfo=NULL;
	public function __construct(){		
		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='login'){
			return;
		}
		if(ROUTE_M=='member' && ROUTE_C=='user' && ROUTE_A=='register'){
			return;
		}
		
		$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));		
		$utype=_encrypt(_getcookie("utype"),'DECODE');
		$ushell=_encrypt(_getcookie("ushell"),'DECODE');
		
      	$sb = $this->DB()->GetOne("select * from `@#_member` where `uid`='$uid' limit 1");
      	if($sb['auto_user']==0){
          	file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$uid}\n", FILE_APPEND);
        	$cc = $this->DB()->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$uid' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100 ){
              //_setcookie("uid","",time()-3600);
              //_setcookie("ushell","",time()-3600);
              //session_start ();
              //unset($_SESSION['gwc']);
              //exit;
              //return ;
            }
        }
		
		if($utype===NULL)$this->HeaderLogin();
		if(!$uid)$this->HeaderLogin();
		$this->userinfo=$this->DB()->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");
		if(!$this->userinfo)$this->HeaderLogin();		
	
		$shell=md5($this->userinfo['uid'].$this->userinfo['password'].$this->userinfo[$utype]);		
		if($ushell!=$shell)	$this->HeaderLogin();
	}
	
	private function HeaderLogin(){
		_message("你还未登录，无权限访问该页！",WEB_PATH."/member/user/login",3);
	}
	
}
?>