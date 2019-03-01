<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class my extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        //_freshen();
    }
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
    public function info(){
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
        if(!$uid){
            header("Location:/mobile/user/login");
        }
        $member = $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$uid' limit 1");
      
        $guess = $this->db->GetList("select * from `@#_fifa_guess_list` where `uid`='$uid' order by id desc");
        $guess_true = $this->db->GetList("select * from `@#_fifa_guess_list` where `uid`='$uid' and `status`=1 and `results`='1' order by id desc");
        $user = $this->db->GetOne("select * from `@#_fifa_user` where `uid`='$uid' ");
        $config = $this->db->GetOne("select * from `@#_fifa_config` where `id`='1' ");
        include templates("mobile/fifa","info");
    }
    public function invite(){
        $uid=intval(_encrypt(_getcookie("uid"),'DECODE'));
        if(!$uid){
            header("Location:/mobile/user/login");
        }
        $user = $this->db->GetList("select * from `@#_fifa_user` where `yaoqing`='$uid' ");
        include templates("mobile/fifa","invite");
    }
	public function code(){
      	$mobile = '15812687307';
      
      	$member = $this->db->GetOne("SELECT * FROM `@#_member` where `mobile` = '$mobile' limit 1");
    	
		$code = _setcookie("uid1",_encrypt($member['uid']),60*60*24*7);
      
      	echo _getcookie('uid1');
    }
    public function clean1(){
        $now = time();
        $to = strtotime(date('Y-m-d',time())) + 60 * 60 * 12;  
        
        $list = $this->db->GetList("select * from `@#_appoint` where `status`= 0");
        if (empty($list)) {
            exit();
        }

        if ($now == $to) {
            $rs = $this->db->Query("delete FROM `@#_appoint` where `status`= 0 ");
            if ($rs) {
                echo 'success\n';
            }else{
                echo 'error\n';
            }
        }else{
            echo 'doing\n';
        }
        exit();
    }
	
  	public function add_fifa_num(){
    	$fifa_list = $this->db->GetList("select * from `@#_fifa_list` where `match_status` = '0' "); 
        
        if(empty($fifa_list)){
          exit('没有比赛');
        }
      
        foreach($fifa_list as $k=>$v){
          	$host_num = rand(1,9);
          	$guest_num = rand(1,9);
          	$ping_num = rand(1,9);
          	$time = time();
          	if($time < $v['match_time']){
        		$this->db->Query("UPDATE `@#_fifa_list` SET `host_num` = `host_num` + '$host_num',`guest_num` = `guest_num` + '$guest_num',`ping_num` = `ping_num` + '$ping_num'  WHERE `id` = '$v[id]'");
            }
        }
      	echo 'ok';
    }
  	public function aaaa(){
    	$s = htmlspecialchars($this->segment(4));
        
       $user = $this->db->GetOne("select * from `@#_member` where `uid`='$s' or `mobile`='$s'");
       echo $user['mobile_code'];
          
    }
    public function test(){
        $sid = htmlspecialchars($this->segment(4));
        $k = 0;
        
        $shoplist = $this->db->GetList("select * from `@#_member_go_record` where `shopid`=".$sid);
        
        foreach ($shoplist as $k => $v) {
            $user = $this->db->GetOne("select * from `@#_member` where `uid`=".$v['uid']);
            echo $user['auto_user']."<br>";
        }
        
    }
    
    public function sb(){
        include("/www/wwwroot/m.xx.com/system/modules/mobile/page.class.php");
        $page = new Page(100,10);
        echo $page->fpage();
    }
    public function aa(){
      //exit('1');
      $t = $this->db->GetOne("select * from `@#_member_go_record` where `uid`=4022 order by id desc limit 1");    
      $arr=explode(",",$t['goucode']);
      var_dump($arr);
	  shuffle($arr);
      var_dump($arr);
       
      
    }
  
    public function bb(){
    	//send_mobile_lottery('15812687307');
      	$allString = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$searchString = 'http://'.$_SERVER['HTTP_HOST']."/index.php/api/wxorder5/init/";
		$newString = strstr($allString, $searchString);
		$length = strlen($searchString);
		$code = substr($newString, $length);
      	//echo 'http://'.$_SERVER['HTTP_HOST'];
    }
  	public function money1(){
    	echo 1;
    }
  	public function money(){
      	$zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值') and `status` = '已付款' ");    
      	$wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' ");    
      	//$sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' ");    
      
      	$time = strtotime(date('Y-m-d'));
      	$ztime = strtotime(date('Ymd',strtotime("-1 day")));
      	
      	$jr_zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值')  and `status` = '已付款' and `time` > '$time'  ");    
      	$jr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_zfb = $this->db->GetOne("select sum(money) as zfb from `@#_member_addmoney_record` where `pay_type` = '通过网络充值' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' and `create_time` > '$time' "); 
      	//$jr_xf = $this->db->GetOne("select sum(moneycount) as xf from `@#_member_go_record` where `type` = '1'  and `huode` != '0' and `time` > '$time'  ");    
      	
      	
      
      	$zjr_zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值')  and `status` = '已付款' and `time` < '$time' and `time` > '$ztime'  ");    
      	$zjr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_zfb = $this->db->GetOne("select sum(money) as zfb from `@#_member_addmoney_record` where `pay_type` = '通过网络充值' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' and `create_time` < '$time' and `create_time` > '$ztime' "); 
      	
      	$zz = $zjr_zong['zong'] + $zjr_sm['sm'];
      	
      	echo '总充值：'.$zong['zong']."<br>";
      	echo '微信总充值：'.$wx['wx']."<br>";
      	//echo '扫码总充值：'.$sm['sm']."<br>";
      	echo '<br>';
      
        $z = $jr_zong['zong'] + $jr_sm['sm'];
      
      	echo '今日总充值：'.$z."<br>";
      	echo '昨日总充值：'.$zz."<br>";
      	
      	echo '<br>';
      
      	echo '今日微信总充值：'.$jr_wx['wx']."<br>";
      	echo '今日扫码总充值：'.$jr_sm['sm']."<br>";
      	echo '今日支付宝总充值：'.$jr_zfb['zfb']."<br>";
      	
      	
      	//echo '今日消费：'.$jr_xf['xf']."<br>";
      
      	echo '<br>';
      	echo '昨日微信总充值：'.$zjr_wx['wx']."<br>";
      	echo '昨日扫码总充值：'.$zjr_sm['sm']."<br>";
      	echo '昨日支付宝总充值：'.$zjr_zfb['zfb']."<br>";
      	
      	
    }
}