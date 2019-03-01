<?php 

$src = 'http://wd.apiplus.net/newly.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&rows=1';
//防止GET本地缓存，增加随机数
$src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
$html = file_get_contents($src);
$json = json_decode($html,true);
$time_flag = time() - strtotime(date("Y-m-d"),time());
echo $time_flag;
if (isset($json['rows'])){
	foreach($json['data'] as $r){
		$expect = preg_replace("/^(\d{8})(\d{3})$/","\\1-\\2",$r['expect']);
		$opencode = $r['opencode'];
		$opentime = $r['opentime'];
		echo "开奖期号：{$expect}<br/>";
		echo "开奖号码：{$opencode}<br/>";
		echo "开奖时间：{$opentime}<br/>";
		echo '<br/>';
		$time = intval(substr($r['expect'], 8, 3));
		//$time = $_GET['id'];
		if($time < 24){
			$str = strtotime(date("Y-m-d",time())) + $time*5*60;
		}else if($time == 24){
			$str = strtotime(date("Y-m-d",time())) + 10*60*60;
		}else if($time <= 96){
			$str = strtotime(date("Y-m-d",time())) + 10*60*60 + ($time-24)*10*60;
		}else{
			$str = strtotime(date("Y-m-d",time())) + 22*60*60 + ($time-96)*5*60;
		}
		$xx = date("Y-m-d H:i:s",$str);
		$expect = date("Ymd",strtotime("+1 days"))."001";
		echo $expect;
	}
}
?>