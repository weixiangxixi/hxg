<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','member','no');

System::load_app_fun('my','go');

System::load_app_fun('user','go');

System::load_sys_fun('send');

System::load_sys_fun('user');

class ajax extends base {

    private $Mcartlist;

    private $Mcartlist_jf;



	public function __construct(){

		parent::__construct();

/* 		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){

			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);

		}	 */

		$this->db = System::load_sys_class('model');





		//查询购物车的信息

		$Mcartlist=_getcookie("Cartlist");

		$this->Mcartlist=json_decode(stripslashes($Mcartlist),true);

	

		$Mcartlist_jf=_getcookie("Cartlist_jf");

		$this->Mcartlist_jf=json_decode(stripslashes($Mcartlist_jf),true);

	}

	public function init(){

	    if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){

			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);

		}



		$member=$this->userinfo;

		$title="我的会员中心";



		 $user['code']=1;

		 $user['username']=get_user_name($member['uid']);

		 $user['uid']=$member['uid'];

		 if(!empty($member)){

		   $user['code']=0;

		 }



		echo json_encode($user);





	}

	//幻灯

	public function slides(){

	  $sql="select * from `@#_wap` where 1";

	  $SlideList=$this->db->GetList($sql);

	  if(empty($SlideList)){

	    $slides['state']=1;

	  }else{

	   $slides['state']=0;

	    foreach($SlideList as $key=>$val){

		   $shopid = ereg_replace('[^0-9]','',$val['link']);

		  // $shopid=explode("/",$val['link']);

		   $slides['listItems'][$key]['alt']=$val['color'];

		   $slides['listItems'][$key]['url']=WEB_PATH."/mobile/mobile/item/".$shopid;

		   $slides['listItems'][$key]['src']=G_WEB_PATH."/statics/uploads/".$val['img'];

		   $slides['listItems'][$key]['width']='614PX';

		   $slides['listItems'][$key]['height']='110PX';



		}



	  }

	   echo json_encode($slides);

	}



   // 今日揭晓商品

    public function show_jrjxshop(){

		$pagetime=safe_replace($this->segment(4));





		$w_jinri_time = strtotime(date('Y-m-d'));

		$w_minri_time = strtotime(date('Y-m-d',strtotime("+1 day")));





		$jinri_shoplist = $this->db->GetList("select * from `@#_shoplist` where `xsjx_time` > '$w_jinri_time' and `xsjx_time` < '$w_minri_time' order by xsjx_time limit 0,3 ");



		if(!empty($jinri_shoplist)){

		   $m['errorCode']=0;

		}else{

		   $m['errorCode']=1;

		}

		//echo $pagetime;

		echo json_encode($m);



	}

	//最新揭晓商品

	public function show_newjxshop(){



		//最新揭晓

		$shopqishu=$this->db->GetList("select * from `@#_shoplist` where `q_end_time` !='' ORDER BY `q_end_time` DESC LIMIT 4");



		echo json_encode($shopqishu);



	}



	//即将揭晓商品

	public function show_msjxshop(){

	      //暂时没做







		//即将揭晓商品

	    $shoplist['listItems'][0]['codeID']=14;  //商品id

	    $shoplist['listItems'][0]['period']=3;  //商品期数

	    $shoplist['listItems'][0]['goodsSName']='苹果';  //商品名称

	    $shoplist['listItems'][0]['seconds']=10;  //商品名称



		$shoplist['errorCode']=0;

		//echo json_encode($shoplist);



	}



    //购物车数量

	public function cartnum(){

	  //$Mcartlist=$this->Mcartlist;
		session_start();
		$Mcartlist = json_decode($_SESSION['gwc'],true);

	  if(is_array($Mcartlist)){

	  	  $cartnum['code']=0;

	      $cartnum['num']=count($Mcartlist);

	  }else{

	  	  $cartnum['code']=1;

	      $cartnum['num']=0;

	  }

      //var_dump($Mcartlist);

	  echo json_encode($cartnum);

	}



	//添加购物车

	public function addShopCart(){
	  if(!$this->userinfo){
	  		$cart['code']=-1;
	  		echo json_encode($cart);exit;
	  }
	  $ShopId=safe_replace($this->segment(4));

	  $ShopNum=safe_replace($this->segment(5));



	  $cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车



	  $shopis=0;          //0表示不存在  1表示存在

	  //$Mcartlist=$this->Mcartlist;
	  session_start();
	  $Mcartlist = json_decode($_SESSION['gwc'],true);

	  $uid = $this->userinfo['uid'];
	  $data = $this->db->GetOne("SELECT `cateid`,`str4`,`sid` FROM `@#_shoplist` WHERE `id` = '$ShopId'");
	  if($data['cateid'] == '177'){
	  		//用户关联号
	   		$cjcs = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$uid'");
	   		//微信1关联号
	   		if(!empty($cjcs['wxopenid1'])){
	   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
	   		}else{
	   			$wx1 = '';
	   		}

	   		foreach ($wx1 as $key => $val) {
	   			$wx1_str[] = $val['uid'];
	   		}
	   		
	   		$wx1_uid = implode(',', $wx1_str);

	   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$ShopId'");

	   		if(count($wx1_data) >= 2){
	   			$cart['code']=3;
	   			echo json_encode($cart);
	  	  		exit;
	   		}

	   		$sid = $data['sid'];
	   		$time_start = strtotime(date("Ymd",time()));
	   		$time_start2 = strtotime(date("Ymd",time()))-20000;
	   		$time_end = $time_start + 86400;
	   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");
	   		foreach ($xgqs as $key => $val) {
	   			$qs[] = $val['id'];
	   		}
	   		$qs_id = implode(',', $qs);
	   		unset($qs);
	   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
	   		$flag = false;
	   		foreach ($qs_bh as $key => $val) {
	   			if($ShopId == $val['shopid']){
	   				$flag = true;
	   				break;
	   			}
	   		}
	   		
	   		if(count($qs_bh) >= 5 && $flag == false){
	   			$cart['code'] = 5;
	   			echo json_encode($cart);
	  	  		exit;
	   		}

	   		$time = time() - strtotime(date("Ymd",time()));
	   		$time1 = 10*60*60;
	   		$time2 = 24*60*60;
	   		if($time < $time1 || $time > $time2){
	   			$cart['code'] = 4;
	   			echo json_encode($cart);
	  	  		exit;
	   		}

		  	$yg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$ShopId'");
			if($yg['m'] >= $data['str4']){
	  	  		$cart['code']=2;   //限购上限 添加失败
	  	  		echo json_encode($cart);
	  	  		exit;
	  	  	}
  	  }

	if($ShopId==0 || $ShopNum==0){



	  $cart['code']=1;   //表示添加失败



	}else{

		  if(is_array($Mcartlist)){

			foreach($Mcartlist as $key=>$val){

			   if($key==$ShopId){
			   	  $shenyu = $this->db->GetOne("SELECT `shenyurenshu` FROM `@#_shoplist` WHERE `id` = '$ShopId'");

			      if(isset($cartbs) && $cartbs=='cart'){

	                $Mcartlist[$ShopId]['num']=$ShopNum;

				  }else{
				    if(($val['num']+$ShopNum) <= $shenyu['shenyurenshu']){
					    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
					}

				  }

				  $shopis=1;

			   }else{

				  $Mcartlist[$key]['num']=$val['num'];

			   }

			}



		  }else{

			  $Mcartlist =array();

			  $Mcartlist[$ShopId]['num']=$ShopNum;

		  }





           if($shopis==0){

		     $Mcartlist[$ShopId]['num']=$ShopNum;

		   }


	  session_start();
	  $_SESSION['gwc'] = json_encode($Mcartlist);
       //_setcookie('Cartlist',json_encode($Mcartlist),'');

	  $cart['code']=0;   //表示添加成功

	}



	  $cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录



	  echo json_encode($cart);



	}
	public function addShopCartAll(){
      	if(!$this->userinfo){echo -1;exit();}
		$arr = $_POST['item'];
		//$data=$this->Mcartlist;
		session_start();
		$data = json_decode($_SESSION['gwc'],true);
		$k = 0;
		foreach ($arr as $k => $v) {
			//$this->addShopCart1($v);
			$data[$v] = ["num"=>"1"];
		}
		//$_COOKIE['Cartlist'] = json_encode($data);
		$_SESSION['gwc'] = json_encode($data);
		//_setcookie('Cartlist',json_encode($data),'');
		echo 0;
	}

	public function addShopCartAll2(){
		$order = $_POST['order'];
		$sortid = $_POST['sortid'];
		if($order == "20"){
			$brr = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '173' AND `q_end_time` is null"); //人气
		}else if($order == "0"){
			$brr = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '173' AND `brandid` = '$sortid' AND `q_end_time` is null"); //分类
		}else if($order == '10' || $order == '11'){
			$brr = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '173' AND `q_end_time` is null"); //即将揭晓
		}else if($order == ''){
			$uid = $this->userinfo['uid'];
			if(empty($uid)){
				$brr = '';
			}else{
				$crr = $this->db->GetList("select * from `@#_collection` where `uid` = $uid order by id");
				foreach ($crr as $key => $val) {
					$id[] = $val['sid'];
				}
				$sc_id = implode(',', $id);

		        $brr = $this->db->GetList("SELECT `id` from `@#_shoplist` WHERE `cateid` = '173' AND `sid` in($sc_id) AND `q_end_time` is null");
			}
		}
		foreach ($brr as $key => $val) {
			$arr[] = $val['id'];
		}
		//$data=$this->Mcartlist;
		session_start();
		$data = json_decode($_SESSION['gwc'],true);
		$k = 0;
		foreach ($arr as $k => $v) {
			//$this->addShopCart1($v);
			$data[$v] = ["num"=>"1"];
		}
		//$_COOKIE['Cartlist'] = json_encode($data);
		$_SESSION['gwc'] = json_encode($data);
		//_setcookie('Cartlist',json_encode($data),'');
		if(empty($brr)){
			$str['error'] = 1;
		}else{
			$str['error'] = 0;
			$str['count'] = count($arr);
		}
		echo json_encode($str);
	}

	public function addAllToCart(){
		$arr = explode(",", $_GET['sids']);
		$num = $_GET['num'];
		session_start();
		$data = json_decode($_SESSION['gwc'],true);
		$k = 0;
		foreach ($arr as $k => $v) {
			$data[$v] = ["num"=>$num];
		}
		$_SESSION['gwc'] = json_encode($data);
		echo 0;
	}

	public function delShopCartAll(){
		//_setcookie('Cartlist','','');
		session_start();
		unset($_SESSION['gwc']);
		echo 0;
	}
	public function addShopCart1($pid){
		$ShopId = $pid;

	  	$ShopNum = 1;
	    $cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车
	    $shopis=0;          //0表示不存在  1表示存在
	    $Mcartlist=$this->Mcartlist;

		if($ShopId==0 || $ShopNum==0){
	  		$cart['code']=1;   //表示添加失败
		}else{
		  	if(is_array($Mcartlist)){
				foreach($Mcartlist as $key=>$val){
			    	if($key==$ShopId){
			      		if(isset($cartbs) && $cartbs=='cart'){
	                    	$Mcartlist[$ShopId]['num']=$ShopNum;
				  		}else{
				      		$Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;
				  		}	
				  		$shopis=1;
			    	}else{
				    	$Mcartlist[$key]['num']=$val['num'];
			    	}
				}
			}else{
			  		$Mcartlist =array();
			  		$Mcartlist[$ShopId]['num']=$ShopNum;
		    	}

			if($shopis==0){
	         	$Mcartlist[$ShopId]['num']=$ShopNum;
	   		}
       		_setcookie('Cartlist',json_encode($Mcartlist),'');
			$cart['code']=0;   //表示添加成功

		}
	  	$cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录
	  	echo json_encode($cart);
	}

	//添加购物车

	public function jf_addShopCart(){

		$ShopId=safe_replace($this->segment(4));

		$ShopNum=safe_replace($this->segment(5));



		$cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车



		$shopis=0;          //0表示不存在  1表示存在

		$Mcartlist=$this->Mcartlist_jf;

		if($ShopId==0 || $ShopNum==0){



		$cart['code']=1;   //表示添加失败



		}else{

		  if(is_array($Mcartlist)){

			foreach($Mcartlist as $key=>$val){

			   if($key==$ShopId){

			      if(isset($cartbs) && $cartbs=='cart'){

		            $Mcartlist[$ShopId]['num']=$ShopNum;

				  }else{

				    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;

				  }

				  $shopis=1;

			   }else{

				  $Mcartlist[$key]['num']=$val['num'];

			   }

			}



		    }else{

				$Mcartlist =array();

				$Mcartlist[$ShopId]['num']=$ShopNum;

		    }





		    if($shopis==0){

		    	$Mcartlist[$ShopId]['num']=$ShopNum;

		    }



			_setcookie('Cartlist_jf',json_encode($Mcartlist),'');

			$cart['code']=0;   //表示添加成功

		}



		$cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录



		echo json_encode($cart);



	}

	
	public function delCartItem1($pid){

	   $ShopId=$pid;



	   $cartlist=$this->Mcartlist;



		if($ShopId==0){



		  $cart['code']=1;   //删除失败



		}else{

			   if(is_array($cartlist)){

			      if(count($cartlist)==1){

				     foreach($cartlist as $key=>$val){

					   if($key==$ShopId){

					     $cart['code']=0;

						    _setcookie('Cartlist','','');

						}else{

					     $cart['code']=1;

					   }

					 }



				  }else{

					   foreach($cartlist as $key=>$val){

							if($key==$ShopId){

							  $cart['code']=0;

							}else{

							  $Mcartlist[$key]['num']=$val['num'];

							}

						}



						   _setcookie('Cartlist',json_encode($Mcartlist),'');



					}



				}else{

				   $cart['code']=1;   //删除失败

				}



		}

		echo json_encode($cart);

	}
	public function delCartItem(){

	   $ShopId=safe_replace($this->segment(4));



	   //$cartlist=$this->Mcartlist;
	   session_start();
	   $cartlist = json_decode($_SESSION['gwc'],true);



		if($ShopId==0){



		  $cart['code']=1;   //删除失败



		}else{

			   if(is_array($cartlist)){

			      if(count($cartlist)==1){

				     foreach($cartlist as $key=>$val){

					   if($key==$ShopId){

					     $cart['code']=0;

						    //_setcookie('Cartlist','','');
					     unset($_SESSION['gwc']);

						}else{

					     $cart['code']=1;

					   }

					 }



				  }else{

					   foreach($cartlist as $key=>$val){

							if($key==$ShopId){

							  $cart['code']=0;

							}else{

							  $Mcartlist[$key]['num']=$val['num'];

							}

						}



						   //_setcookie('Cartlist',json_encode($Mcartlist),'');
						$_SESSION['gwc'] = json_encode($Mcartlist);


					}



				}else{

				   $cart['code']=1;   //删除失败

				}



		}

		echo json_encode($cart);

	}

public function delCartItem_jf(){

	   $ShopId=safe_replace($this->segment(4));



	   $cartlist=$this->Mcartlist_jf;



		if($ShopId==0){



		  $cart['code']=1;   //删除失败



		}else{

			   if(is_array($cartlist)){

			      if(count($cartlist)==1){

				     foreach($cartlist as $key=>$val){

					   if($key==$ShopId){

					     $cart['code']=0;

						    _setcookie('Cartlist_jf','','');

						}else{

					     $cart['code']=1;

					   }

					 }



				  }else{

					   foreach($cartlist as $key=>$val){

							if($key==$ShopId){

							  $cart['code']=0;

							}else{

							  $Mcartlist[$key]['num']=$val['num'];

							}

						}



						   _setcookie('Cartlist_jf',json_encode($Mcartlist),'');



					}



				}else{

				   $cart['code']=1;   //删除失败

				}



		}

		echo json_encode($cart);

	}

	public function getCodeState(){

	  $itemid=safe_replace($this->segment(4));

	  $item=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");



	  $a['Code']=1;

	  if(!$item){

	     $a['Code']=0;

	  }



	 echo json_encode($a);

	}

	public function userlogin(){
	    $username=safe_replace($this->segment(4));
	    $password=md5(base64_decode(safe_replace($this->segment(5))));
		$logintype='';
		if(strpos($username,'@')==false){
			$logintype='mobile';//手机
		}else{
			$logintype='email';//邮箱
		}
		$member=$this->db->GetOne("select * from `@#_member` where `$logintype`='$username' and `password`='$password'");
		$mem = $this->db->GetOne("select * from `@#_member` where `$logintype`='$username'");
		if(!$mem){
			//帐号不存在错误
			$user['state']=1;
			$user['num']=-2;
			echo json_encode($user);die;
		}
		if($member[$logintype.'code'] != 1){
			$user['state']=2; //未验证
			echo json_encode($user);die;
		}
		if(!$member){
			//帐号或密码错误
			$user['state']=1;
			$user['num']=-1;
		}else{
		   //登录成功
			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			$user['state']=0;
		}
		echo json_encode($user);

	}

	public function userlogin2(){
	    $username=safe_replace($this->segment(4));
	    $code=safe_replace($this->segment(5));
	    $type = intval($_GET['type']);
		
		$time = time();
      
        $dj = $this->db->GetOne("select * from `@#_member` where `mobile`='$username'");
		if($dj['auto_user'] == '1'){
			$user['state'] = 1;
			$user['num'] = 2;
			echo json_encode($user);die;
		}
      
		if($type == 1){
			$code_time_flag = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$username' AND `code_time` >= '$time' AND `code_time` != '0'");
			$member=$this->db->GetOne("select * from `@#_member` where `mobile`='$username' and `mobile_code`='$code' and `code_time` >= '$time'");
			$mem = $this->db->GetOne("select * from `@#_member` where `mobile`='$username'");
			if(!$mem){
				//帐号不存在错误
				$user['state']=1;
				$user['num']=-2;
				echo json_encode($user);die;
			}
			if(!$code_time_flag){
				//验证码过期
				$user['state'] = 1;
				$user['num'] = -6;
				echo json_encode($user);die; 
			}
			if(!$member){
				//帐号或密码错误
				$user['state']=1;
				$user['num']=-1;
			}else{
			   //登录成功
				_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
				_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
				$user['state']=0;
			}
			echo json_encode($user);
		}else{
			$psw = md5($code);
			$member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$username' AND `password` = '$psw'");
			$mem = $this->db->GetOne("select * from `@#_member` where `mobile`='$username'");
			if(!$mem){
				//帐号不存在错误
				$user['state']=1;
				$user['num']=-2;
				echo json_encode($user);die;
			}else if($mem['code_user'] == 0){
				//不支持密码登录
				$user['state']=1;
				$user['num']=-9;
				echo json_encode($user);die;
			}
			if(!$member){
				//帐号或密码错误
				$user['state']=1;
				$user['num']=-7;
			}else{
			   //登录成功
				_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
				_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
				$user['state']=0;
			}
			echo json_encode($user);
		}

	}


	//登录成功后

	public function loginok(){



	  $user['Code']=0;

	  echo json_encode($user);

	}

	/***********************************注册*********************************/



	//检测用户是否已注册

	public function checkname(){

	    $config_email = System::load_sys_config("email");

		$config_mobile = System::load_sys_config("mobile");

		$name= $this->segment(4);

		$regtype=null;

		if(_checkmobile($name)){

			$regtype='mobile';

			$cfg_mobile_type  = 'cfg_mobile_'.$config_mobile['cfg_mobile_on'];

			$config_mobile = $config_mobile[$cfg_mobile_type];

			if(empty($config_mobile['mid']) && empty($config_email['mpass'])){



				 $user['state']=2;//_message("系统短息配置不正确!");

				 echo json_encode($user);

				 exit;

			}

		}

		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");

		if(is_array($member)){

			if($member['mobilecode']!=-1 || $member['emailcode']==1){

			  $user['state']=1;//_message("该账号已被注册");

			}else{

			  $sql="DELETE from`@#_member` WHERE `mobile` = '$name'";

			  $this->db->Query($sql);

			  $user['state']=0;

			}

		}else{

		    $user['state']=0;//表示数据库里没有该帐号

		}



	    echo json_encode($user);

	}



	//将数据注册到数据库

	public function userMobile(){

		$name= isset($_GET['username'])? $_GET['username']: $this->segment(4);

		$pass= isset($_GET['password'])? md5($_GET['password']): md5(base64_decode($this->segment(5)));

		$time=time();

		$code=abs(intval(_encrypt(_getcookie("code"),'DECODE')));

		if($code>0){

			$decode = $code;

		}else{

			$decode = 0;
			$yaoqingid = _getcookie("code456");
          	if($yaoqingid){
            	$decode = $yaoqingid;
            }
		}

		//邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证

		$sql="INSERT INTO `@#_member`(`mobile`,password,img,emailcode,mobilecode,yaoqing,time)VALUES('$name','$pass','photo/member.jpg','-1','-1','$decode','$time')";

		if(!$name || $this->db->Query($sql)){

			//header("location:".WEB_PATH."/mobile/user/".$regtype."check"."/"._encrypt($name));

			//exit;

			$userMobile['state']=0;

		}else{

			//_message("注册失败！");

			$userMobile['state']=1;

		}

	  echo json_encode($userMobile);

	}



	//验证输入的手机验证码

	public function mobileregsn(){

	    $mobile= $this->segment(4);

	    $checkcodes= $this->segment(5);



		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");



			if(strlen($checkcodes)!=6){

			    //_message("验证码输入不正确!");

				$mobileregsn['state']=1;

				echo json_encode($mobileregsn);

				exit;

			}

			$usercode=explode("|",$member['mobilecode']);

			if($checkcodes!=$usercode[0]){

			   //_message("验证码输入不正确!");

				$mobileregsn['state']=1;

				echo json_encode($mobileregsn);

				exit;

			}





			$this->db->Query("UPDATE `@#_member` SET mobilecode='1' where `uid`='$member[uid]'");



			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);

			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);



			 $mobileregsn['state']=0;

			 $mobileregsn['str']=1;
			 $uid = $member['uid'];
			 $time = time();

				
			 $new=$this->db->GetOne("SELECT * FROM `@#_new_user` WHERE `uid` = '$uid' LIMIT 1");
			  if (empty($new)) {
			  		//$this->db->Query("INSERT INTO `@#_new_user`(`uid`,`status`,`time`)VALUES('$uid','1','$time')");
			  }
			 

			//$db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
	  // 	 	$this->db->Query("UPDATE `@#_member` SET `money` = `money` + '8' where (`uid` = '$uid')");
   //  		$this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '新注册用户首次充值送8元', '8', '$time')");

	        echo json_encode($mobileregsn);

	}



	//重新发送验证码

	public function sendmobile(){



	  		$name=$this->segment(4);

			$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");

			if(!$member){

			    //_message("参数不正确!");

				$sendmobile['state']=1;

				echo json_encode($sendmobile);

				exit;

		    }

			$checkcode=explode("|",$member['mobilecode']);

			$times=time()-$checkcode[1];

			if($times > 120){



				$sendok = send_mobile_reg_code($name,$member['uid']);

				if($sendok[0]!=1){

					//_message($sendok[1]);exit;

                   	$sendmobile['state']=1;

					echo json_encode($sendmobile);

					exit;

				}

				//成功

				    $sendmobile['state']=0;

					echo json_encode($sendmobile);

					exit;

			}else{

				    $sendmobile['state']=1;

					echo json_encode($sendmobile);

					exit;

			}



	}


    public function hideRepeatShopList($shoplist)
    {

        for ($i = 0; $i < count($shoplist); $i++) {
            $data = $this->db->GetList("select * from `@#_shoplist` where `sid` = '{$shoplist[$i]['sid']}' AND `qishu` > '{$shoplist[$i]['qishu']}' limit 1");
            if(!empty($data)){
//                $this->db->Query("UPDATE `@#_shoplist` SET `is_hide`='1' where `id`= '{$shoplist[$i]['id']}' AND `canyurenshu` = '0'");
                //unset($shoplist[$i]);
            }
        }
        $shoplist = array_values($shoplist);
        return $shoplist;
    }


    //最新揭晓

	public function getLotteryList(){

	   $FIdx=$this->segment(4);

	   $EIdx=10;//$this->segment(5);

	   $isCount=$this->segment(6);

	   $time = time();

	   //$shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist` WHERE `q_uid` is not null AND `q_showtime` = 'N'");



	   //最新揭晓

		//$shoplist['listItems']=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `is_hide` = '0' AND `q_uid` is not null AND `q_showtime` = 'N' ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");
		$shoplist['listItems']=$this->db->GetList("SELECT `cateid`,`id`,`title`,`money`,`yunjiage`,`qishu`,`thumb`,`time`,`q_uid`,`q_user`,`q_user_code`,`q_end_time`, `q_showtime` FROM `@#_shoplist` WHERE `q_uid` is not null AND `q_end_time` < '$time' AND `cateid` != '173' ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");
        $shoplist['listItems'] = $this->hideRepeatShopList($shoplist['listItems']);


		if(empty($shoplist['listItems'])){

		  $shoplist['code']=1;

		}else{

		 foreach($shoplist['listItems'] as $key=>$val){

		 //查询出购买次数

		   $recodeinfo=$this->db->GetOne("select `gonumber` from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");
		   $user = $this->db->GetOne("SELECT `img` FROM `@#_member` WHERE `uid` = '$val[q_uid]'");

		   //echo "select `gonumber` from `@#_member_go_record` where `uid` !='$val[q_uid]'  and `shopid`='$val[id]' ";
		   $shoplist['listItems'][$key]['cateid']=$val['cateid'];

		   $shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);

		   $shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');

		   $shoplist['listItems'][$key]['userphotow']=get_user_key($val['q_uid'],'headimg');

		   $shoplist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);

		   $shoplist['listItems'][$key]['gonumber']=$recodeinfo['gonumber'];

		   $shoplist['listItems'][$key]['img'] = $user['img'];

		 }

		  $shoplist['code']=0;
		  // $shoplist['count']=$shopsum['total'];
		  $shoplist['count']=11652700;

		}



		echo json_encode($shoplist);



	}

	public function getLotteryList1(){

	   $FIdx=$this->segment(4);

	   $EIdx=10;//$this->segment(5);

	   $isCount=$this->segment(6);



	   //$shopsum=$this->db->GetOne("SELECT count(*) AS total FROM `@#_shoplist` WHERE `q_uid` is not null AND `q_showtime` = 'N'");



	   //最新揭晓

		$shoplist['listItems']=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `is_hide` = '0' AND `q_uid` is not null AND `q_showtime` = 'N' ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");
        $shoplist['listItems'] = $this->hideRepeatShopList($shoplist['listItems']);


		if(empty($shoplist['listItems'])){

		  $shoplist['code']=1;

		}else{

		 foreach($shoplist['listItems'] as $key=>$val){

		 //查询出购买次数

		   $recodeinfo=$this->db->GetOne("select `gonumber` from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");

		   //echo "select `gonumber` from `@#_member_go_record` where `uid` !='$val[q_uid]'  and `shopid`='$val[id]' ";

		   $shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);

		   $shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');

		   $shoplist['listItems'][$key]['userphotow']=get_user_key($val['q_uid'],'headimg');

		   $shoplist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);

		   $shoplist['listItems'][$key]['gonumber']=$recodeinfo['gonumber'];

		 }

		  $shoplist['code']=0;
		  // $shoplist['count']=$shopsum['total'];
		  $shoplist['count']=11652700;

		}



		echo json_encode($shoplist);



	}


	//访问他人购买记录

	public function getUserBuyList(){

	   $type=$this->segment(4);

	   $uid=$this->segment(5);

	   $FIdx=$this->segment(6);
	   //$FIdx = 0;

	   $EIdx=30;//$this->segment(7);

	   $isCount=$this->segment(8);

	   $mem_uid = $this->userinfo['uid'];

	   $timed = time();

	   if($type==0){

          //参与参与的商品 全部...

		 $muser=$this->db->GetOne("select * from `@#_member` where uid='$uid' order by uid desc limit 1");

		 if($muser['buy_record'] == 1 && $mem_uid != $uid){
		 	$shop['code'] = 2;
		 }else{
			  if ($muser['auto_user'] == 1) {
			  	  $shoplist = array('1','1','1','1','1','1','1','1','1','1');
	
			   	  $shop['listItems']=$this->db->GetList("select shopid,canyurenshu,zongrenshu,title,qishu,money,q_end_time,q_user_code,q_user,q_uid,thumb,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid order by a.time desc limit 0,30 " );
			  }else{
			  		$shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' limit $FIdx,$EIdx");

				  $data = $this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` where `uid`='$uid' GROUP BY shopid,shopname order by time desc limit 3");
				  $data2 = $this->db->GetList("select * from `@#_member_go_record` where `uid`='$uid' order by time desc limit 3");
				  $arr = array();
				  foreach ($data as $key => $val) {
				  	$arr[] = $val['shopid'];
				  }
				  $id = implode(',', $arr);
				  
				  //var_dump($data);var_dump($data2);
				  //$shop['listItems']=$this->db->GetList("select * from `@#_shoplist` where id in($id) limit 10" );

				  $shop['listItems']=$this->db->GetList("select shopid,canyurenshu,zongrenshu,title,qishu,money,q_end_time,q_user_code,q_user,q_uid,thumb,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx" );
		  	  }
		  	  $shop['code']=0;
		  }
		  

		 }elseif($type==1){

		   //获得奖品
		 	$muser=$this->db->GetOne("select * from `@#_member` where uid='$uid' order by uid desc limit 1");

			 if($muser['gain_shop'] == 1 && $mem_uid != $uid){
			 	$shop['code'] = 2;
			 }else{

			    $shoplist=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' " );

			    $shop['code']=0;

			    $shop['listItems']=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' and '$timed' > q_end_time order by q_end_time desc limit $FIdx,$EIdx" );
			}

		 }elseif($type==2){

		   //晒单记录
		 	$shop['code'] == 4;
		    //$shoplist=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' " );



		    //$shop['listItems']=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' order by a.sd_time desc limit $FIdx,$EIdx" );



		 }



		 if(empty($shop['listItems']) && $shop['code'] != 2){

		   $shop['code']=4;



		 }else{

		   foreach($shop['listItems'] as $key=>$val){

		      if($val['q_end_time']!=''){

			    $shop['listItems'][$key]['codeState']=3;

			    $shop['listItems'][$key]['q_user']=$this->jx_con(get_user_name($val['q_uid']),$val['q_end_time']);
                
                if(strlen($val['q_end_time']) == 10){
                    $val['q_end_time'] = $val['q_end_time'].".000";
                }

                $shop['listItems'][$key]['q_end_time']=$this->jx_con(microt($val['q_end_time']),$val['q_end_time']);

                $shop['listItems'][$key]['q_user_code']=$this->jx_con($val['q_user_code'],$val['q_end_time']);


			  }

			  if(isset($val['sd_time'])){

			   $shop['listItems'][$key]['sd_time']=date('m月d日 H:i',$val['sd_time']);

			  }

		   }

		   $shop['count']=count($shoplist);

		 }

	   echo json_encode($shop);

	}

	public function jx_con($text,$time){
		if ($time > time()) {
			return "正在揭晓";
		}else{
			return $text;
		}
	}

	 //查看计算结果

	 public function getCalResult(){

	     $itemid=$this->segment(4);

		 $curtime=time();



		 $item=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid' and `q_end_time` is not null LIMIT 1");



		if($item['q_content']){

		    $item['contcode']=0;

			$item['itemlist'] = unserialize($item['q_content']);



			foreach($item['itemlist'] as $key=>$val){

			  	$item['itemlist'][$key]['time']	=microt($val['time']);

				$h=date("H",$val['time']);

			    $i=date("i",$val['time']);

			    $s=date("s",$val['time']);

			    list($timesss,$msss) = explode(".",$val['time']);



				$item['itemlist'][$key]['timecode']=$h.$i.$s.$msss;

			}



		}else{

		    $item['contcode']=1;

		}



		if(!empty($item)){

		  $item['code']=0;



		}else{

		  $item['code']=1;

		}



    //echo "<pre>";

	//print_r($item);

	//print_r($record_time);

	   echo json_encode($item);





	 }





	 //付款

	public function UserPay(){





	}



	// 马上揭晓的商品

	public function GetStartRaffleAllList(){

		$maxSeconds = intval($this->segment(4));



		$result = array();

		$result['errorCode'] = 0;

		$result['maxSeconds'] = $maxSeconds;

		$result['listItems'] = array();



		$times = (int)System::load_sys_config('system','goods_end_time');

		$time = time();

		$list = $this->db->getlist("select cateid,qishu,xsjx_time,id,thumb,title,q_uid,q_user,q_end_time,money from `@#_shoplist` where `q_end_time` >= '$time' AND id > '$maxSeconds' AND `cateid` != '173' order by `q_end_time` DESC limit 0,200");

		foreach($list as $item) {

			if ( $result['maxSeconds'] == $maxSeconds ) {

				$result['maxSeconds'] = $item['id'];

			}



			if($item['xsjx_time']){

				$item['q_end_time'] += $times;

			}



			$data = array();
			$data['cateid'] = $item['cateid'];

			$data['id'] = $item['id'];

			$data['qishu'] = $item['qishu'];

			$data['title'] = $item['title'];

			$data['money'] = $item['money'];

			$data['thumb'] = $item['thumb'];

			$data['seconds'] = intval($item['q_end_time'] - $time);

			$result['listItems'][] = $data;

		}





		die(json_encode($result));

	}

	public function BarcodernoInfo(){
		$itemid = intval($this->segment(4));
		$res = $this->db->Query("UPDATE `@#_shoplist` SET `q_showtime`='N' where `id`= $itemid");
		$list = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`= $itemid");
		$num=$this->db->GetOne("SELECT `gonumber` FROM `@#_member_go_record` WHERE `uid` ='$list[q_uid]'  AND `shopid`='$list[id]'");
		$lists = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$list[q_uid]'");
		$result = array();

		// if (!empty($lists['mobile']) && !empty($res)) {
		// 	$this->send_mobile_zj($lists['mobile']);
		// }
		

		if($res>0){
			$result['code'] = 0;
			$result['codeType']=0;
			$result['buyCount']=$num['gonumber'];
			$result['thumb']=$list['thumb'];
			$result['codeRNO'] = $list['q_user_code'];
			$result['codeRTime'] = microt($list['q_end_time']);
			$result['img'] =$lists['img'];
			$result['headimg'] =$lists['headimg'];
			$result['user'] =$lists['username'];
			if (empty($lists['username'])) {
				$result['user'] = substr_replace($lists['mobile'],'****',3,4);
			}
			die(json_encode($result));
		}
	}


	public function paywx(){

		// ini_set('display_errors', 1);

		// error_reporting(E_ALL);



		$tradeno = $this->segment(4);

		if(empty($tradeno)){

			_message("订单不存在!");

		}

		$pay=System::load_app_class('pay','pay');

		$pay->go_pay_wx($tradeno);

		exit;

	}

	function send_mobile_zj($mobile){
			$db=System::load_sys_class('model');					
			$template = $db->GetOne("select * from `@#_caches` where `key` = 'template_mobile_shop'");
			
		
			$content = $template['value'];
				
			return _sendmobile($mobile,$content);
	}

	function shopCartAll(){
		$arr = $_POST['item'];
		$db=System::load_sys_class('model');
		$cate_id = implode(',', $arr);
		$xc = $db->GetList("SELECT distinct `cateid` FROM `@#_brand` WHERE `id` in ($cate_id)");

		$cateid = '';
		$flag = 0;
		foreach ($xc as $key => $val) {
			if($val['cateid'] == '173'){
				$flag = 1;
			}
			if($cateid == ''){
				$cateid = $val['cateid'];
			}else{
				$cateid = $cateid.",".$val['cateid'];
			}
		}
		if($flag == 1){
			$data = $db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `brandid` in ($cate_id) AND `q_uid` is null order by time desc");
		}else{
			$data = $db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `cateid` in ($cateid) AND `q_uid` is null order by time desc");
		}
		$brr = array();
		foreach ($data as $key => $val) {
			$brr[] = $val['id'];
		}
		echo json_encode($brr);
	}

	function abcd_code(){
        $member = $this->userinfo;
		if (!empty($_POST) && $member) {
			$mobile = trim($_POST['mobile']);
			$timed = time();
			$timee = strtotime(date('Ymd'));

			if (empty($_POST['mobile'])) {
				echo '没有填写手机号码';exit();
			}

			if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){  
			    echo "手机号码格式不对";exit();  
			}
			$db=System::load_sys_class('model');
			$data0 = $db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if (empty($data0)) {
				echo '会员不存在';exit();
			}
			if ($data0['vip']==0) {
				echo '会员没有权限';exit();
			}
			$uid = $data0['uid'];

			$data1 = $db->GetOne("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if (!empty($data1)) {
				if ($timed - $data1['time'] < 120) {
					echo "发送太快了";exit(); 
				}
			}

			$data2 = $db->GetList("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' AND `time`>'$timee' order by time desc");
			if (!empty($data2)) {
				if (count($data2) > 5) {
					echo '发送次数超过限制';exit();
				}
			}
			
			$send = send_abcd_code2($mobile,$uid);
			if ($send) {
				echo '发送成功';exit();
			}
		}
	}

	function abcd_code2(){
		if (!empty($_POST)) {
			$hhh_uid = $this->userinfo['uid'];
			$hhh = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$hhh_uid'");
			$mobile = $hhh['mobile'];
			$timed = time();
			$timee = strtotime(date('Ymd'));

			if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){  
			    echo "手机号码格式不对1";exit();  
			}
			$db=System::load_sys_class('model');
			$data0 = $db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if (empty($data0)) {
				echo '会员不存在';exit();
			}
			if ($data0['vip']==0) {
				echo '会员没有权限';exit();
			}
			$uid = $data0['uid'];

			$data1 = $db->GetOne("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if (!empty($data1)) {
				if ($timed - $data1['time'] < 120) {
					echo "发送太快了";exit(); 
				}
			}

			$data2 = $db->GetList("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' AND `time`>'$timee' order by time desc");
			if (!empty($data2)) {
				if (count($data2) > 5) {
					echo '发送次数超过限制';exit();
				}
			}
			
			$send = send_abcd_code($mobile,$uid);
			if ($send) {
				echo '发送成功';exit();
			}
		}
	}

	function abcd_login(){
        $member = $this->userinfo;
		if (!empty($_POST) && $member) {
			$mobile = trim($_POST['mobile']);
			$pwd = trim($_POST['pwd']);
			$code = trim($_POST['code']);

			if (empty($_POST['mobile'])) {
				echo '{"status":"0","msg":"没有填写手机号码"}';exit();
			}
			if (empty($_POST['pwd'])) {
				echo '{"status":"0","msg":"没有填写密码"}';exit();
			}
			if (empty($_POST['code'])) {
				echo '{"status":"0","msg":"没有填写验证码"}';exit();
			}
			if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){    
			    echo '{"status":"0","msg":"手机号码格式不对"}';exit();
			}
			$db=System::load_sys_class('model');
			$data0 = $db->GetOne("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if ($data0['content'] != $code) {
				echo '{"status":"0","msg":"验证码错误"}';exit();
			}
			$time = time();
			if ($time - $data0['time'] > 300) {
				echo '{"status":"0","msg":"验证码过期"}';exit();
			}
			$data1 = $db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if (empty($data1)) {
				echo '{"status":"0","msg":"会员不存在"}';exit();
			}
            if($data1['auto_user'] == '1'){
				echo '{"status":"0","msg":"账号被冻结;请联系客服人员!"}';exit();
			}
			if ($data1['vip']==0) {
				echo '{"status":"0","msg":"会员没有权限"}';exit();
			}
			$uid = $data1['uid'];
			$data2 = $db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid' limit 1");
			if (empty($data2)) {
				echo '{"status":"0","msg":"登陆失败"}';exit();
			}
			if ($data2['zhuan_password'] != md5($pwd)) {
				echo '{"status":"0","msg":"密码错误"}';exit();
			}
			_setcookie("vip", _encrypt($mobile), time()+3600);

			echo '{"status":"1","msg":"登陆成功"}';exit();
		}
	}

	//设置vip密码
	function abcd_login2(){
		if (!empty($_POST)) {
			$pwd = trim($_POST['pwd']);
			$code = trim($_POST['code']);
			$uid = trim($_POST['uid']);

			if (empty($_POST['pwd'])) {
				echo '{"status":"0","msg":"没有填写密码"}';exit();
			}
			if (empty($_POST['code'])) {
				echo '{"status":"0","msg":"没有填写验证码"}';exit();
			}

			$db=System::load_sys_class('model');
			$str = $db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
			$mobile = $str['mobile'];

			$data0 = $db->GetOne("SELECT * FROM `@#_sms_list` WHERE `mobile` = '$mobile' order by time desc limit 1");
			if ($data0['content'] != $code) {
				echo '{"status":"0","msg":"验证码错误"}';exit();
			}
			$time = time();
			if ($time - $data0['time'] > 120) {
				echo '{"status":"0","msg":"验证码过期"}';exit();
			}
			
			$flag = $db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid'");
			$zhuan_password = md5($pwd);
			if(!$flag){
				$data = $db->Query("INSERT INTO `@#_member_vip` (`uid`,`zhuan_password`)VALUES('$uid','$zhuan_password')");
			}else{
				$data = $db->Query("UPDATE `@#_member_vip` SET `zhuan_password`='$zhuan_password' WHERE `uid` = '$uid'");
			}
			
			if($data){
				echo '{"status":"1","msg":"设置密码成功,前往登陆"}';exit();
			}else{
				echo '{"status":"0","msg":"设置密码失败"}';exit();
			}
			
		}
	}

	//修改头像
	function userimg_before(){
      	//return false;
		$db=System::load_sys_class('model');
		$uid = $this->userinfo['uid'];
		$imgname = $_POST['filename'];
		$type = $_POST['filetype'];
		$arr = explode('.', $imgname);
	    $length = count($arr) - 1;
	    switch ($type){ 
		  case 'image/pjpeg':$okType=true; 
		   break; 
		  case 'image/jpeg':$okType=true; 
		   break; 
		  case 'image/gif':$okType=true; 
		   break; 
		  case 'image/png':$okType=true; 
		   break; 
		}
		if(!$okType){
			$data['error'] = 1;
			$data['status'] = "请上传jpg,gif,png等格式的图片！";
		}else{
			$name = "photo/".time().".".$arr[$length];
			$str = $db->Query("UPDATE `@#_member` SET `img` = '$name' WHERE `uid` = '$uid'");
			if($str){
				$data['error'] = 0;
				$data['key'] = $name;
			}else{
				$data['error'] = 1;
				$data['status'] = "上传失败！";
			}
		}
		
		echo json_encode($data);
	}
	function photo_up(){
      	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        	//exit('1');
        }
		$imgname = $_FILES['file']['name'];
	    $tmp = $_FILES['file']['tmp_name'];
	    $name = $_POST['key'];
	    if($_FILES['file']['size'] > '100000'){
	    	$data['status'] = "上传图片过大";
	    }
		//echo json_encode($_FILES);exit();
	    $type = array("image/png","image/jpg","image/png","image/bmp","image/jpeg");
		
		if (!in_array($_FILES['file']['type'],$type)){
			$data['status'] = "格式错误";
			echo json_encode($data);exit();
		}

	    $filepath = '/www/wwwroot/csthsc/statics/uploads/';
	    if(move_uploaded_file($tmp,$filepath.$name)){
	    	$x = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/sc.php?object='.$name.'&img=./statics/uploads/'.$name);
			if($x == 'ok'){
				if(file_exists("./statics/uploads/".$name)){
			        unlink("./statics/uploads/".$name);
			    }
			}
	        $data['status'] = "上传成功";
	        $data['object'] = $name;
	    }else{
	        $data['status'] = "上传失败";
	    }
	    echo json_encode($data);
	}

	/*测试上传图片--start*/

	//修改头像
	function userimg_before2(){
      	//return false;
		$db=System::load_sys_class('model');
		$member = $db->GetOne("SELECT * FROM `@#_member` WHERE `auto_user` = '1' AND `img` = 'photo/member.jpg' ORDER BY rand() LIMIT 1");
		$uid = $member['uid'];
		$imgname = $_POST['filename'];
		$type = $_POST['filetype'];
		$arr = explode('.', $imgname);
	    $length = count($arr) - 1;
	    switch ($type){ 
		  case 'image/pjpeg':$okType=true; 
		   break; 
		  case 'image/jpeg':$okType=true; 
		   break; 
		  case 'image/gif':$okType=true; 
		   break; 
		  case 'image/png':$okType=true; 
		   break; 
		}
		if(!$okType){
			$data['error'] = 1;
			$data['status'] = "请上传jpg,gif,png等格式的图片！";
		}else{
			$name = "photo/".time().".".$arr[$length];
			$str = $db->Query("UPDATE `@#_member` SET `img` = '$name' WHERE `uid` = '$uid'");
			if($str){
				$data['error'] = 0;
				$data['key'] = $name;
			}else{
				$data['error'] = 1;
				$data['status'] = "上传失败！";
			}
		}
		
		echo json_encode($data);
	}
	function photo_up2(){
      	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        	//exit('1');
        }
		$imgname = $_FILES['file']['name'];
	    $tmp = $_FILES['file']['tmp_name'];
	    $name = $_POST['key'];
	    if($_FILES['file']['size'] > '100000'){
	    	$data['status'] = "上传图片过大";
	    }
		//echo json_encode($_FILES);exit();
	    $type = array("image/png","image/jpg","image/png","image/bmp","image/jpeg");
		
		if (!in_array($_FILES['file']['type'],$type)){
			$data['status'] = "格式错误";
			echo json_encode($data);exit();
		}

	    $filepath = '/www/wwwroot/csthsc/statics/uploads/';
	    if(move_uploaded_file($tmp,$filepath.$name)){
	    	$x = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/sc.php?object='.$name.'&img=./statics/uploads/'.$name);
			if($x == 'ok'){
				if(file_exists("./statics/uploads/".$name)){
			        unlink("./statics/uploads/".$name);
			    }
			}
	        $data['status'] = "上传成功";
	        $data['object'] = $name;
	    }else{
	        $data['status'] = "上传失败";
	    }
	    echo json_encode($data);
	}

	/*测试上传图片--end*/

	//透明购最新揭晓
	function lottery_huode_shoplist(){
		$num = $this->segment(4);
		$page = $this->segment(5);
		$start = $num * $page;

		$db=System::load_sys_class('model');
		$data = $db->GetList("SELECT `id`,`q_uid`,`thumb`,`qishu`,`q_user_code`,`q_user`,`q_end_time` FROM `@#_shoplist` WHERE `cateid` = '173' AND `q_uid` is not null order by `v` desc limit $start,$num");

		if($data){
			foreach ($data as $key => $val) {
				$gonumber = $db->GetList("SELECT sum(`gonumber`) AS nums FROM `@#_member_go_record` WHERE `shopid` = '$val[id]' AND `shopqishu` = '$val[qishu]' AND `uid` = '$val[q_uid]'");
				$data[$key]['gonumber'] = $gonumber[0]['nums'];
				$q_user = unserialize($val['q_user']);
				$data[$key]['username'] = get_user_name($val['q_uid']);
				$data[$key]['email'] = $q_user['email'];
				$data[$key]['mobile'] = $q_user['mobile'];
				$data[$key]['img'] = $q_user['img'];
				$data[$key]['q_end_time'] = date("Y-m-d H:i:s",$val['q_end_time']);
			}
			$str['error'] = 0;
			$str['listItems'] = $data;
		}else{
			$str['error'] = 1;
		}
		echo json_encode($str);
	}

	function lottery_going_shoplist(){
		$id = $this->segment(4);
		if(empty($id)){
			$id = 0;
		}
		$db=System::load_sys_class('model');
		$data = $db->GetList("SELECT m.id,m.title,m.thumb,m.qishu,m.money,m.q_end_time FROM `@#_shoplist` AS m LEFT JOIN `@#_tmg` AS n ON m.id = n.shopid WHERE n.ssc_number = '' AND n.id > '$id' AND m.cateid = '173' AND m.q_uid is null order by n.id desc");
		$str['id'] = $id;
		if($data){
			foreach ($data as $key => $val) {
				$arr = $db->GetOne("SELECT `id` FROM `@#_tmg` WHERE `shopid` = '$val[id]' limit 1");
				$data[$key]['sid'] = $arr['id'];  //记录透明购id  用于排序
				$data[$key]['times'] = $val['q_end_time'] - time();
			}
			$str['error'] = 0;
			$str['listItems'] = $data;
		}else{
			$str['error'] = 1;
		}
		echo json_encode($str);
	}

	function lottery_huode_shop(){
		$id = $_GET['oid'];
		$db=System::load_sys_class('model');
		$data = $db->GetOne("SELECT `id`,`q_uid`,`thumb`,`qishu`,`q_user_code`,`q_user`,`q_end_time` FROM `@#_shoplist` WHERE `cateid` = '173' AND `id` = '$id' AND `q_uid` is not null");
		if($data){
			
			$gonumber = $db->GetList("SELECT sum(`gonumber`) AS nums FROM `@#_member_go_record` WHERE `shopid` = '$data[id]' AND `shopqishu` = '$data[qishu]' AND `uid` = '$data[q_uid]'");
			$data['gonumber'] = $gonumber[0]['nums'];
			$q_user = unserialize($data['q_user']);
			$data['username'] = get_user_name($data['q_uid']);
			$data['email'] = $q_user['email'];
			$data['mobile'] = $q_user['mobile'];
			$data['img'] = $q_user['img'];
			$data['q_end_time'] = date("Y-m-d H:i:s",$data['q_end_time']);
			
			$str = $data;
			$str['error'] = false;
		}else{
			$str['error'] = true;
		}
		echo json_encode($str);
	}

	function lottery_huode_shoplist_by_oid(){
		$db=System::load_sys_class('model');
		$data = $db->GetList("SELECT `q_uid`,`title` FROM `@#_shoplist` WHERE `cateid` = '173' AND `q_uid` is not null order by `v` desc limit 5");
		if($data){
			foreach ($data as $key => $val) {
				$data[$key]['username'] = get_user_name($val['q_uid']);
			}
			$str['error'] = 0;
			$str['listItems'] = $data;
		}else{
			$str['error'] = 1;
		}
		echo json_encode($str);
		
	}

	function sscajax(){
		$db=System::load_sys_class('model');
        $order = intval($this->segment(4));
        $page = intval($this->segment(5));
        $sortid = intval($this->segment(6));
        $num = intval($_GET['num']);
        $start = ($page-1)*$num;
        $time = time();
        if($order == '10'){
        	$select_w = 'order by (shenyurenshu/zongrenshu) ASC';
        }else if($order == '11'){
        	$select_w = 'order by (shenyurenshu/zongrenshu) DESC';
        }else if($order == '20'){
        	$select_w = 'order by `order` DESC';
        }else if($order == '0'){
        	$select_w = "AND `brandid` = '$sortid' order by `shenyurenshu` ASC";
        }
        if($order == ''){
        	$str['order'] = '1111';
        }
        $data = $db->GetList("SELECT `cateid`,`id`,`sid`,`title`,`money`,`zongrenshu`,`canyurenshu`,`shenyurenshu`,`qishu`,`maxqishu`,`thumb`,`renqi`,`time`,`order` FROM `@#_shoplist` WHERE `cateid` = '173' AND `q_uid` is null AND `q_end_time` is null $select_w limit $start,$num");
        $str['shoplist'] = $data;
        echo json_encode($str);
    }

    //透明购商品倒计时结束
    function tmg_djs(){
    	$id = $this->segment(4);
    	$db=System::load_sys_class('model');
    	$item = $db->GetOne("select * from `@#_shoplist` where `id`='" . $id . "' LIMIT 1");
    	$time = time();
    	if($item['q_uid'] == NULL && $item['q_end_time'] < $time){
    		$str['error'] = 2;
    	}else if($item['q_uid'] == NULL){
    		$str['error'] = 1;
    	}else{
    		$str['error'] = 0;
    	}
    	echo json_encode($str);
    }

}



?>