<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class fifa extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
    }
  
	public function init(){
    	$member = $this->userinfo;
      	if($member){
          	$uid = $member['uid'];
        	header("Location:/mobile/fifa/guess/".$uid);
        }else{
        	header("Location:/mobile/fifa/guess");
        }
    }
    public function guess(){
		$match = $this->db->GetList("select * from `@#_fifa_list` where `match_status` = '0' limit 4");//未开赛或者未结束比赛
       	//var_dump($match);
      	$yaoqing = $this->segment(4);
        $member = $this->userinfo;
        $uid = $member['uid'];
      	$guess = $this->db->GetList("select * from `@#_fifa_guess_list` where `uid` = '$uid' and `results` = '1' and `status` = '1' ");
      	if(!empty($guess)){
      		$guess_true = count($guess);
          	$money = 0;
          	foreach($guess as $k=>$v){
            	$money+=$v['money'];
            }
        }else{
        	$guess_true = 0;
          	$money = 0;
        }
        if(!empty($yaoqing)){
          session_start();
          $_SESSION['yaoqing'] = $yaoqing;
          //echo $_SESSION['yaoqing'];
        }
      
   		$user_list = $this->db->GetList("select * from `@#_fifa_user` where money >0 order by num*money desc limit 10");

      $tmp = array();
      foreach ($user_list as $key => $val) {
        $num2 = $this->db->GetOne("SELECT * FROM `@#_fifa_user` WHERE `uid` = '$val[uid]'");
        $xx2 = $this->db->GetOne("SELECT * FROM `@#_fifa_times` WHERE `num` <= '$num2[num]' order by `id` desc");
        $user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$val[uid]'");
        $user_list[$key]['username'] = $user['username'];
        $user_list[$key]['mobile'] = $user['mobile'];
        $n2 = $xx2['times'];
        //$user_list[$key]['sort'] = $val['money']*$n2;
        $tmp[$key] = $val['money']*$n2;
      }
      
    
      array_multisort($tmp,SORT_DESC,$user_list);

      	$config = $this->db->GetOne("select * from `@#_fifa_config` where `id` = 1");
      	$jine = $config['money'] / 10000;
      	//分享部分代码
        require_once("system/modules/mobile/jssdk.php");
        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK($wechat['appid'], $wechat['appsecret']);

        $signPackage = $jssdk->GetSignPackage();
        
        include templates("mobile/fifa","index");
    }
	//我的竞猜
    public function info(){
      	$yaoqing = $this->segment(4);
      	if(!empty($yaoqing)){
          session_start();
          $_SESSION['yaoqing'] = $yaoqing;
        }
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
        if(!$uid){
            header("Location:/mobile/user/login");
        }
      	
        $member = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' limit 1");

        $mm = $this->db->GetOne("SELECT * FROM `@#_fifa_user` WHERE `uid` = '$uid'");
      	if(!$mm){
            //header("Location:/mobile/fifa");
        }
        $xx = $this->db->GetOne("SELECT * FROM `@#_fifa_times` WHERE `num` <= '$mm[num]' order by `id` desc");
        if($xx['num'] < 50){
          $id = $xx['id'] + 1;
          $kk = $this->db->GetOne("SELECT * FROM  `@#_fifa_times` WHERE `id` = '$id'");
          $zyq = $kk['num'] - $mm['num'];
          $bs = $kk['times'];
        }
      
        $guess = $this->db->GetList("select * from `@#_fifa_guess_list` where `uid`='$uid' order by id desc");
        $guess_true = $this->db->GetList("select * from `@#_fifa_guess_list` where `uid`='$uid' and `status`=1 and `results`='1' order by id desc");
        $user = $this->db->GetOne("select * from `@#_fifa_user` where `uid`='$uid' ");
        $config = $this->db->GetOne("select * from `@#_fifa_config` where `id`='1' ");
        include templates("mobile/fifa","info");
    }
  	//竞猜规则
	public function rule(){
    	include templates("mobile/fifa","rule");
    }
  	//往期赛程
  	public function competitions(){
        $match = $this->db->GetList("select * from `@#_fifa_list` where `match_status`='-1' order by id desc");
        $config = $this->db->GetOne("select * from `@#_fifa_config` where `id`='1' ");
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
        $member = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' limit 1");
      
        if(!$uid){
            header("Location:/mobile/user/login");
        }
        include templates("mobile/fifa","competitions");
    }
  
  
  
  
  
  
  
  
  
  
  	//实时获取最新比赛跟结果
    public function get_results(){
      	return false;
      	$url = 'http://odds.zgzcw.com/odds/oyzs_ajax.action';
      	$data['type'] = 'jc';
      	$data['issue'] = date('Y-m-d');
      	$data['date'] = '';
      	$data['companys'] = '1,3,12,4';
		$str = $this->request_post($url,$data);
      	$rs = json_decode($str,true);
      	if(empty($rs)){
        	exit();
        }
      	$list = array();
      	for ($i=0; $i < count($rs); $i++) { 
        	if($rs[$i]['SOURCE_LEAGUE_ID']==75){
              	unset($rs[$i]['listOdds']);
            	$list[$i] = $rs[$i];
              	$list[$i]['MATCH_TIME'] = strtotime($rs[$i]['MATCH_TIME']);
            }
        }
      
      	if(empty($list)){
          	$this->get_results2();
        	exit();
        }
      
      	$time = time();
      	$obj = $this->multi_array_sort($list,'MATCH_TIME');
      	//var_dump($list);exit();
      
      	foreach($obj as $k=>$v){
          	$id1 = $v['SOURCE_GUEST_ID'];
        	$a1 = $v['GUEST_NAME'];
          	$a2 = $v['GUEST_GOAL'];
          	$id2 = $v['SOURCE_HOST_ID'];
          	$a3 = $v['HOST_NAME'];
          	$a4 = $v['HOST_GOAL'];
          	$a5 = $v['SOURCE_MATCH_ID'];
          	$a6 = $v['MATCH_STATUS'];
          	$a7 = $v['MATCH_TIME'];
          	
          	$match=$this->db->GetOne("select * from `@#_fifa_list` where `match_id`='$a5'");
          	$config=$this->db->GetOne("select * from `@#_fifa_config` where `id`='1'");
          
          	$num1 = $config['num1'];
          	$num2 = $config['num2'];
          	
          	$jine = $config['money'];
          	
          	$host_num = rand($num1,$num2);
          	$guest_num = rand($num1,$num2);
          	$ping_num = rand($num1,$num2);
          
          	if(empty($match)){
            	$this->db->Query("INSERT INTO `@#_fifa_list`(guest_id,guest_name,guest_goal,host_id,host_name,host_goal,match_id,match_status,match_time,time,host_num,guest_num,ping_num,money) VALUE ('$id1','$a1','$a2','$id2','$a3','$a4','$a5','$a6','$a7','$time','$host_num','$guest_num','$ping_num','$jine')");
            	echo "插入成功\n";
            }
          	
          	
            	if($match['match_status']=='0'){
                  	if($a6 == '-1'){
                  		if($a4 > $a2 ){
                    		$rls = 1;//主队赢
                    	}elseif($a2 > $a4){
                    		$rls = 2;//客队赢
                    	}elseif($a2 == $a4){
                    		$rls = 3;//打平
                    	}else{
                    		$rls = 0;
                    	}
                		$this->db->Query("UPDATE `@#_fifa_list` SET `guest_goal` = '$a2',`host_goal` = '$a4',`match_status`='$a6',`results` = '$rls' WHERE `match_id`='$a5' ");
                  		echo "更新成功\n";
                    }else{
                    	echo "无需更新\n";
                    }
                }else{
                	echo "无需更新\n";
                }
            
            
         }	 
      	//var_dump($list);
      	$this->get_results2();
    }
  	//实时获取最新比赛跟结果
    public function get_results2(){
      	return false;
      	$url = 'http://odds.zgzcw.com/odds/oyzs_ajax.action';
      	$data['type'] = 'jc';
      	$data['issue'] = date("Y-m-d",strtotime("-1 day"));
      	$data['date'] = '';
      	$data['companys'] = '1,3,12,4';
		$str = $this->request_post($url,$data);
      	$rs = json_decode($str,true);
      	if(empty($rs)){
        	exit();
        }
      	$list = array();
      	for ($i=0; $i < count($rs); $i++) { 
        	if($rs[$i]['SOURCE_LEAGUE_ID']==75){
              	unset($rs[$i]['listOdds']);
            	$list[$i] = $rs[$i];
              	$list[$i]['MATCH_TIME'] = strtotime($rs[$i]['MATCH_TIME']);
            }
        }
      
      	if(empty($list)){
        	exit();
        }
      
      	$time = time();
      	$obj = $this->multi_array_sort($list,'MATCH_TIME');
      	//var_dump($list);exit();
      
      	foreach($obj as $k=>$v){
          	$id1 = $v['SOURCE_GUEST_ID'];
        	$a1 = $v['GUEST_NAME'];
          	$a2 = $v['GUEST_GOAL'];
          	$id2 = $v['SOURCE_HOST_ID'];
          	$a3 = $v['HOST_NAME'];
          	$a4 = $v['HOST_GOAL'];
          	$a5 = $v['SOURCE_MATCH_ID'];
          	$a6 = $v['MATCH_STATUS'];
          	$a7 = $v['MATCH_TIME'];
          	
          	$match=$this->db->GetOne("select * from `@#_fifa_list` where `match_id`='$a5'");
          	
          	$config=$this->db->GetOne("select * from `@#_fifa_config` where `id`='1'");
          
          	$num1 = $config['num1'];
          	$num2 = $config['num2'];
          	
          	$jine = $config['money'];
          
          	$host_num = rand($num1,$num2);
          	$guest_num = rand($num1,$num2);
          	$ping_num = rand($num1,$num2);
          
          	if(empty($match)){
            	$this->db->Query("INSERT INTO `@#_fifa_list`(guest_id,guest_name,guest_goal,host_id,host_name,host_goal,match_id,match_status,match_time,time,host_num,guest_num,ping_num,money) VALUE ('$id1','$a1','$a2','$id2','$a3','$a4','$a5','$a6','$a7','$time','$host_num','$guest_num','$ping_num','$jine')");
            	echo "插入成功\n";
            }
          	
          	
            	if($match['match_status']=='0'){
                  	if($a6 == '-1'){
                  		if($a4 > $a2 ){
                    		$rls = 1;//主队赢
                    	}elseif($a2 > $a4){
                    		$rls = 2;//客队赢
                    	}elseif($a2 == $a4){
                    		$rls = 3;//打平
                    	}else{
                    		$rls = 0;
                    	}
                		$this->db->Query("UPDATE `@#_fifa_list` SET `guest_goal` = '$a2',`host_goal` = '$a4',`match_status`='$a6',`results` = '$rls' WHERE `match_id`='$a5' ");
                  		echo "更新成功\n";
                    }else{
                    	echo "无需更新\n";
                    }
                }else{
                	echo "无需更新\n";
                }
            
            
         }	 
      	//var_dump($list);
    }
  	//遍历竞猜表，更新结果
  	public function open_lottery(){
      	return false;
    	$match = $this->db->GetOne("select * from `@#_fifa_list` where `match_status`='-1' and `over_status` = '0' order by match_time desc limit 1");
      	if(empty($match)){
        	exit('比赛未开赛或者未结束');
        }
      	$mid = $match['match_id'];
      	$guess_list = $this->db->GetList("select * from `@#_fifa_guess_list` where `match_id` = '$mid' and `status` = '0' "); 
      	//var_dump($mid);exit();
      	if(empty($guess_list)){
        	exit('没有人竞猜');
        }
      	
      	if($match['results']==1){
        	$qu_num = $match['host_num'];
        }elseif($match['results']==2){
        	$qu_num = $match['guest_num'];
        }elseif($match['results']==3){
        	$qu_num = $match['ping_num'];
        }else{
          	$qu_num = 0;
        }
      	if(empty($qu_num)){
        	exit('出错');
        }
      	foreach($guess_list as $k=>$v){
          	$jiangjin = $this->db->GetOne("select * from `@#_fifa_config` where `id`='1' ");
          	$jj = 0;
          	$money = 0;
      		
          	$jj = round($match['money'] / $qu_num);
          
        	if($v['guess'] == $match['results']){
            	$rs = 1;
              	$money = $jj;
            }else{
            	$rs = 2;
              	$money = 0;
            }
          	$this->db->Query("UPDATE `@#_fifa_guess_list` SET `money` = '$money',`results` = '$rs',`status` = '1' WHERE `id`='$v[id]' ");
            $this->db->Query("UPDATE `@#_fifa_user` SET `money` = `money` + '$money' WHERE `uid` = '$v[uid]'");
          	echo "更新成功\n";
        }
      	//sleep(10);
      	$guess_list2 = $this->db->GetList("select * from `@#_fifa_guess_list` where `match_id`='$mid' and `status` = '0'"); 
      	if(empty($guess_list2)){
        	$this->db->Query("UPDATE `@#_fifa_list` SET `over_status` = '1' WHERE `match_id`='$mid'");
            echo "更新成功\n";
        }else{
        	echo "更新失败\n";
        }
    }
  
  
    //排序方法
    protected function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){ 
        if(is_array($multi_array)){ 
            foreach ($multi_array as $row_array){ 
                if(is_array($row_array)){ 
                    $key_array[] = $row_array[$sort_key]; 
                }else{ 
                    return false; 
                } 
            } 
        }else{ 
            return false; 
        } 
        //sort , SORT_DESC  SORT_ASC
        array_multisort($key_array,$sort,$multi_array); 
        return $multi_array; 
    }
    protected function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }
        
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        return $data;
    }

    public function buy(){
      $match_id = $_POST['match_id'];
      $guess = $_POST['guess'];
      $member = $this->userinfo;

      $time3 = time()-3*86400;
      $xwxw = $this->db->GetOne("SELECT * FROM `@#_wxpay_locat` WHERE `create_time` > '$time3' AND `status` = '1' AND `uid` = '$member[uid]' order by `create_time` desc");
      $xxxx = $this->db->GetOne("SELECT * FROM `@#_member_addmoney_record` WHERE `time` > '$time3' AND `pay_type` in ('微信公众号','通过网络充值') AND `status` = '已付款' AND `uid` = '$member[uid]' order by `time` desc");

      if(!$xwxw && !$xxxx){
        echo 4;exit;
      }

      if(!$member){
        echo 0;exit;
      }

      $hmd = $this->db->GetOne("SELECT * FROM `@#_fifa_hmd` WHERE `uid` = '$member[uid]'");
      if($hmd){
        echo 5;exit;
      }

      $opid = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$member[uid]'");
      if(!empty($opid['wxopenid1'])){
        $opid1_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid1` = '$opid[wxopenid1]'");
        $op1 = array();
        foreach ($opid1_data as $key => $val) {
          $op1[] = $val['uid'];
        }
        $opid1 = implode(',', $op1);
        $wx_sm = $this->db->GetList("SELECT * FROM `@#_fifa_guess_list` WHERE `uid` in ($opid1) AND `match_id` = '$match_id'");
      }
      if(!empty($opid['wxopenid2'])){
        $opid2_data = $this->db->GetList("SELECT `uid` FROM `@#_member` WHERE `wxopenid2` = '$opid[wxopenid2]'");
        $op2 = array();
        foreach ($opid2_data as $key => $val) {
          $op2[] = $val['uid'];
        }
        $opid2 = implode(',', $op2);
        $wx_gzh = $this->db->GetList("SELECT * FROM `@#_fifa_guess_list` WHERE `uid` in ($opid2) AND `match_id` = '$match_id'");
      }
      
      if(count($wx_sm) >= 2 || count($wx_gzh)>=2){
        echo 9;exit;
      }

      $cscs = $this->db->GetOne("SELECT * FROM `@#_fifa_list` WHERE `match_id` = '$match_id'");
      $time = time();

      if($cscs['match_time'] < $time){
        echo 2;exit;
      }
      session_start();
      if(!empty($_SESSION['yaoqing'])){
        if($_SESSION['yaoqing'] != $member['uid']){
          $yaoqing = $_SESSION['yaoqing'];
        }else{
          $yaoqing = 0;
        }
      }else{
        $yaoqing = 0;
      }

      $cc = $this->db->GetOne("SELECT * FROM `@#_fifa_user` WHERE `uid` = '$member[uid]'");
      if(!$cc){
        
        $xx = $this->db->GetList("SELECT * FROM `@#_fifa_user` WHERE `yaoqing` = '$member[uid]");
      	$yy = $this->db->GetOne("SELECT * FROM `@#_fifa_user` WHERE `uid` = '$yaoqing");
      	
        $this->db->Query("INSERT INTO `@#_fifa_user` (uid,username,mobile,yaoqing)VALUES('$member[uid]','$member[username]','$member[mobile]','$yaoqing')");
        if($yy['num'] < 50){
        	$this->db->Query("UPDATE `@#_fifa_user` SET `num` = `num` + 1 WHERE `uid` = '$yaoqing'");
        }
        
      }

      $data = $this->db->GetOne("SELECT * FROM `@#_fifa_guess_list` WHERE `match_id` = '$match_id' AND `uid` = '$member[uid]'");
  
      if(!$data){
        $list = $this->db->Query("INSERT INTO `@#_fifa_guess_list` (`match_id`,`uid`,`username`,`mobile`,`guess`,`time`) VALUES ('$match_id','$member[uid]','$member[username]','$member[mobile]','$guess','$time')");
        if($guess == 1){
          $www = "`host_num` = `host_num`+1";
        }else if($guess == 2){
          $www = "`guest_num` = `guest_num`+1";
        }else{
          $www = "`ping_num` = `ping_num`+1";
        }
        $hhh = $this->db->Query("UPDATE `@#_fifa_list` SET $www WHERE `match_id` = '$match_id'");
        echo 1;exit;
      }else{
        echo 1;exit;
      }
    }

    public function buy000(){
     
      $member = $this->userinfo;
      $match_id = $_GET['match_id'];
      $opid = $this->db->GetOne("SELECT `wxopenid1`,`wxopenid2` FROM `@#_member` WHERE `uid` = '$member[uid]'");
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
      var_dump($wx_sm);
      var_dump($wx_gzh);
    }

    public function money_tx(){
       $member = $this->userinfo;
       $uid = $member['uid'];
       if(empty($uid)){
        header("Location:/mobile/user/login");exit;
       }
       $mm = $this->db->GetOne("SELECT * FROM `@#_fifa_user` WHERE `uid` = '$uid'");
       $xx = $this->db->GetOne("SELECT * FROM `@#_fifa_times` WHERE `num` <= '$mm[num]' order by `id` desc");
       $money = $mm['money']*$xx['times'];
       $money = intval($money);
       $money2 = intval($mm['money']);
       $time = time();
       if($money != 0){
        $this->db->Autocommit_start();
        $data = $this->db->Query("UPDATE `@#_member` SET `money` = `money`+'$money' WHERE `uid` = '$uid'");
        $str = $this->db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','1','账户','竞猜奖金充值到余额','$money','$time')");
        $arr = $this->db->Query("UPDATE `@#_fifa_user` SET `money` = `money`-'$money2' WHERE `uid` = '$uid'");
        $crr = $this->db->Query("INSERT INTO `@#_fifa_tx` (`uid`,`money`,`create_time`)VALUES('$uid','$money','$time')");
        if($data && $str && $arr && $crr){
          $this->db->Autocommit_commit();
          _messagemobile("提现到余额成功！本次提现".$money."元","/index.php/mobile/fifa/info");
        }else{
          $this->db->Autocommit_rollback();
          _messagemobile("提现到余额失败","/index.php/mobile/fifa/info");
        }
       }

    }

    public function bbbb(){
      $arr = array();
      foreach ($arr as $key => $val) {
        $data = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile`= '$val'");
        $uid = $data['uid'];

        $time = time();
        if(!empty($uid)){
          $str = $this->db->Query("INSERT INTO `@#_fifa_hmd` (`uid`,`mobile`,`create_time`)VALUES('$uid','$val','$time')");
          var_dump($str);
        }
      }
    }
}