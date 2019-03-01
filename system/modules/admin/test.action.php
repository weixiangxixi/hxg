<?php 
defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class test extends admin {

	private $db;

	public function __construct(){		

		parent::__construct();	

		System::load_app_fun('global',G_ADMIN_DIR);	

		$this->db=System::load_sys_class('model');		

		$this->ment=array(

						array("lists","订单列表",ROUTE_M."/dingdan/lists"),					

						array("lists","中奖订单",ROUTE_M."/dingdan/zhongjiang"),					

						array("lists","已发货",ROUTE_M."/dingdan/lists/sendok"),

						array("lists","未发货",ROUTE_M."/dingdan/lists/notsend"),						

						array("insert","已完成",ROUTE_M."/dingdan/lists/ok"),

						array("insert","已作废",ROUTE_M."/dingdan/lists/del"),

						array("insert","待收货",ROUTE_M."/dingdan/lists/shouhuo"),

						array("lists","未填地址",ROUTE_M."/dingdan/weitian"),

						array("lists","已填地址",ROUTE_M."/dingdan/yitian"),

						array("genzhong","<b>快递跟踪</b>",ROUTE_M."/dingdan/genzhong"),

						array("lists","微信扫码充值订单",ROUTE_M."/dingdan/wxpay"),

						array("lists","支付宝扫码充值订单",ROUTE_M."/dingdan1/alipay"),

						array("lists","打单设置",ROUTE_M."/test/shop_play_cord"),

						array("lists","打单订单",ROUTE_M."/test/dadan"),

						array("lists","不打单订单",ROUTE_M."/test/not_dadan"),

						array("lists","活动打单",ROUTE_M."/test/hd_dadan"),

						array("lists","兑换打单",ROUTE_M."/test/dh_dadan"),


						

		);

	}
	public function find_mobile_code(){
      	$uid=$this->segment(4);
    	$data = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$uid' limit 1");
      	echo $data['mobile_code'];
    }
	public function test6(){
        $data = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `maxqishu`!='200' order by time desc limit 1800");
        $data2 = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `maxqishu`='200' order by time asc limit 1500");
        $arr = array();
        $arr2 = array();
        foreach ($data as $key => $val) {
            $arr[] = $val['title'];
        }
        foreach ($data2 as $key => $val) {
            $arr2[] = $val['title'];
        }
        $arr3 = array();
        $brr = array_values((array_unique($arr)));
        $brr2 = array_values((array_unique($arr2)));
        foreach ($brr as $key => $val) {
            $t = 0;
            foreach ($brr2 as $k => $v) {
                if($v == $val){
                    $t = 1;
                }
            }
            if($t == 0){
                $arr3[] = $val;
            }

        }
        //$arr4 = array();
        foreach ($data as $key => $val) {
            foreach ($arr3 as $k => $v) {
                if($val['title'] == $v){
                    $arr4[$val['id']] = $v;
                }
            }
        }
        $arr4 = array_unique($arr4);
        $arr5 = array();
        foreach ($arr4 as $key => $val) {
            $arr5[] = $key;
        }
        //var_dump($arr4);
        //var_dump($brr2);
        foreach ($arr4 as $key => $val) {
            if($key=='1676355' || $key=='1676303' || $key=='1676287' || $key=='1676285' || $key=='1676283' || $key=='1676266' || $key=='1676260' || $key=='1676259' || $key=='1676249' || $key=='1676222' || $key=='1675632' || $key=='1674725' || $key=='1674718' || $key=='1674699' || $key=='1674624'){
                unset($arr4[$key]);
            }
        }
        foreach ($arr5 as $key => $val) {
            if($val=='1676355' || $val=='1676303' || $val=='1676287' || $val=='1676285' || $val=='1676283' || $val=='1676266' || $val=='1676260' || $val=='1676259' || $val=='1676249' || $val=='1676222' || $val=='1675632' || $key=='1674725' || $key=='1674718' || $key=='1674699' || $key=='1674624'){
                unset($arr5[$key]);
            }
        }
        var_dump($arr4);
        //var_dump($arr5);
        //$fafa = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id`='1676346'");
        //System::load_app_fun("content",G_ADMIN_DIR);
        //$ttt = $this->auto_add_shop_2($fafa);
        //var_dump($ttt);
    }

    function test5(){
    	 $type=$this->segment(4);

    	 var_dump($type);
	   $uid=$this->segment(5);

	   $FIdx=$this->segment(6);
	   //$FIdx = 0;

	   $EIdx=10;//$this->segment(7);

	   $isCount=$this->segment(8);

	   $timed = time();

		 if($type==0){

          //参与参与的商品 全部...

		 	$muser=$this->db->GetOne("select * from `@#_member` where uid='$uid' order by uid desc limit 1");
		  if ($muser['auto_user'] == 1) {
		  	//$shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid limit 0,10");
		  	$shoplist = array('1','1','1','1','1','1','1','1','1','1');


		   $shop['listItems']=$this->db->GetList("select shopid,title,qishu,money,q_end_time,q_user_code,q_user,q_uid,thumb,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid order by a.time desc limit 0,10 " );
		   var_dump($shop['listItems']);
		  }else{
		  	$shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' limit $FIdx,$EIdx");

			  $data = $this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` where `uid`='$uid' GROUP BY shopid,shopname order by time desc limit 3");
			  $data2 = $this->db->GetList("select * from `@#_member_go_record` where `uid`='$uid' order by time desc limit 3");
			  $arr = array();
			  foreach ($data as $key => $val) {
			  	$arr[] = $val['shopid'];
			  }
			  $id = implode(',', $arr);
			  
			  var_dump($data);var_dump($data2);
			  $shop['listItems']=$this->db->GetList("select * from `@#_shoplist` where id in($id) limit 10" );
			  
			  //$shop['listItems']=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx" );
		  }
		  

		 }elseif($type==1){

		   //获得奖品

		    $shoplist=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' " );



		    $shop['listItems']=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' and '$timed' > q_end_time order by q_end_time desc limit $FIdx,$EIdx" );

		 }elseif($type==2){

		   //晒单记录

		    $shoplist=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' " );



		    $shop['listItems']=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' order by a.sd_time desc limit $FIdx,$EIdx" );



		 }



		if(empty($shop['listItems'])){

		   $shop['code']=4;



		 }else{

		   foreach($shop['listItems'] as $key=>$val){

		      if($val['q_end_time']!=''){

			    $shop['listItems'][$key]['codeState']=3;

			    $shop['listItems'][$key]['q_user']=$this->jx_con(get_user_name($val['q_uid']),$val['q_end_time']);

                $shop['listItems'][$key]['q_end_time']=$this->jx_con(microt($val['q_end_time']),$val['q_end_time']);

                $shop['listItems'][$key]['q_user_code']=$this->jx_con($val['q_user_code'],$val['q_end_time']);


			  }

			  if(isset($val['sd_time'])){

			   $shop['listItems'][$key]['sd_time']=date('m月d日 H:i',$val['sd_time']);

			  }

		   }

		   $shop['code']=0;

		   $shop['count']=count($shoplist);

		 }

	   echo json_encode($shop);
    }

    function content_get_codes_table(){
	$db = System::load_sys_class("model");
	$num = $db->GetOne("SELECT * from `@#_caches` where `key` = 'shopcodes_table' LIMIT 1");
	$table = 'shopcodes_'.$num['value'];	
	$shopcodes_table = $db->GetOne("SHOW TABLE STATUS LIKE '@#_$table'");
	
	if(!$shopcodes_table || !$num) return false;
	if($shopcodes_table['Auto_increment'] >=99999){
		$num = intval($num['value'])+1;
		$shopcodes_table = 'shopcodes_'.$num;
		$q1 = $db->Query("		
				CREATE TABLE `@#_$shopcodes_table` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `s_id` int(10) unsigned NOT NULL,
				  `s_cid` smallint(5) unsigned NOT NULL,
				  `s_len` smallint(5) DEFAULT NULL,
				  `s_codes` text,
				  `s_codes_tmp` text,
				  PRIMARY KEY (`id`),
				  KEY `s_id` (`s_id`),
				  KEY `s_cid` (`s_cid`),
				  KEY `s_len` (`s_len`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
		");
		$q2 = $db->Query("UPDATE `@#_caches` SET `value` = '$num' where `key` = 'shopcodes_table'");
		if(!$q1 || !$q2)return false;
	}else{
		$num = intval($num['value']);
		$shopcodes_table = 'shopcodes_'.$num;
	}
	return $shopcodes_table;

}

/*
   生成参与码 
   CountNum @ 生成个数
   len 	    @ 生成长度
   sid	    @ 商品ID
*/
function content_get_go_codes($CountNum=null,$len=null,$sid=null){	
	$db = System::load_sys_class("model");
	//$db->Query("set global max_allowed_packet = 2*1024*1024*10");
	$table = $db->GetOne("SELECT * from `@#_caches` where `key` = 'shopcodes_table' LIMIT 1");
	$table = '@#_shopcodes_'.$table['value'];
	
	$num = ceil($CountNum/$len);
	$code_i = $CountNum;
	if($num == 1){
		$codes=array();
		for($i=1;$i<=$CountNum;$i++){
			$codes[$i]=10000000+$i;
		}shuffle($codes);$codes=serialize($codes);
		$query = $db->Query("INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '1','$CountNum','$codes','$codes')");
		unset($codes);
		return $query;
	}
	$query_1 = true;
	for($k=1;$k<$num;$k++){
		$codes=array();
		for($i=1;$i<=$len;$i++){
			$codes[$i]=10000000+$code_i;
			$code_i--;
		}shuffle($codes);$codes=serialize($codes);
		$query_1 = $db->Query("INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '$k','$len','$codes','$codes')");
		unset($codes);
	}
	$CountNum = $CountNum - (($num-1)*$len);
	$codes=array();	
	for($i=1;$i<=$CountNum;$i++){
			$codes[$i]=10000000+$code_i;	
			$code_i--;
	}shuffle($codes);$codes=serialize($codes);
	$query_2 = $db->Query("INSERT INTO `$table` (`s_id`, `s_cid`,`s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '$num','$CountNum','$codes','$codes')");
	unset($codes);
	return $query_1 && $query_2;
}

//content_get_go_codes(ceil($zongrenshu/3000),3000,$shopid);


/*
	 添加推荐位
*/
function content_add_position(){}

/*
 新建一期商品
 info 	 商品的ID 或者 商品的数组
 使用此函数注意传进来的的商品期数不等于最大期数
*/
function content_add_shop_install($info=null){
	
	$db = System::load_sys_class("model");
	$db->Autocommit_start();
	
	unset($info['id']);
	unset($info['q_uid']);
	unset($info['q_user']);
	unset($info['q_user_code']);
	unset($info['q_content']);
	unset($info['q_counttime']);
	unset($info['q_end_time']);		
	unset($info['zhiding']);	
	
	$info['xsjx_time']=0;
	$info['time'] = time();
	$info['qishu'] = intval($info['qishu']);
	$info['qishu']++;	
	$info['canyurenshu'] = '0';
	$info['shenyurenshu'] = $info['zongrenshu'];
	$info['codes_table'] = content_get_codes_table();
	$info['q_showtime']= 'N';
	$info['zhiding'] = '0';

	$keys  = $vals = '(';
	foreach($info as $key=>$val){
		$keys.="`$key`,";
		$vals.="'$val',";
	}
	$keys = rtrim($keys,',');
	$vals = rtrim($vals,',');
	$keys.= ')';
	$vals.= ')';
	
	$sql = "INSERT INTO `@#_shoplist` ".$keys." VALUES ".$vals;
	$q1 = $db->Query($sql);
	$id = $db->insert_id();	
	$q2 = content_get_go_codes($info['zongrenshu'],3000,$id);

    //file_put_contents("/www/wwwroot/m.xx.com/sql_log/autolottery_install_c.log","{$_SERVER['PHP_SELF']}:{$sql}\n",FILE_APPEND);

    if($q1 && $q2){
		$db->Autocommit_commit();
		return true;
	}else{
		$db->Autocommit_rollback();
		return false;
	}
	
}//

/*
 重新开启新一期商品
 info 	 商品的ID 或者 商品的数组
*/
function auto_add_shop($info){
	if(!empty($info)){		
		$db = System::load_sys_class("model");
		$db->Autocommit_start();
		
		unset($info['id']);
		unset($info['q_uid']);
		unset($info['q_user']);
		unset($info['q_user_code']);
		unset($info['q_content']);
		unset($info['q_counttime']);
		unset($info['q_end_time']);		
		unset($info['zhiding']);	
		
		$info['xsjx_time']=0;
		$info['time'] = time();
		$info['qishu'] = 1;
		$info['sid'] = $info['sid'];
        $info['maxqishu'] = $info['maxqishu'];
		$info['canyurenshu'] = '0';
		$info['shenyurenshu'] = $info['zongrenshu'];
		$info['q_showtime']= 'N';
		$info['zhiding'] = '0';

		$keys  = $vals = '(';
		foreach($info as $key=>$val){
			$keys.="`$key`,";
			$vals.="'$val',";
		}
		$keys = rtrim($keys,',');
		$vals = rtrim($vals,',');
		$keys.= ')';
		$vals.= ')';
		
		$sql = "INSERT INTO `@#_shoplist` ".$keys." VALUES ".$vals;
		$query_1 = $db->Query($sql);

		$id = $db->insert_id();	

		$query_2 = content_get_go_codes($info['zongrenshu'],3000,$id);

		$query_table = content_get_codes_table();

		if(!$query_table){

			$db->Autocommit_rollback();

			return false;

		}

		$query_3 = $db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`def_renshu` = '$canyurenshu' where `id` = '$id'");

		if($query_1 && $query_2 && $query_3){
			$db->Autocommit_commit();				
			return true;
		}else{		
			$db->Autocommit_rollback();
			return false;
		}	

	}

}

/*
 重新开启新一期商品
 info 	 商品的ID 或者 商品的数组
*/
function auto_add_shop_2($info){
	if(!empty($info)){		
		$db = System::load_sys_class("model");
		$db->Autocommit_start();
		
		unset($info['id']);
		unset($info['q_uid']);
		unset($info['q_user']);
		unset($info['q_user_code']);
		unset($info['q_content']);
		unset($info['q_counttime']);
		unset($info['q_end_time']);		
		unset($info['zhiding']);	
		
		$info['xsjx_time']=0;
		$info['time'] = time();
		$info['qishu'] = 1;
		$info['maxqishu'] = 200;
		$info['sid'] = 333333;
		$info['canyurenshu'] = '0';
		$info['shenyurenshu'] = $info['zongrenshu'];
		$info['q_showtime']= 'N';
		$info['zhiding'] = '0';

		$keys  = $vals = '(';
		foreach($info as $key=>$val){
			$keys.="`$key`,";
			$vals.="'$val',";
		}
		$keys = rtrim($keys,',');
		$vals = rtrim($vals,',');
		$keys.= ')';
		$vals.= ')';

		$sql = "INSERT INTO `@#_shoplist` ".$keys." VALUES ".$vals;
		$query_1 = $db->Query($sql);
		$id = $db->insert_id();
		return $id;	

		$query_2 = $this->content_get_go_codes($info['zongrenshu'],3000,$id);

		$query_table = $this->content_get_codes_table();

		if(!$query_table){

			$db->Autocommit_rollback();

			return false;

		}

		$query_3 = $db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$id',`def_renshu` = '$canyurenshu' where `id` = '$id'");

		if($query_1 && $query_2 && $query_3){
			$db->Autocommit_commit();				
			return true;
		}else{		
			$db->Autocommit_rollback();
			return false;
		}	

	}

}

	public function test(){
		$time1 = 1519488000;
		$data = $this->db->GetList("SELECT * FROM `@#_member` WHERE `time`>'$time1' AND `auto_user`!='1' order by `jingyan` desc");
		if($_POST){
			if(!empty($_POST['uid2'])){
				$phone = $_POST['uid2'];
				$x = $this->db->GetOne("SELECT `uid` FROM `@#_member` WHERE `mobile` = '$phone'");
				$uid = $x['uid'];
			}else{
				$uid = $_POST['uid'];
			}
			
			$wx_data = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `uid`='$uid' AND `status`='1' order by `update_time` desc");
			$zfb_data = $this->db->GetList("SELECT * FROM `@#_alipay_locat` WHERE `uid`='$uid' AND `status`='1' order by `update_time` desc");
			$huiyuan = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$uid'");
			if(empty($huiyuan['jingyan'])){
				$jingyan = 0;
			}else{
				$jingyan = $huiyuan['jingyan'];
			}
			$wx_money = 0;
			$zfb_money = 0;
			$total1 = 0;
			$total2 = 0;
			$zz = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid`='$uid' and `content` LIKE '%获得用户%'");
			$zz_money = 0;
			$total3 = 0;
			
		}
		$act_y = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `state` = '1'");
		$money_y = array();
		$money_yes = 0;
		foreach ($act_y as $key => $val) {
			$arr = explode(',', $val['amount']);
			$money_y[$key] = 0;
			foreach ($arr as $k => $v) {
				$money_y[$key] += $v; 
			}
			$money_yes += $money_y[$key];
		}
		$act_n = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `state` = '0'");
		$money_n = array();
		$money_no = 0;
		foreach ($act_n as $key => $val) {
			$arr = explode(',', $val['amount']);
			$money_n[$key] = 0;
			foreach ($arr as $k => $v) {
				$money_n[$key] += $v; 
			}
			$money_no += $money_n[$key];
		}

		$arr = $this->db->GetList("SELECT id,sid FROM `@#_shoplist` WHERE `q_uid` is null order by time desc");
		$count = $this->db->GetCount("SELECT COUNT(id) FROM `@#_shoplist` WHERE `q_uid` is null");
		$brr = array();
		foreach ($arr as $key => $val) {
			$brr[$val['id']] = $val['sid'];
		}
		$uq_brr = array_unique($brr);
		$crr = array_diff_assoc($brr,$uq_brr);

		$drr = array();
		foreach ($brr as $key => $val) {
			foreach ($crr as $k => $v) {
				if($v == $val){
					$drr[$key] = $val;
				}
			}
		}

		$wrr = $this->db->GetList("SELECT * FROM `@#_member_flow_recharge` WHERE 1");
		$xrr = 0;
		foreach ($wrr as $key => $val) {
			$xrr += $val['flow'];
		}
		include $this->tpl(ROUTE_M,'test.test');
	}

	public function test2(){
		$data = $this->db->GetList("SELECT * FROM `@#_member_go_record` WHERE `uid` = '3697' AND `huode` != '0'");
		$m_1 = 0;
		foreach ($data as $key => $val) {
			$shopid = $val['shopid'];
			$mm = $this->db->GetOne("SELECT `money` FROM `@#_shoplist` WHERE `id` = '$shopid'");
			$m_1 += $mm['money'];
		}
		var_dump($m_1);
		$arr = $this->db->GetList("SELECT * FROM `@#_member_go_record` WHERE `uid` = '3601' AND `huode` != '0'");
		$m_2 = 0;
		foreach ($arr as $key => $val) {
			$shopid = $val['shopid'];
			$mm2 = $this->db->GetOne("SELECT `money` FROM `@#_shoplist` WHERE `id` = '$shopid'");
			$m_2 += $mm2['money'];
		}
		var_dump($m_2);

		$brr = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '3697' AND `type` = '1' AND `content` = '通过微信公众号充值' AND `time` > '1515859200'");
		$m_3 = 0;
		foreach ($brr as $key => $val) {
			$m_3 += $val['money'];
		}
		var_dump($m_3);

		$brr2 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '3697' AND `type` = '1' AND `content` = '通过微信公众号充值' AND `time` > '1515427200'");
		$m_4 = 0;
		foreach ($brr2 as $key => $val) {
			$m_4 += $val['money'];
		}
		var_dump($m_4);
	}

	public function user_details(){
		$user_type = $this->segment(4);
		$user = urldecode($this->segment(5));
		$type = $this->segment(6);
	
		if(!empty($user_type) && !empty($user) && !empty($type)){
			if($user_type == 'uid'){
				$mm = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$user'");
				$uid = $user;
			}else if($user_type == 'mobile'){
				$mm = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile`='$user'");
				$uid = $mm['uid'];
			}else{
				$mm = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `username`='$user'");
				$uid = $mm['uid'];
			}

			if($type == 'zhye_submit'){
				$data = $this->db->GetOne("SELECT `money`,`jingyan`,`qf_money` FROM `@#_member` WHERE `uid` = '$uid'");
				$money = $data['money'];
				$jingyan = $data['jingyan'];
				$qf_money = $data['qf_money'];

				$data0 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '1' and `pay` = '账户' and `content` not like '%不中全返%' and `content` != '商城卡充值'");
		    	$money1 = 0;
		    	foreach ($data0 as $key => $val) {
		    		$money1 += $val['money'];
		    	}

		    	$data_sc = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '1' and `pay` = '账户' and `content` not like '%不中全返%' and `content` = '商城卡充值'");
		    	$money_sc = 0;
		    	foreach ($data_sc as $key => $val) {
		    		$money_sc += $val['money'];
		    	}

		    	$data2 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '1' and `pay` = '账户' and `content` like '%不中全返%'");
		    	$money2 = 0;
		    	foreach ($data2 as $key => $val) {
		    		$money2 += $val['money'];
		    	}

		    	$data3 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' and `money` != '0' and `type` = '-1' and `content` like '%不中全返%'");
		    	$money3 = 0;
		    	foreach ($data3 as $key => $val) {
		    		$money3 += $val['money'];
		    	}
		    	$money2_3 = $money2-$money3;

		    	$data4 = $this->db->GetList("SELECT * FROM `@#_member_go_record` WHERE `uid` = '$uid' and `shopid` in (SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '170')");
		    	$money4 = 0;
		    	foreach ($data4 as $key => $val) {
		    		$money4 += $val['moneycount'];
		    	}

		    	$data_sc2 = $this->db->GetOne("SELECT sum(money) sum_money FROM `@#_shoplist` WHERE `q_uid` = '$uid' AND `str1` != '0'");
		    	$money_sc2 = $data_sc2['sum_money'];

		    	$data_sc3 = $this->db->GetOne("SELECT sum(gonumber) sum_money FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS n ON m.shopid = n.id WHERE m.uid = '$uid' AND n.str1 != '0'");
		    	$money_sc3 = $data_sc3['sum_money'];


			}else if($type == 'czjl_submit'){
				$wx_data = $this->db->GetList("SELECT * FROM `@#_wxpay_locat` WHERE `uid`='$uid' order by `create_time` desc");
				$wx_money = 0;
				foreach ($wx_data as $key => $val) {
					if($val['status'] == 1){
						$wx_money += $val['money'];
                      	if($val['zhuijia'] != 0){
							$wx_money += $val['money']*$val['zhuijia'];
						}
					}
				}
				$zfb_data = $this->db->GetList("SELECT * FROM `@#_alipay_locat` WHERE `uid`='$uid' order by `create_time` desc");
				$zfb_money = 0;
				foreach ($zfb_data as $key => $val) {
					if($val['status'] == 1){
						$zfb_money += $val['money'];
					}
				}
				$zz_data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid`='$uid' and (`content` LIKE '%获得用户%' or `content`='使用佣金充值到参与账户') order by `time` desc");
				$zz_money = 0;
				foreach ($zz_data as $key => $val) {
					$zz_money += $val['money'];
				}

				$gzh_data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid`='$uid' and `content`='通过微信公众号充值' order by `time` desc");
				$gzh_money = 0;
				foreach ($gzh_data as $key => $val) {
					$gzh_money += $val['money'];
				}

				$wl_data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid`='$uid' and `content`='通过网络充值' order by `time` desc");
				$wl_money = 0;
				foreach ($wl_data as $key => $val) {
					$wl_money += $val['money'];
				}

				$yhk_data = $this->db->GetList("SELECT * FROM `@#_bank_locat` WHERE `uid`='$uid' order by `create_time` desc");
				$yhk_money = 0;
				foreach ($yhk_data as $key => $val) {
					if($val['status'] == 1){
						$yhk_money += $val['money'];
						if($val['zhuijia'] != 0){
							$yhk_money += $val['money']*$val['zhuijia'];
						}
					}
				}
			}else if($type == 'gmjl_submit'){
				$num=20;

				$total_money = $this->db->GetOne("SELECT sum(gonumber) as money FROM `@#_member_go_record` WHERE `uid`='$uid'");

				$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` WHERE `uid`='$uid'"); 

				$page=System::load_sys_class('page');

				if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

				$page->config($total,$num,$pagenum,"0");

				$data=$this->db->GetPage("SELECT m.id,m.username,m.uid,m.code,m.code_tmp,m.shopname,m.shopid,m.shopqishu,m.gonumber,m.time,m.huode,n.money,n.q_end_time,n.cateid FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS n ON m.shopid= n.id WHERE m.uid='$uid' order by m.time desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
			}else if($type == 'zjjl_submit'){
				$num=20;

				$list = $this->db->GetList("SELECT `shopid` FROM `@#_member_go_record` WHERE `uid`='$uid' AND `huode`!='0'"); 
				$zj_money = 0;
				foreach ($list as $key => $val) {
					$list2 = $this->db->GetOne("SELECT `money`,`cateid` FROM `@#_shoplist` WHERE `id`='$val[shopid]' AND `str1` = 0"); 
					if($list2['cateid'] == '177'){
						$list2['money'] = $list2['money'] * 2;
					}
					$zj_money += $list2['money'];
				}

				$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` WHERE `uid`='$uid' AND `huode`!='0'"); 

				$page=System::load_sys_class('page');

				if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

				$page->config($total,$num,$pagenum,"0");

				$data=$this->db->GetPage("SELECT m.id,m.username,m.uid,m.code,m.code_tmp,m.shopname,m.shopid,m.shopqishu,m.gonumber,m.time,m.huode,n.money,n.q_end_time,n.cateid FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS n ON m.shopid= n.id WHERE m.uid='$uid' AND m.huode!='0' AND n.str1 = 0 order by m.time desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
			}else if($type == 'csg_submit'){
				$num=20;

				$list = $this->db->GetOne("SELECT sum(n.price) as sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid='$uid' AND m.status='1'"); 
				$csg_money = intval($list['sum_money']);

				$list_xf = $this->db->GetOne("SELECT sum(n.money) as sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid='$uid' AND m.free = '0' AND n.free = '0'");
				$csg_xf = intval($list_xf['sum_money']);

				$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_kh_record` WHERE `uid`='$uid' AND `status`='1'"); 

				$page=System::load_sys_class('page');

				if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

				$page->config($total,$num,$pagenum,"0");

				$data=$this->db->GetPage("SELECT m.flag,m.id,m.uid,m.code,n.name,m.shop_id,m.create_time,m.update_time,m.status,n.price FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id= n.id WHERE m.uid='$uid' AND m.status='1' order by m.update_time desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

				$str_csg = $this->db->GetList("SELECT `id`,`name` FROM `@#_kh_shop` WHERE `is_delete` = '0'");
				foreach ($str_csg as $key => $val) {
					$shopid = $val['id'];
					$str_csg[$key]['zj'] = $this->db->GetCount("SELECT COUNT(*) FROM `@#_kh_record` WHERE `shop_id` = '$shopid' AND `uid` = '$uid' AND `status` = '1'");
					$str_csg[$key]['cy'] = $this->db->GetCount("SELECT COUNT(*) FROM `@#_kh_record` WHERE `shop_id` = '$shopid' AND `uid` = '$uid'");
					$zj_money = $this->db->GetOne("SELECT sum(n.price) as sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid = '$uid' AND m.status = '1' AND m.shop_id = '$shopid'");
					if(empty($zj_money['sum_money'])){
						$str_csg[$key]['zj_money'] = 0;
					}else{
						$str_csg[$key]['zj_money'] = intval($zj_money['sum_money']);
					}
					
					$cy_money = $this->db->GetOne("SELECT sum(n.money) as sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid='$uid' AND m.free = '0' AND n.free = '0' AND m.shop_id = '$shopid'");
					if(empty($cy_money['sum_money'])){
						$str_csg[$key]['cy_money'] = 0;
					}else{
						$str_csg[$key]['cy_money'] = intval($cy_money['sum_money']);
					}

				}
				array_multisort(array_column($str_csg,'cy'),SORT_DESC,$str_csg);
			}else if($type == 'yjmx_submit'){
						
				$brr2 = $this->db->GetList("SELECT * FROM `@#_member_recodes` WHERE `uid`='$uid' AND `type`!='1'");
				$zzc = 0;
				foreach ($brr2 as $key => $val) {
					$zzc += $val['money'];
				}
				
				$yqr = $this->db->GetList("SELECT * FROM `@#_member` WHERE `yaoqing`='$uid'");
				
				foreach ($yqr as $key => $val) {
					$one = 0;
					$brr = $this->db->GetList("SELECT * FROM `@#_member_recodes` WHERE `uid`='$val[uid]' AND `type`='1'");
					foreach ($brr as $k => $v) {
						$one += $v['money'];
					}
					$one_total[] = $one;
				}
				$zsr = 0;
				foreach ($one_total as $key => $val) {
					$zsr += $val;
				}
				$yjye = number_format($zsr - $zzc,2);

				$zrr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$uid'");
				$yaoqingren = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$zrr[yaoqing]'");

				$u_id = $this->segment(7);
				if($u_id == "txjl"){
					$num=20;

					$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_recodes` WHERE `uid`='$uid' AND `type`!='1'"); 

					$page=System::load_sys_class('page');

					if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

					$page->config($total,$num,$pagenum,"0");
					$data=$this->db->GetPage("SELECT * FROM `@#_member_recodes` WHERE `uid`='$uid' AND `type`!='1' order by `time` desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
				}else{
					$num=20;

					$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_recodes` WHERE `uid`='$u_id' AND `type`='1'"); 

					$page=System::load_sys_class('page');

					if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

					$page->config($total,$num,$pagenum,"0");
					$data=$this->db->GetPage("SELECT * FROM `@#_member_recodes` WHERE `uid`='$u_id' AND `type`='1' order by `time` desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
				}
			}else if($type == "shdz_submit"){
				$data = $this->db->GetList("SELECT * FROM `@#_member_dizhi` WHERE `uid`='$uid' AND `is_delete`='0' order by `time` desc");
				$user_data = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$uid'");
			}else if($type == "llcx_submit"){
				$cate = $this->segment(7);
				if(empty($cate)){
					$cate = 'one';
				}
				if($cate == "one"){
					$arr_cz = $this->db->GetList("SELECT * FROM `@#_member_flow_history` WHERE `uid` = '$uid' order by `create_time` desc");
					$total_cz = 0;
					foreach ($arr_cz as $key => $val) {
						if($val['status'] == 1){
							$total_cz += $val['flow_num'];
						}
					}

					$arr_dh = $this->db->GetList("SELECT * FROM `@#_member_flow_recharge` WHERE `uid` = '$uid' order by `create_time` desc");
					$total_dh = 0;
					foreach ($arr_dh as $key => $val) {
						$total_dh += $val['flow'];
					}

					$arr_ye = $this->db->GetOne("SELECT * FROM `@#_member_flow` WHERE `uid` = '$uid' limit 1");
				}else if($cate == "all"){
					if($this->segment(8) == ''){
						$time1 = strtotime(date('Y-m-d',time()-86400));
						$time2 = strtotime(date('Y-m-d',time()));
					}else if($this->segment(8) != '' && $this->segment(9) != ''){
						$time1 = strtotime($this->segment(8));
						$time2 = strtotime($this->segment(9));
					}
					$arr_cz = $this->db->GetList("SELECT * FROM `@#_member_flow_history` WHERE `create_time` > '$time1' and `create_time` < '$time2' order by `create_time` desc");
					$total_cz = 0;
					foreach ($arr_cz as $key => $val) {
						if($val['status'] == 1){
							$total_cz += $val['flow_num'];
						}
					}

					$arr_dh = $this->db->GetList("SELECT * FROM `@#_member_flow_recharge` WHERE `create_time` > '$time1' and `create_time` < '$time2' order by `create_time` desc");
					$total_dh = 0;
					foreach ($arr_dh as $key => $val) {
						$total_dh += $val['flow'];
					}

					$arr_total = $this->db->GetList("SELECT * FROM `@#_member_flow` WHERE 1");
					$total_ye = 0;
					foreach ($arr_total as $key => $val) {
						$total_ye += $val['flow'];
					}
					$arr_ye['flow'] = $total_ye;
				}
			}else if($type == "glhcx_submit"){
				$arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
				if(!empty($arr[wxopenid1])){
					$brr = $this->db->GetList("SELECT `uid`,`mobile`,`username` FROM `@#_member` WHERE `wxopenid1` = '$arr[wxopenid1]'");
				}
				if(!empty($arr['wxopenid2'])){
					$crr = $this->db->GetList("SELECT `uid`,`mobile`,`username` FROM `@#_member` WHERE `wxopenid2` = '$arr[wxopenid2]'");
				}
			}
		}
		
		include $this->tpl(ROUTE_M,'test.user_details');
	}

	public function test_x(){
		$tm = time()-172800;
		$data = $this->db->GetList("select m.code,m.shopname,m.username,m.gonumber,m.status,m.redbag_status,m.shopid,m.huode,m.id,m.shopqishu,k.q_end_time from `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE n.auto_user=0 and m.huode !='0' and m.time>'$tm' limit 1000");
		// foreach ($data as $key => $val) {
		// 	$sort[] = $val['q_end_time'];
		// }
		// array_multisort($sort,SORT_DESC,$data);
		var_dump($data);
	}

	public function auto(){
		include $this->tpl(ROUTE_M,'test.auto');
	}

	public function lottery_details(){
		$time = $this->segment(4);
		if($time){
			$data = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `current_count`='$time' order by `create_time` desc");
		}else{
			$data = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE 1 order by `created_at` desc");
			foreach ($data as $key => $val) {
				$arr[] = $val['current_count'];
			}
			$arr = array_values(array_unique($arr));
			foreach ($arr as $k => $v) {
				$crr[$k]['time2'] = $v;
				$crr[$k]['time'] = substr($v, 0, 4)."-".substr($v, 4, 2)."-".substr($v, 6);
				$xx = $this->db->GetList("SELECT distinct `user_id` FROM `@#_activity_lottery` WHERE `current_count` = '$v'");
				$crr[$k]['count2'] = count($xx);
				$crr[$k]['count'] = $this->db->GetCount("SELECT COUNT(uid) FROM `@#_activity_lottery` WHERE `current_count`='$v'");
				//$brr = $this->db->GetList("SELECT m.* FROM `@#_activity_lottery` AS m LEFT JOIN `@#_zhuanpan_shop` AS  WHERE `current_count`='$v'");
				$brr = $this->db->GetList("SELECT `amount`,sum(amount) as m FROM `@#_activity_lottery` WHERE `current_count` = '$v' group by `amount`");
				
				// foreach ($brr as $key => $val) {
				// 	$crr[$k]['money_yes'] += $val['amount'];
				// }

				// $brr2 = $this->db->GetList("SELECT * FROM `@#_red_pachet` WHERE `current_count`='$v' AND `state`='0'");
				// $crr[$k]['money_no'] = 0;
				// foreach ($brr2 as $key => $val) {
				// 	$drr = explode(',', $val['amount']);
				// 	$money_n = 0;
				// 	foreach ($drr as $k2 => $v2) {
				// 		$money_n += $v2; 
				// 	}
				// 	$crr[$k]['money_no'] += $money_n;
				// }
			}
		}
		include $this->tpl(ROUTE_M,'test.lottery_details');
	}

	public function sure_money(){
		$current_count = $this->segment(4);
		$user_id = $this->segment(5);
		$money = $this->segment(6);
		$time = time();
		$this->db->Autocommit_start();
		$t = $this->db->Query("UPDATE `@#_activity_lottery` SET `state` = '1' WHERE `user_id` = '$user_id' AND `current_count`='$current_count' AND `state`='0'");
		$flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$user_id','1','账户','元宵红包充值','$money','$time')");
		$f = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$user_id')");
		if($t && $flag && $f){
			$this->db->Autocommit_commit();
			_message("充值成功!");  //充值成功
		}else{
			$this->db->Autocommit_rollback();
			_message("充值失败!");  //充值失败
		}
		
	}

	public function sck_details(){
		$time = $this->segment(4);
		if($time){
			$time1 = strtotime($time);
			$time2 = $time1 + 86400;
			$arr = $this->db->GetList("SELECT m.* FROM `@#_member_account` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE `content` = '商城卡充值' AND n.auto_user='0' AND m.time>'$time1' AND m.time<'$time2' order by m.time desc");
		}else{
			$arr = $this->db->GetList("SELECT m.* FROM `@#_member_account` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE `content` = '商城卡充值' AND n.auto_user='0' order by m.time desc");
			foreach ($arr as $key => $val) {
				$brr[] = date("Y-m-d",$val['time']);
			}
			$brr = array_values(array_unique($brr));
		}
		include $this->tpl(ROUTE_M,'test.sck_details');
	}

	public function zhuan_details(){
		$time = $this->segment(5);
		$uid = $this->segment(4);
		if($time){
			$time1 = strtotime($time);
			$time2 = $time1 + 86400;
			$arr = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `time`>'$time1' AND `time`<'$time2' AND `type`='-1' AND `content` LIKE '%给用户%' order by `time` desc");
		}else{
			$arr = $this->db->GetList("SELECT * FROM `@#_member` WHERE `zhuan_status` = 1");
			foreach ($arr as $key => $val) {
				$drr[] = $val['uid'];
			}
			$crr = implode(',', $drr);
			$brr = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` in ($crr) AND `time`>'1522512000' AND `type`='-1' AND `content` LIKE '%给用户%' order by `time` desc");
			foreach ($brr as $key => $val) {
				$str2[] = date("Y-m-d",$val['time']);
				if($str_s[date("Y-m-d",$val['time'])][$val['uid']]){
					$str_s[date("Y-m-d",$val['time'])][$val['uid']] += $val['money'];
				}else{
					$str_s[date("Y-m-d",$val['time'])][$val['uid']] = $val['money'];
				}
				
			}
			$str = array_values(array_unique($str2));
		}
		include $this->tpl(ROUTE_M,'test.zhuan_details');
	}

	public function liuliang_details(){

		if($this->segment(4) == ''){
			$time1 = strtotime(date('Y-m-d',time()-86400));
			$time2 = strtotime(date('Y-m-d',time()));
		}else if($this->segment(4) != '' && $this->segment(5) != ''){
			$time1 = strtotime($this->segment(4));
			$time2 = strtotime($this->segment(5));
		}
		$arr_cz = $this->db->GetList("SELECT * FROM `@#_member_flow_history` WHERE `create_time` > '$time1' and `create_time` < '$time2' order by `create_time` desc");
		$total_cz = 0;
		foreach ($arr_cz as $key => $val) {
			if($val['status'] == 1){
				$total_cz += $val['flow_num'];
			}
		}

		$arr_dh = $this->db->GetList("SELECT * FROM `@#_member_flow_recharge` WHERE `create_time` > '$time1' and `create_time` < '$time2' order by `create_time` desc");
		$total_dh = 0;
		foreach ($arr_dh as $key => $val) {
			$total_dh += $val['flow'];
		}

		$arr_total = $this->db->GetList("SELECT * FROM `@#_member_flow` WHERE 1");
		$total_ye = 0;
		foreach ($arr_total as $key => $val) {
			$total_ye += $val['flow'];
		}
		$arr_ye['flow'] = $total_ye;

		include $this->tpl(ROUTE_M,'test.liuliang_details');
	}

	public function shop_play_cord(){
		$data = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1'");
		$cateid = $this->segment(4);
		if(empty($cateid)){
			$str = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `q_uid` is null");
			foreach ($str as $key => $val) {
				$sid = $val['sid'];
				$arr = $this->db->GetOne("SELECT * FROM `@#_shop_cord` WHERE `shop_sid` = '$sid'");
				$str[$key]['cord'] = $arr['cord'];
			}
		}else{
			$str = $this->db->GetList("SELECT `title`,`sid`,`cateid` FROM `@#_shoplist` WHERE `q_uid` is null AND `cateid` = '$cateid'");
			foreach ($str as $key => $val) {
				$sid = $val['sid'];
				$arr = $this->db->GetOne("SELECT * FROM `@#_shop_cord` WHERE `shop_sid` = '$sid'");
				$str[$key]['cord'] = $arr['cord'];
			}
		}
		include $this->tpl(ROUTE_M,'test.shop_play_cord');
	} 

	public function do_shop_play_cord(){
		$sid = $this->segment(4);
		$cateid = $this->segment(5);
		$data = $this->db->GetOne("SELECT * FROM `@#_shop_cord` WHERE `shop_sid` = '$sid'");
		$time = time();
		if($data){
			if($data['cord'] == 0){
				$cord = 1;
			}else{
				$cord = 0;
			}
			$flag = $this->db->Query("UPDATE `@#_shop_cord` set `cord` = '$cord',`createtime` = '$time' WHERE `shop_sid` = '$sid'");
		}else{
			$flag = $this->db->Query("INSERT INTO `@#_shop_cord` (shop_sid,cord,createtime) VALUES ('$sid','1','$time')");
		}
		if($flag){
			header("Location:/admin/test/shop_play_cord/".$cateid);
		}
	} 

	public function dadan(){
		$num=20;
		$list = $this->db->GetList("SELECT m.* FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id and m.company != '惠享购配送' LEFT JOIN `@#_shop_cord` AS n ON k.sid=n.shop_sid where m.dizhi_id != 0 and n.cord = 1 and m.status LIKE '%未发货%'");

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("SELECT m.*,k.title2 FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id and m.company != '惠享购配送' LEFT JOIN `@#_shop_cord` AS n ON k.sid=n.shop_sid where m.dizhi_id != 0 and n.cord = 1 and m.status LIKE '%未发货%'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	

		foreach ($list as $key => $val) {
			$str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$val[dizhi_id]' limit 1");
			$addr[$key]['name'] = $str['shouhuoren'];
			$addr[$key]['gh'] = '';
			$addr[$key]['mobile'] = $str['mobile'];
			$addr[$key]['dizhi'] = $str['sheng'].$str['shi'].$str['xian'].$str['jiedao'];
			$addr[$key]['bz'] = '';
			$addr[$key]['title'] = "(第".$val['shopqishu']."期) ".$val['shopname'];
			$addr[$key]['num'] = '1';
		}
		$time = date("Y-m-d",time())."中奖";
		_setcookie('bt',$time,60*60);
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($addr));
		include $this->tpl(ROUTE_M,'test.dadan');	
	}
	//活动打单
	public function hd_dadan(){
		$num=20;
        
		$list = $this->db->GetList("SELECT m.*,n.shopname,k.uid FROM `@#_activity_yyy` AS m LEFT JOIN `@#_yyy_shop` AS n ON m.shiwu_id = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE m.shiwu_id > '6' AND m.dizhi_id != '0' AND m.company_code = ''");
		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("SELECT m.*,n.shopname,k.uid FROM `@#_activity_yyy` AS m LEFT JOIN `@#_yyy_shop` AS n ON m.shiwu_id = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE m.shiwu_id > '6' AND m.dizhi_id != '0' AND m.company_code = ''",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
		foreach ($list as $key => $val) {
			$str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$val[dizhi_id]' limit 1");
			$addr[$key]['name'] = $str['shouhuoren'];
			$addr[$key]['gh'] = '';
			$addr[$key]['mobile'] = $str['mobile'];
			$addr[$key]['dizhi'] = $str['sheng'].$str['shi'].$str['xian'].$str['jiedao'];
			$addr[$key]['bz'] = '';
			$addr[$key]['title'] = $val['shopname'];
			$addr[$key]['num'] = '1';
		}
		$time = date("Y-m-d",time())."活动";
		_setcookie('bt',$time,60*60);
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($addr));
		include $this->tpl(ROUTE_M,'test.hd_dadan');	
	}
	//兑换打单
	public function dh_dadan(){
		$num=20;
        $data = $this->db->GetOne("SELECT `bind_phone` FROM `@#_member` WHERE `uid` = ".MK_UID);
      	$data2 = explode('，',$data['bind_phone']);
        $number = implode(',',$data2);
        $str = $this->db->GetList("SELECT `id` FROM `@#_member_dizhi` WHERE `mobile` in ($number)");
        $str = array_column($str,'id');
        $id2 = implode(',',$str);
      
		$list = $this->db->GetList("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE k.auto_user = '0' AND m.addr_id != '0' AND m.company_code = '' AND m.addr_id not in($id2) order by m.create_time desc");
		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$recordlist=$this->db->GetPage("SELECT m.*,n.title FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id LEFT JOIN `@#_member` AS k ON m.uid = k.uid WHERE k.auto_user = '0' AND m.addr_id != '0' AND m.company_code = '' AND m.addr_id not in($id2) order by m.create_time desc",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	
		foreach ($list as $key => $val) {
			$str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$val[addr_id]' limit 1");
			$addr[$key]['name'] = $str['shouhuoren'];
			$addr[$key]['gh'] = '';
			$addr[$key]['mobile'] = $str['mobile'];
			$addr[$key]['dizhi'] = $str['sheng'].$str['shi'].$str['xian'].$str['jiedao'];
			$addr[$key]['bz'] = '';
			$addr[$key]['title'] = $val['title'];
			$addr[$key]['num'] = $val['amount'];
		}
		$time = date("Y-m-d",time())."兑换";
		_setcookie('bt',$time,60*60);
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($addr));
      
		include $this->tpl(ROUTE_M,'test.dh_dadan');	
	}

	public function not_dadan(){
		$num=20;
		$data = $this->db->GetList("SELECT m.* FROM `@#_member_go_record` AS m LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id and m.company != '惠享购配送' LEFT JOIN `@#_shop_cord` AS n ON k.sid=n.shop_sid where m.dizhi_id != 0 and n.cord = 1 and m.status LIKE '%未发货%'");
		
		foreach ($data as $key => $val) {
			$arr[] = $val['id'];
		}
		$brr = implode(',', $arr);
		if(!empty($brr)){
			$list = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `dizhi_id` != 0 and company != '惠享购配送' and `id` not in($brr) and `status` LIKE '%未发货%'");
		}else{
			$list = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `dizhi_id` != 0 and company != '惠享购配送' and `status` LIKE '%未发货%'");
		}
		

		$total=count($list);

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");
		if(!empty($brr)){
			$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` where `dizhi_id` != 0 and company != '惠享购配送' and `id` not in($brr) and `status` LIKE '%未发货%'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
		}else{
			$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` where `dizhi_id` != 0 and company != '惠享购配送' and `status` LIKE '%未发货%'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
		}
		
		foreach ($list as $key => $val) {
			$str = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$val[dizhi_id]' limit 1");
			$addr[$key]['name'] = $str['shouhuoren'];
			$addr[$key]['gh'] = '';
			$addr[$key]['mobile'] = $str['mobile'];
			$addr[$key]['dizhi'] = $str['sheng'].$str['shi'].$str['xian'].$str['jiedao'];
			$addr[$key]['bz'] = '';
			$addr[$key]['title'] = $val['shopname'];
			$addr[$key]['num'] = '1';
		}
		$time = date("Y-m-d",time())."不打印订单";
		_setcookie('bt',$time,60*60);
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($addr));

		include $this->tpl(ROUTE_M,'test.not_dadan');	
	}

	public function wxpay_details(){
		if(isset($_POST['sososubmit'])){

			$posttime1=isset($_POST['posttime1'])?$_POST['posttime1']:'';

			$posttime2=isset($_POST['posttime2'])?$_POST['posttime2']:'';

			$cate = $_POST['cate'];

			$sel = $_POST['sel'];

			if($cate == 'username'){
				$member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `username` = '$sel'");
				$uid = $member['uid'];
			}else if($cate == 'mobile'){
				$member = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$sel' ");
				$uid = $member['uid'];
			}else{
				$uid = $sel;
			}
		

			$times = '`content` = "通过微信在线充值" AND `type` = 1';
			//if(empty($posttime1) || empty($posttime2)) _message("2个时间都不为能空！");

			if(!empty($posttime1) && !empty($posttime2)){ //如果2个时间都不为空

				$posttime1=strtotime($posttime1);

				$posttime2=strtotime($posttime2);

				if($posttime1 > $posttime2){

					_message("前一个时间不能大于后一个时间");

				}

				$times.= " AND `time`>='$posttime1' AND `time`<='$posttime2'";

			}

			if(!empty($sel)){
				$times = $times." AND `uid`='$uid'";
			}

			$wheres=$times;
			$recordlist = $this->db->GetList("SELECT * FROM `@#_member_account` AS m LEFT JOIN `@#_member` AS n ON m.uid = n.uid WHERE ".$wheres." order by `time` DESC");
		}

		$total = count($recordlist);
		include $this->tpl(ROUTE_M,'test.wxpay_details');
	}

	 //导出数据库商品
    public function shop_out(){
        $str = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `brandid` IN (65,94) AND `q_uid` IS NULL order by `money` desc limit 38,20");

        foreach ($str as $key => $val) {
        	$content = $val['content'];
        	$content = str_replace('http://m.csthsc.com', 'http://m.weimicm.com', $content);
        	$content = str_replace('http://m.cx065i.cn', 'http://m.weimicm.com', $content);
        	$content = str_replace('http://m.csthsc.cn', 'http://m.weimicm.com', $content);
        	$q_end_time = null;
        	$arr = $this->db->Query("UPDATE `@#_shoplist` SET `content` = '$content',`q_end_time`=null,`q_uid`=null,`codes_table`='shopcodes_1' WHERE `id` = '$val[id]'");
        	//var_dump($arr);
        }
        
        foreach ($str as $key => $val) {
            $data[$key]['cateid'] = $val['cateid'];
            $data[$key]['brandid'] = $val['brandid'];
            $data[$key]['title'] = $val['title'];
            $data[$key]['title_style'] = $val['title_style'];
            $data[$key]['title2'] = $val['title2'];
            $data[$key]['keywords'] = $val['keywords'];
            $data[$key]['description'] = $val['description'];
            $data[$key]['money'] = $val['money'];
            $data[$key]['yunjiage'] = $val['yunjiage'];
            $data[$key]['zongrenshu'] = $val['zongrenshu'];
            $data[$key]['canyurenshu'] = $val['canyurenshu'];
            $data[$key]['shenyurenshu'] = $val['shenyurenshu'];
            $data[$key]['qishu'] = $val['qishu'];
            $data[$key]['maxqishu'] = $val['maxqishu'];
            $data[$key]['thumb'] = $val['thumb'];
            $data[$key]['picarr'] = $val['picarr'];
            $data[$key]['content'] = $val['content'];
            $data[$key]['xsjx_time'] = $val['xsjx_time'];
            $data[$key]['renqi'] = $val['renqi'];
            $data[$key]['pos'] = $val['pos'];
            $data[$key]['buynum'] = $val['buynum'];
            $data[$key]['buynum2'] = $val['buynum2'];
            $data[$key]['str1'] = $val['str1'];
        }
        $sid = json_encode($data);
        var_dump($data);
        var_dump(file_put_contents('/www/wwwroot/csthsc/system/caches/shop_out/shop_9.txt', $sid));
    }

	public function shop_xxx(){
        //$file_path = "/www/wwwroot/csthsc/system/caches/shop_out/shop_9.txt";
        if(file_exists($file_path)){
             $str = file_get_contents($file_path);
             $data = json_decode($str,true);
             var_dump(count($data));
        }  
        $i = 0;
        foreach ($data as $key => $val) {
            $buynum2 = $val['buynum2'];   

            $buynum = $val['buynum']; 

            $cateid = $val['cateid'];

            $brandid = $val['brandid'];

            $title = $val['title'];

            $title2 = $val['title2'];

            $keywords = $val['keywords'];

            $description = $val['description'];

            $content = $val['content'];

            $money = $val['money'];

            $yunjiage = $val['yunjiage'];

            $thumb = $val['thumb'];    

            $maxqishu = $val['maxqishu'];         

            $canyurenshu = 0;       

            $goods_key_pos = $val['pos'];

            $goods_key_renqi = $val['renqi'];

            $shop_money = $val['str1'];
            

            $title_style= $val['title_style'];

            $picarr = $val['picarr'];
 

            $xsjx_time = $val['xsjx_time'];                  

            $zongrenshu = ceil($money/$yunjiage);

            $codes_len = ceil($zongrenshu/3000);

            $shenyurenshu = $zongrenshu-$canyurenshu;
                    

            $time=time();   //商品添加时间        

            $this->db->Autocommit_start();
     

            $query_1 = $this->db->Query("INSERT INTO `@#_shoplist` (`cateid`, `brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`,`shenyurenshu`, `qishu`,`maxqishu`,`thumb`, `picarr`, `content`,`xsjx_time`,`renqi`,`pos`, `time`,`buynum`,`buynum2`,`str1`) VALUES ('$cateid', '$brandid', '$title', '$title_style', '$title2', '$keywords', '$description', '$money', '$yunjiage', '$zongrenshu', '$canyurenshu','$shenyurenshu', '1','$maxqishu', '$thumb', '$picarr', '$content','$xsjx_time','$goods_key_renqi', '$goods_key_pos','$time','$buynum','$buynum2','$shop_money')");            

            $shopid = $this->db->insert_id();

            System::load_app_fun("content");        

            $query_table = content_get_codes_table();

            if(!$query_table){

                $this->db->Autocommit_rollback();

                _message("参与码仓库不正确!");

            }

            $query_2 = content_get_go_codes($zongrenshu,3000,$shopid);

            $query_3 = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");

                    

            if($query_1 && $query_2 && $query_3){

                $this->db->Autocommit_commit();  
                $i++;           
                var_dump($i);
               

            }else{      

          
                $this->db->Autocommit_rollback();
               

            }  
        }  
    }

    public function smsm(){
    	$data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '27935' and `money` != '0' and `type` = '1' and `content` not like '%不中全返%'");
    	$money1 = 0;
    	foreach ($data as $key => $val) {
    		$money1 += $val['money'];
    	}

    	$data2 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '27935' and `money` != '0' and `type` = '1' and `content` like '%不中全返%'");
    	$money2 = 0;
    	foreach ($data2 as $key => $val) {
    		$money2 += $val['money'];
    	}

    	$data3 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '27935' and `money` != '0' and `type` = '-1' and `content` like '%不中全返%'");
    	$money3 = 0;
    	foreach ($data3 as $key => $val) {
    		$money3 += $val['money'];
    	}
    	var_dump("充值:".$money1);
    	var_dump("不中全返修改前：".$money2);
    	var_dump("不中全返修改后:".-$money3);
    	var_dump($money1+$money2-$money3);

    	$data4 = $this->db->GetList("SELECT * FROM `@#_member_go_record` WHERE `uid` = '27935' and `shopid` in (SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '170')");
    	$money4 = 0;
    	foreach ($data4 as $key => $val) {
    		$money4 += $val['moneycount'];
    	}
    	var_dump($money4);
    }

    public function yyyy(){
    	$uid = '27590';
    	$data = $this->db->GetList("SELECT * FROM `@#_member` WHERE `yaoqing` = '$uid'");
    	$total_money = 0;
    	foreach ($data as $key => $val) {
    		$id = $val['uid'];
    		$str = $this->db->GetList("SELECT `money` FROM `@#_member_recodes` WHERE `uid` = '$id' AND `type` = '1' AND `time` < '1528809420'");
    		$money = 0;
    		foreach ($str as $k => $v) {
    			$money += $v['money'];
    		}
    		$total_money += $money;
    	}
    	var_dump($total_money);
    }

    public function wcwc(){
    	$time = time() - 15*86400;
    	$data = $this->db->GetList("SELECT `sid` FROM `@#_shoplist` WHERE `time` > '$time'");
    	foreach ($data as $key => $val) {
    		$arr[] = $val['sid'];
    	}
    	$str = array_unique($arr);
    	

    	$data2 = $this->db->GetList("SELECT `sid` FROM `@#_shoplist` WHERE `q_end_time` is null");
    	foreach ($data2 as $key => $val) {
    		$arr2[] = $val['sid'];
    	}
    	$str2 = array_unique($arr2);
    	var_dump(count($str));
    	var_dump(count($str2));
    }

    //每天收入支出
    public function in_out(){
    	$CheckId = _encrypt(_getcookie("AID"), 'DECODE');
    	$time1 = strtotime($_POST['time1']);
    	$time2 = strtotime($_POST['time2']);
   
    	if(empty($time1) || empty($time2) || $CheckId == 6){
    		$time_1 = date("Ymd",time());
    		$time_2 = date("Ymd",strtotime("+1 days"));
    	}else{
    		$time_1 = date("Ymd",$time1);
    		$time_2 = date("Ymd",$time2);
    	}

    	if($time_1 >= $time_2){
    		_message("前一个时间不能大于等于后一个时间(以天计)");
    	}

    	$tt = (strtotime($time_2)-strtotime($time_1))/86400;
    	for ($i=0; $i < $tt; $i++) { 
    		$arr[] = date("Ymd",strtotime($time_2)-($i+1)*86400);
    	}
  
    	// $arr = array();
    	// for($i = $time_2 - 1; $i >= $time_1; $i--){
    	// 	$arr[] = intval($i);
    	// }
    	$tt = array();
    	$total_yjtx = 0;
    	foreach ($arr as $key => $val) {
    	
    		$time_start = strtotime($val);
    		$time_end = $time_start+86400;
    		$str = $this->db->GetOne("SELECT sum(`money`) sum_money $money FROM `@#_wxpay_locat` WHERE `create_time` >= '$time_start' AND `create_time` < '$time_end' AND `status` = '1'");
    		$str2 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '微信公众号' AND `status` = '已付款'");
    		$str_zfb = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_alipay_locat` WHERE `create_time` >= '$time_start' AND `create_time` < '$time_end' AND `status` = '1'");
    		$str3 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '使用佣金充值到参与账户' AND `time` >= '$time_start' AND `time` < '$time_end'");
    		$str4 = $this->db->GetList("SELECT `amount` FROM `@#_red_pachet` WHERE `current_count` = '$val'");
    		$str5 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.q_uid != '74447'");
    		$str5_2 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.cateid = '177' AND m.q_uid != '74447'");

    		$crr = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `zhuan_status` = '1' AND `uid` != ".MK_UID);
			foreach ($crr as $k => $v) {
				$drr[] = $v['uid'];
			}
			$krr = implode(',', $drr);
			$str6 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` in ($krr) AND `time` > '$time_start' AND `time` < '$time_end' AND `type`='-1' AND `content` LIKE '%给用户%' order by `time` desc");

			$str7 = $this->db->GetOne("SELECT sum(m.moneycount) sum_money FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end' AND m.uid != '74447'");

			$str7_2 = $this->db->GetOne("SELECT sum(m.moneycount) sum_money FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid LEFT JOIN `@#_shoplist` AS k ON k.id = m.shopid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end' AND k.cateid = '177' AND m.uid != '74447'");

			$str8 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '通过网络充值' AND `status` = '已付款'");
            
            $str9 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '新注册用户首次充值送8元' AND `time` >= '$time_start' AND `time` < '$time_end'");

            $str10 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `content` = '商城卡充值' AND `time` >= '$time_start' AND `time` < '$time_end'");

            $str11 = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time_start' AND m.q_end_time < '$time_end' AND m.q_uid is not null AND n.auto_user = '0' AND m.str1 != '0' AND m.q_uid != '74447'");

            $str12 = $this->db->GetOne("SELECT sum(n.score) sum_money FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.create_time > '$time_start' AND m.create_time <= '$time_end'");
		
    		$money4 = 0;
    		$total = array();
    		
    		foreach ($str4 as $k => $v) {
    			$drr = explode(',', $v['amount']);
    			$m = 0;
    			foreach ($drr as $ke => $va) {
    				$m += $va;
    			}
    			$money4 += $m;
    		}
    		$login_num = $this->db->GetCount("SELECT COUNT(*) FROM `@#_member_login` WHERE `day` = '$val'");
    		$pay_num = $this->db->GetList("SELECT distinct m.uid FROM `@#_member_go_record` AS m LEFT JOIN `@#_member` AS n ON m.uid=n.uid WHERE n.auto_user='0' AND m.time >= '$time_start' AND m.time < '$time_end'");

    		$csg = $this->db->GetOne("SELECT sum(n.money) sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.shop_id != 4 AND m.shop_id != 5 AND m.free = 0 AND m.update_time >= '$time_start' AND m.update_time < '$time_end'");
    		$csg_zj = $this->db->GetOne("SELECT sum(n.price) sum_money FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.status = 1 AND m.update_time >= '$time_start' AND m.update_time < '$time_end'");
    		$yjtx = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_cashout` WHERE `time` >= '$time_start' AND `time` < '$time_end'");
    		$total_yjtx += $yjtx['sum_money'];
  
    		$total['sm'] = intval($str['sum_money']);
    		$total['gzh'] = intval($str2['sum_money']);
    		$total['wl'] = intval($str_zfb['sum_money']);
    		$total['xx'] = intval($str6['sum_money']);
    		$total['yj'] = intval($str3['sum_money']);
    		$total['hd'] = $money4;
    		$total['zj'] = intval($str5['sum_money']) + intval($str5_2['sum_money']);
    		$total['xf'] = intval($str7['sum_money']);
    		$total['zfb'] = intval($str8['sum_money']);
    		$total['sj'] = $val;
            $total['xyh'] = intval($str9['sum_money']);
            $total['bj'] = intval($str5_2['sum_money']*2);
            $total['bj_gm'] = intval($str7_2['sum_money']);
            $total['login_num'] = intval($login_num);
            $total['pay_num'] = count($pay_num);
            $total['sck_cz'] = intval($str10['sum_money']);
            $total['sck_zj'] = intval($str11['sum_money']);
            $total['csg'] = intval($csg['sum_money']);
            $total['csg_zj'] = intval($csg_zj['sum_money']);
            $total['dh'] = intval($str12['sum_money']/100);
    		$tt[] = $total;
    	}
    	include $this->tpl(ROUTE_M,'test.in_out');
    }

    //中奖金额大于充值金额2000
    public function tfzx(){
    	$time1 = strtotime($_POST['time1']);
    	$time2 = strtotime($_POST['time2']);
   
    	if(empty($time1) || empty($time2)){
    		$time_1 = date("Ymd",time());
    		$time_2 = date("Ymd",strtotime("+1 days"));
    	}else{
    		$time_1 = date("Ymd",$time1);
    		$time_2 = date("Ymd",$time2);
    	}
    	$time_start = strtotime($time_1);//"1532361600";
    	$time_end = strtotime($time_2);//"1532497167";
    	if($time_1 >= $time_2){
    		_message("前一个时间不能大于等于后一个时间(以天计)");
    	} 
    	$data = $this->db->GetList("SELECT m.uid FROM `@#_member` AS m LEFT JOIN `@#_shoplist` AS n ON m.uid = n.q_uid WHERE m.auto_user = '0' AND n.str1 = '0' AND n.q_end_time >= '$time_start' AND n.q_end_time < '$time_end'");
    	foreach ($data as $key => $val) {
    		$arr[] = $val['uid'];
    	}
    	$brr = array_unique($arr);

    	foreach ($brr as $key => $val) {
    		$str = $this->db->GetOne("SELECT sum(`money`) sum_money $money FROM `@#_wxpay_locat` WHERE `uid` = '$val' AND `create_time` >= '$time_start' AND `create_time` < '$time_end' AND `status` = '1'");
    		$str2 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `uid` = '$val' AND `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '微信公众号' AND `status` = '已付款'");
			$str3 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$val' AND `time` > '$time_start' AND `time` < '$time_end' AND `type`='1' AND `content` LIKE '%获得用户%' order by `time` desc");
			$str4 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_shoplist` WHERE `q_uid` = '$val' AND `str1` = '0' AND `q_end_time` >= '$time_start' AND `q_end_time` < '$time_end'");
			$str5 = $this->db->GetOne("SELECT sum(`moneycount`) sum_money FROM `@#_member_go_record` WHERE `uid` = '$val' AND `time` >= '$time_start' AND `time` < '$time_end'");
			$str6 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_addmoney_record` WHERE `uid` = '$val' AND `time` >= '$time_start' AND `time` < '$time_end' AND `pay_type` = '通过网络充值' AND `status` = '已付款'");
          	$str7 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$val' AND `time` > '$time_start' AND `time` < '$time_end' AND `type`='1' AND `content` = '商城卡充值' order by `time` desc");
          	$str8 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$val' AND `time` > '$time_start' AND `time` < '$time_end' AND `type`='1' AND `content` = '使用佣金充值到参与账户' order by `time` desc");
			$str_t = intval($str4['sum_money']) - (intval($str['sum_money']) + intval($str2['sum_money']) + intval($str3['sum_money']) + intval($str6['sum_money']));
			$str_t2 = intval($str5['sum_money']) - (intval($str['sum_money']) + intval($str2['sum_money']) + intval($str3['sum_money']) + intval($str6['sum_money']) + intval($str7['sum_money']) + intval($str8['sum_money']));
			if($str_t > 1000){
				$je['uid'] = $val;
				$je['money'] = $str_t;
				$je_t[] = $je;
			}
			if($str_t2 > 100){
				$xf['uid'] = $val;
				$xf['money'] = $str_t2;
				$xf_t[] = $xf;
			}
			if($str_t < -1000){
				$sd['uid'] = $val;
				$sd['money'] = -$str_t;
				$sd_t[] = $sd;
			}
    	}
    	include $this->tpl(ROUTE_M,'test.tfzx');
    }

    public function jfxq(){
    	$data = $this->db->GetList("SELECT `uid`,`time` FROM `@#_member` WHERE `auto_user` = '0'");
    	$time1 = strtotime(date("Ymd",time()));
    	$time2 = strtotime(date("Ymd",time())+1);

    	foreach ($data as $key => $val) {
    		$str = $this->db->GetOne("SELECT `sum` FROM `@#_qiandao` WHERE `uid` = '$val[uid]'");
    		
    		if($str['sum'] > "40"){
    			$aa['uid'] = $val['uid'];
    			$aa['total'] = $str['sum']*100;
    			$brr[] = $aa;
    		}
			
       	}

       	$data2 = $this->db->GetList("SELECT m.uid FROM `@#_member` AS m LEFT JOIN `@#_member_account` AS n ON m.uid = n.uid WHERE m.auto_user = '0' AND n.type = '-1' AND n.pay = '积分' AND n.content = '购买了商品' AND n.time >= '$time1' AND n.time < '$time2'");
       	foreach ($data2 as $key => $val) {
       		$crr[] = $val['uid'];
       	}
       	$drr = array_unique($crr);
       	foreach ($drr as $key => $val) {
       		$str2 = $this->db->GetOne("SELECT sum(`money`) sum_money FROM `@#_member_account` WHERE `uid` = '$val' AND `type` = '-1' AND `pay` = '积分' AND `content` = '购买了商品' AND `time` >= '$time1' AND `time` < '$time2'");
			if($str2['sum_money'] > "500"){
				$bb['uid'] = $val;
	    		$bb['gm'] = $str2['sum_money'];
    			$ccc[] = $bb;
	    	}
       	}
       	foreach ($data as $key => $val) {
       		$time_1 = (strtotime(date("Y-m-d",time())) -strtotime(date("Y-m-d",$val['time'])))/86400;

       		$times = intval($time_1) + 1;
      
       		$hhh = $this->db->GetOne("SELECT `sum` FROM `@#_qiandao` WHERE `uid` = '$val[uid]'");

       		if($hhh['sum'] > $times){
       			$hh['uid'] = $val['uid'];
       			$hh['num'] = $hhh['sum'];
       			$hh['time'] = date("Y-m-d H:i:s",$val['time']);
       			$hh['ts'] = $times;
       			$cs[] = $hh;
       		}
       	}
       	include $this->tpl(ROUTE_M,'test.jfxq');
    }

    /* 
	*处理Excel导出 
	*@param $datas array 设置表格数据 
	*@param $titlename string 设置head 
	*@param $title string 设置表头 
	*/ 
	public function excelData($datas,$titlename,$title,$filename){ 
	    $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>"; 
	    $str .="<table border=1><head>".$titlename."</head>"; 
	    $str .= $title; 
	    foreach ($datas  as $key=> $rt ) 
	    { 
	        $str .= "<tr>"; 
	        foreach ( $rt as $k => $v ) 
	        { 
	            $str .= "<td>{$v}</td>"; 
	        } 
	        $str .= "</tr>\n"; 
	    } 
	    $str .= "</table></body></html>"; 
	    header( "Content-Type: application/vnd.ms-excel; name='excel'" ); 
	    header( "Content-type: application/octet-stream" ); 
	    header( "Content-Disposition: attachment; filename=".$filename ); 
	    header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
	    header( "Pragma: no-cache" ); 
	    header( "Expires: 0" ); 
	    exit( $str ); 
	} 

	public function set_exel(){
		$path = G_CACHES .'set_exel'.'/exel.php';
		$str = file_get_contents($path);      //todo:导出数据（自行设置）
		$dataResult = json_decode($str,true);
		$money = 0;
		foreach ($dataResult as $key => $val) {
			$money += $val['second_money'];
		}
		
		$headTitle = ""; 
		$title = $_COOKIE['bt']; 
		$headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
		$titlename = "<tr> 
               <th style='width:350px;' >收件人</th> 
               <th style='width:100px;' >固话</th> 
               <th style='width:100px;'>手机</th> 
               <th style='width:270px;'>地址</th> 
               <th style='width:120px;'>买家备注</th> 
               <th style='width:170px;'>商品标题</th> 
               <th style='width:70px;'>商品数量</th> 
           </tr>"; 
           $filename = $title.".xls"; 

       $this->excelData($dataResult,$headtitle,$titlename,$filename); 
	}
    
    public function vip_song(){
        $str = $this->db->GetList("SELECT `uid`,`mobile`,`username` FROM `@#_member` WHERE `vip` = '1'");
        foreach($str as $key=>$val){
        	$str2 = $this->db->GetOne("SELECT `song_state` FROM `@#_vip_song` WHERE `uid` = '$val[uid]'");
            $str[$key]['state'] = $str2['song_state'];
        }
        $data = $this->db->GetList("SELECT m.*,n.username,n.mobile FROM `@#_vip_song` AS m LEFT JOIN `@#_member` AS n ON m.uid = n.uid WHERE 1");
		include $this->tpl(ROUTE_M,'test.vip_song');
	}
  
    public function close_vip_song(){
    	$uid = $this->segment(4);
        $this->db->Query("DELETE FROM `@#_vip_song` WHERE `uid` = '$uid'");
        header("Location:/admin/test/vip_song/");
    }
  	public function open_vip_song(){
    	$uid = $this->segment(4);
        $this->db->Query("INSERT INTO `@#_vip_song` (`uid`,`song_state`)VALUES('$uid','1')");
        header("Location:/admin/test/vip_song/");
    }
  
  	public function yyy_z(){
        $time = strtotime(date('Y-m-d',time()));
    	$data = $this->db->GetList("SELECT m.amount,n.score FROM `@#_exchange_record` AS m LEFT JOIN `@#_shoplist_exchange` AS n ON m.shopid = n.id WHERE m.create_time < $time");
      	$money = 0;
      	foreach($data as $key=>$val){
          
          	$m = $val['amount']*$val['score']/100;
        	$money += $m;
        }
      var_dump($money);
    }
  
  	public function cha(){
    	$uid = $this->segment(4);
      	$data = $this->db->GetList("SELECT `money` FROM `@#_member_account` WHERE `uid` = '$uid' AND `type` = '1' AND `pay` = '账户' AND `money` > 50 AND `content` LIKE '%通过线下扫码充值%'");
      	$str = '';
      	foreach($data as $key=>$val){
        	if($str == ''){
            	$str = $val['money'];
            }else{
            	$str .= ",".$val['money'];
            }
        }
      	$data2 = $this->db->GetList("SELECT `money`,`zhuijia` FROM `@#_wxpay_locat` WHERE `uid` = '$uid' AND `status` = '1' AND `money` > 50");
      	$str2 = '';
      	foreach($data2 as $key=>$val){
        	if($str2 == ''){
              	if($val['zhuijia'] == 1){
                	$str2 = $val['money'].",".$val['money'];
                }else{
                	$str2 = $val['money'];
                }
            }else{
              	if($val['zhuijia'] == 1){
                	$str2 .= ",".$val['money'].",".$val['money'];
                }else{
                	$str2 .= ",".$val['money'];
                }
            }
        }
      	var_dump($str);var_dump($str2);
    }

}