<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my', 'go');
System::load_app_fun('user', 'go');
System::load_sys_fun('send');
System::load_sys_fun('user');
System::load_app_fun('member', ROUTE_M);

class test extends base
{
    //申请提现
    public function cashout()
    {
        $mysql_model = System::load_sys_class('model');
        $member = [
            "uid" => 2156
        ];
        $uid = 2156;
        $total = 0;
        $shourutotal = 0;
        $zhichutotal = 0;
        $cashoutdjtotal = 0;
        $cashouthdtotal = 0;
        //查询邀请好友id
        $invifriends = $mysql_model->GetList("select uid from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");

        //查询佣金收入
        for ($i = 0; $i < count($invifriends); $i++) {
            $sqluid = $invifriends[$i]['uid'];
            //查询邀请好友给我反馈的佣金
            $recodes[$sqluid] = $mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");
            $tmp = $mysql_model->GetList("select sum(money) as c from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");
            $recodes_tmp[$sqluid]= $tmp[0]['c'];
        }

        //查询佣金消费(提现,充值)
        $zhichu = $mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$uid' and `type`!=1 ORDER BY `time` DESC");

        //查询被冻结金额
        $cashoutdj = $mysql_model->GetOne("select SUM(money) as summoney  from `@#_member_cashout` where `uid`='$uid' and `auditstatus`!='1' ORDER BY `time` DESC");


        $jilu = [];
        $jilu2 = [];
        if (!empty($recodes)) {
            foreach ($recodes as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    $shourutotal += $val2['money'];     //总佣金收入
                    $jilu[date("Y-m-d H", $val2['time'])] += $val2['money'];
                }
            }
        }
        if (!empty($zhichu)) {
            foreach ($zhichu as $key => $val3) {
                $zhichutotal += $val3['money'];    //总支出的佣金
                $jilu2[date("Y-m-d H:i:s", $val3['time'])] += $val3['money'];
            }
        }


        $total = $shourutotal - $zhichutotal;  //计算佣金余额
        $cashoutdjtotal = $cashoutdj['summoney'];  //冻结佣金余额
        $cashouthdtotal = $total - $cashoutdj['summoney'];  //活动佣金余额
        //INSERT INTO `go_member_recodes`\(`uid`,`type`,`content`,`money`,`time`,`cashoutid`\)VALUES\(13
        $money = 10;
        $type = 1;
        $pay = "佣金";
        $time = time();
        $content = "使用佣金充值到参与账户";

        ksort($jilu);
        ksort($jilu2);
        exit(json_encode($recodes_tmp));

    }

}

?>