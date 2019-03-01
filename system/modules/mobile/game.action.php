<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
class game extends memberbase {
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");

		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        	echo " <h1>Sorry！请在微信浏览器访问</h1>";
          	exit();return false;
        }
	}

	public function csg(){
		$sid = $this->segment(4);
		$fz = $_GET['fz'];
		$uid = $this->userinfo['uid'];
		$flag = 0;
		if($sid && $uid){
			$fz_id = _encrypt($sid,"DECODE");
			$str = $this->db->GetOne("SELECT * FROM `@#_kh_fz` WHERE `id` = '$fz_id'");
			
			if($str && !(strpos($str['friends_union'], $uid) !== false) && $str['uid'] != $uid){
				if(empty($str['friends_union'])){
					$friends_union = $uid;
				}else{
					$friends_union = $str['friends_union'].",".$uid;
				}
				$brr = $this->db->Query("UPDATE `@#_kh_fz` SET `friends_union` = '$friends_union' WHERE `id` = '$fz_id'");
				if($brr){
					$flag = 1;
				}
			}
		}

		if($fz && $uid){
			$rid = _encrypt($fz,"DECODE");
			$str = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid'");

			if($str && !(strpos($str['friends_union'], $uid) !== false) && $str['uid'] != $uid){
				if(empty($str['friends_union'])){
					$friends_union = $uid;
				}else{
					$friends_union = $str['friends_union'].",".$uid;
				}
				$brr = $this->db->Query("UPDATE `@#_kh_record` SET `friends_union` = '$friends_union' WHERE `id` = '$rid'");
				if($brr){
					$flag = 1;
				}
			}
		}

		$data = $this->db->GetOne("SELECT `money` FROM `@#_member` WHERE `uid` = '$uid'");
		$money = $data['money'];
		include templates("mobile/game","csg");
	}

	public function play(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_GET['sid']);
		$rid = intval($_GET['rid']);
		$data = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$sid'");
		$str = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid'");
		if(!$uid){
			_messagemobile("您未登录","/index.php/mobile/user/login");
		}
		if(!$data || !$str){
			 _messagemobile("商品不存在！","/index.php/mobile/game/csg");
		}
		include templates("mobile/game","tiyan");
	}

	public function do_play(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_POST['mid']);
		$rid = intval($_POST['oid']);
		$str = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$sid'");
		$str2 = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid'");
		$str3 = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid' AND `update_time` = '0'");

		if(!$uid){
			$data['code'] = 2;  //未登录
			$data['btn'] = "<a href='/index.php/mobile/user/login' style='text-decoration: none;'><img src='/statics/templates/yungou/html/game/res/raw-assets/huafei/not_login_in.png'></a>";
			echo json_encode($data);
			exit;
		} 

		if(!$str || !$str2){
			$data['code'] = 0;  //未找到商品
			$data['msg'] = "<a href='/index.php/mobile/game/csg' style='text-decoration: none;'><img src='/statics/templates/yungou/html/game/res/raw-assets/huafei/chongxintiaozhan.ca6f1.png'></a>";
			echo json_encode($data);
			exit;
		}

		if(!$str3){
			$data['code'] = 3;  //游戏结束
			$data['msg'] = "<a href='/index.php/mobile/game/csg' style='text-decoration: none;'><img src='/statics/templates/yungou/html/game/res/raw-assets/huafei/game_over.png'></a>";
			echo json_encode($data);
			exit;
		}
		$data['flag'] = $str3['flag'];
		$data['code'] = 1;
		echo json_encode($data);
	}

	public function go_game(){
		$id = intval($_POST['id']);
		$str = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$id'");
		$member = $this->userinfo;
		$uid = $member['uid'];
		$money = $str['money'];
		$game_time = date("Ymd",time());
		$time = time();
		if(!$member){
			$data['code'] = 0;
			$data['msg'] = "您未登录,正在前往登录！";
			echo json_encode($data);
			exit;
		}
		if(!$str){
			$data['code'] = 3;
			$data['msg'] = "没有找到该商品！";
			echo json_encode($data);
			exit;
		}
		if($member['money'] < $str['money']){
			$data['code'] = 2;
			echo json_encode($data);
			exit;
		}
		if($str['free'] == 1){
			$record = $this->db->GetCount("SELECT COUNT(*) FROM `@#_kh_record` WHERE `uid` = '$uid' AND `shop_id` = '$id' AND `game_time` = '$game_time'");
			if($record >= 1){
				$data['code'] = 4;
				$data['msg'] = "今天免费次数已用完！";
				echo json_encode($data);
				exit;
			}else{
				$time_cz = strtotime(date("Y-m-d",time())) - 24*60*60*6;
				$cz = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = '$uid' AND `status` = '已付款' AND `time` > '$time_cz'");
				if(!$cz){
					$data['code'] = 6;
					$data['msg'] = "您7天内没有进行充值！";
					echo json_encode($data);
					exit;
				}
				$flag3 = $this->db->Query("INSERT INTO `@#_kh_record` (`shop_id`,`uid`,`status`,`create_time`,`game_time`)VALUES('$id','$uid','0','$time','$game_time')");
				$rid = $this->db->insert_id();
				if($flag3){
					$data['code'] = 1;
					$data['sid'] = $id;
					$data['rid'] = $rid;
					echo json_encode($data);
					exit;
				}else{
					$data['code'] = 5;
					$data['msg'] = "参与活动失败！";
					echo json_encode($data);
					exit;
				}
			}
		}else{
			$this->db->Autocommit_start();
			$flag = $this->db->Query("UPDATE `@#_member` SET `money` = `money` - '$money' WHERE `uid` = '$uid'");
			$flag2 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','-1','账户','参与闯三关活动','$money','$time')");
			$flag3 = $this->db->Query("INSERT INTO `@#_kh_record` (`shop_id`,`uid`,`status`,`create_time`,`game_time`)VALUES('$id','$uid','0','$time','$game_time')");
			$rid = $this->db->insert_id();
			if($flag && $flag2 && $flag3){
				$this->db->Autocommit_commit();
				$data['code'] = 1;
				$data['sid'] = $id;
				$data['rid'] = $rid;
				echo json_encode($data);
				exit;
			}else{
				$this->db->Autocommit_rollback();
				$data['code'] = 5;
				$data['msg'] = "参与活动失败！";
				echo json_encode($data);
				exit;
			}
		}
		
	}

	public function get_shop(){
		$uid = $this->userinfo['uid'];
		$page = intval($_POST['page']);
		$num = 20;
		$start = ($page - 1) * $num;

		$list = $this->db->GetList("SELECT * FROM `@#_kh_shop` WHERE `is_delete` = '0' order by `price` asc limit $start,$num");

		foreach ($list as $key => $val) {
			$id = $val['id'];
			$str = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `shop_id` = '$id' AND `uid` = '$uid' AND `update_time` = '0'");
			if($str){
				$list[$key]['cx'] = 1;
				$list[$key]['sid'] = $str['shop_id'];
				$list[$key]['rid'] = $str['id'];
			}else{
				$list[$key]['cx'] = 0;
			}
		}
		if($list){
			$data['status'] = 1;
			$data['lists'] = $list;
		}else{
			$data['status'] = 0;
		}
		echo json_encode($data);
	}

	public function game_fail(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_POST['mid']);
		$rid = intval($_POST['oid']);

		$str = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$sid'");
		$str2 = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid' AND `update_time` = '0'");

		if($str && $str2){
			$time = time();
			$str3 = $this->db->Query("UPDATE `@#_kh_record` SET `update_time` = '$time' WHERE `uid` = '$uid' AND `id` = '$rid' AND `update_time` = '0'");
		}
		$data['code'] = 1;
		echo json_encode($data);

	}

	public function game_success(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_POST['mid']);
		$rid = intval($_POST['oid']);

		$str = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$sid'");
		$str2 = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid'");

		if($str && $str2){
			$time = time();
			$str3 = $this->db->Query("UPDATE `@#_kh_record` SET `status` = '1',`update_time` = '$time',`wd` = '1' WHERE `uid` = '$uid' AND `id` = '$rid'");
			$data['code'] = 1;
			echo json_encode($data);
		}else{
			$data['code'] = 0;
			echo json_encode($data);
		}
	}

	public function init(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_GET['sid']);
		$rid = intval($_GET['rid']);
		$data = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$sid'");
		$str = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$rid' AND `uid` = '$uid'");
		if(!$uid){
			_messagemobile("您未登录","/index.php/mobile/user/login");
		}
		if(!$data || !$str){
			 _messagemobile("商品不存在！","/index.php/mobile/game/csg");
		}
		if($str['flag'] == 0){
			$l = rand(1,100);
			if($l < 11){
				$luck  = 4;
			}else if($l == 33 || $l == 66 || $l == 99){
				$luck = 3;
			}else if($l == 88){
				$luck = 2;
			}else{
				$luck = 5;
			}
			$arr = $this->db->Query("UPDATE `@#_kh_record` SET `flag` = '$luck' WHERE `id` = '$rid'");
		}else{
			$luck = $str['flag'];
		}

		if($luck == 4){
			$data['game_time'] = $data['game_time'] + 20;
			$data['game_time2'] = $data['game_time2'] + 20;
			$data['game_time3'] = $data['game_time3'] + 20;
		}else if($luck == 3){
			$data['kh_num3'] = $data['kh_num3'] - 3;
		}else if($luck == 2){
			$data['kh_num2'] = $data['kh_num2'] - 2;
			$data['kh_num3'] = $data['kh_num3'] - 3;
		}
		include templates("game/game","index");
	}

	public function get_merchandise(){
		include templates("game/game","get_merchandise");
	}

	public function swaprecord(){
		$member = $this->userinfo;
		if(!$member){
			header("Location:".WEB_PATH."/mobile/user/login");exit;
		}
		$uid = $this->userinfo['uid'];
		$page = intval($_GET['page']);
		if(!$page){
			$page = 1;
		}
		$num = 20;
		$start = ($page - 1) * 20;
		$data = $this->db->GetList("SELECT m.*,n.name,n.price,n.thumb,n.friends,n.free FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.uid = '$uid' AND m.status = '1' AND m.update_time != '0' ORDER BY m.update_time DESC LIMIT $start,$num");
		foreach ($data as $key => $val) {
			if($val['free'] == 1){
				if(empty($val['friends_union'])){
					$friends_num = 0;
				}else{
					$friends_num = count(explode(',', $val['friends_union']));
				}
				
				if($friends_num >= $val['friends']){
					$data[$key]['yes'] = 1;
				}else{
					$data[$key]['friends_num'] = $friends_num;
					$data[$key]['yes'] = 0;
				}
			}else{
				$data[$key]['yes'] = 1;
			}
			$data[$key]['create_time'] = date("Y-m-d H:i:s",$val['update_time']);
		}
		echo json_encode($data);
	}

	public function wsdz(){
		$id = $this->segment(4);
		$uid = $this->userinfo['uid'];
		$data = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$id' AND `uid` = '$uid' AND `status` = '1' AND `update_time` != '0'");
		if(!$data){
			_messagemobile("大爷,你走错地方了","/",3);
		}else{
			$arr = $this->db->GetOne("SELECT m.*,n.name,n.thumb,n.price,n.friends,n.free FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.id='$id' AND m.dizhi_id = '0' AND m.status = '1' AND m.update_time != '0' order by m.id desc");
			if(empty($arr['friends_union'])){
				$friends_num = 0;
			}else{
				$friends_num = count(explode(',', $arr['friends_union']));
			}
			if($arr['free'] == 1 && $friends_num < $arr['friends']){
				$cz = $arr['friends'] - $friends_num;
				_messagemobile("您还差".$cz."个好友辅助","/index.php/mobile/game/get_merchandise",3);exit;
			}
			if(!$arr){
				_messagemobile("地址已填写","/index.php/mobile/game/get_merchandise",3);exit;
			}else{
				$dizhi = $this->db->GetList("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid' AND `is_delete` = 0");
			}
		}
		include templates("game/game","wsdz");
	}

	public function do_wsdz(){
		$uid = $this->userinfo['uid'];
		$sid = intval($_POST['sid']);
		$did = intval($_POST['did']);

		$data = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE `id` = '$sid' AND `uid` = '$uid' AND `dizhi_id` = '0'");
		$data_s = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$data[shop_id]'");
		if($data){

			$data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$did);
			$data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);

			if (!empty($data2['bind_phone'])) {
				if (strpos($data2['bind_phone'],$data1['mobile']) !== false && $data_s['free'] == 1) {
					echo 2;exit();
				}
			}

			$time = time();
			$code = $uid.time();
			$q = $this->db->Query("UPDATE `@#_kh_record` SET `dizhi_id` = '$did',`dizhi_time` = '$time',`code` = '$code',`wuliu` = '未发货' WHERE `id` = '$sid'");

			if (!empty($data2['bind_phone'])) {
				if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
					$time = explode ( " ", microtime () );
					$time = $time[0] * 1000;  
					$time2 = explode (".", $time );  
					$time = $time2[0];
					$company_code = date("YmdHis",time()).$time;
					$this->db->Query("UPDATE `@#_kh_record` SET `company` = '惠享配送',`company_code` = '$company_code' WHERE `id` = $sid");
				}
			}
			if ($q) {
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 3;
		}
	}

	//商品物流详情
	public function swapOrderDetail(){
		$member = $this->userinfo;
		if(!$member){
			header("Location:".WEB_PATH."/mobile/user/login");exit;
		}
		$psw = _getcookie('save_password');
		if(!$psw){
			header("Location:".WEB_PATH."/mobile/home/identification?type=2");exit;
		}
		$uid = $this->userinfo['uid'];
		$id = $this->segment(4);
		$str = $this->db->GetOne("SELECT m.*,n.thumb,n.name,n.price FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.id = '$id' AND m.uid = '$uid' AND m.status = '1'");

		if (!$str) {
			_messagemobile("无此订单号","/index.php/mobile/game/csg");exit;
		}
		//判断是否为秒款号
		$data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$str['dizhi_id']);
		$data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);
		$mk = false;
		if (!empty($data2['bind_phone'])) {
			if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
				$mk = true;
				$status = $str['wuliu'];
				if($status == '已完成'){
					$flag_status = 3;
				}else if($status == '已发货'){
					$flag_status = 2;
				}else{
					$flag_status = 1;
				}
			}
		}

		if($str['company'] == '惠享配送'){
			$mk = true;
		}

		if(!empty($str['company_code']) && $str['company'] != '惠享配送'){
			if ($str['company'] == '京东快递') {	
			    $wuliu = $this->express('JD',trim($str['company_code']));
			}else{
			  	$wuliu = $this->express($str['shipper_code'],trim($str['company_code']));
			}
		}
		$place =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$str['dizhi_id']);
		include templates("game/game","swapOrderDetail");
	}

	public function express($type,$code){
    	$key = 'af2334b002a52daee3ed77d99fd44d06';
    	$code = $code;
  
    	$api = 'https://way.jd.com/fegine/gbexp?n='.$code.'&t='.$type.'&appkey='.$key;
    	$data = $this->send_get($api);
    	$rs = json_decode($data,true);
    	
    	$info = $rs['result']['Traces'];
 		$info = array_reverse($info);
    	return $info;
    }

    //get请求数据
    public function send_get($url) {  
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出,参数为0表示不带头文件，为1表示带头文件
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;
    }

    public function csg_shouhuo(){
    	$did = intval($_POST['did']);
		$member=$this->userinfo;
        $str = $this->db->GetOne("SELECT * FROM `@#_kh_record` WHERE id = '$did'");
		$status = "已完成";
        $q_time = time();
		if (!empty($_POST) && $str['uid'] == $member['uid'] && $str['f_time'] != 0) {
			$q = $this->db->Query("UPDATE `@#_kh_record` SET `wuliu` = '$status',`q_time` = '$q_time' WHERE `id` = $did");
			if ($q) {
				echo 1;
			}else{
				echo 0;
			}
		}
    }

    public function goodsdesc(){
        $key = "图文详情";
        $itemid = intval($this->segment(4));
        $desc = $this->db->GetOne("select `content` from `@#_kh_shop` where `id`='$itemid'");
        if (!$desc) {
            _messagemobile('页面错误!');
        }
        include templates("mobile/index", "goodsdesc");
    }

    public function free_fz(){
    	$id = intval($_POST['id']);
    	$uid = $this->userinfo['uid'];
    	$time = date("Ymd",time());

    	if(!$uid){
			$data['status'] = 2;
			$data['msg'] = '您未登录，请先登录！';
			echo json_encode($data);exit;
		}else{

			$data2 = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$id' AND `is_delete` = '0'");
    		$str = $this->db->GetOne("SELECT * FROM `@#_kh_fz` WHERE `shopid` = '$id' AND `uid` = '$uid' AND `fz_time` = '$time'");

			if(!$str){
				$str2 = $this->db->Query("INSERT INTO `@#_kh_fz` (`uid`,`shopid`,`fz_time`,`status`)VALUES('$uid','$id','$time','0')");
				$fz_id = $this->db->insert_id();
				if(!$str2){
					$data['status'] = 0;
					$data['msg'] = '打开失败，请重新打开！';
					echo json_encode($data);exit;
				}
			}else{
				$fz_id = $str['id'];
			}
			if($data2){
				$shopid = _encrypt($fz_id);
				$file = "statics/templates/yungou/images/mobile/game/free_get/".$fz_id.".jpg";
		      	$host = "http://".$_SERVER['HTTP_HOST'];
		        if(!file_exists($file)){
		            $crr = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text={$host}/index.php/mobile/game/csg/".$shopid);
		            file_put_contents($file, $crr);
		     	}
		        $img = "/statics/templates/yungou/images/mobile/game/free_get/".$fz_id.".jpg";
		        $data['status'] = 1;
		        $data['sid'] = $data2['id'];
		        $data['fid'] = $fz_id;

		        if(empty($str['friends_union'])){
		    		$data['friends_num'] = 0;
		    	}else{
		    		$data['friends_num'] = count(explode(',',$str['friends_union']));
		    	}
		    	$data['friends'] = $data2['free_friends'];
		    	$data['status_cg'] = $str['status'];
		    	$data['id'] = $str['id'];

		        echo json_encode($data);exit;
		    }else{
		    	$data['status'] = 3;
		    	$data['msg'] = '商品不存在！';
				echo json_encode($data);exit;
		    } 

	    }

    }

    public function free_game(){
    	$str['status'] = 2;
		$str['msg'] = '该免费闯关功能已关闭！';
		echo json_encode($str);exit;
  //   	$id = intval($_POST['id']);
  //   	$uid = $this->userinfo['uid'];
  //   	if(!$uid){
  //   		$str['status'] = 0;
  //   		$str['msg'] = '您未登录，请先登录！';
  //   		echo json_encode($str);exit;
  //   	}

  //   	$time_cz = strtotime(date("Y-m-d",time())) - 24*60*60*6;
		// $cz = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `uid` = '$uid' AND `status` = '已付款' AND `time` > '$time_cz'");
		// if(!$cz){
		// 	$str['status'] = 6;
		// 	$str['msg'] = "您7天内没有进行充值！";
		// 	echo json_encode($str);
		// 	exit;
		// }
  //   	$brr = $this->db->GetOne("SELECT * FROM `@#_kh_fz` WHERE `id` = '$id' AND `uid` = '$uid' AND `status` = '0'");
  //   	$arr = $this->db->Query("UPDATE `@#_kh_fz` SET `status` = '1' WHERE `id` = '$id' AND `uid` = '$uid'");
  //   	$shopid = $brr['shopid'];
  //   	$create_time = time();
  //   	$time = date("Ymd",time());
  //   	if(!$arr || !$brr){
  //   		$str['status'] = 2;
  //   		$str['msg'] = '您没有该商品的免费闯关机会！';
  //   		echo json_encode($str);exit;
  //   	}
  //   	$data = $this->db->Query("INSERT INTO `@#_kh_record` (`uid`,`shop_id`,`create_time`,`game_time`,`free`)VALUES('$uid','$shopid','$create_time','$time','1')");
  //   	$rid = $this->db->insert_id();
  //   	if($data){
  //   		$str['status'] = 1;
  //   		$str['sid'] = $shopid;
  //   		$str['rid'] = $rid;
  //   		echo json_encode($str);exit;
  //   	}else{
  //   		$str['status'] = 2;
  //   		$str['msg'] = '游戏开始失败，请重新开始游戏！';
  //   		echo json_encode($str);exit;
  //   	}
    }

    public function fz_get(){
    	$id = intval($_POST['id']);
    	$uid = $this->userinfo['uid'];

    	if(!$uid){
			$data['status'] = 2;
			$data['msg'] = '您未登录，请先登录！';
			echo json_encode($data);exit;
		}else{

    		$str = $this->db->GetOne("SELECT m.*,n.friends FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.id = '$id' AND m.uid = '$uid' AND m.status = '1'");

			if(!$str){		
				$data['status'] = 0;
				$data['msg'] = '无效链接！';
				echo json_encode($data);exit;
			}else{
				$shopid = _encrypt($id);
				$file = "statics/templates/yungou/images/mobile/game/free_get/".$id."-free.jpg";
		      	$host = "http://".$_SERVER['HTTP_HOST'];
		        if(!file_exists($file)){
		            $crr = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text={$host}/index.php/mobile/game/csg?fz=".$shopid);
		            file_put_contents($file, $crr);
		     	}
		        $img = "/statics/templates/yungou/images/mobile/game/free_get/".$id."-free.jpg";
		        $data['status'] = 1;
		        $data['fid'] = $id;
		        $data['sid'] = $str['shop_id'];

		        echo json_encode($data);exit;
		    }
		}
    }

    public function lunbo(){
    	$str = $this->db->GetList("SELECT m.uid,n.name FROM `@#_kh_record` AS m LEFT JOIN `@#_kh_shop` AS n ON m.shop_id = n.id WHERE m.status = '1' order by m.update_time DESC LIMIT 20");
    	if($str){
    		foreach ($str as $key => $val) {
	    		$arr[$key]['username'] = get_user_name($val['uid']);
	    		$arr[$key]['title'] = $val['name'];
	    	}
	    	$data['error'] = 0;
	    	$data['listItems'] = $arr;
    	}else{
    		$data['error'] = 1;
    	}
    	echo json_encode($data);
    }

   //  public function xxxx(){
   //  	$id = '500,545,643456,643442';
   //  	$time = '1541865600';
   //  	$data = $this->db->GetList("SELECT * FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE n.auto_user = 0 AND m.sid in ($id) AND m.q_uid is not null AND m.time > '$time'");
   //  	foreach ($data as $key => $val) {
   //  		$arr[] = $val['id'];
   //  	}
   //  	$sid = implode(',', $arr);
   //  	$str = $this->db->GetList("SELECT `shopname`,`dizhi_id` FROM `@#_member_go_record` WHERE `shopid` in ($sid) AND `huode` != '0' order by `dizhi_id` asc");
   //  	foreach ($str as $key => $val) {
   //  		$did = $val['dizhi_id'];
   //  		$data1 =  $this->db->GetOne("SELECT * FROM `@#_member_dizhi` where  `id` = ".$did);
			// $data2 =  $this->db->GetOne("SELECT * FROM `@#_member` where  `uid` = ".MK_UID);

			// if (!empty($data2['bind_phone'])) {
			// 	if (strpos($data2['bind_phone'],$data1['mobile']) !== false) {
			// 		unset($str[$key]);
			// 	}
			// }
   //  	}
    	
   //  	foreach ($str as $key => $val) {
   //  		$xxx = $val['dizhi_id'];
   //  		$crr = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$xxx'");
   //  		echo $crr['sheng'].$crr['shi'].$crr['xian'].$crr['jiedao']."--".$crr['shouhuoren']."--".$crr['mobile']."--件数:1件 (".$val['shopname'].")";
   //  		echo "<br><br>";
   //  	}
   //  }

}