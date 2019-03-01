<?php 
defined('G_IN_SYSTEM')or exit('');
System::load_app_class('admin','','no');
class menu extends admin {
	private $db;
	public function __construct(){
		parent::__construct();
		$this->db=$this->DB();
		$this->ment=array(
						array("lists","手机导航",ROUTE_M.'/'.ROUTE_C."/lists"),
						array("addcate","添加链接",ROUTE_M.'/'.ROUTE_C."/addimg"),
						
		);
	}
	
	//友情链接列表
	public function lists(){
		$linkres=array();
		$linkres=$this->db->Getlist("select * from `@#_index_menu`");		
		include $this->tpl(ROUTE_M,'menu.list');
		//var_dump(ROUTE_M);
	}
	
	//添加图片链接
	public function addimg(){
		if(isset($_POST['submit'])){
			$name=htmlspecialchars($_POST['name']);
			$url=htmlspecialchars($_POST['url']);	
			$logo='';
			if(empty($name) || empty($url)){
				_message("插入失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}			
			if(isset($_FILES['image'])){
				System::load_sys_class('upload','sys','no');
				upload::upload_config(array('png','jpg','jpeg','gif'),500000,'linkimg');
				upload::go_upload($_FILES['image']);
				if(!upload::$ok){
					_message(upload::$error,WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
				}
				$logo=upload::$filedir."/".upload::$filename;
			}
	
			$this->db->Query("INSERT INTO `@#_index_menu`(name,img,url)VALUES('$name','$logo','$url')");
			if($this->db->affected_rows()){
					_message("插入成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}else{
					_message("插入失败");
			}			
		}
		include $this->tpl(ROUTE_M,'menu.addimg');	
	}
	
	//添加文字链接
	public function addtext(){
		if(isset($_POST['submit'])){
			$name=$_POST['name'];
			$url=$_POST['url'];
			if(empty($name) && empty($url)){
				_message("插入失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}
			$this->db->Query("INSERT INTO `@#_link`(type,name,url)VALUES('1','$name','$url')");
			
			if($this->db->affected_rows()){
				_message("插入成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}else{
				_message("插入失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}
		}
		include $this->tpl(ROUTE_M,'link.addtext');	
	}

	//执行修改链接
	public function modifiylink(){
		$linkid=intval($this->segment(4));
		$linkinfo=$this->db->GetOne("SELECT * FROM `@#_index_menu` where `id`='$linkid'");
		if(!$linkinfo)_message("参数不正确");	
		
		if(isset($_POST['submit'])){		
				$name=htmlspecialchars($_POST['name']);
				$url=htmlspecialchars($_POST['url']);		
			
				$logo=$linkinfo['img'];
				if(!empty($_FILES['file']['tmp_name'])){
					System::load_sys_class('upload','sys','no');
					upload::upload_config(array('png','jpg','jpeg','gif'),500000,'linkimg');
					upload::go_upload($_FILES['image']);
					if(!upload::$ok){
						_message(upload::$error,WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
					}
					$logo=upload::$filedir."/".upload::$filename;
				}			
		
				$this->db->Query("UPDATE `@#_index_menu` SET `name`='$name',`url`='$url',`img`='$logo' WHERE `id`='$linkid'");
				if($this->db->affected_rows()){
						_message("修改成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
				}else{
						_message("修改失败");
				}
				
				
			
		}
		include $this->tpl(ROUTE_M,'menu.editlink');	
	}
		//删除链接
	public function dellink(){
		$dellink=intval($this->segment(4));
		if($dellink){
			$this->db->Query("DELETE FROM `@#_index_menu` WHERE `id`='$dellink'");
			if($this->db->affected_rows()){
				_message("删除成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}else{
				_message("删除失败",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/lists");
			}
		}		
	}

}
?>