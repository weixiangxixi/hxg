<?php

$ymd=date('Y-m-d');			 		
$ninesj=' 21:59:59';			
$tensj=' 23:59:59';			

$minsj=strtotime($ymd.$ninesj); //公布中奖码
$maxsj=strtotime($ymd.$tensj); //公布中奖码
$time=time();
 

if(($time>$minsj) && ($time<$maxsj)){ 					
	 //开启suoket开奖
	 $url= 'http://caipiao.163.com/?/newmodel/index/doLottery';	 
	triggerRequest($url);	 
}

function triggerRequest($url, $post_data = array(), $cookie = array()){
        $method = "GET";  //可以通过POST或者GET传递一些参数给要触发的脚本
        $url_array = parse_url($url); //获取URL信息，以便平凑HTTP HEADER	 
		 
        $port = isset($url_array['port'])? $url_array['port'] : 80; 
      
        $fp = fsockopen($url_array['host'],$port,$errno,$errstr,30); 
        if (!$fp){
               echo "$errstr ($errno)<br />\n"; 
        }
        $getPath = $url_array['path'].$url_array['query'];
        
        $header =$method." ".$getPath." HTTP/1.1\r\n";      
        $header.="Host: ".$url_array['host']."\r\n"; //HTTP 1.1 Host域不能省略        
        $header.="Connection: Close\r\n\r\n";
        
        fwrite($fp,$header);         
        fclose($fp);
       
} 

?>