<?php 
defined('G_IN_SYSTEM')or exit('no');
ignore_user_abort(TRUE);
set_time_limit(0); 
System::load_sys_fun("send");
System::load_sys_fun("user");
System::load_app_class('admin',G_ADMIN_DIR,'no');

class phone extends admin {

	private $db;

	public function __construct(){		

		parent::__construct();		

		$this->db=System::load_sys_class('model');		

		$this->ment=array();

	}

	public function order(){
		$where = $this->segment(4);

		$brr = $this->db->GetOne("select bind_phone from `@#_member` where `uid` = ".MK_UID);
		$arr = explode('，', $brr['bind_phone']);
		
		if(!empty($_POST['phone'])){
			$mobile = trim($_POST['phone']);
			$hhh = $mobile."(".date("Y-m-d",strtotime('-1 day')).")";
			setcookie("exel_mobile",$hhh);
			$bp = $this->db->GetOne("select * from `@#_member` where `uid` = ".MK_UID);
			$phone = $bp['bind_phone'];
			if (strpos($phone,$mobile) !== false) {
				//$limit = "0 , 9";
				if(!empty($_POST['posttime1'])){
					$posttime1 = strtotime($_POST['posttime1']);
				}else{
					$posttime1 = 0;
				}
				if(!empty($_POST['posttime2'])){
					$posttime2 = strtotime($_POST['posttime2']);
				}else{
					$posttime2 = time();
				}
				$user = $this->db->GetList("select * from `@#_member_dizhi` where `mobile` = '$mobile'");

				if (empty($user)) {
					_message("找不到有填写此号码的收货地址");
					exit();
				}
				foreach ($user as $k => $v) {
					$user_record[$k] = $this->db->GetList("SELECT m.*,n.cateid from `@#_member_go_record` as m left join `@#_shoplist` as n on m.shopid = n.id where m.huode!=0 and  m.dizhi_time>'$posttime1' and m.dizhi_time<'$posttime2' and  m.dizhi_id = ".$v['id']." order by m.dizhi_time desc ");
				}
				$data = call_user_func_array('array_merge', $user_record);
				$data = $this->multi_array_sort($data,'dizhi_time',SORT_DESC);

				$data2 = $this->db->GetList("SELECT m.uid,m.id,m.shopid,m.create_time,m.amount,k.second_money,m.addr_id FROM `@#_exchange_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.addr_id=n.id LEFT JOIN `@#_shoplist_exchange` AS k ON m.shopid = k.id WHERE n.mobile='$mobile' AND m.addr_id!='0' AND m.create_time>'$posttime1' AND m.create_time < '$posttime2'  order by m.create_time desc");

				$data3 = $this->db->GetList("SELECT m.uid,m.id,m.shop_id,m.dizhi_time,k.name,k.price,k.second_money,m.dizhi_id FROM `@#_kh_record` AS m LEFT JOIN `@#_member_dizhi` AS n ON m.dizhi_id = n.id LEFT JOIN `@#_kh_shop` AS k ON m.shop_id = k.id WHERE n.mobile = '$mobile' AND m.dizhi_id != '0' AND m.dizhi_time > '$posttime1' AND m.dizhi_time < '$posttime2' order by m.dizhi_time desc");
            	
              	if(!empty($data2)){
                	foreach($data2 as $k=>$v){
                      $us = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$v[uid]'");
                      $shop = $this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$v[shopid]'");
                      $data2[$k]['username'] = get_user_name($v['uid']);
                      $data2[$k]['shopname'] = $shop['title'];
                      $data2[$k]['shopqishu'] = '(兑换商品)';
                      $data2[$k]['dizhi_time'] = $v['create_time'];
                      $data2[$k]['dizhi_id'] = $v['addr_id'];
                      $data2[$k]['money'] = ($shop['score']/100)*$v['amount'];
                      $data2[$k]['amount'] = $v['amount'];
                      $data2[$k]['second_money'] = $v['second_money']*$v['amount'];
                      $data2[$k]['dh'] = '1';
                	}
                  	$data = array_merge_recursive($data,$data2);
                }

                if(!empty($data3)){
                	foreach($data3 as $k=>$v){
                      $data3[$k]['username'] = get_user_name($v['uid']);
                      $data3[$k]['shopname'] = $v['name'];
                      $data3[$k]['shopqishu'] = '(活动商品)';
                      $data3[$k]['dizhi_time'] = $v['dizhi_time'];
                      $data3[$k]['dizhi_id'] = $v['dizhi_id'];
                      $data3[$k]['money'] = $v['price'];
                      $data3[$k]['second_money'] = $v['second_money'];
                      $data3[$k]['hd'] = '1';
                	}
                  	$data = array_merge_recursive($data,$data3);
                }
                $data = $this->multi_array_sort($data,'dizhi_time',SORT_DESC);
				$count = count($data);
				//$user_record = $this->page_array($count,1,$data,0);
				
				//$user_record = $this->db->GetList("select * from `@#_member_go_record` where huode!=0 and  `uid` = ".$user['uid']." and status like '%已发货%' order by time desc limit ".$limit);
				//$user_record_count = $mysql_model->GetList("select * from `@#_member_go_record` where huode!=0 and  `uid` = ".$user['uid']." and status like '%已发货%' order by time desc ");

				$keyword = $mobile;
				foreach ($data as $k => $v) {
					//$list[$k]['url'] = "onclick=\"location.href= 'http://m.yyygcs.vip/index.php/mobile/user/buyDetail/".$v['shopid']."'\"";
					$list[$k]['id'] = $v['id'];
					$list[$k]['cateid'] = $v['cateid'];
					$list[$k]['username'] = $v['username'];
					$list[$k]['shopid'] = $v['shopid'];
					$list[$k]['shopname'] = $v['shopname'];
					if($v['dh'] == 1){
						$list[$k]['thumb'] = '';
						$list[$k]['price'] = $v['money'];
						$list[$k]['second_money'] = $v['second_money'];
						$list[$k]['dh'] = 1;
						$list[$k]['amount'] = $v['amount'];
					}else if($v['hd'] == 1){
						$list[$k]['thumb'] = '';
						$list[$k]['price'] = $v['money'];
						$list[$k]['second_money'] = $v['second_money'];
						$list[$k]['hd'] = 1;
					}else{
						$list[$k]['thumb'] = $this->get_shop_img($v['shopid']);
						if($v['cateid'] == '177'){
							$list[$k]['price'] = $this->get_shop_price($v['shopid'])*2;
						}else{
							$list[$k]['price'] = $this->get_shop_price($v['shopid']);
						}
						
						$list[$k]['second_money'] = $this->take_second_money($v['shopid']);
					}
					
					$list[$k]['shopqishu'] = $v['shopqishu'];
					

					$list[$k]['time'] = date('Y-m-d H:i',$v['dizhi_time']);
					if (empty($v['dizhi_time'])) {
						$list[$k]['time'] = date('Y-m-d H:i',$v['time']);
					}
					
					
					if($v['dh'] == 1){
						$list2[$k]['shopname'] = "(兑换商品)".$v['shopname'];
						$list2[$k]['username'] = $v['username'];
						$dz_id = $v['dizhi_id'];
						$dz = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$dz_id'");
						$list2[$k]['shouhuoren'] = $dz['shouhuoren'];
						$list2[$k]['dizhi'] = $dz['sheng'].$dz['shi'].$dz['xian'].$dz['jiedao'];
						$list2[$k]['s_phone'] = $dz['mobile'];
						$list2[$k]['money'] = $v['money']."(共".$v['amount']."件)";
						$list2[$k]['second_money'] = $v['second_money'];
					}else if($v['hd'] == 1){
						$list2[$k]['shopname'] = "(活动商品)".$v['shopname'];
						$list2[$k]['username'] = $v['username'];
						$dz_id = $v['dizhi_id'];
						$dz = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$dz_id'");
						$list2[$k]['shouhuoren'] = $dz['shouhuoren'];
						$list2[$k]['dizhi'] = $dz['sheng'].$dz['shi'].$dz['xian'].$dz['jiedao'];
						$list2[$k]['s_phone'] = $dz['mobile'];
						$list2[$k]['money'] = $v['money'];
						$list2[$k]['second_money'] = $v['second_money'];
					}else{
						if($v['cateid'] == '177'){
							$list2[$k]['shopname'] = "(双11半价)(第".$v['shopqishu']."期)".$v['shopname'];
						}else{
							$list2[$k]['shopname'] = "(第".$v['shopqishu']."期)".$v['shopname'];
						}
						
						$list2[$k]['username'] = $v['username'];
						$dz_id = $v['dizhi_id'];
						$dz = $this->db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `id` = '$dz_id'");
						$list2[$k]['shouhuoren'] = $dz['shouhuoren'];
						$list2[$k]['dizhi'] = $dz['sheng'].$dz['shi'].$dz['xian'].$dz['jiedao'];
						$list2[$k]['s_phone'] = $dz['mobile'];
						$shop_id = $v['shopid'];
						$shop = $this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$shop_id'");
						if($v['cateid'] == '177'){
							$list2[$k]['money'] = $shop['money']*2;
						}else{
							$list2[$k]['money'] = $shop['money'];
						}
						
						$sid = $shop['sid'];
						$second_money = $this->db->GetOne("SELECT * FROM `@#_second_money` WHERE `sid` = '$sid'");
						$list2[$k]['second_money'] = $second_money['second_money'];
					}
					
					$list2[$k]['dizhi_time'] = date("Y-m-d H:i:s",$v['dizhi_time']);
				}	
			}else{
				_message("您没有此号码的查询权限");
				//_messagemobile("您没有此号码的查询权限",WEB_PATH."/mobile/home/myorder",2);
				exit();
			}
		
		}
		$path = G_CACHES .'set_exel'.'/exel.php';
		file_put_contents($path, json_encode($list2));
		include $this->tpl(ROUTE_M,'phone.order');
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
	    //sort , SORT_DESC	SORT_ASC
	    array_multisort($key_array,$sort,$multi_array); 
	    return $multi_array; 
	}	

	function page_array($count,$page,$array,$order){  
	    global $countpage; #定全局变量  
	    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面   
	       $start=($page-1)*$count; #计算每次分页的开始位置  
	    if($order==1){  
	      $array=array_reverse($array);  
	    }     
	    $totals=count($array);    
	    $countpage=ceil($totals/$count); #计算总页面数  
	    $pagedata=array();  
	    $pagedata=array_slice($array,$start,$count);  
	    return $pagedata;  #返回查询数据  
	} 
	public function get_shop_img($id){
		$mysql_model=System::load_sys_class('model');
		$shop = $mysql_model->GetOne("select * from `@#_shoplist` where `id` = '$id'");
		return "/statics/uploads/".$shop['thumb'];
	}
	public function get_shop_price($id){
		$mysql_model=System::load_sys_class('model');
		$shop = $mysql_model->GetOne("select * from `@#_shoplist` where `id` = '$id'");
		return $shop['money'];
	}

	function ajax_second_money(){
		$id = $_POST['id'];
		$money = $_POST['money'];
		$data = $this->db->GetOne("SELECT `sid` FROM `@#_shoplist` where `id` = '$id'");
		$sid = $data['sid'];

		$arr = $this->db->GetOne("SELECT * FROM `@#_second_money` WHERE `sid` = '$sid'");
		if($arr){
			$this->db->Query("UPDATE `@#_second_money` SET `second_money`='$money' WHERE `sid`='$sid'");
		}else{
			$this->db->Query("INSERT INTO `@#_second_money`(sid,second_money) VALUES ('$sid','$money')");
		}
		echo 1;
	}

	function take_second_money($id){
		$data = $this->db->GetOne("SELECT `sid` FROM `@#_shoplist` WHERE `id` = '$id'");
		$sid = $data['sid'];
		$arr = $this->db->GetOne("SELECT `second_money` FROM `@#_second_money` WHERE `sid` = '$sid'");
		return $arr['second_money'];
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
		$i = count($dataResult);
		$j = $i + 1;
		$z = $i + 2;
		$jl_money = floor($money/100)*1.5;
		$total_money = $money + $jl_money;
		$dataResult[$i]['shopname'] = '';
		$dataResult[$i]['username'] = '';
		$dataResult[$i]['shouhuoren'] = '';
		$dataResult[$i]['dizhi'] = '';
		$dataResult[$i]['s_phone'] = '';
		$dataResult[$i]['money'] = '';
		$dataResult[$i]['second_money'] = $money;

		$dataResult[$j]['shopname'] = '';
		$dataResult[$j]['username'] = '';
		$dataResult[$j]['shouhuoren'] = '';
		$dataResult[$j]['dizhi'] = '';
		$dataResult[$j]['s_phone'] = '';
		$dataResult[$j]['money'] = '奖励';
		$dataResult[$j]['second_money'] = $jl_money;

		$dataResult[$z]['shopname'] = '';
		$dataResult[$z]['username'] = '';
		$dataResult[$z]['shouhuoren'] = '';
		$dataResult[$z]['dizhi'] = '';
		$dataResult[$z]['s_phone'] = '';
		$dataResult[$z]['money'] = '';
		$dataResult[$z]['second_money'] = $total_money;
		
		$headTitle = ""; 
		$title = $_COOKIE['exel_mobile']; 
		$headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
		$titlename = "<tr> 
               <th style='width:350px;' >商品名称</th> 
               <th style='width:100px;' >中奖用户</th> 
               <th style='width:100px;'>收货人</th> 
               <th style='width:270px;'>收货地址</th> 
               <th style='width:120px;'>收货人手机</th> 
               <th style='width:70px;'>商品总价</th> 
               <th style='width:70px;'>收货价</th> 
               <th style='width:130px;'>提交时间</th>
           </tr>"; 
           $filename = $title.".xls"; 
       $this->excelData($dataResult,$headtitle,$titlename,$filename); 
	}
	
}