<?php 



defined('G_IN_SYSTEM')or exit('No permission resources.');

define("MEMBER",true);

System::load_sys_fun("user");

class base extends SystemAction {

	protected $userinfo=NULL;	

	public function __construct(){

		$this->db = System::load_sys_class("model");
		session_start();
		$uid=intval(_encrypt(_getcookie("uid"),'DECODE'));		
		$_SESSION['uuuid'] = $uid;
      	$sb = $this->db->GetOne("select * from `@#_member` where `uid`='$uid' limit 1");
      	if($sb['auto_user']==0){
          	//file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$uid}\n", FILE_APPEND);
        	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$uid' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100 ){
              _setcookie("uid","",time()-3600);
              _setcookie("ushell","",time()-3600);
              //session_start ();
              unset($_SESSION['gwc']);
              exit;
              return ;
            }
        }
      	if($sb['auto_user']==1){
        	_setcookie("uid","",time()-3600);
            _setcookie("ushell","",time()-3600);
            //session_start ();
            unset($_SESSION['gwc']);
          	_messagemobile("账号被冻结,请与客服联系！","/index.php/mobile/user/login");
            exit;
        }
      
        if($sb['auto_user'] == 0 && $uid !=0){
        	$day = date("Ymd",time());
        	$time = time();
        	$xx = $this->db->GetOne("SELECT * FROM `@#_member_login` WHERE `uid` = '$uid' AND `day` = '$day'");
        	if(!$xx){
        		$this->db->Query("INSERT INTO `@#_member_login` (`uid`,`day`,`create_time`)VALUES('$uid','$day','$time')");
        	}
        }
      
		$ushell=_encrypt(_getcookie("ushell"),'DECODE');

		if(!$uid)$this->userinfo=false;

		if (!isset($_GET['wxid'])) {

			$this->userinfo=$this->db->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");
		}else{
			$wxid = $_GET['wxid'];
			$mem=$this->db->GetOne("select * from `@#_member_band` where `b_code`='".$wxid."'");
			$this->userinfo=$member=$this->db->GetOne("select * from `@#_member` where `uid`='".$mem['b_uid']."'");
			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
		}

		if(!$this->userinfo)$this->userinfo=false;

		$shell=md5($this->userinfo['uid'].$this->userinfo['password'].$this->userinfo['mobile'].$this->userinfo['email']);

		if($ushell!=$shell)$this->userinfo=false;



		global $_cfg;		

		$_cfg['userinfos']=$this->userinfo;

	}

	

	protected function checkuser($uid,$ushell){

		$uid=intval(_encrypt($uid,'DECODE'));

		$ushell=_encrypt($ushell,'DECODE');	

		if(!$uid)return false;

		if($ushell===NULL)return false;

		$this->userinfo=$this->db->GetOne("SELECT * from `@#_member` where `uid` = '$uid'");

		if(!$this->userinfo){

			$this->userinfo=false;

			return false;

		}

		$shell=md5($this->userinfo['uid'].$this->userinfo['password'].$this->userinfo['mobile'].$this->userinfo['email']);

		if($ushell!=$shell){

			$this->userinfo=false;

			return false;

		}else{

			return true;

		}

		

	}

	public function get_user_info(){

		if($this->userinfo){

			return $this->userinfo;

		}else{

			return false;

		}

	}

	protected function HeaderLogin(){

		_message("你还未登录，无权限访问该页！",WEB_PATH."/member/user/login");

	}

	

}

?>