<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');

class panic extends base{

    public function __construct(){
        parent::__construct();
        $this->db = System::load_sys_class('model');
        _freshen();
    }

    public function index(){

        include templates("mobile/panic", "index");
    } 

    public function indexajax(){
        $p = htmlspecialchars($this->segment(4)) ? $this->segment(4) : 1;
        //分页

        $end = 50;
        $star = ($p - 1) * $end;
 
        $arr = array();
       
        $count = $this->db->GetCount("select count(*) from `@#_shoplist` where `cateid` = '170' and `q_end_time` is null");

        $shoplist = $this->db->GetList("select cateid,id,sid,title,money,yunjiage,zongrenshu,canyurenshu,shenyurenshu,qishu,maxqishu,thumb,renqi,time,`order` from `@#_shoplist` where `cateid` = '170' and `q_end_time` is null limit $star,$end");

        foreach ($shoplist as $key => $val) {
            $sid = $val['sid'];
            $time1 = strtotime(date("Y-m-d",time()));
            $time2 = $time1+86400;
            $str = $this->db->GetList("SELECT * FROM `@#_shoplist` WHERE `sid` = '$sid' and `q_end_time` >= '$time1' and `q_end_time` < '$time2'");
            $num = $this->db->GetOne("SELECT * FROM `@#_bzqf_num` WHERE `id` = '1'");
            if(count($str) >= $num['num']){
                // unset($shoplist[$key]);
                // --$count;
                $shoplist[$key]['mmm'] = 1;  //当天售完
            }
        }
        $shoplist = array_values($shoplist);

        $this_time = time();
        if (count($shoplist) > 1) {
            if ($shoplist[0]['time'] > $this_time - 86400 * 3)
                $shoplist[0]['t_new_goods'] = 1;
        }
        $pagex = ceil($count / $end);
        if ($p <= $pagex) {
            $shoplist[0]['page'] = $p + 1;
        }
        if ($pagex > 0) {
            $shoplist[0]['sum'] = $pagex;
        } else if ($pagex == 0) {
            $shoplist[0]['sum'] = $pagex;
        }

        echo json_encode($shoplist);
    }

    public function glist(){
        include templates("mobile/panic", "glist");
    }

     public function glistajax(){
        $p = htmlspecialchars($this->segment(4)) ? $this->segment(4) : 1;
        //分页

        $end = 10;
        $star = ($p - 1) * $end;
 
        $arr = array();
       
        $count = $this->db->GetCount("select count(*) from `@#_shoplist` where `cateid` = '170' and `q_end_time` is not null");

        $shoplist = $this->db->GetList("select cateid,q_uid,id,q_end_time,q_user_code,sid,title,money,yunjiage,zongrenshu,canyurenshu,shenyurenshu,qishu,maxqishu,thumb,renqi,time,`order` from `@#_shoplist` where `cateid` = '170' and `q_end_time` is not null order by `q_end_time` desc limit $star,$end");
        foreach ($shoplist as $key => $val) {
            $uid = $val['q_uid'];
            $id = $val['id'];
            $str = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
            if(!empty($str['username'])){
                $shoplist[$key]['username'] = $str['username'];
            }else{
                $shoplist[$key]['username'] = substr($str['mobile'], 0, 3)."****".substr($str['mobile'], 7, 4);
            }

            $total_num = 0;
            $str2 = $this->db->GetList("SELECT `gonumber` FROM `@#_member_go_record` WHERE `shopid` = '$id' AND `uid` = '$uid'");
            foreach ($str2 as $k => $v) {
                $total_num += $v['gonumber'];
            }
            $shoplist[$key]['total_num'] = $total_num;
            $shoplist[$key]['q_end_time'] = date("Y-m-d H:i:s",intval($val['q_end_time']));
        }
        $shoplist =  array_values($shoplist);
        $this_time = time();
        if (count($shoplist) > 1) {
            if ($shoplist[0]['time'] > $this_time - 86400 * 3)
                $shoplist[0]['t_new_goods'] = 1;
        }
        $pagex = ceil($count / $end);
        if ($p <= $pagex) {
            $shoplist[0]['page'] = $p + 1;
        }
        if ($pagex > 0) {
            $shoplist[0]['sum'] = $pagex;
        } else if ($pagex == 0) {
            $shoplist[0]['sum'] = $pagex;
        }

        echo json_encode($shoplist);
    }
}

?>