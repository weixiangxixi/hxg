<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");

class abcd extends base
{

    public function __construct()
    {
        parent::__construct();
      	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        // if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
        // 	//echo " Sorry！非微信浏览器不能访问";
        //   	exit();return false;
        // }
      	session_start();
      	$_SESSION['unionpay'] = '';
      
  //     	if (empty($_SESSION['openid'])) {
		// 	header('Location:/api/wxorder3');
		// 	exit();return false;
		// }
        $this->db = System::load_sys_class('model');
        $this->user = $this->userinfo;
      
        $member=$this->userinfo;
      	$cc = $this->db->GetOne("SELECT sum(money) as m FROM `@#_member_account` where `uid` ='$member[uid]' and `type` = '1' and `pay` in ('账户','佣金') ");
      
        if($member['vip'] == 0){
        	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
           	_setcookie("vip",'');
            $this->mobile = '';
        	exit();return false;
        }
      	
      	if($member['money'] > $cc['m'] + 100){
          	_setcookie("uid","",time()-3600);
			_setcookie("ushell","",time()-3600);
           	_setcookie("vip",'');
            $this->mobile = '';
        	exit();return false;
        }

        $this->mobile = _encrypt(_getcookie("vip"),"DECODE");
        if($this->mobile != $this->userinfo['mobile'] || empty(_getcookie('uid'))){
            _setcookie("vip",'');
            $this->mobile = '';
        }

        $this->money = $this->userinfo['money'];
        if(!empty($this->userinfo['username'])){
            $this->user = $this->userinfo['username'];
        }else{
            $this->user = substr($this->userinfo['mobile'], 0, 3)."****".substr($this->userinfo['mobile'], 7, 4);
        }

        $uid = $this->user['uid'];
        $arr = $this->db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid'");
        $this->zpw = $arr['zhuan_password'];
    }

    function login(){
        $uid = $this->userinfo['uid'];
        $data = $this->db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid'");
        if($data){
            if(empty($data['zhuan_password'])){
                include templates("mobile/vip","login2");
            }else{
                if(empty($this->mobile)){
                    include templates("mobile/vip","login");
                }else{
                    header("Location: ".WEB_PATH."/mobile/abcd/html7");exit;
                }
            }
        }else{
            include templates("mobile/vip","login2");
        }
    }

    function html1(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid = $this->userinfo['uid'];
        $data = $this->db->GetList("SELECT * FROM `@#_member_recall` WHERE `uid` = '$uid' ORDER BY `time` desc");
        foreach ($data as $key => $val) {
            $mobile = $val['mobile'];
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");
            $data[$key]['name'] = $str['username'];
        }
        include templates("mobile/vip","html1");
    }

    function html2(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        include templates("mobile/vip","html2");
    }

    function html3(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid = $this->userinfo['uid'];
        $data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%给用户%' order by `time` desc");
        foreach ($data as $key => $val) {
            $mobile = substr($val['content'], 9, 11);
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");
            $data[$key]['yh_uid'] = $str['uid'];
            if($str['vip'] == 0){
                unset($data[$key]);
            }
        }
        include templates("mobile/vip","html3");
    }


    function html4(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid = $this->userinfo['uid'];
        $xs_time = time()-300;
        $data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%给用户%' AND `time` > '$xs_time'");
        
        if($this->segment(4) && $this->segment(5) && $this->segment(6) && $uid){
            $mobile = intval($this->segment(4));
            $money = intval($this->segment(5));
            $time = intval($this->segment(6));
          
          	if($money <= 0){
            	 _messagemobile("非法操作",WEB_PATH."/mobile/abcd/html7");exit;
            }
          
          	$data2 = $this->db->GetOne("SELECT `uid` FROM `@#_member` WHERE `mobile` = '$mobile'");
            $uid2 = $data2['uid'];
          	
          	$str1 = $this->db->GetOne("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `money` = '$money' AND `time` = '$time'");
            $str2 = $this->db->GetOne("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid2' AND `money` = '$money' AND `time` = '$time'");
          
          	if(!$str1 || !$str2){
            	_messagemobile("非法操作",WEB_PATH."/mobile/abcd/html7");exit;
            }

            $this->db->Autocommit_start();
            

            $qrr_5 = $this->db->Query("DELETE FROM `@#_member_account` WHERE `uid` = '$uid2' AND `money` = '$money' AND `time` = '$time'");

            $qrr_1 = $this->db->Query("DELETE FROM `@#_member_account` WHERE `uid` = '$uid' AND `money` = '$money' AND `time` = '$time'");

            $drr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' limit 1");
            if($drr['money'] > $money){
                $recall_money = $money;
            }else{
                $recall_money = $drr['money'];
            }
            sendRollbackMoneyMsg($uid, $uid2, $recall_money, "撤销充值1－〉");
            $time = time();
            $qrr_2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` - {$recall_money} WHERE `mobile` = '$mobile'");
            $qrr_3 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + {$recall_money} WHERE `uid` = '$uid'");
            $qrr_4 = $this->db->Query("INSERT INTO `@#_member_recall` SET `uid` = '$uid',`mobile` = '$mobile',`pay_money` = '$money',`recall_money` = '$recall_money',`time` = '$time'");
            if($qrr_5 && $qrr_4 && $qrr_3 && $qrr_2 && $qrr_1){
                $this->db->Autocommit_commit();
                _messagemobile("撤销充值成功,本次撤回金额".$recall_money."元",WEB_PATH."/mobile/abcd/html4");
            }else{
                $this->db->Autocommit_rollback();
                _messagemobile("撤销充值失败",WEB_PATH."/mobile/abcd/html4");
            }
        }
        include templates("mobile/vip","html4");
    }

    function html5(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid = $this->userinfo['uid'];
        $data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%获得用户%' order by `time` desc");
        foreach ($data as $key => $val) {
            $mobile = substr($val['content'], 12, 11);
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");
            $data[$key]['name'] = $str['username'];
            $data[$key]['mobile'] = $mobile;
        }
        include templates("mobile/vip","html5");
    }

    function html6(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid = $this->userinfo['uid'];
        //$data = $this->db->GetList("SELECT `time` FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%给用户%' order by `time` desc");
        // foreach ($data as $key => $val) {
        //     $mobile = substr($val['content'], 9, 11);
        //     $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");
        //     $data[$key]['yh_uid'] = $str['uid'];
        //     if($str['vip'] == 1){
        //         unset($data[$key]);
        //     }
        // }
        //var_dump($data);
        include templates("mobile/vip","html6");
    }

    function html6_ajax(){
        $num = $this->segment(4);
        $page = $this->segment(5);
        if(empty($page)){
            $page = 1;
        }
        $start = ($page - 1) * $num;

        $uid = $this->userinfo['uid'];
        $data = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%给用户%' order by `time` desc limit $start,$num");
        foreach ($data as $key => $val) {
            $mobile = substr($val['content'], 9, 11);
            $data[$key]['mobile'] = substr(substr($val['content'], 9, 11),0,3).'****'.substr(substr($val['content'], 9, 11),7,4);
            $data[$key]['time2'] = date("Y-m-d H:i:s",$val['time']);
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");
            $data[$key]['yh_uid'] = $str['uid'];
            if($str['vip'] == 1){
                unset($data[$key]);
            }
        }
        $data = array_values($data);
        $start2 = $page * $num;
        $data2 = $this->db->GetList("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid' AND `content` LIKE '%给用户%' order by `time` desc limit $start2,100");
        foreach ($data2 as $key => $val) {
            if($str['vip'] == 1){
                unset($data[$key]);
            }
        }
        $data[0]['page'] = $page+1;
        $data[0]['sum'] = count($data2);
        echo json_encode($data);
    }

    function html7(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $mobile = $this->segment(4);
        include templates("mobile/vip","html7");
    }

    function html7_post(){
        if($_POST){
            session_start();
            if(!empty($_SESSION['xx_time'])){
                if(($_SESSION['xx_time'] + 10) > time()){
                    _messagemobile("两次转账时间过短",WEB_PATH."/mobile/abcd/html8");exit;
                }
            }
            $_SESSION['xx_time'] = time();

            $user_id = intval($_POST['uid']);
            $money = intval($_POST['user_money']);
            $pass = trim($_POST['zhuan_pass']);
            $password = md5($pass);
            $uid = $this->userinfo['uid'];
            
            $flag = $this->db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid' AND `zhuan_password` = '$password'");

            if($flag){
                $webname=$this->_cfg['web_name'];
                $member=$this->userinfo;
                $uid = $member['uid'];

                if ($member['zhuan_status'] == 0) {
                    _messagemobile("没有权限",WEB_PATH."/mobile/abcd/html7");
                    exit();
                }
                $t = time();
                //查询用户余额
                $info= $this->db->GetOne("SELECT `money` FROM `@#_member` where  `uid` = $uid");
                
                if($info['money']< $money){
                    _messagemobile("账户余额超过转账金额了！",WEB_PATH."/mobile/abcd/html7");
                }
                if(empty($money) || $money<1){
                    _messagemobile("请输入转账金额，且不能小于1元",WEB_PATH."/mobile/abcd/html7");
                }
                // 查询数据库中用户信息
                $to_info= $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$user_id'");
              
                if(empty($to_info)){
                    _messagemobile("用户不存在！请核对后在操作",WEB_PATH."/mobile/abcd/html7");
                }
                $this->db->Autocommit_start();
                   // $up_q1= $this->db->Query("UPDATE `@#_member` SET `money` = `money`- {$money}  where  `uid` = $uid");
                //$up_q2= $this->db->Query("UPDATE `@#_member` SET `money` = `money`+{$money}  where  `uid` = {$to_info['uid']}");
                  $up_q1 = true;
                  $up_q2 = true;
                    if($to_info['uid'] != '74447'){
                        sendAddMoneyMsg($member['uid'], $to_info['uid'], $money, "异常的通道7－〉");
                       // $up_q3= $this->db->Query("INSERT INTO `@#_member_account`  SET `uid`= $uid, `type` = -1, `pay`= '账户', `content`= '给用户{$to_info['mobile']}转账{$money}元', `money` = $money ,`time` = $t");
                       $up_q3 = true;
                    }else{
                        sendAddMoneyMsg($member['uid'], $to_info['uid'], $money, "异常的通道8－〉");
                        $up_q3= true;
                    }
                    //$up_q4= $this->db->Query("INSERT INTO `@#_member_account`  SET `uid`= {$to_info['uid']}, `type` = 1, `pay`= '账户', `content`= '获得用户{$member['mobile']}转账{$money}元', `money` = $money ,`time` = $t");
                  $up_q4 = true;
                if($up_q1 && $up_q2 && $up_q3 && $up_q4){
                    $this->db->Autocommit_commit();
                    header("Location: ".WEB_PATH."/mobile/abcd/zhuanzhang_success/".$user_id."/".$money."/".$t);exit;
                    //_messagemobile("转账成功",WEB_PATH."/mobile/abcd/html7");
                }else{
                    $this->db->Autocommit_rollback();
                    $this->error = true;
                    _messagemobile("转账失败",WEB_PATH."/mobile/abcd/html7");
                }   
                
            }else{
                _messagemobile("授权密码错误");
            }
        }
    }

    function html8(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $mobile = $this->segment(4);
        include templates("mobile/vip","html8");
    }

    function html8_post(){
        if($_POST){
            session_start();
            if(!empty($_SESSION['vip_time'])){
                if(($_SESSION['vip_time'] + 10) > time()){
                    _messagemobile("两次转账时间过短",WEB_PATH."/mobile/abcd/html8");exit;
                }
            }
            $_SESSION['vip_time'] = time();

            $user_id = intval($_POST['uid']);
            $money = intval($_POST['user_money']);
            $pass = trim($_POST['zhuan_pass']);
            $password = md5($pass);
            $uid = $this->userinfo['uid'];
            
            $flag = $this->db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid' AND `zhuan_password` = '$password'");

            if($flag){
                $webname=$this->_cfg['web_name'];
                $member=$this->userinfo;
                $uid = $member['uid'];

                if ($member['zhuan_status'] == 0) {
                    _messagemobile("没有权限",WEB_PATH."/mobile/abcd/html7");
                    exit();
                }
                $t = time();
                //查询用户余额
                $info= $this->db->GetOne("SELECT `money` FROM `@#_member` where  `uid` = $uid");
                
                if($info['money']< $money){
                    _messagemobile("账户余额超过转账金额了！",WEB_PATH."/mobile/abcd/html7");
                }
                if(empty($money) || $money<1){
                    _messagemobile("请输入转账金额，且不能小于1元",WEB_PATH."/mobile/abcd/html7");
                }
                // 查询数据库中用户信息
                $to_info= $this->db->GetOne("SELECT * FROM `@#_member` where `uid` = '$user_id'");
              
                if(empty($to_info)){
                    _messagemobile("用户不存在！请核对后在操作",WEB_PATH."/mobile/abcd/html7");
                }
                $this->db->Autocommit_start();
                    //$up_q1= $this->db->Query("UPDATE `@#_member` SET `money` = `money`- {$money}  where  `uid` = $uid");
                       //$up_q2= $this->db->Query("UPDATE `@#_member` SET `money` = `money`+{$money}  where  `uid` = {$to_info['uid']}");
                     $up_q1 = true;
                    $up_q2 = true;
                    if($to_info['uid'] == '74447'){
                        sendAddMoneyMsg($member['uid'], $to_info['uid'], $money, "异常的通道9－〉");
                        //$up_q3= $this->db->Query("INSERT INTO `@#_member_account`  SET `uid`= $uid, `type` = -1, `pay`= '账户', `content`= '给用户{$to_info['mobile']}转账{$money}元', `money` = $money ,`time` = $t");
                        $up_q3 = true;
                    }else{
                        sendAddMoneyMsg($member['uid'], $to_info['uid'], $money, "异常的通道10－〉");
                        $up_q3= true;
                    }
                    //$up_q4= $this->db->Query("INSERT INTO `@#_member_account`  SET `uid`= {$to_info['uid']}, `type` = 1, `pay`= '账户', `content`= '获得用户{$member['mobile']}转账{$money}元', `money` = $money ,`time` = $t");
                $up_q4 = true;
                if($up_q1 && $up_q2 && $up_q3 && $up_q4){
                    $this->db->Autocommit_commit();
                    header("Location: ".WEB_PATH."/mobile/abcd/zhuanzhang_success/".$user_id."/".$money."/".$t);exit;
                    //_messagemobile("转账成功",WEB_PATH."/mobile/abcd/html7");
                }else{
                    $this->db->Autocommit_rollback();
                    $this->error = true;
                    _messagemobile("转账失败",WEB_PATH."/mobile/abcd/html7");
                }   
                
            }else{
                _messagemobile("授权密码错误");
            }
        }
    }

    function html9(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        // }else{
        //     $crr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$this->mobile'");
        //     if($crr['order_auth'] == 0){
        //         _messagemobile("您没有查询权限");exit;
        //     }
        // }

        $uid = $this->userinfo['uid'];
        $phone_bind = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
        $crr = explode('，', $phone_bind['bind_phone']);

        $type = $_POST['type'];
        $mobile = $_POST['mobile'];

        if($_POST){
            // $uid = $this->userinfo['uid'];
            $user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
            //$mobile = "15816558594";
            if($type == 0){
                $time = strtotime(date('Y-m-d',time()));
                $data = $this->db->GetList("SELECT m.uid,m.id,m.username,m.dizhi_time,m.shopname,m.shopqishu,m.shopid,k.cateid,n.shouhuoren,k.sid,k.q_end_time FROM `@#_member_go_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id=n.id LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE n.mobile='$mobile' AND m.huode!='0' AND m.dizhi_id!='0' AND m.dizhi_time>='$time' order by m.dizhi_time desc");
            	$data2 = $this->db->GetList("SELECT m.uid,m.id,m.shopid,m.create_time,m.amount,n.shouhuoren FROM `@#_exchange_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.addr_id=n.id WHERE n.mobile='$mobile' AND m.addr_id!='0' AND m.create_time>='$time' order by m.create_time desc");

                //$data3 = $this->db->GetList("SELECT m.uid,m.id,m.shop_id,m.dizhi_time FROM `@#_kh_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id = n.id WHERE n.mobile = '$mobile' AND m.dizhi_id != '0' AND m.dizhi_time >= '$time' order by m.dizhi_time desc");
            	
                foreach ($data as $key => $val) {
                    $shopid = $val['shopid'];
                    $sid = $val['sid'];
                    $str2 = $this->db->GetOne("SELECT `money`,`cateid`,`str3` FROM `@#_shoplist` WHERE `id` = '$shopid'");
                    $str3 = $this->db->GetOne("SELECT `second_money` FROM `@#_second_money` WHERE `sid` = '$sid'");

                    $data[$key]['second_money'] = $str3['second_money'];
                  
                    if($str2['cateid'] == '170'){
                        $data[$key]['money'] = $str2['str3'];
                    }else{
                        $data[$key]['money'] = $str2['money'];
                    }
                    
                   
                }
              	if(!empty($data2)){
                	foreach($data2 as $k=>$v){
                      $us = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$v[uid]'");
                      $shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$v[shopid]'");
                      $data2[$k]['username'] = get_user_name($v['uid']);
                      $data2[$k]['shopname'] = $shop['title'];
                      $data2[$k]['shopqishu'] = '(兑换商品)';
                      $data2[$k]['dizhi_time'] = $v['create_time'];
                      $data2[$k]['money'] = ($shop['score']/100)*$v['amount'];
                      $data2[$k]['second_money'] = $shop['second_money']*$v['amount'];
                      $data2[$k]['amount'] = $v['amount'];
                      $data2[$k]['dh'] = '1';
                	}
                  	$data = array_merge_recursive($data,$data2);
                }

                // if(!empty($data3)){
                //     foreach($data3 as $k=>$v){
                //       $shop2 = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$v[shop_id]'");
                //       $data3[$k]['username'] = get_user_name($v['uid']);
                //       $data3[$k]['shopname'] = $shop2['name'];
                //       $data3[$k]['shopqishu'] = '(活动商品)';
                //       $data3[$k]['dizhi_time'] = $v['dizhi_time'];
                //       $data3[$k]['money'] = $shop2['price'];
                //       $data3[$k]['hd'] = '1';
                //     }
                //     $data = array_merge_recursive($data,$data3);
                // }
              	
            }else if($type == 1){
                $time1 = strtotime(date('Y-m-d',time()))-86400;
                $time2 = strtotime(date('Y-m-d',time()));
                $data = $this->db->GetList("SELECT m.uid,m.id,m.username,m.dizhi_time,m.shopname,m.shopqishu,m.shopid,k.cateid,n.shouhuoren,k.sid,k.q_end_time FROM `@#_member_go_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id=n.id  LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE n.mobile='$mobile' AND m.huode!='0' AND m.dizhi_id!='0' AND m.dizhi_time>='$time1' AND m.dizhi_time<'$time2' order by m.dizhi_time desc");
            	$data2 = $this->db->GetList("SELECT m.uid,m.id,m.shopid,m.create_time,m.amount,n.shouhuoren FROM `@#_exchange_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.addr_id=n.id WHERE n.mobile='$mobile' AND m.addr_id!='0' AND m.create_time>='$time1' AND m.create_time<'$time2' order by m.create_time desc");

                $data3 = $this->db->GetList("SELECT m.uid,m.id,m.shop_id,m.dizhi_time FROM `@#_kh_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id = n.id WHERE n.mobile = '$mobile' AND m.dizhi_id != '0' AND m.dizhi_time >= '$time1' AND m.dizhi_time<'$time2' order by m.dizhi_time desc");

                foreach ($data as $key => $val) {
                    $shopid = $val['shopid'];
                    $sid = $val['sid'];
                    $str2 = $this->db->GetOne("SELECT `money`,`cateid`,`str3` FROM `@#_shoplist` WHERE `id` = '$shopid'");

                    $str3 = $this->db->GetOne("SELECT `second_money` FROM `@#_second_money` WHERE `sid` = '$sid'");

                    $data[$key]['second_money'] = $str3['second_money'];
                  
                    if($str2['cateid'] == '170'){
                        $data[$key]['money'] = $str2['str3'];
                    }else{
                        $data[$key]['money'] = $str2['money'];
                    }
                    
                   
                }
            	
              	if(!empty($data2)){
                	foreach($data2 as $k=>$v){
                      $us = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$v[uid]'");
                      $shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$v[shopid]'");
                      $data2[$k]['username'] = $us['username'];
                      $data2[$k]['shopname'] = $shop['title'];
                      $data2[$k]['shopqishu'] = '(兑换商品)';
                      $data2[$k]['dizhi_time'] = $v['create_time'];
                      $data2[$k]['money'] = ($shop['score']/100)*$v['amount'];
                      $data2[$k]['second_money'] = $shop['second_money']*$v['amount'];
                      $data2[$k]['amount'] = $v['amount'];
                      $data2[$k]['dh'] = '1';
                	}
                  	$data = array_merge_recursive($data,$data2);
                }

                // if(!empty($data3)){
                //     foreach($data3 as $k=>$v){
                //       $shop2 = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$v[shop_id]'");
                //       $data3[$k]['username'] = get_user_name($v['uid']);
                //       $data3[$k]['shopname'] = $shop2['name'];
                //       $data3[$k]['shopqishu'] = '(活动商品)';
                //       $data3[$k]['dizhi_time'] = $v['dizhi_time'];
                //       $data3[$k]['money'] = $shop2['price'];
                //       $data3[$k]['hd'] = '1';
                //     }
                //     $data = array_merge_recursive($data,$data3);
                // }
                
            }else{
                $time = strtotime(date('Y-m-d',time()))-86400;
                $data = $this->db->GetList("SELECT m.uid,m.id,m.username,m.dizhi_time,m.shopname,m.shopqishu,m.shopid,k.cateid,n.shouhuoren,k.sid,k.q_end_time FROM `@#_member_go_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id=n.id  LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE n.mobile='$mobile' AND m.huode!='0' AND m.dizhi_id!='0' AND m.dizhi_time>='$time' order by m.dizhi_time desc");
            	$data2 = $this->db->GetList("SELECT m.uid,m.id,m.shopid,m.create_time,m.amount,n.shouhuoren FROM `@#_exchange_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.addr_id=n.id WHERE n.mobile='$mobile' AND m.addr_id!='0' AND m.create_time>='$time' order by m.create_time desc");

                // $data3 = $this->db->GetList("SELECT m.uid,m.id,m.shop_id,m.dizhi_time FROM `@#_kh_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id = n.id WHERE n.mobile = '$mobile' AND m.dizhi_id != '0' AND m.dizhi_time >= '$time' order by m.dizhi_time desc");

                foreach ($data as $key => $val) {
                    $shopid = $val['shopid'];
                    $sid = $val['sid'];
                    $str2 = $this->db->GetOne("SELECT `money`,`cateid`,`str3` FROM `@#_shoplist` WHERE `id` = '$shopid'");

                    $str3 = $this->db->GetOne("SELECT `second_money` FROM `@#_second_money` WHERE `sid` = '$sid'");

                    if(!$str3){
                        $data[$key]['second_money'] = '暂无';
                    }else{
                        $data[$key]['second_money'] = $str3['second_money'];                        
                    }
                  
                    if($str2['cateid'] == '170'){
                        $data[$key]['money'] = $str2['str3'];
                    }else{
                        $data[$key]['money'] = $str2['money'];
                    }
                    
                   
                }
            	
              	if(!empty($data2)){
                	foreach($data2 as $k=>$v){
                      $us = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$v[uid]'");
                      $shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$v[shopid]'");
                      $data2[$k]['username'] = $us['username'];
                      $data2[$k]['shopname'] = $shop['title'];
                      $data2[$k]['shopqishu'] = '(兑换商品)';
                      $data2[$k]['dizhi_time'] = $v['create_time'];
                      $data2[$k]['money'] = ($shop['score']/100)*$v['amount'];
                      $data2[$k]['second_money'] = $shop['second_money']*$v['amount'];
                      $data2[$k]['amount'] = $v['amount'];
                      $data2[$k]['dh'] = '1';
                	}
                  	$data = array_merge_recursive($data,$data2);
                }


                // if(!empty($data3)){
                //     foreach($data3 as $k=>$v){
                //       $shop2 = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$v[shop_id]'");
                //       $data3[$k]['username'] = get_user_name($v['uid']);
                //       $data3[$k]['shopname'] = $shop2['name'];
                //       $data3[$k]['shopqishu'] = '(活动商品)';
                //       $data3[$k]['dizhi_time'] = $v['dizhi_time'];
                //       $data3[$k]['money'] = $shop2['price'];
                //       $data3[$k]['hd'] = '1';
                //     }
                //     $data = array_merge_recursive($data,$data3);
                // }
            }

            
        }else{
            $user = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
            $mobile = $user['mobile'];
            $time = strtotime(date('Y-m-d',time()));
            $data = $this->db->GetList("SELECT m.uid,m.id,m.username,m.dizhi_time,m.shopname,m.shopqishu,m.shopid,k.cateid,n.shouhuoren,k.sid,k.q_end_time FROM `@#_member_go_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id=n.id  LEFT JOIN `@#_shoplist` AS k ON m.shopid=k.id WHERE n.mobile='$mobile' AND m.huode!='0' AND m.dizhi_id!='0' AND m.dizhi_time>='$time' order by m.dizhi_time desc");
			$data2 = $this->db->GetList("SELECT m.uid,m.id,m.shopid,m.create_time,m.amount,n.shouhuoren FROM `@#_exchange_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.addr_id=n.id WHERE n.mobile='$mobile' AND m.addr_id!='0' AND m.create_time>='$time' order by m.create_time desc");

            //$data3 = $this->db->GetList("SELECT m.uid,m.id,m.shop_id,m.dizhi_time FROM `@#_kh_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id = n.id WHERE n.mobile = '$mobile' AND m.dizhi_id != '0' AND m.dizhi_time >= '$time' order by m.dizhi_time desc");

            foreach ($data as $key => $val) {
                $shopid = $val['shopid'];
                $sid = $val['sid'];
                $str2 = $this->db->GetOne("SELECT `money`,`cateid`,`str3` FROM `@#_shoplist` WHERE `id` = '$shopid'");

                $str3 = $this->db->GetOne("SELECT `second_money` FROM `@#_second_money` WHERE `sid` = '$sid'");

                $data[$key]['second_money'] = $str3['second_money'];
              
                if($str2['cateid'] == '170'){
                    $data[$key]['money'] = $str2['str3'];
                }else{
                    $data[$key]['money'] = $str2['money'];
                }
                
               
            }
            	
            if(!empty($data2)){
              foreach($data2 as $k=>$v){
                $us = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$v[uid]'");
                $shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$v[shopid]'");
                $data2[$k]['username'] = $us['username'];
                $data2[$k]['shopname'] = $shop['title'];
                $data2[$k]['shopqishu'] = '(兑换商品)';
                $data2[$k]['dizhi_time'] = $v['create_time'];
                $data2[$k]['money'] = ($shop['score']/100)*$v['amount'];
                $data2[$k]['second_money'] = $shop['second_money']*$v['amount'];
                $data2[$k]['amount'] = $v['amount'];
                $data2[$k]['dh'] = '1';
              }
              $data = array_merge_recursive($data,$data2);
            }

            //  if(!empty($data3)){
            //     foreach($data3 as $k=>$v){
            //       $shop2 = $this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$v[shop_id]'");
            //       $data3[$k]['username'] = get_user_name($v['uid']);
            //       $data3[$k]['shopname'] = $shop2['name'];
            //       $data3[$k]['shopqishu'] = '(活动商品)';
            //       $data3[$k]['dizhi_time'] = $v['dizhi_time'];
            //       $data3[$k]['money'] = $shop2['price'];
            //       $data3[$k]['hd'] = '1';
            //     }
            //     $data = array_merge_recursive($data,$data3);
            // }

        }

        $second_money_total = 0;
        foreach ($data as $key => $val) {
            $user_id = $val['uid'];
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$user_id'");
            $data[$key]['phone'] = $str['mobile'];
            $second_money_total += $val['second_money'];
        }

        $data = $this->multi_array_sort($data,'dizhi_time',SORT_DESC);
        include templates("mobile/vip","html9");
    }

    function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){ 
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

    function html10(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        include templates("mobile/vip","html10");
    }

    function html10_post(){
        $num = trim($_POST['num']);
        $data = $this->db->GetOne("SELECT * FROM `@#_member_go_record` WHERE `id` = '$num' limit 1");
        if($data){
            $str['shopname'] = $data['shopname'];
            $str['qishu'] = $data['shopqishu'];
            $str['dizhi_time'] = date('Y-m-d H:i:s',$data['dizhi_time']);

            $uid = $data['uid'];
            $dizhi_id = $data['dizhi_id'];

            $arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
            $str['name'] = $arr['username'];
            $str['mobile'] = substr($arr['mobile'],0,3)."****".substr($arr['mobile'], 7);

            $drr = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$dizhi_id'");
            $str['phone'] = $drr['mobile'];
            echo json_encode($str);
        }else{  
            echo 0;
        }
    }

    function user_sure(){
        $user = $_POST['user'];

        $mobile = $this->mobile;
        $crr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile'");

        if($crr['zhuan_status'] == 0){
            echo 3;
        }else{
            //$flag = _checkmobile($user);
            //if($flag){
                $data = $this->db->GetOne("SELECT `uid`,`username`,`mobile`,`vip` FROM `@#_member` WHERE (`mobile` = '$user' or `uid` = '$user') and `auto_user` = 0 ");
          		
                if($data){
                    $data['mobile'] = substr_replace($data['mobile'],'****',3,4);
                    if($data['vip'] == 1){
                        echo 2; // 不是普通用户
                    }else{
                        echo json_encode($data);
                    }
                }else{
                    echo 0; //不存在用户
                }
            //}else{
             //   echo 1;  //号码不正确
            //}
        }
    }
    function vip_sure(){
        $user = $_POST['user'];
        //$flag = _checkmobile($user);
        //if($flag){
            $data = $this->db->GetOne("SELECT `uid`,`username`,`mobile`,`vip` FROM `@#_member` WHERE (`mobile` = '$user' or `uid` = '$user') and `auto_user` = 0 ");
          	$data['mobile'] = substr_replace($data['mobile'],'****',3,4);
            if($data){
                if($data['vip'] == 0){
                    echo 2; // 不是vip用户
                }else{
                    echo json_encode($data);
                }
            }else{
                echo 0; //不存在用户
            }
        // }else{
        //     echo 1;  //号码不正确
        // }
    }

    function logout(){
        _setcookie("vip", '');
        header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
    }

    function edit_pwd(){
        $pwd = md5(trim($_POST['pwd']));
        $new_pwd = md5(trim($_POST['new_pwd']));
        $uid = $this->userinfo['uid'];
        $data = $this->db->GetOne("SELECT * FROM `@#_member_vip` WHERE `uid` = '$uid' AND `zhuan_password` = '$pwd'");
        if($data){
            $flag = $this->db->Query("UPDATE `@#_member_vip` SET `zhuan_password` = '$new_pwd' WHERE `uid` = '$uid'");
            if($flag){
                _setcookie("vip",'');
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 0;
        }
    }

    function zhuanzhang_success(){
        if(empty($this->mobile)){
            header("Location: ".WEB_PATH."/mobile/abcd/login");exit;
        }
        $uid2 = $this->segment(4);
        $money2 = $this->segment(5);
        $t2 = $this->segment(6);
        
        $data = $this->db->GetOne("SELECT * FROM `@#_member_account` WHERE `uid` = '$uid2' AND `money` = '$money2' AND `time` = '$t2'");
        if($data){
            $username_data = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid2'");
            if(empty($username_data['username'])){
                $username = substr($username_data['mobile'] ,0 ,3)."****".substr($username_data['mobile'], 7);
            }else{
                $username = $username_data['username'];
            }
            $money = $money2;
            $t = $t2;
        }
        include templates("mobile/vip","zhuanzhang_success");
    }
}
