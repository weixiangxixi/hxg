<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun('global');

class test extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
        _freshen();
    }

    public function auto_recharge(){
    	include templates("mobile/index","auto_recharge");
    }
	public function mySend(){
		sendWarnMsg('test', 'test');
  }
    public function sendMyAddMoney(){
      sendAddMoneyMsg("test", "test", 123, "充值通道－〉");
    }
    public function get_auto_recharge1(){
        //100 ->237009
        //500 -> 237015
        //1000 ->237031
        $array = array('237009','237015','237031');
        $timed = time();
        $k = 0;
        foreach ($array as $k => $v) {
            $shoplist[$k] = $this->db->GetOne("select * from `@#_shoplist` where sid=$v and q_uid != 0 and q_end_time < $time order by time desc limit 1");
        }
        var_dump($shoplist);
        
    }
    public function get_auto_recharge()
    {
    	//exit('0');
    	//$quan = $this->db->GetList("SELECT * FROM `@#_member_go_record` WHERE `shopname` like '%商城购物券%' and `status` = '已付款,未发货,未完成' and `huode` !=0 limit 1");
        $timed = time();
        //$shoplist=$this->db->GetList("select a.id,uid,shopid,shopname from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.shopname like '%商城购物券%' and a.status = '已付款,未发货,未完成' and a.huode != 0 and b.q_end_time < $timed order by a.time " );

        $shoplist=$this->db->GetOne("select `@#_member_go_record`.id,`@#_member_go_record`.uid,`@#_member_go_record`.shopid,`@#_member_go_record`.shopname from `@#_member_go_record` left join `@#_shoplist` on `@#_member_go_record`.shopid=`@#_shoplist`.id left join `@#_member` on `@#_member_go_record`.uid=`@#_member`.uid where `@#_member_go_record`.shopname like '%商城购物券%' and `@#_member_go_record`.status = '已付款,未发货,未完成' and `@#_member_go_record`.huode != 0 and `@#_shoplist`.q_end_time < $timed and `@#_member`.auto_user = 0 order by `@#_member_go_record`.time limit 1");
        //echo json_encode($shoplist);exit();
        if (!empty($shoplist)) {
            if(preg_match('/\d+/',$shoplist['shopname'],$arr)){
               $money = $arr[0];
               //echo $money.",".$shoplist['id'];
               //exit();
            }
            if (!empty($money)) {
            	$money = $money;
                $shuzhi = "100,500,1000";
                if(strpos($shuzhi,$money) !== false){
                	$content = $shoplist['shopname']." 充值";

                	$user=$this->db->GetOne("select * from `@#_member` `uid` = ".$shoplist['uid'] );
                	
                	if ($user['auto_user'] == 0) {
                		$this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$shoplist[uid]', '1', '账户', '$content', '$money', '$timed')");

                		$this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money'  WHERE `uid` = ".$shoplist['uid']);

                        $this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,已发货,未完成' ,`company` = '新潮配送' WHERE `id` = ".$shoplist['id']);
                	}
                	
                	
               	
                	echo 1;
                	
                }else{
                	echo 0;
                }
            }else{
            	echo 0;
            }           
        }else{
        	echo 0;
        }
        exit();
    }

    public function lottery_list(){
        $timed = time();
        //$shoplist=$this->db->GetList("select a.id,uid,shopid,shopname from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.shopname like '%商城购物券%' and a.status = '已付款,未发货,未完成' and a.huode != 0 and b.q_end_time < $timed order by a.time " );

        $list = $this->db->GetOne("select * from `@#_lottery_list` where `status` = 1 limit 1");

        if (!empty($list)) {

            //sleep(5);

            //$send = send_mobile_lottery($list['mobile']);
          
          	$shop = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$shopid' limit 1");
			$jxtime = $shop['q_end_time'];
          	if(time() > $jxtime){
            	$fa = $this->is_mobile_time_send($list['mobile'],'您购买的商品以备发货，请您及时登录新潮特惠会员中心填写收货地址。祝您购物愉快！');
              	//$fa = _sendmobile($list['mobile'],'您购买的商品以备发货，请您及时登录新潮特惠会员中心填写收货地址。祝您购物愉快！');
              	if($fa){
                	$this->db->Query("UPDATE `@#_lottery_list` SET `status` = '0' , `send_time` = '$timed'  WHERE `id` = ".$list['id']);
                }
            }
			           
            echo 1;
            exit();

        }else{
            echo 0;
            exit();
        }
    }
  
  	 public function lottery_list222(){
        $timed = time();
        //$shoplist=$this->db->GetList("select a.id,uid,shopid,shopname from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.shopname like '%商城购物券%' and a.status = '已付款,未发货,未完成' and a.huode != 0 and b.q_end_time < $timed order by a.time " );

         $list = $this->db->GetOne("select * from `@#_lottery_list` where `id` = 4045 limit 1");
       var_dump($list);

        if (!empty($list)) {

          	$shop = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$shopid' limit 1");
			$jxtime = $shop['q_end_time'];
          	if(time() > $jxtime){
            	$fa = $this->is_mobile_time_send($list['mobile'],'您购买的商品以备发货，请您及时登录新潮特惠会员中心填写收货地址。祝您购物愉快！');
              	//$fa = _sendmobile($list['mobile'],'您购买的商品以备发货，请您及时登录新潮特惠会员中心填写收货地址。祝您购物愉快！');
              	if($fa){
                	$this->db->Query("UPDATE `@#_lottery_list` SET `status` = '0' , `send_time` = '$timed'  WHERE `id` = ".$list['id']);
                }
            }
			           
            echo 1;
            exit();

        }else{
            echo 0;
            exit();
        }
    }
	public function dx(){
      	echo "别人服务器时间：".file_get_contents('http://106.ihuyi.cn/time.php')."<br>";
      	echo "我们的：".date("Y-m-d H:i:s");
      	$this->is_mobile_time_send('15812687307','您购买的商品以备发货，请您及时登录新潮特惠会员中心填写收货地址。祝您购物愉快！');
        //if($i){echo 'ok';}else{echo 'no';} 	     	
    }
  	public function is_mobile_time_send1($mobile,$content){
		//return true;
        $time = time() + 35;//延迟50秒发送

        $url = 'http://api.ihuyi.com/webservice/sms.php?method=Submit';
        $post_data['account'] = 'C54758485';
        $post_data['password'] = 'df83d8c6c6f77cb943bfec25449e009d';
        $post_data['mobile'] = $mobile;
        $post_data['content'] = $content;
        $post_data['stime'] = date('Y-m-d H:i:s',$time);
        $post_data['format'] = 'json';
        //$post_data = array();
        $res = $this->request_post($url, $post_data);  
        $res = json_decode($res,true);   
        //return $res;  
        //var_dump($res);
        if ($res['code']== 2) {
        	return true;
        }else{
        	return false;
        }
    }
	public function is_mobile_time_send($mobile,$content){
		//return true;
        $time = time() + 35;//延迟50秒发送

        $url = 'http://api.ihuyi.com/webservice/sms.php?method=Submit';
        $post_data['account'] = 'C54758485';
        $post_data['password'] = 'df83d8c6c6f77cb943bfec25449e009d';
        $post_data['mobile'] = $mobile;
        $post_data['content'] = $content;
        $post_data['stime'] = date('Y-m-d H:i:s',$time);
        $post_data['format'] = 'json';
        //$post_data = array();
        $res = $this->request_post($url, $post_data);  
        $res = json_decode($res,true);   
        //return $res;  
        //var_dump($res);
        if ($res['code']== 2) {
        	return true;
        }else{
        	return false;
        }
    }
  	public function request_post($url = '', $post_data = array()) {
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
    public function express(){
    	$key = 'dd29e44bb2ed2c09045bc3f5c5eb0d4e';
    	$code = '3831443509873';
    	$api = 'https://way.jd.com/jisuapi/query?type=auto&number='.$code.'&appkey='.$key;
    	$data = $this->send_get($api);
    	$rs = json_decode($data,true);
    	$info = '';
    	if ($rs['result']['status'] == 0) {
    		$info = $rs['result']['result']['list'];
    		for ($i=0; $i < count($info); $i++) { 
    			$info[$i]['id'] = $i;
    		}
    		var_dump($info);
    	}
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

    public function test(){
        $c_re = array('1');
        $n = 50;
        $run1 = 0;
        if($n >= 100 && count($c_re) <= 1){
            $run1 = 1;
            echo 0;
        }
        if($n >= 50 && count($c_re) >= 1 && $run1==0){
            echo 1;
        }
       
        
    }

    public function sck_pay(){
        $webname=$this->_cfg['web_name'];
        if(!$member=$this->userinfo){
          header("location: ".WEB_PATH."/mobile/user/login");
        }
        $oid=intval($this->segment(4));
        if($oid==0 || !$oid) {_messagemobile("无此信息！",WEB_PATH."/mobile/home/orderlist");}
        else{
           $mysql_model=System::load_sys_class('model');

           $record=$mysql_model->GetOne("select m.*,n.str1,n.str2 FROM  `@#_member_go_record` AS m left join `@#_shoplist` AS n ON m.shopid=n.id where m.id='$oid' limit 1");

           if($record['str2'] != 0){
               _messagemobile("该卡已充值过，无法再进行充值！",WEB_PATH."/mobile/home/orderlist");
           }else{
                $time = time();
                $str=$mysql_model->GetOne("select * FROM  `@#_member` where `uid`='$record[uid]' limit 1");
                $Money = $str['money'] + $record['str1'];
                $mysql_model->Autocommit_start();
                $query_1 = $mysql_model->Query("UPDATE `@#_shoplist` SET `str2`='1' WHERE `id`='$record[shopid]'");
                $query_2 = $mysql_model->Query("UPDATE `@#_member` SET `money`='$Money' WHERE (`uid`='$record[uid]')");         //金额
                $query_3 = $mysql_model->GetOne("SELECT * FROM  `@#_member` WHERE (`uid`='$record[uid]') LIMIT 1");
                $query_4 = $mysql_model->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$record[uid]', '1', '账户', '商城卡充值', '$record[str1]', '$time')");
                if($query_1 && $query_2 && $query_3 && $query_4){
                    $mysql_model->Autocommit_commit();
                    _messagemobile("充值成功！",WEB_PATH."/mobile/home/orderlist");
                }else{
                    $mysql_model->Autocommit_rollback();
                    _messagemobile("充值失败！",WEB_PATH."/mobile/home/orderlist");
                }
           }   
        }
    }

    public function sure_money(){
        $time = date("Ymd",time());
        $data = $this->db->GetList("SELECT * FROM `@#_activity_lottery` WHERE `current_count`='$time' and `state`='0'");
        foreach ($data as $key => $val) {
            $this->db->Autocommit_start();
            $t = $this->db->Query("UPDATE `@#_activity_lottery` SET `state` = '1' WHERE `user_id` = '$val[user_id]'");
            $total = explode(',',$val['amount']);
            $money = 0;
            foreach ($total as $k => $v) {
                $money += $v;
            }
            $time = time();
            $flag = $this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`) VALUE ('$val[user_id]','1','账户','元宵红包充值','$money','$time')");
            $f = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + '$money' where (`uid` = '$val[user_id]')");
            if($t && $flag && $f){
                var_dump('yes');
                $this->db->Autocommit_commit();
            }else{
                var_dump('no');
                $this->db->Autocommit_rollback();
            }
        }
        var_dump($data);
    }

    //导出数据库商品
    public function shop_out(){
        $str = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `q_uid` is null limit 20,50");
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
        var_dump(file_put_contents('shop3.txt', $sid));
    }

    public function wcwc(){
        $id = $this->segment(4);
        $data = $this->db->GetOne("SELECT `q_content` FROM `@#_shoplist` WHERE `id` = '$id'");
        $str = unserialize($data['q_content']);
        $time = 0;
        foreach ($str as $key => $val) {
            $time += $val['time_add'];
        }
        var_dump($time);

        $data2 = $this->db->GetList("SELECT `goucode` FROM `@#_member_go_record` WHERE `shopid` = '$id'");
        foreach ($data2 as $key => $val) {
            var_dump($val['goucode']);
        }
    }

    public function cxcx(){
        // $uid = $this->segment(4);
        // $data = $this->db->GetList("SELECT `money` FROM `@#_wxpay_locat` WHERE `update_time` - `create_time` > '3600' AND `uid` = '$uid'");
        // $str = $this->db->GetList("SELECT sum(money) as m FROM `@#_wxpay_locat` WHERE `update_time` - `create_time` > '3600' AND `uid` = '$uid'");
        // var_dump($str[0]['m']);
        // var_dump($data);
        $ip = _get_ip();
        var_dump($ip);
    }

    public function ab(){
    	echo md5('cszx123456');
    }
    //添加用户对应的短地址
    public function ddz(){
        // for ($i=108606; $i < 122000; $i++) { 
        //    $x = _encrypt($i);
            $request_url = sprintf("http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long=http://m.weimicm.com/index.php/mobile/user/register/".$x);

             $data = json_decode(file_get_contents($request_url),true);
             $sort = $data[0]['url_short'];
      		 echo $sort;
        //     $ss = $this->db->Query("INSERT INTO `@#_member_short` (`uid`,`url_short`)VALUES('$i','$sort')");
        //     var_dump($ss);
        // }
    }
    //修改商品content里的域名
    public function xxxxx(){
        // $data = $this->db->GetList("SELECT * FROM `@#_shoplist`");
        // foreach ($data as $key => $val) {
        //     $img = $val['content'];
        //     $content = str_replace('m.666cf.cn', 'm.weimicm.com', $img);
        //     if(!empty($content)){
        //         $arr = $this->db->Query("UPDATE `@#_shoplist` SET `content` = '$content' WHERE `id` = '$val[id]'");
        //     }
        //     var_dump($arr);
        // }
    }
  	public function ssss(){
        // $data = $this->db->GetList("SELECT m.* FROM `@#_appoint` AS m LEFT JOIN `@#_shoplist` AS n ON m.shopid = n.id WHERE n.q_end_time is not null order by `time` desc limit 500");
        // foreach ($data as $key => $val) {
        //     $str = $this->db->GetOne("SELECT `q_content`,`q_counttime` FROM `@#_shoplist` WHERE `id` = '$val[shopid]'");
        //     $arr = unserialize($str['q_content']);
        //     $time = 0;
        //     foreach ($arr as $k => $v) {
        //         $h = abs(date("H", $v['time']));
        //         $i = date("i", $v['time']);
        //         $s = date("s", $v['time']);
        //         $w = substr($v['time'], 11, 3);
        //         $user_shop_time_add = $h . $i . $s . $w;
        //         $time += $user_shop_time_add;
        //     }
        //     if($str['q_counttime'] != $time){
        //         var_dump($val['shopid']);
        //         //var_dump($str['q_counttime'] - $time);
        //     }
        // }
        $data = $this->db->GetList("select * from `@#_cnmb` WHERE 1");
        foreach ($data as $key => $val) {
            $shop = $this->db->GetOne("select `id`,`q_uid` from `@#_shoplist` WHERE `sid` ='$val[sid]' and `q_uid` is null order by id desc LIMIT 1");
            var_dump($shop);
        }
        var_dump(count($data));
        
    }

    public function zc_in(){
        $user = $this->db->GetList("SELECT `uid`,`money` FROM `@#_member` WHERE `auto_user` = 0");
        foreach ($user as $key => $val) {
            $data = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` WHERE `uid` = '$val[uid]' AND `type` = '1' AND `pay` in ('账户','佣金')");
            $out = $this->db->GetOne("SELECT sum(money) as n FROM `@#_member_account` WHERE `uid` = '$val[uid]' AND `type` = '-1' AND `pay` = '账户'");
            if($out['n'] > ($data['m'] + $val['money'] + 50)){
                $cz = $out['n'] - $data['m'] - $val['money'];
                var_dump("UID:".$val['uid']."-支出:".$out['n']."-充值:".$data['m']."-余额:".$val['money']."差值：**".$cz."**");
            }
        }
    }
  
     public function jf_jk(){
        $user = $this->db->GetList("SELECT `uid`,`score` FROM `@#_member` WHERE `auto_user` = 0");
        foreach ($user as $key => $val) {
            if($val['score'] > 35000){
            	var_dump($val['uid']."-".$val['score']);
           }
        }
    }

    public function cs(){
        require_once '/www/wwwroot/csthsc/system/modules/oss/samples/Object.php';
        $xx = Object::xxxx();
        var_dump($xx);
    }

    //设置
    public function shezhi_haha(){
        $data = $this->db->GetList("SELECT `money`,`title`,`sid` FROM `@#_shoplist` WHERE `money` > '5000' AND `q_uid` is null AND `cateid` != '173'");
        foreach ($data as $key => $val) {
            $sid = $val['sid'];
            $str = $this->db->GetOne("SELECT * FROM `@#_cnmb` WHERE `sid` = '$sid'");
            if(!$str){
                $haha = $this->db->Query("INSERT INTO `@#_cnmb`  (`sid`)VALUES('$sid')");
                var_dump($haha);
            }
        }
    }

    //设置2
    public function shezhi_hehe(){
        $data = $this->db->GetList("SELECT `money`,`title`,`sid` FROM `@#_shoplist` WHERE `money` < '16667' AND `q_uid` is null AND `cateid` != '173'");
        foreach ($data as $key => $val) {
            $sid = $val['sid'];
            $str = $this->db->GetOne("SELECT * FROM `@#_cnmb` WHERE `sid` = '$sid'");
            if($str){
                $haha = $this->db->Query("DELETE FROM `@#_cnmb` WHERE `sid` = '$str[sid]'");
                var_dump($haha);
            }
        }
        //$str = $this->db->Query("DELETE FROM `@#_appoint` WHERE `shopid` = '484987'");
        //$time = time();

        //$str = $this->db->Query("INSERT INTO `@#_appoint` (`shopid`,`userid`,`time`,`aid`,`ip`) VALUES ('484987','75898','$time','8','1111')");
        //var_dump($str);
    }

    public function aaaa(){
        $time_flag = 1550851500 - strtotime(date("Y-m-d"),time());
        if($time_flag > 1800 && $time_flag <= 11400){
            $times = ceil(($time_flag-1800)/1200)+1;
            $str = strtotime(date("Y-m-d",time())) + 1800 + ($times-1)*20*60;
        }else if($time_flag > 11400 && $time_flag <= 27000){
            $times = 10;
            $str = strtotime(date("Y-m-d",time())) + 27000;
        }else if($time_flag > 27000 && $time_flag <= 85800){
            $times = ceil(($time_flag-27000)/1200)+10;
            $str = strtotime(date("Y-m-d",time())) + 27000 + ($times-10)*20*60;
        }else if($time_flag <= 1800){
            $times = 1;
            $str = strtotime(date("Y-m-d",time())) + 1800;
        }else if($time_flag > 85800){
            $times = 1;
            $str = strtotime(date("Y-m-d",strtotime('+1day'))) + 1800;
        }

        if($times < 10){
            if($time_flag > 85800){
                $expect = date("Ymd",strtotime('+1day'))."00".$times;
            }else{
                $expect = date("Ymd",time())."00".$times;
            }
        }else if($times < 100){
            $expect = date("Ymd",time())."0".$times;
        }else{
            $expect = date("Ymd",time()).$times;
        }
        var_dump($times);
        var_dump($expect);
    }

    //查询100条购买记录时间之和
    public function yb_add(){
        // $id = '987093';
        // $str = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$id'");
        // $data = unserialize($str['q_content']);
        $time = '1551098487.661';
        $data = $this->db->GetList("select * from `@#_member_go_record` where `time` <= '$time' order by `time` DESC limit 0,100");
        $count_time = 0;
        foreach ($data as $key => $v) {
            $h=abs(date("H",$v['time']));
            $i=date("i",$v['time']);
            $s=date("s",$v['time']);    
            list($time,$ms) = explode(".",$v['time']);
            $time = $h.$i.$s.$ms;
            $count_time += $time;
        }
        var_dump($count_time);
    }
    //监视微信支付二维码是否正常
    public function qrcode_js(){
        $time = time() - 60*60;
        $data = $this->db->GetList("SELECT * FROM `@#_wxpay_locat_config` WHERE `canshu` = 'paycode' AND `status` = '1'");
        foreach ($data as $key => $val) {
            $id = $val['id'];
            $aid = $val['zhi'];
            $str2 = $this->db->GetList("SELECT `status` FROM `@#_wxpay_locat` WHERE `aid` = '$aid' AND `create_time` > '$time' ORDER BY `create_time` DESC LIMIT 4");
            $num = 0;
            foreach ($str2 as $k => $v) {
                $num += $v['status'];
            }
            if($str2 && $num == 0){
                $xx = $this->db->Query("UPDATE `@#_wxpay_locat_config` SET `status` = '0' WHERE `id` = '$id'");
                var_dump($xx);
            }
        }
    }

}
