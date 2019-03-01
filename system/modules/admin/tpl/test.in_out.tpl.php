<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 
<style type="text/css">
    #posttime1,#posttime2{
        width: 160px;
        background: #fff url(/statics/plugin/laydate/calendar.gif) 140px no-repeat;
        cursor: pointer;           
        height: 22px;
        border: 1px solid #B4B4B4;
    }
</style>
</head>
<body>
<div class="header-data lr10">
    <span class="lr10"> </span><span class="lr10"> </span>
   <form action="" method="post">
 时间搜索: <input name="time1" type="text" id="posttime1" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($time1)?date("Y-m-d H:i:s",$time1):''?>"/> -  
          <input name="time2" type="text" id="posttime2" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($time2)?date("Y-m-d H:i:s",$time2):''?>"/>
            <script type="text/javascript">
                laydate.render({
                  elem: '#posttime1' //指定元素
                  ,type: 'datetime'
                });
                laydate.render({
                  elem: '#posttime2' //指定元素
                  ,type: 'datetime'
                });
                            
            </script>
    <input class="button" type="submit" name="sososubmit" value="搜索">
</form>
</div>
<div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <?php $total_sm = 0;$total_gzh = 0;$total_zfb = 0;$total_xx = 0;$total_yj = 0;$total_xyh = 0;$total_hd = 0;$total_czz = 0;$total_sj = 0;$total_xfz = 0;$total_zjz = 0;$total_bjgm = 0;$total_bj = 0;$total_log = 0;$total_pay = 0;$total_sck_cz = 0;$total_sck_zj = 0;$total_dh = 0; ?>
    	<?php foreach ($tt as $key => $val) { ?>
        <table width="12%" style="float: left;">   
            <thead>
                <tr><th colspan="2" align="center"><a style="color: #fff"><?php echo substr($val['sj'], 0, 4)."-".substr($val['sj'], 4, 2)."-".substr($val['sj'], 6); ?></a></th></tr>
            </thead>
            <tbody>
               
                <tr>
                    <td align="center">扫码充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['sm']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">公众号充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['gzh']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">支付宝充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['zfb']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">网络充值(支付宝)</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['wl']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">线下转账</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['xx']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">佣金转入</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['yj']; ?></span>元</td>
                </tr>
               <!--  <tr>
                    <td align="center">新用户送8元</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['xyh']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">活动红包</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['hd']; ?></span>元</td>
                </tr> -->
                <tr>
                    <td align="center">充值总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php $czz = $val['sm']+$val['wl']+$val['gzh']+$val['xx']+$val['yj']+$val['hd']+$val['zfb']+$val['xyh']; echo $czz; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">实际充值总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php $sj = $val['sm']+$val['wl']+$val['gzh']+intval($val['xx']*0.97)+$val['zfb']; echo $sj; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">消费总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['xf']; ?></span>元</td>
                </tr>
                
                <tr>
                    <td align="center">中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['zj']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">兑换金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['dh']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">中奖金额(除商城卡)</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['zj']-$val['sck_zj']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">商城卡充值金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['sck_cz']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">商城卡中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['sck_zj']; ?></span>元</td>
                </tr>
               <!--  <tr>
                    <td align="center">双十一购买金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['bj_gm']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">双十一中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['bj']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">闯三关消费金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['csg']; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">闯三中奖总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['csg_zj']; ?></span>元</td>
                </tr> -->
                <tr>
                    <td align="center">当天登录人数</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['login_num']; ?></span>人</td>
                </tr>
                <tr>
                    <td align="center">当天购买人数</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['pay_num']; ?></span>人</td>
                </tr>
    
            </tbody>
        </table>
        <?php $total_sm += $val['sm'];$total_wl += $val['wl'];$total_gzh += $val['gzh'];$total_zfb += $val['zfb'];$total_xx += $val['xx'];$total_yj += $val['yj'];$total_xyh += $val['xyh'];$total_hd += $val['hd'];$total_czz += $czz;$total_sj += $sj;$total_xfz += $val['xf'];$total_zjz += $val['zj'];$total_bjgm += $val['bj_gm'];$total_bj += $val['bj'];$total_log += $val['login_num'];$total_pay += $val['pay_num'];$total_sck_cz += $val['sck_cz'];$total_sck_zj += $val['sck_zj'];$total_dh += $val['dh']; ?>
        <?php } ?>
        <?php if(count($tt) > 1){ ?>
        <table width="15%" style="float: left;">   
            <thead>
                <tr><th colspan="2" align="center">总计</th></tr>
            </thead>
            <tbody>
               
                <tr>
                    <td align="center">总扫码充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_sm; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总公众号充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_gzh; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总支付宝充值</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_zfb; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">网络充值(支付宝)</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_wl; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总线下转账</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_xx; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总佣金转入</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_yj; ?></span>元</td>
                </tr>
                <!-- <tr>
                    <td align="center">总新用户送8元</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_xyh; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总活动红包</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_hd; ?></span>元</td>
                </tr> -->
                <tr>
                    <td align="center">总充值总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_czz; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总实际充值总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_sj; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总消费总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_xfz; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_zjz; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">兑换总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_dh; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">中奖金额(除商城卡)</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_zjz-$total_sck_zj; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">商城卡充值金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_sck_cz; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">商城卡中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_sck_zj; ?></span>元</td>
                </tr>
               <!--  <tr>
                    <td align="center">总双十一购买金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_bjgm; ?></span>元</td>
                </tr>
                <tr>
                    <td align="center">总总双十一中奖金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_bj; ?></span>元</td>
                </tr> -->
                <tr>
                    <td align="center">总登录人数</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_log; ?></span>人</td>
                </tr>
                <tr>
                    <td align="center">总购买人数</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_pay; ?></span>人</td>
                </tr>
                <tr>
                    <td align="center">佣金提现总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_yjtx; ?></span>元</td>
                </tr>
    
            </tbody>
        </table>
        <?php }else{ ?>
            <table width="15%" style="float: left;">   
            <thead>
                <tr><th colspan="2" align="center">总计</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center">佣金提现总金额</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_yjtx; ?></span>元</td>
                </tr>
    
            </tbody>
        </table>
        <?php } ?>
    </table>

</div>

</body>
</html> 