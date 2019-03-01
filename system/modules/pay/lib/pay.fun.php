<?php
System::load_sys_fun("send");
/*

*   生成购买的参与码

*	user_num 		@生成个数

*	shopinfo		@商品信息

*	ret_data		@返回信息

*/

function pay_get_shop_codes($user_num = 1, $shopinfo = null, &$ret_data = null)
{


    $db = System::load_sys_class("model");

    $ret_data['query'] = true;

    $table = '@#_' . $shopinfo['codes_table'];

    $codes_arr = array();

    $codes_one = $db->GetOne("select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' order by `s_cid` DESC  LIMIT 1 for update");

    $codes_arr[$codes_one['s_cid']] = $codes_one;

    $codes_count_len = $codes_arr[$codes_one['s_cid']]['s_len'];


    if ($codes_count_len < $user_num && $codes_one['s_cid'] > 1) {

        for ($i = $codes_one['s_cid'] - 1; $i >= 1; $i--):

            $codes_arr[$i] = $db->GetOne("select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' and `s_cid` = '$i'  LIMIT 1 for update");

            $codes_count_len += $codes_arr[$i]['s_len'];

            if ($codes_count_len > $user_num) break;

        endfor;

    }


    if ($codes_count_len < $user_num) $user_num = $codes_count_len;


    $ret_data['user_code'] = '';

    $ret_data['user_code_len'] = 0;


    foreach ($codes_arr as $icodes) {

        $u_num = $user_num;

        $icodes['s_codes'] = unserialize($icodes['s_codes']);

        $code_tmp_arr = array_slice($icodes['s_codes'], 0, $u_num);

        $ret_data['user_code'] .= implode(',', $code_tmp_arr);

        $code_tmp_arr_len = count($code_tmp_arr);


        if ($code_tmp_arr_len < $u_num) {

            $ret_data['user_code'] .= ',';

        }


        $icodes['s_codes'] = array_slice($icodes['s_codes'], $u_num, count($icodes['s_codes']));

        $icode_sub = count($icodes['s_codes']);

        $icodes['s_codes'] = serialize($icodes['s_codes']);


        if (!$icode_sub) {

            $query = $db->Query("UPDATE `$table` SET `s_cid` = '0',`s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'");

            if (!$query) $ret_data['query'] = false;

        } else {

            $query = $db->Query("UPDATE `$table` SET `s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'");

            if (!$query) $ret_data['query'] = false;

        }

        $ret_data['user_code_len'] += $code_tmp_arr_len;

        $user_num = $user_num - $code_tmp_arr_len;


    }


}


//生成订单号

function pay_get_dingdan_code($dingdanzhui = '')
{
    return $dingdanzhui . time() . substr(microtime(), 2, 6) . rand(0, 9);

}


/*

	揭晓与插入商品

	@shop   商品数据

*/


function pay_insert_shop_x($shop = '', $type = '')
{


    $g_c_x = System::load_app_config("get_code_x", '', "pay");

    if (is_array($g_c_x) && isset($g_c_x['class'])) {

        $gcx_db = System::load_app_class($g_c_x['class'], "pay");

    } else {

        $g_c_x = array("class" => "tocode");

        $gcx_db = System::load_app_class($g_c_x['class'], "pay");

    }


    $gcx_db->config($shop, $type);

    $gcx_db->get_run_tocode();

    $ret_data = $gcx_db->returns();


}


/*

	揭晓与插入商品

	@shop   商品数据

*/

function pay_insert_shop($shop = '', $type = '')
{


    $time = sprintf("%.3f", microtime(true) + (int)System::load_sys_config('system', 'goods_end_time'));

    $db = System::load_sys_class("model");

    if ($shop['xsjx_time'] != '0') {

        return $db->Query("UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,	`shenyurenshu` = '0' where `id` = '$shop[id]'");

    }

    $tocode = System::load_app_class("tocode", "pay");

    $tocode->shop = $shop;

    $time_end = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' order by id desc limit 1");

    //获取商品的cateid
    $str_cate = $db->GetOne("SELECT `cateid` FROM `@#_shoplist` WHERE `id` = '$shop[id]' order by `id` desc limit 1");
    $cate_id = $str_cate['cateid'];
    $time_flag = time() - strtotime(date("Y-m-d"),time());
    //透明购 时间在02:00~10:00不开奖
    if($cate_id == '173'){

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
        $gtimes = (int)System::load_sys_config('system', 'goods_end_time');

        if ($gtimes == 0 || $gtimes == 1) {

            $q_showtime = 'N';

        } else {

            $q_showtime = 'Y';

        }

        $tocode->run_tocode($time_end['time'], 100, $shop['canyurenshu'], 0, $shop);

        $content = addslashes($tocode->go_content);

        $counttime = $tocode->count_time;

        $query = true;
        $time_tmg = time();
        $str2 = sprintf("%.3f", microtime(true) + 10);
        $q = $db->Query( "UPDATE `@#_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0',`q_end_time` = '$str2',`q_content` = '$content',`q_counttime` = '$counttime',`v` = '$time',`q_showtime` = '$q_showtime' where `id` = '$shop[id]'");
        $q_2 = $db->Query("INSERT INTO `@#_tmg` (`shopid`,`ssc_times`,`end_time`)VALUES('$shop[id]','$expect','$str')");
        $sss_id = $shop['id'];
        //file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$sss_id}:{$q}:{$q_2}:{$expect}:{$str}\n", FILE_APPEND);
        if (!$q || !$q_2) $query = false;
    }else{
    
        $tocode->run_tocode($time_end['time'], 100, $shop['canyurenshu'], 0, $shop);


        $code = $tocode->go_code;


        $content = addslashes($tocode->go_content);

        $counttime = $tocode->count_time;

        //20140901新增，判断是否指定中奖//

        $shopid123 = $shop['id'];
        
        
        $zhiding456_status = false;
        $zhiding456 = $db->GetOne("SELECT * from `@#_appoint` WHERE `shopid` ='$shopid123' LIMIT 1");
        if (!empty($zhiding456)) {
            $zhiding456_status = true;
            $aabbcc = $zhiding456['userid'];
         	// sendWatchMail("指定中奖：中奖uid".$aabbcc." 中奖商品id：".$shopid123); 
          	$appuser = $db->GetOne("SELECT * from `@#_member` WHERE `uid` ='$aabbcc' LIMIT 1");
          	if($appuser['auto_user']==0){
			sendWatchMail("外部号指定中奖：中奖uid".$aabbcc." 中奖商品id：".$shopid123);
            		$zhiding456_status = false;
            	}
          	if($zhiding456['aid']==3){
                //$zhiding456_status = true;
            }
        }
      
        if( $zhiding456_status ){

            $shop['zhiding'] = $aabbcc;

            $ex_info=$db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `uid`='{$shop['zhiding']}'");

            $data_code = $db->GetList("SELECT * FROM `@#_member_go_record` WHERE `shopid` = '$shop[id]' and `type` = '0'");
            if($data_code){
                foreach ($data_code as $key => $val) {
                    $str_code[] = $val['goucode'];
                }
                $arr_code = implode(',', $str_code);
                $crr_code = explode(',', $arr_code);
                $code_flag = $code;
                $i = 0;
                while (1) {
                    $code_flag++;
                    $i++;
                    if(!in_array($code_flag, $crr_code)){
                        $chazhi = $code_flag - $code;
                        break;
                    }
                    if($i > 99999){
                        break;
                    }
                }
            }

            $ex_code=explode(",",$ex_info['goucode']);

            $ex_count=count($ex_code);

            $ex_rand=rand(0,$ex_count-1);

            if($ex_code[$ex_rand]){
                if(empty($chazhi)){
                    $chazhi=$ex_code[$ex_rand]-$code;
                    $code=$ex_code[$ex_rand];
                }else{
                    $code = $code_flag;
                }

                if($chazhi>0)$counttime=$counttime+$chazhi;

                else $counttime=$counttime-abs($chazhi);

                
                //本来的中奖码对应的记录
                $tempinfo = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `goucode` LIKE  '%$code%'");

                //替换中奖纪录

                $str=str_replace($code,$ex_code[0],$tempinfo['goucode']);

                $db->Query("update `@#_member_go_record` set goucode='$str' where id='{$tempinfo['id']}'");

                //将系统原来的中奖吗对应的购买记录换成指定中奖会员购买的code

                $str2=str_replace($ex_code[0],$code,$ex_info['goucode']);
    			
                
              
              	if($zhiding456_status){
                	$db->Query("update `@#_member_go_record` set goucode='$str2' where id='{$ex_info['id']}'");
                }
                //$db->Query("update `@#_member_go_record` set goucode='$str2' where id='{$ex_info['id']}'");

                //将指定中奖会员的购买记录中的code换成系统计算出来的中奖吗

                



                //添加时间校准

                if(!empty($chazhi)){

                    $last_info=$db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' order by id desc limit 1");

                    $time_t_str = str_replace('.','',$last_info['time']);

                    $time_str = bcadd($time_t_str,$chazhi,0);

                    $time_arr = str_split($time_str,10);

                    $str_t_time = $time_arr[0].'.'.$time_arr[1];
    				
                  
                    $db->Query("UPDATE `@#_member_go_record` SET `time`='$str_t_time' where `id` = '{$last_info['id']}'");

    			
                        $tocode = System::load_app_class("tocode","pay");

                        $tocode->shop = $shop;

                        $tocode->run_tocode($str_t_time,100,$shop['canyurenshu'],0,$shop);

                        $content = addslashes($tocode->go_content);
                  

            

                }

            }

        }


        $u_go_info = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `goucode` LIKE  '%$code%' for update");

        $u_info = $db->GetOne("select uid,username,email,mobile,img from `@#_member` where `uid` = '$u_go_info[uid]'");


        //更新商品

        $query = true;

        if ($u_info) {

            $u_info['username'] = _htmtocode($u_info['username']);

            $q_user = serialize($u_info);

            $gtimes = (int)System::load_sys_config('system', 'goods_end_time');

            if ($gtimes == 0 || $gtimes == 1) {

                $q_showtime = 'N';

            } else {

                $q_showtime = 'Y';

            }

		// sendWatchMail("更新中奖3：中奖uid：".$u_info['uid']."  商品shopid：".$shop['id']."\\n");
            $sqlss = "UPDATE `@#_shoplist` SET

    							`canyurenshu`=`zongrenshu`,

    							`shenyurenshu` = '0',

    							`q_uid` = '$u_info[uid]',

    							`q_user` = '$q_user',

    							`q_user_code` = '$code',

    							`q_content`	= '$content',

    							`q_counttime` ='$counttime',

    							`q_end_time` = '$time',
    							
    							`v` = '$time',

    							`q_showtime` = '$q_showtime'

    							 where `id` = '$shop[id]'";


            $q = $db->Query($sqlss);
            if (!$q) $query = false;

            $date = date("Y-m-d");
            $path = '/www/wwwroot/csthsc/system/caches/cs/' . $date . '.php';
            $date = date("Y-m-d H:i:s");
            $html = $date . "------>" . $u_info['uid'] . "--" . $shop['id'];
            $html .= "\n";
            file_put_contents($path, $html, FILE_APPEND);

            //如果没有中奖短信就强制在发送一遍--E
            if ($q) {

                $q = $db->Query("UPDATE `@#_member_go_record` SET `huode` = '$code' where `id` = '$u_go_info[id]' and `code` = '$u_go_info[code]' and `uid` = '$u_go_info[uid]' and `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]'");

                $_record_num = $db->GetOne("select COUNT(*) as c from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `huode` != 0  for update");
               
                if(intval($_record_num['c']) > 1){
                    $q = false;
                }

                if (!$q) {

                    $query = false;

                } else {
                    $type = System::load_sys_config("send", "type");


                    $timed = time();
                    $shop_1 = $db->GetOne("select * from `@#_shoplist` where `id` = '$shop[id]'  LIMIT 1");
                    $user_1 = $db->GetOne("select * from `@#_member` where `uid` = ".$shop_1['q_uid']);
                    if ($user_1['auto_user'] == 0 && !empty($user_1['mobile'])) {
                        $db->Query("INSERT INTO `@#_lottery_list` (`shopid`, `qishu` ,`uid`, `mobile`, `status`, `time`) VALUES ('$shop[id]','$shop[qishu]', '$shop_1[q_uid]', '$user_1[mobile]', '1', '$timed')");
                    }

                   
                }

            } else {

                $query = false;

            }

        } else {

            $query = false;

        }
    }


    /******************************/

    if($shop['cateid'] == '170'){
        $www = $db->GetOne("SELECT * FROM `@#_qf_money` WHERE `shopid` = '$shop[id]'");
        if(!$www){
            $xxx = $db->GetList("SELECT * FROM `@#_member_go_record` WHERE `shopid` = '$shop[id]'");
            foreach ($xxx as $key => $val) {
                $uid = $val['uid'];
                $content = "(第".$val['shopqishu']."期)".$val['shopname']."--不中全返";
                $time = time();
                if($val['huode'] != 0){
                    $money = ($val['moneycount']/$val['gonumber'])*($val['gonumber']-1);
                }else{
                    $money = $val['moneycount'];
                }
                $time = time();
                $gm_time = $val['time'];
                $ccc = $db->Query("INSERT INTO `@#_qf_money` (`uid`,`money`,`gm_time`,`create_time`,`shopid`)VALUES('$uid','$money','$gm_time','$time','$shop[id]')");
                if($ccc){
                    $db->Query("INSERT INTO `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES('$uid','-1','账户','$content','-$money','$time')");
                }
            }
        }
    }

    /*新建*/

    if ($query) {
		$file = "/www/wwwroot/csthsc/lock/{$shop['sid']}.lock";
        $fp = fopen($file , 'a+');    
        if(flock($fp , LOCK_EX)){    
        	$file_text = fread($fp, filesize($file));
        	$file_text_arr = explode("\n",$file_text);
            for ($i = 1; $i < 3; $i++) {
              //最大期数为2的话这个地方不适用
                  if(intval($file_text_arr[count($file_text_arr) - $i]) == intval($shop['qishu'])){
            		return $query;
                  };
            }
            fwrite($fp , "{$shop['qishu']}\n");    
        }else{
            return $query;
        }
      
      
      
        //$db->GetOne("SELECT * FROM `@#_shoplist` where `id`='$shop[id]' for update");

        if ($shop['qishu'] >= $shop['maxqishu']) {
            $auto = $db->GetOne("select * from `@#_shoplist` where `sid` = '$shop[sid]' order by `id` DESC LIMIT 1");

            System::load_app_fun("content",G_ADMIN_DIR);

            $autointall = auto_add_shop($auto,false);
			
            if(!$autointall){
              flock($fp , LOCK_UN); 
              return $query;
            	 
            }
        }
        
        if ($shop['qishu'] < $shop['maxqishu']) {

            $maxinfo = $db->GetOne("select * from `@#_shoplist` where `sid` = '$shop[sid]' order by `id` DESC LIMIT 1");

            if (!$maxinfo) {

                $maxinfo = array("qishu" => $shop['qishu']);

            }

            System::load_app_fun("content", G_ADMIN_DIR);

            //file_put_contents("/www/wwwroot/csthsc/sql_log/pay_insert_shop.log", "{$shop['id']}:{$shop['zongrenshu']}:{$shop['canyurenshu']}\n", FILE_APPEND);
            //file_put_contents("/www/wwwroot/m.xx.com/sql_log/pay_insert_shop.log", "{$shop['id']}:{$maxinfo['id']}:{$maxinfo['zongrenshu']}:{$maxinfo['canyurenshu']}\n", FILE_APPEND);
            if ($shop['id'] != $maxinfo['id']) {
                //file_put_contents("/www/wwwroot/m.xx.com/sql_log/pay_insert_shop_error.log", "{$shop['id']}:{$maxinfo['id']}:{$maxinfo['zongrenshu']}:{$maxinfo['canyurenshu']}\n", FILE_APPEND);
             	flock($fp , LOCK_UN);    
                return $query;
            }
            // if ($shop['maxqishu'] == 33333 || $shop['maxqishu'] == 9999) {

            //     $autointall = auto_add_shop_2($maxinfo,false);

            //     if(!$autointall) return $query;
            // }else{
            //     // $intall = content_add_shop_install($maxinfo,false);

            //     // if(!$intall) return $query;
            // }
            $intall = content_add_shop_install($maxinfo, false);

           if (!$intall){
             flock($fp , LOCK_UN);  
             return $query;
           	   
           }
        }
         
    	flock($fp , LOCK_UN);  

    }

    return $query;

}


/*

	参与基金

	go_number @参与人次

*/

function pay_go_fund($go_number = null)
{

    if (!$go_number) return true;

    $db = System::load_sys_class("model");

    $fund = $db->GetOne("select * from `@#_fund` where 1");

    if ($fund && $fund['fund_off']) {

        $money = $fund['fund_money'] * $go_number + $fund['fund_count_money'];

        return $db->Query("UPDATE `@#_fund` SET `fund_count_money` = '$money'");

    } else {

        return true;

    }

}


/*

	用户佣金

	uid 		用户id

	dingdancode	@订单号

*/

function pay_go_yongjin($uid = null, $dingdancode = null)
{

    if (!$uid || !$dingdancode) return true;

    $db = System::load_sys_class("model");
    $time = time();

    $config = System::load_app_config("user_fufen", '', 'member');//积分/经验/佣金

    $yesyaoqing = $db->GetOne("SELECT `yaoqing` FROM `@#_member` WHERE `uid`='$uid'");

    if ($yesyaoqing['yaoqing']) {

        $yongjin = $config['fufen_yongjin']; //每一元返回的佣金

    } else {

        return true;

    }

    $yongjin = floatval(substr(sprintf("%.3f", $yongjin), 0, -1));

    $gorecode = $db->GetList("SELECT * FROM `@#_member_go_record` WHERE `code`='$dingdancode'");

    foreach ($gorecode as $val) {

        $y_money = $val['moneycount'] * $yongjin;

        $content = "(第" . $val['shopqishu'] . "期)" . $val['shopname'];

        $db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`shopid`,`money`,`ygmoney`,`time`)VALUES('$uid','1','$content','$val[shopid]','$y_money','$val[moneycount]','$time' )");

    }


}

/*   透明购更新商品   */
function tmg_pay_shop($shop = '',$opencode){
    $tocode = System::load_app_class("tocode", "pay");
    $db = System::load_sys_class("model");

    $tocode->shop = $shop;

    $time_end = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' order by id desc limit 1");
    
    $tocode->run_tocode($time_end['time'], 100, $shop['canyurenshu'], $opencode, $shop);

    $code = $tocode->go_code;

    $content = addslashes($tocode->go_content);

    $counttime = $tocode->count_time;

    $shopid123 = $shop['id'];
  
    /////////////////


    $u_go_info = $db->GetOne("select * from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `goucode` LIKE  '%$code%' for update");

    $u_info = $db->GetOne("select uid,username,email,mobile,img from `@#_member` where `uid` = '$u_go_info[uid]'");

    //更新商品

    $query = true;

    if ($u_info) {

        $u_info['username'] = _htmtocode($u_info['username']);

        $q_user = serialize($u_info);

        $gtimes = (int)System::load_sys_config('system', 'goods_end_time');

        if ($gtimes == 0 || $gtimes == 1) {

            $q_showtime = 'N';

        } else {

            $q_showtime = 'Y';

        }
	
	//sendWatchMail("更新中奖4：中奖uid：".$u_info['uid']."  商品shopid：".$shop['id']."\\n");

        $sqlss = "UPDATE `@#_shoplist` SET

                            `q_uid` = '$u_info[uid]',

                            `q_user` = '$q_user',

                            `q_user_code` = '$code',

                            `q_counttime` ='$counttime'

                             where `id` = '$shop[id]'";


        $q = $db->Query($sqlss);

        if (!$q) $query = false;

        //如果没有中奖短信就强制在发送一遍--E
        if ($q) {

            $q = $db->Query("UPDATE `@#_member_go_record` SET `huode` = '$code' where `id` = '$u_go_info[id]' and `code` = '$u_go_info[code]' and `uid` = '$u_go_info[uid]' and `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]'");

            $_record_num = $db->GetOne("select COUNT(*) as c from `@#_member_go_record` where `shopid` = '$shop[id]' and `shopqishu` = '$shop[qishu]' and `huode` != 0  for update");
           
            if(intval($_record_num['c']) > 1){
                $q = false;
            }

            if (!$q) {

                $query = false;

            } else {
                $type = System::load_sys_config("send", "type");

                $timed = time();
                $shop_1 = $db->GetOne("select * from `@#_shoplist` where `id` = '$shop[id]'  LIMIT 1");
                $user_1 = $db->GetOne("select * from `@#_member` where `uid` = ".$shop_1['q_uid']);
                if ($user_1['auto_user'] == 0 && !empty($user_1['mobile'])) {
                    $db->Query("INSERT INTO `@#_lottery_list` (`shopid`, `qishu` ,`uid`, `mobile`, `status`, `time`) VALUES ('$shop[id]','$shop[qishu]', '$shop_1[q_uid]', '$user_1[mobile]', '1', '$timed')");
                }
            }

        } else {

            $query = false;

        }

    } else {

        $query = false;

    }
    return $query;
}

?>



