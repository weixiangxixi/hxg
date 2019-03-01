  <?php
  defined ('G_IN_SYSTEM') or exit ( 'No permission resources.' );
  System::load_app_class ( 'base', 'member', 'no' );
  System::load_app_fun ( 'user', 'go' );
  class cart extends base {
	 private $Cartlist;
	  private $Cartlist_jf;
	  public function __construct() {
		  $this->Cartlist = _getcookie('Cartlist');
		  $this->Cartlist_jf = _getcookie('Cartlist_jf');
		  $this->db = System::load_sys_class("model");
  
	  }
  
  		//购物车商品列表
	  public function cartlist1() {
		  $webname = $this->_cfg ['web_name'];
		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  if ($shopids != NULL) {
$shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );}
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
				  if ($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) {
					  $shoplist [$key] = $val;
					  $Mcartlist [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
					  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
				  }
			  }
			  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
		  }
		  
		  $MoenyCount = 0;
		  $Cartshopinfo = '{';
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
					  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
				  }
			  }
		  }
		  
		  $shop = 0;
		  
		  if (! empty ( $shoplist )) {
			  $shop = 1;
		  }
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		  include templates ( "mobile/cart", "cartlist1" );
	  }
	  //购物车商品列表
	  public function cartlist() {
	  	  parent::__construct ();
	  	  $uid = $this->userinfo['uid'];
	  	  session_start();
		  $webname = $this->_cfg ['web_name'];
		  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  $Mcartlist = json_decode($_SESSION['gwc'],true);
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  //var_dump($Mcartlist);
		  if ($shopids != NULL) {
$shoparr = $this->db->GetList ( "SELECT `sid`,`money`,`title`,`thumb`,`q_end_time`,`shenyurenshu`,`id`,`yunjiage`,`zongrenshu`,`canyurenshu`,`qishu`,`cateid`,`str4` FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ));}
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
				  if (($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) && $val['shenyurenshu']>0) {

					if($val['cateid'] == '177'){  //双11(限购)
				  	  	$shopid = $val['id'];

				  	  	//用户关联号
				   		$cjcs = $this->db->GetOne("SELECT `wxopenid1` FROM `@#_member` WHERE `uid` = '$uid'");
				   		//微信1关联号
				   		if(!empty($cjcs['wxopenid1'])){
				   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
				   		}else{
				   			$wx1 = '';
				   		}

				   		foreach ($wx1 as $k => $v) {
				   			$wx1_str[] = $v['uid'];
				   		}
				   		
				   		$wx1_uid = implode(',', $wx1_str);

				   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$shopid'");

				   		if(count($wx1_data) >= 2){   //关联用户参与多余两个
				   			unset($shparr[$key]);
				  	  		continue;
				   		}

				   		$time = time() - strtotime(date("Ymd",time()));
				   		$time1 = 10*60*60;
				   		$time2 = 24*60*60;
				   		if($time < $time1 || $time > $time2){
				   			unset($shparr[$key]);
				  	  		continue;
				   		}

				   		$sid = $val['sid'];
				   		$time_start = strtotime(date("Ymd",time()));
				   		$time_start2 = strtotime(date("Ymd",time()))-20000;
				   		$time_end = $time_start + 86400;
				   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");

				   		foreach ($xgqs as $k => $v) {
				   			$qs[] = $v['id'];
				   		}
				   		$qs_id = implode(',', $qs);
				   		unset($qs);
				   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
				   		$flag = false;
				   		foreach ($qs_bh as $k => $v) {
				   			if($shopid == $v['shopid']){
				   				$flag = true;
				   			}
				   		}
				   		if(count($qs_bh) >= 5 && $flag == false && $qs_bh){
				 
				   			unset($shparr[$key]);
				  	  		continue;
				   		}

				  	  	$xg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$shopid'");
				  	  	if(empty($xg['m'])){
				  	  		$xg['m'] = 0;
				  	  	}
				  	  	if($xg['m'] >= $val['str4']){
				  	  		unset($shparr[$key]);
				  	  		continue;
				  	  	}
				  	  	$shoplist [$key] = $val;
				  	  	$shoplist[$key]['yg'] = $xg['m'];  //已购买次数
				  	  	$sycs = $val['str4'] - $xg['m'];
				  	  	  if($Mcartlist [$val ['id']] ['num'] > $sycs){
						  	  $Mcartlist2 [$val ['id']] ['num'] = $sycs;
						  	  $Mcartlist [$val ['id']] ['num'] = $sycs;
						  }else{
						  	  $Mcartlist2 [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
						  }
				  	  }else{
				  	  	  $shoplist [$key] = $val;
				  	  	  if($Mcartlist [$val ['id']] ['num'] > $val ['shenyurenshu']){
						  	  $Mcartlist2 [$val ['id']] ['num'] = $val ['shenyurenshu'];
						  	  $Mcartlist [$val ['id']] ['num'] = $val ['shenyurenshu'];
						  }else{
						  	  $Mcartlist2 [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
						  }
				  	  }
					  
					  $Mcartlist2 [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist2 [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist2 [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
				  }
			  }
			  $_SESSION['gwc'] = json_encode($Mcartlist2);
			  $Mcartlist = json_decode($_SESSION['gwc'],true);
			  //_setcookie ( 'Cartlist', json_encode ($Mcartlist2), '' );
			  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  }
		  $MoenyCount = 0;
		  $Cartshopinfo = '{';
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
					  $shoplist [$key] ['money'] = $shoplist[$key]['money'];
					  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
				  }
			  }
		  }
		  $shop = 0;
		  if (! empty ( $shoplist )) {
			  $shop = 1;
		  }
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  
		  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";
		  include templates ( "mobile/cart", "cartlist1" );
	  }
	//购物车商品列表
	public function jf_cartlist(){
        $webname=$this->_cfg['web_name'];
		$Mcartlist=json_decode(stripslashes($this->Cartlist_jf),true);
		$shopids='';
		if(is_array($Mcartlist)){
			foreach($Mcartlist as $key => $val){
				$shopids.=intval($key).',';
			}
			$shopids=str_replace(',0','',$shopids);
			$shopids=trim($shopids,',');

		}
		$shoplist=array();
		if($shopids!=NULL){
			$shoparr=$this->db->GetList("SELECT * FROM `@#_jf_shoplist` where `id` in($shopids)",array("key"=>"id"));
		}
		if(!empty($shoparr)){
		  foreach($shoparr as $key=>$val){
		    if($val['q_end_time']=='' || $val['q_end_time']==NULL){
			   $shoplist[$key]=$val;
			   $Mcartlist[$val['id']]['num']=$Mcartlist[$val['id']]['num'];
			   $Mcartlist[$val['id']]['shenyu']=$val['shenyurenshu'];
			   $Mcartlist[$val['id']]['money']=$val['yunjiage'];
			}
		  }
		}

		$MoenyCount=0;
		$Cartshopinfo='{';
		if(count($shoplist)>=1){
			foreach($Mcartlist as $key => $val){
					$key=intval($key);
					if(isset($shoplist[$key])){
						$shoplist[$key]['cart_gorenci']=$val['num'] ? $val['num'] : 1;
						$MoenyCount+=$shoplist[$key]['yunjiage']*$shoplist[$key]['cart_gorenci'];
						$shoplist[$key]['cart_xiaoji']=substr(sprintf("%.3f",$shoplist[$key]['yunjiage']*$val['num']),0,-1);
						$shoplist[$key]['cart_shenyu']=$shoplist[$key]['zongrenshu']-$shoplist[$key]['canyurenshu'];
						$Cartshopinfo.="'$key':{'shenyu':".$shoplist[$key]['cart_shenyu'].",'num':".$val['num'].",'money':".$shoplist[$key]['yunjiage']."},";
					}
			}
		}

		$shop=0;

		if(!empty($shoplist)){
		   $shop=1;
		}
		$MoenyCount=substr(sprintf("%.3f",$MoenyCount),0,-1);
		$Cartshopinfo.="'MoenyCount':$MoenyCount}";
		include templates("mobile/cart","jf_cartlist");
	}
	public function turn_cartpay(){
		_setcookie ( 'Cartlist', NULL );
	}
	// 支付界面2
	  public function cartpay2() {
          return false;
	  	  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }

		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  if ($shopids != NULL) {
			$shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );}
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
				  if ($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) {
					  $shoplist [$key] = $val;
					  $Mcartlist [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
					  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
				  }
			  }
			  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
		  }
		  
		  $MoenyCount = 0;
		  $Cartshopinfo = '{';
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
					  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
				  }
			  }
		  }
		  
		  $shop = 0;
		  
		  if (! empty ( $shoplist )) {
			  $shop = 1;
		  }
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";

		  // $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // $shopids = '';
		  // if (is_array ( $Mcartlist )) {
			 //  foreach ( $Mcartlist as $key => $val ) {
				//   $shopids .= intval ( $key ) . ',';
			 //  }
			 //  $shopids = str_replace ( ',0', '', $shopids );
			 //  $shopids = trim ( $shopids, ',' );
		
		  // }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  if (empty($shoplist)) {
		  	 header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  
		  session_start ();
		  // $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  //include templates ( "mobile/cart", "payment" );
		  include templates ( "mobile/cart", "cartpay2" );
	  }
	  // 支付界面
	  // public function cartpay() {
	  // 	  $webname = $this->_cfg ['web_name'];
		 //  parent::__construct ();
		 //  if (! $member = $this->userinfo) {
			//   header ( "location: " . WEB_PATH . "/mobile/user/login" );
		 //  }

		 //  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		 //  session_start();
		 //  $Mcartlist = json_decode($_SESSION['gwc'],true);
		 //  // echo "<pre>";
		 //  // print_r($Mcartlist);
		 //  $shopids = '';
		 //  if (is_array ( $Mcartlist )) {
			//   foreach ( $Mcartlist as $key => $val ) {
			// 	  $shopids .= intval ( $key ) . ',';
			//   }
			//   $shopids = str_replace ( ',0', '', $shopids );
			//   $shopids = trim ( $shopids, ',' );
		 //  }
		 //  // echo $shopids;
		 //  $shoplist = array ();
		 //  if ($shopids != NULL) {
			// $shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );}
		 //  if (! empty ( $shoparr )) {
			//   foreach ( $shoparr as $key => $val ) {
			// 	  if ($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) {
			// 		  $shoplist [$key] = $val;
			// 		  $Mcartlist [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
			// 		  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
			// 		  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
			// 		  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
			// 	  }
			//   }
			//  // _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
			//   $_SESSION['gwc'] = json_encode($Mcartlist);
		 //  }
		  
		 //  $MoenyCount = 0;
		 //  $Cartshopinfo = '{';
		 //  if (count ( $shoplist ) >= 1) {
			//   foreach ( $Mcartlist as $key => $val ) {
			// 	  $key = intval ( $key );
			// 	  if (isset ( $shoplist [$key] )) {
			// 		  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
			// 		  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
			// 		  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
			// 		  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
			// 		  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
			// 	  }
			//   }
		 //  }
		  
		 //  $shop = 0;
		  
		 //  if (! empty ( $shoplist )) {
			//   $shop = 1;
		 //  }
		 //  // echo "<pre>";
		 //  // print_r($Mcartlist);
		 //  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		 //  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";

		 //  // $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		 //  // $shopids = '';
		 //  // if (is_array ( $Mcartlist )) {
			//  //  foreach ( $Mcartlist as $key => $val ) {
			// 	//   $shopids .= intval ( $key ) . ',';
			//  //  }
			//  //  $shopids = str_replace ( ',0', '', $shopids );
			//  //  $shopids = trim ( $shopids, ',' );
		
		 //  // }
		  
		 //  $shoplist = array ();
		 //  if ($shopids != NULL) {
			//   $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
			// 		  "key" => "id" 
			//   ) );
		 //  }
		 //  $MoenyCount = 0;
		 //  if (count ( $shoplist ) >= 1) {
			//   foreach ( $Mcartlist as $key => $val ) {
			// 	  $key = intval ( $key );
			// 	  if (isset ( $shoplist [$key] )) {
			// 		  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
			// 		  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
			// 		  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
			// 		  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
			// 	  }
			//   }
			//   $shopnum = 0; // 表示有商品
		 //  } else {
			//   _setcookie ( 'Cartlist', NULL );
			//   // _message("购物车没有商品!",WEB_PATH);
			//   $shopnum = 1; // 表示没有商品
		 //  }
		  
		 //  if (empty($shoplist)) {
		 //  	 header ( "location: " . WEB_PATH . "/mobile/mobile" );
		 //  }
		 //  // 总支付价格
		 //  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		 //  // 会员余额
		 //  $Money = $member ['money'];
		 //  //全返金额
		 //  $QF_money = $member['qf_money'];
		 //  // 商品数量
		 //  $shoplen = count ( $shoplist );
		  
		 //  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		 //  if ($fufen ['fufen_yuan']) {
			//   $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		 //  } else {
			//   $fufen_dikou = 0;
		 //  }
		 //  //$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		 //  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where pay_id=9");
		 //  $nopay = 1;
		 //  if (empty($paylist)) {
		 //  	 $nopay = 0;
		 //  }
		 //  session_start ();
		 //  // $_SESSION ['submitcode'] = $submitcode = uniqid ();
		 //  //include templates ( "mobile/cart", "payment" );
		 //  include templates ( "mobile/cart", "cartpay2" );
	  // }
	  public function cartpay3() {
        return false;
	  	  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }

		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		  }
		  // echo $shopids;
		  $shoplist = array ();
		  if ($shopids != NULL) {
			$shoparr = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array ("key" => "id" ) );}
		  if (! empty ( $shoparr )) {
			  foreach ( $shoparr as $key => $val ) {
				  if ($val ['q_end_time'] == '' || $val ['q_end_time'] == NULL) {
					  $shoplist [$key] = $val;
					  $Mcartlist [$val ['id']] ['num'] = $Mcartlist [$val ['id']] ['num'];
					  $Mcartlist [$val ['id']] ['shenyu'] = $val ['shenyurenshu'];
					  $Mcartlist [$val ['id']] ['money'] = $val ['yunjiage'];
					  $Mcartlist [$val ['id']] ['sun'] = $val ['yunjiage']*$Mcartlist [$val ['id']] ['num'];
				  }
			  }
			  _setcookie ( 'Cartlist', json_encode ( $Mcartlist ), '' );
		  }
		  
		  $MoenyCount = 0;
		  $Cartshopinfo = '{';
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
					  $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist [$key] ['cart_shenyu'] . ",'num':" . $val ['num'] . ",'money':" . $shoplist [$key] ['yunjiage'] . "},";
				  }
			  }
		  }
		  
		  $shop = 0;
		  
		  if (! empty ( $shoplist )) {
			  $shop = 1;
		  }
		  // echo "<pre>";
		  // print_r($Mcartlist);
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  $Cartshopinfo .= "'MoenyCount':$MoenyCount}";

		  // $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  // $shopids = '';
		  // if (is_array ( $Mcartlist )) {
			 //  foreach ( $Mcartlist as $key => $val ) {
				//   $shopids .= intval ( $key ) . ',';
			 //  }
			 //  $shopids = str_replace ( ',0', '', $shopids );
			 //  $shopids = trim ( $shopids, ',' );
		
		  // }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  if (empty($shoplist)) {
		  	 header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1 order by pay_id desc");
		  //$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where pay_id=18");
		  $nopay = 1;
		  if (empty($paylist)) {
		  	 $nopay = 0;
		  }
		  session_start ();
		  // $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  //include templates ( "mobile/cart", "payment" );
		  include templates ( "mobile/cart", "cartpay33" );
	  }
	  // 支付界面
	  public function pay_1() {
		  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
        	$member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
        
		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		
		  }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  include templates ( "mobile/cart", "payment" );
	  }
	  // 支付界面212
	  public function pay212() {
		  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
        	$member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
        
		  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  session_start();
		  $Mcartlist = json_decode($_SESSION['gwc'],true);
		
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		
		  }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids) and `shenyurenshu` > 0", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  //全返余额
		  $QF_money = $member['qf_money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  //$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay_config` where `id` = '3' and `status` = '111'");
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  include templates ( "mobile/cart", "payment" );
	  }
	  // 支付界面
	  public function pay() {
          $member=$this->userinfo;
          $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");

          if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
              _setcookie("uid","",time()-3600);
              _setcookie("ushell","",time()-3600);
              session_start();
			  unset($_SESSION['gwc']);
              exit();return false;
          }
        
		  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
		  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  session_start();
		  $Mcartlist = json_decode($_SESSION['gwc'],true);
		
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		
		  }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids) and `shenyurenshu` > 0", array (
					  "key" => "id" 
			  ) );
		  }
		  foreach ($shoplist as $key => $val) {
		  	  if($val['cateid'] == '177'){
		  	  	    $uid = $member['uid'];
		  	  	    $shopid = $val['id'];

		  	  	    //用户关联号
			   		$cjcs = $this->db->GetOne("SELECT `wxopenid1` FROM `@#_member` WHERE `uid` = '$uid'");
			   		//微信1关联号
			   		if(!empty($cjcs['wxopenid1'])){
			   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
			   		}else{
			   			$wx1 = '';
			   		}

			   		foreach ($wx1 as $k => $v) {
			   			$wx1_str[] = $v['uid'];
			   		}
			   		
			   		$wx1_uid = implode(',', $wx1_str);

			   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$shopid'");

			   		if(count($wx1_data) >= 2){   //关联用户参与多余两个
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			   		$sid = $val['sid'];
			   		$time_start = strtotime(date("Ymd",time()));
			   		$time_start2 = strtotime(date("Ymd",time()))-20000;
			   		$time_end = $time_start + 86400;
			   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");
			   		foreach ($xgqs as $k => $v) {
			   			$qs[] = $v['id'];
			   		}
			   		$qs_id = implode(',', $qs);
			   		unset($qs);
			   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
			   		$flag = false;
			   		foreach ($qs_bh as $k => $v) {
			   			if($shopid == $v['shopid']){
			   				$flag = true;
			   			}
			   		}
			   		if(count($qs_bh) >= 5 && $flag == false){
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			   		$time = time() - strtotime(date("Ymd",time()));
			   		$time1 = 10*60*60;
			   		$time2 = 24*60*60;
			   		if($time < $time1 || $time > $time2){
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			  	  	$xg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$shopid'");
			  	  	if(empty($xg['m'])){
			  	  		$xg['m'] = 0;
			  	  	}
			  	  	if($xg['m'] >= $val['str4']){
			  	  		unset($shoplist[$key]);
			  	  		continue;
			  	  	}
			  	  	if(($xg['m'] + $Mcartlist[$shopid]['num']) > $val['str4']){
				  		if($xg['m'] < $val['str4']){
				  			$Mcartlist[$shopid]['num'] = $val['str4'] - $xg['m'];
				  		}else{
				  			unset($shoplist[$key]);
				  			continue;
				  		}
				  	}
		  	  }
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
				  	 
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  //全返余额
		  $QF_money = $member['qf_money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  //$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay_config` where `id` = '3' and `status` = '1'");
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();
          
          //include templates ( "mobile/cart", "payment" );exit;
          $pay_config = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '3'");
          if($pay_config['status'] == 1){
          	 include templates ( "mobile/cart", "payment4" );exit;
          }
          $pay_config2 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '4'");
          if($pay_config2['status'] == 1){
          	 include templates ( "mobile/cart", "payment4" );exit;
          }
          $pay_config3 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '5'");
          if($pay_config3['status'] == 1){
          	 include templates ( "mobile/cart", "payment4" );exit;
          }
          $pay_config4 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '7'");
          if($pay_config4['status'] == 1){
          	 include templates ( "mobile/cart", "payment4" );exit;
          }
		  include templates ( "mobile/cart", "payment" );
	  }

	  // 支付界面_测试
	  public function pay_test() {
          $member=$this->userinfo;
          $cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");

          if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
              _setcookie("uid","",time()-3600);
              _setcookie("ushell","",time()-3600);
              session_start();
			  unset($_SESSION['gwc']);
              exit();return false;
          }
        
		  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
		  //$Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  session_start();
		  $Mcartlist = json_decode($_SESSION['gwc'],true);
		
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		
		  }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids) and `shenyurenshu` > 0", array (
					  "key" => "id" 
			  ) );
		  }
		  foreach ($shoplist as $key => $val) {
		  	  if($val['cateid'] == '177'){
		  	  	    $uid = $member['uid'];
		  	  	    $shopid = $val['id'];

		  	  	    //用户关联号
			   		$cjcs = $this->db->GetOne("SELECT `wxopenid1` FROM `@#_member` WHERE `uid` = '$uid'");
			   		//微信1关联号
			   		if(!empty($cjcs['wxopenid1'])){
			   			$wx1 = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$cjcs[wxopenid1]' AND `uid` != '$uid'");
			   		}else{
			   			$wx1 = '';
			   		}

			   		foreach ($wx1 as $k => $v) {
			   			$wx1_str[] = $v['uid'];
			   		}
			   		
			   		$wx1_uid = implode(',', $wx1_str);

			   		$wx1_data = $this->db->GetList("SELECT distinct `uid` FROM `@#_member_go_record` WHERE `uid` in ($wx1_uid) AND `shopid` = '$shopid'");

			   		if(count($wx1_data) >= 2){   //关联用户参与多余两个
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			   		$sid = $val['sid'];
			   		$time_start = strtotime(date("Ymd",time()));
			   		$time_start2 = strtotime(date("Ymd",time()))-20000;
			   		$time_end = $time_start + 86400;
			   		$xgqs = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `sid` = '$sid' AND `time` > '$time_start2' AND `time` <= '$time_end'");
			   		foreach ($xgqs as $k => $v) {
			   			$qs[] = $v['id'];
			   		}
			   		$qs_id = implode(',', $qs);
			   		unset($qs);
			   		$qs_bh = $this->db->GetList("SELECT distinct `shopid` FROM `@#_member_go_record` WHERE `shopid` in ($qs_id) AND `uid` = '$uid' AND `time` > '$time_start' AND `time` <= '$time_end'");
			   		$flag = false;
			   		foreach ($qs_bh as $k => $v) {
			   			if($shopid == $v['shopid']){
			   				$flag = true;
			   			}
			   		}
			   		if(count($qs_bh) >= 5 && $flag == false){
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			   		$time = time() - strtotime(date("Ymd",time()));
			   		$time1 = 10*60*60;
			   		$time2 = 24*60*60;
			   		if($time < $time1 || $time > $time2){
			   			unset($shoplist[$key]);
			  	  		continue;
			   		}

			  	  	$xg = $this->db->GetOne("SELECT sum(gonumber) as m FROM `@#_member_go_record` WHERE `uid` = '$uid' AND `shopid` = '$shopid'");
			  	  	if(empty($xg['m'])){
			  	  		$xg['m'] = 0;
			  	  	}
			  	  	if($xg['m'] >= $val['str4']){
			  	  		unset($shoplist[$key]);
			  	  		continue;
			  	  	}
			  	  	if(($xg['m'] + $Mcartlist[$shopid]['num']) > $val['str4']){
				  		if($xg['m'] < $val['str4']){
				  			$Mcartlist[$shopid]['num'] = $val['str4'] - $xg['m'];
				  		}else{
				  			unset($shoplist[$key]);
				  			continue;
				  		}
				  	}
		  	  }
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
				  	 
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  //全返余额
		  $QF_money = $member['qf_money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  //$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay_config` where `id` = '3' and `status` = '1'");
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();


		  //抵用券
		  $time = time();
		  $dyq = $this->db->GetList("SELECT m.*,n.title,n.money FROM `@#_voucher_details` AS m LEFT JOIN `@#_shop_voucher` AS n ON m.v_id = n.id WHERE `uid` = '$member[uid]' AND `use_time` = '0' AND `valid_time` > '$time'");

          //include templates ( "mobile/cart", "payment" );exit;
          $pay_config = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '3'");
          if($pay_config['status'] == 1){
          	 include templates ( "mobile/cart", "payment4_test" );exit;
          }
          $pay_config2 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '4'");
          if($pay_config2['status'] == 1){
          	 include templates ( "mobile/cart", "payment4_test" );exit;
          }
          $pay_config3 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '5'");
          if($pay_config3['status'] == 1){
          	 include templates ( "mobile/cart", "payment4_test" );exit;
          }
          $pay_config4 = $this->db->GetOne("SELECT * FROM `@#_pay_config` where `id` = '7'");
          if($pay_config4['status'] == 1){
          	 include templates ( "mobile/cart", "payment4_test" );exit;
          }
		  include templates ( "mobile/cart", "payment_test" );
	  }
	  public function pay1() {
          $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		  $webname = $this->_cfg ['web_name'];
		  parent::__construct ();
		  if (! $member = $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
		  }
		  $Mcartlist = json_decode ( stripslashes ( $this->Cartlist ), true );
		  $shopids = '';
		  if (is_array ( $Mcartlist )) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $shopids .= intval ( $key ) . ',';
			  }
			  $shopids = str_replace ( ',0', '', $shopids );
			  $shopids = trim ( $shopids, ',' );
		
		  }
		  
		  $shoplist = array ();
		  if ($shopids != NULL) {
			  $shoplist = $this->db->GetList ( "SELECT * FROM `@#_shoplist` where `id` in($shopids)", array (
					  "key" => "id" 
			  ) );
		  }
		  $MoenyCount = 0;
		  if (count ( $shoplist ) >= 1) {
			  foreach ( $Mcartlist as $key => $val ) {
				  $key = intval ( $key );
				  if (isset ( $shoplist [$key] )) {
					  $shoplist [$key] ['cart_gorenci'] = $val ['num'] ? $val ['num'] : 1;
					  $MoenyCount += $shoplist [$key] ['yunjiage'] * $shoplist [$key] ['cart_gorenci'];
					  $shoplist [$key] ['cart_xiaoji'] = substr ( sprintf ( "%.3f", $shoplist [$key] ['yunjiage'] * $val ['num'] ), 0, - 1 );
					  $shoplist [$key] ['cart_shenyu'] = $shoplist [$key] ['zongrenshu'] - $shoplist [$key] ['canyurenshu'];
				  }
			  }
			  $shopnum = 0; // 表示有商品
		  } else {
			  _setcookie ( 'Cartlist', NULL );
			  // _message("购物车没有商品!",WEB_PATH);
			  $shopnum = 1; // 表示没有商品
		  }
		  
		  // 总支付价格
		  $MoenyCount = substr ( sprintf ( "%.3f", $MoenyCount ), 0, - 1 );
		  // 会员余额
		  $Money = $member ['money'];
		  // 商品数量
		  $shoplen = count ( $shoplist );
		  
		  $fufen = System::load_app_config ( "user_fufen", '', 'member' );
		  if ($fufen ['fufen_yuan']) {
			  $fufen_dikou = intval ( $member ['score'] / $fufen ['fufen_yuan'] );
		  } else {
			  $fufen_dikou = 0;
		  }
		  $paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1' AND pay_mobile = 1");
		  
		  session_start ();
		  $_SESSION ['submitcode'] = $submitcode = uniqid ();
		  include templates ( "mobile/cart", "payment4" );
	  }
	//支付界面
	public function jf_pay(){
        $webname=$this->_cfg['web_name'];
		parent::__construct();
		if(!$member=$this->userinfo){
		  header("location: ".WEB_PATH."/mobile/user/login");
		}
      	
      	$member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
      
		$Mcartlist=json_decode(stripslashes($this->Cartlist_jf),true);
		$shopids='';
		if(is_array($Mcartlist)){
			foreach($Mcartlist as $key => $val){
				$shopids.=intval($key).',';
			}
			$shopids=str_replace(',0','',$shopids);
			$shopids=trim($shopids,',');

		}

		$shoplist=array();
		if($shopids!=NULL){
			$shoplist=$this->db->GetList("SELECT * FROM `@#_jf_shoplist` where `id` in($shopids)",array("key"=>"id"));
		}
		$MoenyCount=0;
		if(count($shoplist)>=1){
			foreach($Mcartlist as $key => $val){
					$key=intval($key);
					if(isset($shoplist[$key])){
						$shoplist[$key]['cart_gorenci']=$val['num'] ? $val['num'] : 1;
						$MoenyCount+=$shoplist[$key]['yunjiage']*$shoplist[$key]['cart_gorenci'];
						$shoplist[$key]['cart_xiaoji']=substr(sprintf("%.3f",$shoplist[$key]['yunjiage']*$val['num']),0,-1);
						$shoplist[$key]['cart_shenyu']=$shoplist[$key]['zongrenshu']-$shoplist[$key]['canyurenshu'];
					}
			}
			$shopnum=0;  //表示有商品
		}else{
			_setcookie('Cartlist_jf',NULL);
			//_message("购物车没有商品!",WEB_PATH);
			$shopnum=1; //表示没有商品
		}

		//总支付价格
		$MoenyCount=substr(sprintf("%.3f",$MoenyCount),0,-1);
		//会员余额
		$Money=$member['money'];
		//商品数量
		$shoplen=count($shoplist);

		$fufen = System::load_app_config("user_fufen",'','member');
		if($fufen['fufen_yuan']){
			$fufen_dikou = intval($member['score'] / $fufen['fufen_yuan']);
		}else{
			$fufen_dikou = 0;
		}
		$paylist = $this->db->GetList("select * from `@#_pay` where `pay_start` = '1'");

		session_start();
		$_SESSION['submitcode'] = $submitcode = uniqid();
		include templates("mobile/cart","jf_payment");
	}
	  
	  // 开始支付
	  public function paysubmit() {
          $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		  $webname = $this->_cfg ['web_name'];
		  header ( "Cache-control: private" );
		  parent::__construct ();
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  session_start ();
		  
		  
		  $checkpay = $this->segment ( 4 ); // 获取支付方式 fufen money bank
		  $banktype = $this->segment ( 5 ); // 获取选择的银行 CMBCHINA ICBC CCB
		  $money = $this->segment ( 6 ); // 获取需支付金额
		  $fufen = $this->segment ( 7 ); // 获取积分
		  $submitcode1 = $this->segment ( 8 ); // 获取SESSION
		  
		  $uid = $this->userinfo ['uid'];
		  
		  
		  if (! empty ( $submitcode1 )) {
			  if (isset ( $_SESSION ['submitcode'] )) {
				  $submitcode2 = $_SESSION ['submitcode'];
			  } else {
				  $submitcode2 = null;
			  }
			  // if ($submitcode1 == $submitcode2) {
				 //  unset ( $_SESSION ["submitcode"] );
			  // } else {
				 //  $WEB_PATH = WEB_PATH;
				 //  _messagemobile ( "请不要重复提交...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
				 //  exit ();
			  // }
		  } else {
  // 			$WEB_PATH = WEB_PATH;
  // 			_messagemobile ( "正在返回购物车...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  
		
		  $zhifutype = $this->db->GetOne ( "select * from `@#_pay` where `pay_class` = 'alipay' " );
		  if (! $zhifutype) {
			  _messagemobile ( "手机支付只支持易宝,请联系站长开通！" );
		  }
		  
		  $pay_checkbox = false;
		  $pay_type_bank = false;
		  $pay_type_id = false;
		  $pay_type_flag = false;
		  
		  if ($checkpay == 'money') {
			  $pay_checkbox = true;
		  }

		  if ($checkpay == 'wx'){
		  	  $pay_checkbox = true;
		  	  $pay_type_flag = true;
		  }
		  
		  if ($banktype != 'nobank') {
			  $pay_type_id = $banktype;
		  }
		  
		  if (! empty ( $zhifutype )) {
			  $pay_type_bank = $zhifutype ['pay_class'];
		  }
		  
		 
		  if (! $pay_type_id) {
			  if ($checkpay != 'fufen' && $checkpay != 'money' && $checkpay != 'wx')
				  _messagemobile ( "选择支付方式" );
		  }
		  
		  /**
		   * ***********
		   * start
		   * ***********
		   */
		  
	
		$pay=System::load_app_class('pay','pay');
 //修改支付每次都要使用积分问题 lq 2014-12-01
        //$pay->fufen = $fufen;
		$pay->fufen = $checkpay=='fufen'?$fufen:0;
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init($uid,$pay_type_id,'go_record');	//参与商品
		if($ok != 'ok'){
			//_setcookie('Cartlist',NULL);
			unset($_SESSION['gwc']);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		  }
		  
		  $check = $pay->go_pay ( $pay_checkbox , $pay_type_flag );
		  if (! $check) {
			  _messagemobile ( "订单添加失败,请<a href='" . WEB_PATH . "/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  if ($check) {
			  // 成功
		  	  $c_flow = $money * 0.1;
		  	  $c_if = $this->db->GetOne ( "select * from `@#_member_flow` where `uid` = ".$uid );
		  	  if (empty($c_if)) {
		  	  	$flow = $this->db->Query("INSERT INTO `@#_member_flow` (`uid`, `flow`) VALUES ('$uid', '$c_flow')");
		  	  }else{
		  	  	$flow = $this->db->Query("UPDATE `@#_member_flow` SET `flow` = `flow` + $c_flow where (`uid` = '$uid')");	
		  	  }
		  	  
		  	$new=$this->db->GetOne("select * from `@#_new_user` where `uid` = ".$uid);
		 	if (!empty($new)) {
		  	 	if($new['status'] == 1){
		  	 		  $opid = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$uid'");
				      if(!empty($opid['wxopenid1'])){
				        $opid1_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$opid[wxopenid1]'");
				        $op1 = array();
				        foreach ($opid1_data as $key => $val) {
				          $op1[] = $val['uid'];
				        }
				        $opid1 = implode(',', $op1);
				        $wx_sm = $this->db->GetList("SELECT * FROM `@#_new_user` WHERE `uid` in ($opid1) AND `status` = '0'");
				      }
				      if(!empty($opid['wxopenid2'])){
				        $opid2_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid2` = '$opid[wxopenid2]'");
				        $op2 = array();
				        foreach ($opid2_data as $key => $val) {
				          $op2[] = $val['uid'];
				        }
				        $opid2 = implode(',', $op2);
				        $wx_gzh = $this->db->GetList("SELECT * FROM `@#_new_user` WHERE `uid` in ($opid2) AND `status` = '0'");
				      }
				      $czze = $this->db->GetOne("SELECT sum(`money`) as m FROM `@#_member_account` WHERE `type` = '1' AND `pay` = '账户' AND `uid` = '$uid'");
				      if(count($wx_sm) >= 2 || count($wx_gzh)>=2){
				       	$this->db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
				      }else{
						if($czze['m'] > 9){
                          $time = time();
                          //$this->db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
                          //$this->db->Query("UPDATE `@#_member` SET `money` = `money` + '8' where (`uid` = '$uid')");
                          //$this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '新注册用户首次充值送8元', '8', '$time')");
                        }
	    			}
    			}
    		}
			header ( "location: " . WEB_PATH . "/mobile/cart/paysuccess" );
		  } else {
			  // 失败
			  //_setcookie ( 'Cartlist', NULL );
		  	  unset($_SESSION['gwc']);
			  header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  exit ();
	  }

	  // 开始支付_测试
	  public function paysubmit_test() {
          $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		  $webname = $this->_cfg ['web_name'];
		  header ( "Cache-control: private" );
		  parent::__construct ();
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  session_start ();
		  
		  
		  $checkpay = $this->segment ( 4 ); // 获取支付方式 fufen money bank
		  $banktype = $this->segment ( 5 ); // 获取选择的银行 CMBCHINA ICBC CCB
		  $money = $this->segment ( 6 ); // 获取需支付金额
		  $fufen = $this->segment ( 7 ); // 获取积分
		  $submitcode1 = $this->segment ( 8 ); // 获取SESSION

		  $dyq_id = intval($_GET['dyq']);
		  
		  $uid = $this->userinfo ['uid'];
		  
		  
		  if (! empty ( $submitcode1 )) {
			  if (isset ( $_SESSION ['submitcode'] )) {
				  $submitcode2 = $_SESSION ['submitcode'];
			  } else {
				  $submitcode2 = null;
			  }
			  // if ($submitcode1 == $submitcode2) {
				 //  unset ( $_SESSION ["submitcode"] );
			  // } else {
				 //  $WEB_PATH = WEB_PATH;
				 //  _messagemobile ( "请不要重复提交...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
				 //  exit ();
			  // }
		  } else {
  // 			$WEB_PATH = WEB_PATH;
  // 			_messagemobile ( "正在返回购物车...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  
		
		  $zhifutype = $this->db->GetOne ( "select * from `@#_pay` where `pay_class` = 'alipay' " );
		  if (! $zhifutype) {
			  _messagemobile ( "手机支付只支持易宝,请联系站长开通！" );
		  }
		  
		  $pay_checkbox = false;
		  $pay_type_bank = false;
		  $pay_type_id = false;
		  $pay_type_flag = false;
		  
		  if ($checkpay == 'money') {
			  $pay_checkbox = true;
		  }

		  if ($checkpay == 'wx'){
		  	  $pay_checkbox = true;
		  	  $pay_type_flag = true;
		  }
		  
		  if ($banktype != 'nobank') {
			  $pay_type_id = $banktype;
		  }
		  
		  if (! empty ( $zhifutype )) {
			  $pay_type_bank = $zhifutype ['pay_class'];
		  }
		  
		 
		  if (! $pay_type_id) {
			  if ($checkpay != 'fufen' && $checkpay != 'money' && $checkpay != 'wx')
				  _messagemobile ( "选择支付方式" );
		  }
		  
		  /**
		   * ***********
		   * start
		   * ***********
		   */
		  
	
		$pay=System::load_app_class('pay','pay');
 //修改支付每次都要使用积分问题 lq 2014-12-01
        //$pay->fufen = $fufen;
		$pay->fufen = $checkpay=='fufen'?$fufen:0;
		$pay->pay_type_bank = $pay_type_bank;

		$ok = $pay->init_test($uid,$pay_type_id,'go_record');	//参与商品
		if($ok != 'ok'){
			//_setcookie('Cartlist',NULL);
			unset($_SESSION['gwc']);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		  }
		  var_dump($dyq_id);exit;
		  $check = $pay->go_pay_test ( $pay_checkbox , $pay_type_flag , $dyq_id);

		  if (! $check) {
			  _messagemobile ( "订单添加失败,请<a href='" . WEB_PATH . "/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }

		  if ($check) {
			  // 成功

		  	  $c_flow = $money * 0.1;
		  	  $c_if = $this->db->GetOne ( "select * from `@#_member_flow` where `uid` = ".$uid );
		  	  if (empty($c_if)) {
		  	  	$flow = $this->db->Query("INSERT INTO `@#_member_flow` (`uid`, `flow`) VALUES ('$uid', '$c_flow')");
		  	  }else{
		  	  	$flow = $this->db->Query("UPDATE `@#_member_flow` SET `flow` = `flow` + $c_flow where (`uid` = '$uid')");	
		  	  }
		  	  
		  	$new=$this->db->GetOne("select * from `@#_new_user` where `uid` = ".$uid);
		 	if (!empty($new)) {
		  	 	if($new['status'] == 1){
		  	 		  $opid = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$uid'");
				      if(!empty($opid['wxopenid1'])){
				        $opid1_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$opid[wxopenid1]'");
				        $op1 = array();
				        foreach ($opid1_data as $key => $val) {
				          $op1[] = $val['uid'];
				        }
				        $opid1 = implode(',', $op1);
				        $wx_sm = $this->db->GetList("SELECT * FROM `@#_new_user` WHERE `uid` in ($opid1) AND `status` = '0'");
				      }
				      if(!empty($opid['wxopenid2'])){
				        $opid2_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid2` = '$opid[wxopenid2]'");
				        $op2 = array();
				        foreach ($opid2_data as $key => $val) {
				          $op2[] = $val['uid'];
				        }
				        $opid2 = implode(',', $op2);
				        $wx_gzh = $this->db->GetList("SELECT * FROM `@#_new_user` WHERE `uid` in ($opid2) AND `status` = '0'");
				      }
				      $czze = $this->db->GetOne("SELECT sum(`money`) as m FROM `@#_member_account` WHERE `type` = '1' AND `pay` = '账户' AND `uid` = '$uid'");
				      if(count($wx_sm) >= 2 || count($wx_gzh)>=2){
				       	$this->db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
				      }else{
						if($czze['m'] > 9){
                          $time = time();
                          //$this->db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
                          //$this->db->Query("UPDATE `@#_member` SET `money` = `money` + '8' where (`uid` = '$uid')");
                          //$this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '新注册用户首次充值送8元', '8', '$time')");
                        }
	    			}
    			}
    		}
			header ( "location: " . WEB_PATH . "/mobile/cart/paysuccess" );
		  } else {
			  // 失败
			  //_setcookie ( 'Cartlist', NULL );
		  	  unset($_SESSION['gwc']);
			  header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  exit ();
	  }
	   // 开始支付
	  public function paysubmit1() {
          $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		  $webname = $this->_cfg ['web_name'];
		  header ( "Cache-control: private" );
		  parent::__construct ();
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  session_start ();
		  
		  
		  $checkpay = $this->segment ( 4 ); // 获取支付方式 fufen money bank
		  $banktype = $this->segment ( 5 ); // 获取选择的银行 CMBCHINA ICBC CCB
		  $money = $this->segment ( 6 ); // 获取需支付金额
		  $fufen = $this->segment ( 7 ); // 获取积分
		  $submitcode1 = $this->segment ( 8 ); // 获取SESSION
		  
		  $uid = $this->userinfo ['uid'];
		  
		  
		  if (! empty ( $submitcode1 )) {
			  if (isset ( $_SESSION ['submitcode'] )) {
				  $submitcode2 = $_SESSION ['submitcode'];
			  } else {
				  $submitcode2 = null;
			  }
			  // if ($submitcode1 == $submitcode2) {
				 //  unset ( $_SESSION ["submitcode"] );
			  // } else {
				 //  $WEB_PATH = WEB_PATH;
				 //  _messagemobile ( "请不要重复提交...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
				 //  exit ();
			  // }
		  } else {
  // 			$WEB_PATH = WEB_PATH;
  // 			_messagemobile ( "正在返回购物车...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  
		
		  $zhifutype = $this->db->GetOne ( "select * from `@#_pay` where `pay_class` = 'alipay' " );
		  if (! $zhifutype) {
			  _messagemobile ( "手机支付只支持易宝,请联系站长开通！" );
		  }
		  
		  $pay_checkbox = false;
		  $pay_type_bank = false;
		  $pay_type_id = false;
		  
		  if ($checkpay == 'money') {
			  $pay_checkbox = true;
		  }
		  
		  if ($banktype != 'nobank') {
			  $pay_type_id = $banktype;
		  }
		  
		  if (! empty ( $zhifutype )) {
			  $pay_type_bank = $zhifutype ['pay_class'];
		  }
		  
		 
		  if (! $pay_type_id) {
			  if ($checkpay != 'fufen' && $checkpay != 'money')
				  _messagemobile ( "选择支付方式" );
		  }
		  
		  /**
		   * ***********
		   * start
		   * ***********
		   */
		  
	
		$pay=System::load_app_class('pay','pay');
 //修改支付每次都要使用积分问题 lq 2014-12-01
        //$pay->fufen = $fufen;
		$pay->fufen = $checkpay=='fufen'?$fufen:0;
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init($uid,$pay_type_id,'go_record');	//参与商品
		if($ok != 'ok'){
			_setcookie('Cartlist',NULL);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		  }
		  
		  $check = $pay->go_pay1 ( $pay_checkbox );
		  if (! $check) {
			  _messagemobile ( "订单添加失败,请<a href='" . WEB_PATH . "/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  if ($check) {
			  // 成功
		  	  $c_flow = $money * 0.1;
		  	  $c_if = $this->db->GetOne ( "select * from `@#_member_flow` where `uid` = ".$uid );
		  	  if (empty($c_if)) {
		  	  	$flow = $this->db->Query("INSERT INTO `@#_member_flow` (`uid`, `flow`) VALUES ('$uid', '$c_flow')");
		  	  }else{
		  	  	$flow = $this->db->Query("UPDATE `@#_member_flow` SET `flow` = `flow` + $c_flow where (`uid` = '$uid')");	
		  	  }
		  	  
			  header ( "location: " . WEB_PATH . "/mobile/cart/paysuccess" );
		  } else {
			  // 失败
			  _setcookie ( 'Cartlist', NULL );
			  header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  exit ();
	  }
	//开始支付--全返余额支付
	 public function paysubmit2() {
           $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		  $webname = $this->_cfg ['web_name'];
		  header ( "Cache-control: private" );
		  parent::__construct ();
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  session_start ();
		  
		  
		  $checkpay = $this->segment ( 4 ); // 获取支付方式 fufen money bank
		  $banktype = $this->segment ( 5 ); // 获取选择的银行 CMBCHINA ICBC CCB
		  $money = $this->segment ( 6 ); // 获取需支付金额
		  $fufen = $this->segment ( 7 ); // 获取积分
		  $submitcode1 = $this->segment ( 8 ); // 获取SESSION
		  
		  $uid = $this->userinfo ['uid'];
		  
		  
		  if (! empty ( $submitcode1 )) {
			  if (isset ( $_SESSION ['submitcode'] )) {
				  $submitcode2 = $_SESSION ['submitcode'];
			  } else {
				  $submitcode2 = null;
			  }
			  // if ($submitcode1 == $submitcode2) {
				 //  unset ( $_SESSION ["submitcode"] );
			  // } else {
				 //  $WEB_PATH = WEB_PATH;
				 //  _messagemobile ( "请不要重复提交...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
				 //  exit ();
			  // }
		  } else {
  // 			$WEB_PATH = WEB_PATH;
  // 			_messagemobile ( "正在返回购物车...<a href='{$WEB_PATH}/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  
		
		  $zhifutype = $this->db->GetOne ( "select * from `@#_pay` where `pay_class` = 'alipay' " );
		  if (! $zhifutype) {
			  _messagemobile ( "手机支付只支持易宝,请联系站长开通！" );
		  }
		  
		  $pay_checkbox = false;
		  $pay_type_bank = false;
		  $pay_type_id = false;
		  
		  if ($checkpay == 'money') {
			  $pay_checkbox = true;
		  }
		  
		  if ($banktype != 'nobank') {
			  $pay_type_id = $banktype;
		  }
		  
		  if (! empty ( $zhifutype )) {
			  $pay_type_bank = $zhifutype ['pay_class'];
		  }
		  
		 
		  if (! $pay_type_id) {
			  if ($checkpay != 'fufen' && $checkpay != 'money')
				  _messagemobile ( "选择支付方式" );
		  }
		  
		  /**
		   * ***********
		   * start
		   * ***********
		   */
		  
	
		$pay=System::load_app_class('pay','pay');
 //修改支付每次都要使用积分问题 lq 2014-12-01
        //$pay->fufen = $fufen;
		$pay->fufen = $checkpay=='fufen'?$fufen:0;
		$pay->pay_type_bank = $pay_type_bank;
		$ok = $pay->init($uid,$pay_type_id,'go_record');	//参与商品
		if($ok != 'ok'){
			//_setcookie('Cartlist',NULL);
			unset($_SESSION['gwc']);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		  }
		  
		  $check = $pay->go_pay2 ( $pay_checkbox );

		  if (! $check) {
			  _messagemobile ( "订单添加失败,请<a href='" . WEB_PATH . "/mobile/cart/cartlist' style='color:#22AAFF'>返回购物车</a>查看" );
		  }
		  if ($check) {
			  // 成功
		  	  $c_flow = $money * 0.1;
		  	  $c_if = $this->db->GetOne ( "select * from `@#_member_flow` where `uid` = ".$uid );
		  	  if (empty($c_if)) {
		  	  	$flow = $this->db->Query("INSERT INTO `@#_member_flow` (`uid`, `flow`) VALUES ('$uid', '$c_flow')");
		  	  }else{
		  	  	$flow = $this->db->Query("UPDATE `@#_member_flow` SET `flow` = `flow` + $c_flow where (`uid` = '$uid')");	
		  	  }
		  	  
		  	$new=$this->db->GetOne("select * from `@#_new_user` where `uid` = ".$uid);
		 	if (!empty($new)) {
		  	 	if($new['status'] == 1){
		  			$time = time();
		  			$this->db->Query("UPDATE `@#_new_user` SET `status` = 0 where (`uid` = '$uid')");
	  	 			$this->db->Query("UPDATE `@#_member` SET `money` = `money` + '8' where (`uid` = '$uid')");
    				$this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '新注册用户首次充值送8元', '8', '$time')");
    			}
    		}
			header ( "location: " . WEB_PATH . "/mobile/cart/paysuccess" );
		  } else {
			  // 失败
			  //_setcookie ( 'Cartlist', NULL );
		  	  unset($_SESSION['gwc']);
			  header ( "location: " . WEB_PATH . "/mobile/mobile" );
		  }
		  exit ();
	  }
	//开始支付
	public function jf_paysubmit(){
        $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
		$webname=$this->_cfg['web_name'];
		header("Cache-control: private");
		parent::__construct();
		if(!$this->userinfo){
		  header("location: ".WEB_PATH."/mobile/user/login");
		  exit;
		}
		session_start();

		// if(isset($_POST['submitcode'])) {
		// 	if(isset($_SESSION['submitcode'])){
		// 		$submitcode = $_SESSION['submitcode'];
		// 	}else{
		// 		$submitcode = null;
		// 	}
		// 	if($_POST['submitcode'] == $submitcode){
		// 		unset($_SESSION["submitcode"]);
		// 	}else{
		// 		_message("请不要重复提交...",WEB_PATH.'/mobile/cart/jf_cartlist');
		// 	}
		// }else{
		// 	_message("正在返回购物车...",WEB_PATH.'/mobile/cart/jf_cartlist');
		// }

		$uid = $this->userinfo['uid'];

		$pay_checkbox=!empty($_POST['moneycheckbox']) ? intval($_POST['moneycheckbox']) : 0;
		$shop_score=!empty($_POST['shop_score']) ? intval($_POST['shop_score']) : 0;
		$jf_use_num=!empty($_POST['jf_use_num']) ? intval($_POST['jf_use_num']) : 0;

		if(!$pay_checkbox && !$shop_score){
			_message("请选择支付方式",WEB_PATH.'/mobile/cart/jf_cartlist');
		}

		if($pay_checkbox){
			$payact = 'zh';
		}
		if($shop_score){
			$payact = 'jf';
		}
		if($shop_score && $pay_checkbox){
			$payact = 'all';
		}


		$pay=System::load_app_class('pay','pay');

		$pay->fufen = $shop_score ? $jf_use_num : 0;
		$pay->pay_type_bank = 0;
		$ok = $pay->init($uid,$payact,'jf_go_record');	//参与商品
		if($ok != 'ok'){
			$_COOKIE['Cartlist_jf'] = NULL;
			_setcookie("Cartlist_jf",null);
			_messagemobile("购物车没有商品请<a href='".WEB_PATH."/mobile/cart/jf_cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		}

		$check = $pay->jf_go_pay($pay_checkbox);
		if($check + 100 == 0){
			_messagemobile("账户余额不足以支付，请<a href='".WEB_PATH."/mobile/home/userrecharge' style='color:#22AAFF'>充值</a>");
		}
		if(!$check){
			_messagemobile("订单添加失败,请<a href='".WEB_PATH."/mobile/cart/jf_cartlist' style='color:#22AAFF'>返回购物车</a>查看");
		}
		if($check){
			//成功
			header("location: ".WEB_PATH."/mobile/cart/jf_paysuccess");
		}else{
			//失败
			$_COOKIE['Cartlist_jf'] = NULL;
			_setcookie("Cartlist_jf",null);
			header("location: ".WEB_PATH."/mobile/mobile");
		}
		exit;
	}

	//成功页面
	public function paysuccess(){
	    $webname=$this->_cfg['web_name'];
		//_setcookie('Cartlist',NULL);
		session_start();
		unset($_SESSION['gwc']);
		include templates("mobile/cart","paysuccess");
	}

	//成功页面
	public function paycancel(){
	    $webname=$this->_cfg['web_name'];
		include templates("mobile/cart","paycancel");
	}

	//成功页面
	public function jf_paysuccess(){
	    $webname=$this->_cfg['web_name'];
		$_COOKIE['Cartlist_jf'] = NULL;
		_setcookie("Cartlist_jf",null);
		include templates("mobile/cart","jf_paysuccess");
	}
	  
	  // 充值
	  public function addmoney() {
		  parent::__construct ();
		  $webname = $this->_cfg ['web_name'];
		  $money = $this->segment ( 4 ); // 获取充值金额
		  $pay_id = $this->segment ( 5 ); // 获取选择的支付方式
			
        	$member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
        
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  $payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
		  
		  
		  if (! $payment) {
			  _messagemobile ( "对不起，没有您所选择的支付方式！" );
		  }
		  
		  if (! empty ( $payment )) {
			  $pay_type_bank = $payment ['pay_class'];
		  }
		  $pay_type_id = $pay_id;
  // 		$pay_type_bank=isset($_POST['pay_bank']) ? $_POST['pay_bank'] : false;
  // 		$pay_type_id=isset($_POST['account']) ? $_POST['account'] : false;
  // 		$money=intval($_POST['money']);
		  $uid = $this->userinfo ['uid'];
		  $pay = System::load_app_class ( 'pay', 'pay' );
		  $pay->pay_type_bank = $pay_type_bank;
		  $ok = $pay->init ( $uid, $pay_type_id, 'addmoney_record', $money );
  
		  if ($ok === 'not_pay') {
			  _messagemobile ( "未选择支付平台" );
		  }
	  }

	  // 充值
	  public function addmoney1() {
		  parent::__construct ();
		  $webname = $this->_cfg ['web_name'];
		  $money = $this->segment ( 4 ); // 获取充值金额
		  $pay_id = $this->segment ( 5 ); // 获取选择的支付方式
			
        	$member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      	
      	if($member['money'] > $cc['m'] + 100 && $member['vip'] == 0){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
          	session_start();
			unset($_SESSION['gwc']);
        	exit();return false;
        }
        
		  if (! $this->userinfo) {
			  header ( "location: " . WEB_PATH . "/mobile/user/login" );
			  exit ();
		  }
		  
		  $payment = $this->db->GetOne ( "select * from `@#_pay` where `pay_id` = ".$pay_id );
		  
		  
		  if (! $payment) {
			  _messagemobile ( "对不起，没有您所选择的支付方式！" );
		  }
		  
		  if (! empty ( $payment )) {
			  $pay_type_bank = $payment ['pay_class'];
		  }
		  $pay_type_id = $pay_id;
  // 		$pay_type_bank=isset($_POST['pay_bank']) ? $_POST['pay_bank'] : false;
  // 		$pay_type_id=isset($_POST['account']) ? $_POST['account'] : false;
  // 		$money=intval($_POST['money']);
		  $uid = $this->userinfo ['uid'];
		  $pay = System::load_app_class ( 'pay', 'pay' );
		  $pay->pay_type_bank = $pay_type_bank;
		  $ok = $pay->init ( $uid, $pay_type_id, 'addmoney_record', $money );
  
		  if ($ok === 'not_pay') {
			  _messagemobile ( "未选择支付平台" );
		  }
	  }

	  //调用透明购  获取开奖码
	  public function tmg_number(){
	  	$pay=System::load_app_class('pay','pay');
		$ok = $pay->tmg_jx();
		var_dump($ok);
	  }

	  //调用透明购  获取开奖码 手动输入
	  public function tmg_number_sd(){
	  	$expect = $this->segment(4);
	  	$opencode = $this->segment(5);
	  	$opentimestamp = $this->segment(6);
	  	$pay=System::load_app_class('pay','pay');
		$ok = $pay->tmg_jx_sd($expect,$opencode,$opentimestamp);
		var_dump($ok);
	  }
  }
  
  ?>
