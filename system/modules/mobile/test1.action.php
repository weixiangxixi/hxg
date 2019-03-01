<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class test1 extends base
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

                        $this->db->Query("UPDATE `@#_member_go_record` SET `status` = '已付款,已发货,未完成' ,`company` = '惠享配送' WHERE `id` = ".$shoplist['id']);
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
            	$fa = $this->is_mobile_time_send($list['mobile'],'您购买的商品以备发货，请您及时登录惠享购会员中心填写收货地址。祝您购物愉快！');
              	//$fa = _sendmobile($list['mobile'],'您购买的商品以备发货，请您及时登录惠享购会员中心填写收货地址。祝您购物愉快！');
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
      	//$this->is_mobile_time_send1('15812687307','您购买的商品以备发货，请您及时登录惠享购会员中心填写收货地址。祝您购物愉快！');
        //if($i){echo 'ok';}else{echo 'no';} 	     	
    }
  	public function is_mobile_time_send1($mobile,$content){
		//return true;
        $time = time() + 35;//延迟50秒发送

        $url = 'http://api.ihuyi.com/webservice/sms.php?method=Submit';
        $post_data['account'] = 'C74262219';
        $post_data['password'] = '7af0ce72d41f9443071fd1955d0fc00d';
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
        $time = time() + 45;//延迟50秒发送

        $url = 'http://api.ihuyi.com/webservice/sms.php?method=Submit';
        $post_data['account'] = 'C74262219';
        $post_data['password'] = '7af0ce72d41f9443071fd1955d0fc00d';
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
        $xx = ceil(301/300);
        var_dump($xx);
    }

    
}