<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');

class lottery extends base{
	public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
    }

    //调用透明购  获取开奖码
	public function tmg_number(){
	  	$pay=System::load_app_class('pay','pay');
		$ok = $pay->tmg_jx();
		var_dump($ok);
    }

    //透明购往期未开奖
    public function wq_tmg_not(){
        $src = 'http://wd.apiplus.net/newly.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&rows=1';
        $src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
        $html = file_get_contents($src);
        $json = json_decode($html,true);
        $expect = $json['data'][0]['expect'];
       
        $data = $this->db->GetOne("SELECT `ssc_times` FROM `@#_tmg` WHERE `ssc_time` = '0' AND `ssc_times` < '$expect' order by `id` desc");

        if($data){
            $expect2 = $data['ssc_times'];
            $daily = date("Y-m-d",strtotime(substr($data['ssc_times'], 0, 8 )));
            $src2 = 'http://wd.apiplus.net/daily.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&date='.$daily;
            $src2 .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
            $html2 = file_get_contents($src2);
            $json2 = json_decode($html2,true);
            if (isset($json2['rows'])){
                foreach($json2['data'] as $r){
                    if($expect2 == $r['expect']){
                        $opencode = str_replace(',','',$r['opencode']);
                        $opentimestamp = $r['opentimestamp'];
                        break;
                    }
                }
            }
            $pay=System::load_app_class('pay','pay');
            if(empty($opencode)){
                var_dump('no');
            }else{
                $ok = $pay->tmg_wq_jx($expect2,$opencode,$opentimestamp);
                var_dump($ok);
            }
        }
    }

    //透明购一个小时没开奖按00000开奖
    public function tmg_not_open(){
        $src = 'http://wd.apiplus.net/newly.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&rows=1';
        $src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
        $html = file_get_contents($src);
        $json = json_decode($html,true);
        $expect = $json['data'][0]['expect'];
        $time = time() - 3600;
        $data = $this->db->GetOne("SELECT `ssc_times` FROM `@#_tmg` WHERE `ssc_time` = '0' AND `end_time` < $time order by `id` asc");
      
        if($data){
            $expect2 = $data['ssc_times'];
            $daily = date("Y-m-d",strtotime(substr($data['ssc_times'], 0, 8 )));
            $src2 = 'http://wd.apiplus.net/daily.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&date='.$daily;
            $src2 .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
            $html2 = file_get_contents($src2);
            $json2 = json_decode($html2,true);
            if (isset($json2['rows'])){
                foreach($json2['data'] as $r){
                    if($expect2 == $r['expect']){
                        $opencode = str_replace(',','',$r['opencode']);
                        $opentimestamp = $r['opentimestamp'];
                        break;
                    }
                }
            }
            $pay=System::load_app_class('pay','pay');
            if(empty($opencode)){
                $opencode = '00000';
                $opentimestamp = time();
                $ok = $pay->tmg_wq_jx($expect2,$opencode,$opentimestamp);
            }else{
                $ok = $pay->tmg_wq_jx($expect2,$opencode,$opentimestamp);
            }
            var_dump($ok);
        }else{
            var_dump('no');
        }
    }

    //透明购开奖失败商品
    public function tmg_kj_lose(){
        $data = $this->db->GetOne("SELECT `id` FROM `@#_shoplist` WHERE `cateid` = '173' AND `shenyurenshu` = '0' AND `q_uid` is NULL order by `q_end_time` asc");
        $id = $data['id'];
        $data_tmg = $this->db->GetOne("SELECT * FROM `@#_tmg` WHERE `shopid` = '$id' AND `ssc_time` != '0'");
        if($data_tmg){
            $pay=System::load_app_class('pay','pay');
            $expect = $data_tmg['ssc_times'];
            $opencode = $data_tmg['ssc_number'];
            $opentimestamp = $data_tmg['ssc_time'];
            $ok = $pay->tmg_lose($expect,$opencode,$opentimestamp);
            var_dump($ok);
        }else{
            var_dump('no');
        }
    }
  
  	//透明购未开奖
    public function tmg_wkj(){
        $src = 'http://wd.apiplus.net/newly.do?token=t271dc14ff14e85a7k&code=cqssc&format=json&rows=1';
        $src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
        $html = file_get_contents($src);
        $json = json_decode($html,true);
        $expect = $json['data'][0]['expect'];
       	
      	if($expect == '20190203120'){
        
            $data = $this->db->GetOne("SELECT `ssc_times` FROM `@#_tmg` WHERE `ssc_time` = '0' AND `ssc_times` > '$expect' order by `id` asc");

            if($data){
                $expect2 = $data['ssc_times'];
                $opencode = '00000';
                $opentimestamp = time();
               	$pay=System::load_app_class('pay','pay');
                $ok = $pay->tmg_wq_jx($expect2,$opencode,$opentimestamp);
                var_dump($ok);
            }
        }
    }
}