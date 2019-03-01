<?php 



defined('G_IN_SYSTEM')or exit('no');

System::load_app_class('admin',G_ADMIN_DIR,'no');

class user extends admin {

	private $db;

	protected $ment;

	public function __construct(){		

		parent::__construct();

		$this->db=System::load_sys_class("model");

		$this->ment=array(

						array("lists","管理员管理",ROUTE_M.'/'.ROUTE_C."/lists"),

						array("reg","添加管理员",ROUTE_M.'/'.ROUTE_C."/reg"),

						array("edit","修改管理员",ROUTE_M.'/'.ROUTE_C."/reg",'hide'),

		);	

		

	}

	

	public function reg111(){

		/*=============new===============*/
		
		  
	    $admin_power1=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='0'");//一级  
		  foreach($admin_power1 as $key=> $v1){
			  $categoryshtml.=" <div class=\"panel panel-default\"><div class=\"panel-heading\"><h3 class=\"panel-title\">".$v1['action_name']."</h3></div><div class=\"panel-body\"><ul>";
		       $admin_power2=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='$v1[action_id]'");//二级
			     foreach($admin_power2 as  $v2){     
		            $admin_power3=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='$v2[action_id]'");//二级
				    foreach($admin_power3 as $v3){
						$categoryshtml.="<label class=\"checkbox-inline\"><input  name=\"checkboxs\" id=\"inlineCheckbox1\" type=\"checkbox\" value=\"".$v3['action_id']."\"  topname=\"".$v1['action_id'].",".$v2['action_id']."\" >".$v3['action_name']."</label>";
						
				    }
			    }
		      $categoryshtml.="</ul></div></div>";
	     }
		
		/*=============newend===============*/

		if(isset($_POST['submit-1'])){

			$username=safe_replace($_POST['username']);

			if($username!=$_POST['username'] || empty($username)){

				_message("用户名格式错误!");			

			}	

			if(_strlen($username)>15){

				_message("用户名长度为2-15个字符,1个汉字等于2个字符!");

			}

			$password1=$_POST['password'];

			$password2=$_POST['pwdconfirm'];

			if(empty($password2) || ($password1!=$password2)){

				_message("2次密码不一致!");

			}

			if(!_checkemail($_POST['email'])){

				_message("邮箱格式错误!");

			}

			//$pmid=isset($_POST['mid']) ? intval($_POST['mid']) : 0;
			$pmid=isset($_POST['power']) ?$_POST['power'] : "all";

			$password=md5($password2);		

			$addtime=time();

			$ip=_get_ip();

			$this->db->Query("INSERT INTO `@#_admin` (`mid`, `username`, `userpass`, `useremail`, `addtime`, `logintime`, `loginip`) VALUES ('$pmid', '$username', '$password', '$_POST[email]','$addtime','0','$ip')");

			if($this->db->affected_rows()){			

				$path=WEB_PATH.ROUTE_M.'/user/lists';

				_message("添加管理员成功!",$path);

			}else{

				_message("添加管理员失败!");

			}

		}

		include $this->tpl(ROUTE_M,'user.reg');	

		

		

	}

	public function lists(){	

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_admin` WHERE 1"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");		

		$AdminList=$this->db->GetPage("SELECT * FROM `@#_admin` WHERE 1",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0)); 

		include $this->tpl('admin','user.list');		

	}

	public function edit(){

		$path=WEB_PATH.'/'.ROUTE_M.'/user/lists';

		$uid=intval($this->segment(4));

		$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `uid`='$uid'");

		if(!is_array($info)){

				_message("没有这个用户!",$path);

		}

				/*=============new===============*/
		$midinfo= str_replace(',','qq',$info['mid']);  
	
		 
	 
		  
	    $admin_power1=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='0'");//一级  
		  foreach($admin_power1 as $key=> $v1){
			  $categoryshtml.=" <div class=\"panel panel-default\"><div class=\"panel-heading\"><h3 class=\"panel-title\">".$v1['action_name']."</h3></div><div class=\"panel-body\"><ul>";
		       $admin_power2=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='$v1[action_id]'");//二级
			     foreach($admin_power2 as  $v2){     
		            $admin_power3=$this->db->GetList("SELECT * FROM `@#_admin_action` where `parent_id`='$v2[action_id]'");//二级
				     foreach($admin_power3 as $v3){
				 
					 
					 if(strpos('qq'.$midinfo.'qq','qq'.$v3['action_id'].'qq')=== false)
					 { $checked="";}
					 else
					 {$checked="checked";}

						$categoryshtml.="<label class=\"checkbox-inline\"><input  name=\"checkboxs\" id=\"inlineCheckbox1\" type=\"checkbox\" value=\"".$v3['action_id']."\"  topname=\"".$v1['action_id'].",".$v2['action_id']."\"  $checked>".$v3['action_name']."</label>";
						
				    }
			    }
		      $categoryshtml.="</ul></div></div>";
	     }
		
		/*=============newend===============*/

		if(isset($_POST['submit-1'])){			

			$password=$info['userpass'];
			if(!empty($_POST['password']))
			{
			   if( ($_POST['password']!=$_POST['pwdconfirm'])){
				   _adminmessage("2次密码不一致!");
			    }
				else
				{
					$password=md5($_POST['pwdconfirm']);
			    }
			}

			$pmid=isset($_POST['power']) ?$_POST['power'] : "all";

			//$ok = $this->db->Query("UPDATE `@#_admin` SET `userpass`='$password' WHERE (`uid`='$uid')");
			$ok = $this->db->Query("UPDATE `@#_admin` SET `mid`='$pmid' ,`userpass`='$password' WHERE (`uid`='$uid')");
			
			if($ok){

				echo "<script>

				alert('修改成功');

				parent.location.reload();				

				</script>";		

			}else{

				_message("修改失败!");

			}

		

		}else{		

			include $this->tpl(ROUTE_M,'user.edit');

		}

	}

	

	public function del(){

		

		if(isset($_POST['ajax'])){		

			$uid=intval($this->segment(4));

			if($uid==1){

				echo 0;

				return;

			}

			$path=WEB_PATH.'/'.ROUTE_M.'/user/lists';

			$this->db->Query("DELETE FROM `@#_admin` WHERE (`uid`='$uid')");

			if($this->db->affected_rows())			

				echo $path;

			else

				echo 'no';

			exit;

		}

	}



	public function login11111112(){

		if(isset($_POST['ajax'])){			

			$location=WEB_PATH.'/'.ROUTE_M.'/index';

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

			

			$info=$this->db->GetOne("SELECT * FROM `@#_admin` WHERE `username` = '$username' LIMIT 1");		

			if(!$info){$message['error']=true;$message['text']="登录失败,请检查用户名或密码!";echo json_encode($message);exit;}

			if($info['userpass']!=md5($password)){$message['error']=true;$message['text']="登陆失败!";echo json_encode($message);exit;}

			

			if(!$message['error']){				

				_setcookie("AID",_encrypt($info['uid'],'ENCODE'));

				_setcookie("ASHELL",_encrypt(md5($info['username'].$info['userpass'])));

				$this->AdminInfo=$info;

				$time=time();$ip=_get_ip();

				$this->db->Query("UPDATE `@#_admin` SET `logintime`='$time' WHERE (`uid`='$info[uid]')");

				$this->db->Query("UPDATE `@#_admin` SET `loginip`='$ip' WHERE (`uid`='$info[uid]')");

			}

			echo json_encode($message);

			exit;			

		}else{

			include $this->tpl(ROUTE_M,'user.login');

		}

	}

	

	public function out(){

			_setcookie("AID",'');

			_setcookie("ASHELL",'');

			_message("退出成功",G_MODULE_PATH.'/user/login');

	}

}

?>