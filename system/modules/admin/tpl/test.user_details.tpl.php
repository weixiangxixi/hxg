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
<link href="<?php echo G_TEMPLATES_CSS; ?>/mobile/switchery.css" rel="stylesheet" type="text/css">
<script src="/statics/templates/yungou/js/mobile/switchery.js" language="javascript" type="text/javascript"></script>
<script src="/statics/templates/yungou/js/layer/layer.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" href="/statics/templates/yungou/css/mobile/layer.css" id="layuicss-layer">
<style type="text/css">
    .switchery{
        height: 25px;
        margin: 5px;
    }
</style>
</head>
<body>

<div class="header-data lr10" style="margin-top: 20px">
 
    <select name="user_type" id="user_type" style="height: 34px;line-height: 16px;border: 1px solid #B4B4B4;background: #fff;border-radius: 3px;">
        <option value="uid" <?php if($user_type=='uid'){echo "selected";} ?>>用户uid</option>
        <option value="mobile" <?php if($user_type=='mobile'){echo "selected";} ?>>手机号码</option>
        <option value="username" <?php if($user_type=='username'){echo "selected";} ?>>用户昵称</option>
    </select>
    <input type="text" name="user" value="<?php echo $user; ?>" class="input-text" style="width: 160px" />
	<input type ="submit" value=" 账户余额 " name="zhye_submit" class="button" onclick="get(this);" <?php if($type == 'zhye_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 充值记录 " name="czjl_submit" class="button" onclick="get(this);" <?php if($type == 'czjl_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 购买记录 " name="gmjl_submit" class="button" onclick="get(this);" <?php if($type == 'gmjl_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 中奖记录 " name="zjjl_submit" class="button" onclick="get(this);" <?php if($type == 'zjjl_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 闯三关记录 " name="csg_submit" class="button" onclick="get(this);" <?php if($type == 'csg_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 佣金明细 " name="yjmx_submit" class="button" onclick="get(this);" <?php if($type == 'yjmx_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 收货地址 " name="shdz_submit" class="button" onclick="get(this);" <?php if($type == 'shdz_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 流量查询 " name="llcx_submit" class="button" onclick="get(this);" <?php if($type == 'llcx_submit'){echo "style='background-color: red'";} ?>/>
    <input type ="submit" value=" 关联号查询 " name="glhcx_submit" class="button" onclick="get(this);" <?php if($type == 'glhcx_submit'){echo "style='background-color: red'";} ?>/>
</div>
<script type="text/javascript">
    function get(k){
        var name = k.name;
        var user = $("input[name='user']").val();
        var user_type = $('#user_type option:selected').val();
        if(user != ''){
            window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/"+name;
        }
    }
</script>
<div class="bk10"></div>
<?php if($type == 'zhye_submit'){ ?>
<div class="table-list lr10">
    <table width="80%" cellspacing="0">
        <thead>
            <tr>
                <th align="center">账户余额</th>
                <th align="center">全返余额</th>
                <th align="center">经验</th>
                <th align="center">充值金额</th>
                <th align="center">商城卡充值金额</th>
                <th align="center">商城卡中奖金额</th>
                <th align="center">商城卡购买金额</th>
                <th align="center">全返金额</th>
                <th align="center">购买全返商品金额</th>
                <th align="center">登录验证码</th>
                <th align="center">切换登录方式</th>
            </tr>
            <tr>
                <th align="center"><?php echo $money; ?>元</th>
                <th align="center"><?php echo $qf_money; ?>元</th>
                <th align="center"><?php echo $jingyan;?></th>
                <th align="center"><?php echo $money1; ?>元</th>
                <th align="center"><?php echo $money_sc;?>.00元</th>
                <th align="center"><?php echo $money_sc2; ?>元</th>
                <th align="center"><?php echo $money_sc3; ?>.00元</th>
                <th align="center"><?php echo $money2_3; ?>元</th>
                <th align="center"><?php echo $money4;?>元</th>
                <th align="center"><?php echo $mm['mobile_code'];?>（有效期至<?php echo date("Y-m-d H:i:s",$mm['code_time']); ?>）</th>
                <th align="center"><div class="ios-switch"><label class="sub_nav_content"><?php if($mm['code_user'] == 0){echo "短信验证登录";}else{echo "短信/密码登录";} ?></label><input id="loginType" data-privacy-type="3" type="checkbox" <?php if($mm['code_user'] == 1){echo 'checked=""';} ?> data-switchery="true" style="display: none;"></div></th>
            </tr>
        </thead>
    <tbody>
    </tbody>
</table>
</div>
<?php }else if($type == 'czjl_submit'){ ?>
<div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <table width="20%" style="float: left;">   
            <thead>
                <tr><th colspan="4" align="center">微信充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $wx_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">状态</th>
                    <th align="center">充值时间</th>
                    <th align="center">确认时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($wx_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center" <?php if($v['status'] == 0){echo "style='color:red'";}else{echo "style='color:green'";} ?>><?php if($v['status'] == 1){echo "成功";}else{echo "失败";} ?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                        <td align="center"><?php if(!empty($v['update_time'])){echo date("Y-m-d H:i:s",$v['update_time']);} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <table width="20%" style="float: left;">
            <thead>
                <tr><th colspan="4" align="center">支付宝充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $zfb_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">状态</th>
                    <th align="center">充值时间</th>
                    <th align="center">确认时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($zfb_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center" <?php if($v['status'] == 0){echo "style='color:red'";}else{echo "style='color:green'";} ?>><?php if($v['status'] == 1){echo "成功";}else{echo "失败";} ?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                        <td align="center"><?php if(!empty($v['update_time'])){echo date("Y-m-d H:i:s",$v['update_time']);} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
         <table width="20%" style="float: left;">
            <thead>
                <tr><th colspan="4" align="center">银行卡充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $yhk_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">状态</th>
                    <th align="center">充值时间</th>
                    <th align="center">确认时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($yhk_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center" <?php if($v['status'] == 0){echo "style='color:red'";}else{echo "style='color:green'";} ?>><?php if($v['status'] == 1){echo "成功";}else{echo "失败";} ?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                        <td align="center"><?php if(!empty($v['update_time'])){echo date("Y-m-d H:i:s",$v['update_time']);} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <table width="14%" style="float: left;">
            <thead>
                <tr><th colspan="4" align="center">微信公众号充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $gzh_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">转账时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($gzh_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']); ?></td>
                <?php } ?>
            </tbody>
        </table>
        <table width="14%" style="float: left;">
            <thead>
                <tr><th colspan="4" align="center">网络充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $wl_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">转账时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($wl_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']); ?></td>
                <?php } ?>
            </tbody>
        </table>
        <table width="12%" style="float: left;">
            <thead>
                <tr><th colspan="3" align="center">其他充值记录(共充值<span style="color:red;font-size: 18px"><?php echo $zz_money; ?></span>元)</th></tr>
                <tr>
                    <th align="center">金额</th>
                    <th align="center">转账时间</th>
                    <th align="center">详情</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($zz_data AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['money'];?></td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']); ?></td>
                        <td align="center"><?php echo $v['content'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </table>
</div>
<?php }else if($type == 'gmjl_submit'){ ?>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">用户名</th>
            <th align="center">UID</th>
            <th align="center">订单号</th>
            <th align="center">商品标题(<?php echo $total_money['money']; ?>)</th>
            <th align="center">购买次数</th>
            <th align="center">商品总价</th>
            <th align="center">购买日期</th>
            <th align="center">揭晓日期</th>
            <th align="center">中奖</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data AS $v) {  ?>      
            <tr>
                <td align="center"><?php echo $v['username'];?></td>
                <td align="center"><?php echo $v['uid']; ?></td>              
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>">
                <?php if($v['cateid'] == '177'){echo "(<em style='font-size:16px;color:red;'>双十一</em>)";} ?>第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center">￥<?php if($v['cateid'] == '177'){echo $v['money']*2;}else{echo $v['money'];} ?>元</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['q_end_time']);?></td>
                <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div>
<?php }else if($type == 'zjjl_submit'){ ?>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">用户名</th>
            <th align="center">UID</th>
            <th align="center">订单号</th>
            <th align="center">商品标题(中奖金额：<?php echo $zj_money; ?>元)</th>
            <th align="center">购买次数</th>
            <th align="center">商品总价</th>
            <th align="center">购买日期</th>
            <th align="center">揭晓日期</th>
            <th align="center">中奖</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data AS $v) { ?>      
            <tr>
                <td align="center"><?php echo $v['username'];?></td>
                <td align="center"><?php echo $v['uid']; ?></td>              
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>">
                <?php if($v['cateid'] == '177'){echo "(<em style='font-size:16px;color:red;'>双十一</em>)";} ?>第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center">￥<?php if($v['cateid'] == '177'){echo $v['money']*2;}else{echo $v['money'];} ?>元</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['q_end_time']);?></td>
                <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div>
<?php }else if($type == 'csg_submit'){ ?>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">用户名</th>
            <th align="center">UID</th>
            <th align="center">订单号</th>
            <th align="center">商品标题(中奖金额：<?php echo $csg_money; ?>元/消费金额：<?php echo $csg_xf;?>元)</th>
            <th align="center">商品总价</th>
            <th align="center">难度等级</th>
            <th align="center">购买日期</th>
            <th align="center">获得日期</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data AS $v) { ?>      
            <tr>
                <td align="center"><?php echo get_user_name($v['uid']);?></td>
                <td align="center"><?php echo $v['uid']; ?></td>              
                <td align="center"><?php echo $v['code'];?></td>
                <td align="center">
                <?php echo _strcut($v['name'],0,25);?>
                </td>
                <td align="center">￥<?php echo $v['price']; ?>元</td>
                <td align="center"><em style="color:red;font-size: 16px;"><?php echo $v['flag']; ?></em>颗星</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['update_time']);?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_csg_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
<div class="header-data lr10" style="margin-top: 20px">
    <?php foreach ($str_csg as $key => $val) { ?>
        <div style="display: block;"><input type="text" value="<?php echo $val['name']; ?>" class="input-text" style="width: 260px;" /><input type="text" value="<?php echo '参与次数：'.$val['cy'].'次'; ?>" class="input-text" style="width: 100px;" /><input type="text" value="<?php echo '中奖次数：'.$val['zj'].'次'; ?>" class="input-text" style="width: 100px;" /><input type="text" value="<?php echo '消费金额：'.$val['cy_money'].'元'; ?>" class="input-text" style="width: 150px;" /><input type="text" value="<?php echo '中奖金额：'.$val['zj_money'].'元'; ?>" class="input-text" style="width: 150px;" /></div>
    <?php } ?>
</div>
</div>
<?php }else if($type == 'yjmx_submit'){ ?>
<div class="yjmx">
    <div class="table-list lr10">
    <p style="border: 1px solid #161E22;background: #161E22;padding: 6px 20px;color: #fff;margin-bottom: 10px;width: 150px;height: 25px;border-radius: 10%;">佣金余额：<span style="color: red;font-size: 18px;"><?php echo $yjye; ?></span>元</p>
    <a href="<?php echo G_MODULE_PATH."/test/user_details/".$this->segment(4)."/".$this->segment(5)."/".$this->segment(6)."/txjl"; ?>" style="border: 1px solid <?php if($this->segment(7) == "txjl"){echo 'red';}else{echo '#2a8bbb';} ?>;padding: 5px 10px;background: <?php if($this->segment(7) == "txjl"){echo 'red';}else{echo '#2a8bbb';} ?>;color: #fff;border-radius: 10%;margin: 5px 5px;display: inline-block;">提现记录</a>
    <?php foreach ($yqr as $key => $val) { ?>
        <a href="<?php echo G_MODULE_PATH."/test/user_details/".$this->segment(4)."/".$this->segment(5)."/".$this->segment(6)."/".$val['uid']; ?>" style="border: 1px solid <?php if($this->segment(7) == $val['uid']){echo 'red';}else{echo '#2a8bbb';} ?>;padding: 5px 10px;background: <?php if($this->segment(7) == $val['uid']){echo 'red';}else{echo '#2a8bbb';} ?>;color: #fff;border-radius: 10%;margin: 5px 5px;display: inline-block;"><?php if(!empty($val['mobile'])){echo $val['mobile'];}else if(!empty($val['username'])){echo $val['username'];}?></a>
    <?php } ?>
    <?php if(!empty($yaoqingren)){ ?>
        <a href="<?php echo G_MODULE_PATH."/test/user_details/".$this->segment(4)."/".$yaoqingren['uid']."/".$this->segment(6); ?>" style="border: 1px solid <?php if($this->segment(7) == "yqr"){echo 'red';}else{echo '#2a8bbb';} ?>;padding: 5px 10px;background: <?php if($this->segment(7) == "yqr"){echo 'red';}else{echo '#2a8bbb';} ?>;color: #fff;border-radius: 10%;margin: 5px 5px;display: inline-block;">邀请人：<?php if(!empty($yaoqingren['mobile'])){echo $yaoqingren['mobile'];}else{echo $yaoqingren['username'];} ?></a>
    <?php } ?>
    </div>
    <div class="table-list lr10">
    <!--start-->
      <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th align="center">用户名</th>
                <th align="center">UID</th>
                <th align="center">手机号</th>
                <th align="center">商品id</th>
                <th align="center">商品详情</th>
                <th align="center">云购金额</th>
                <th align="center">佣金</th>
                <th align="center">时间</th>
                <th align="center">申请提现id</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data AS $v) {  ?>      
                <tr>
                    <td align="center"><?php $arr = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='$v[uid]'");echo $arr['username']; ?></td>
                    <td align="center"><?php echo $v['uid']; ?></td>              
                    <td align="center"><?php echo $arr['mobile']; ?></td>
                    <td align="center"><?php echo $v['shopid']; ?></td>
                    <td align="center"><?php echo $v['content']; ?></td>
                    <td align="center">￥<?php echo $v['ygmoney']; ?>元</td>
                    <td align="center"><?php echo $v['money']; ?></td>
                    <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                    <td align="center"><?php echo $v['cashoutid'] ;?></td>
                </tr>
            <?php } ?>
        </tbody>
      </table>
      <div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
    </div>
</div>
<?php }else if($type == 'llcx_submit'){ ?>
<style type="text/css">
    #llcx div{
        float: left;
        margin: 10px 30px;
    }
    #llcx div span{
        border: 1px solid rgb(187, 187, 187);
        background: #161E22;
        color: #fff;
        font-size: 14px;
        box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.3), 0px 2px 2px -1px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.3) inset;
        padding: 5px 20px;
        font: bold 12px 微软雅黑,arial,helvetica,sans-serif;
        border-radius: 5px;
    }
    #time1,#time2{
        width: 160px;
        background: #fff url(/statics/plugin/laydate/calendar.gif) 140px no-repeat;
        cursor: pointer;           
        height: 22px;
        border: 1px solid #B4B4B4;
    
        
    }
</style>
    <div id="llcx">
        <div><span style="<?php if($cate=="one"){echo "color: red";} ?>">个人流量明细</span><input type="radio" name="llcx" value="one" style="margin-left: 10px;width: 20px;height: 20px;" <?php if($cate=="one"){echo "checked";} ?>></div>
        <div><span style="<?php if($cate=="all"){echo "color: red";} ?>">全部流量明细</span><input type="radio" name="llcx" value="all" style="margin-left: 10px;width: 20px;height: 20px;" <?php if($cate=="all"){echo "checked";} ?>></div>
        <div id="select_time" style="<?php if($cate=='all'){echo 'display: block';}else{echo 'display: none';} ?>;margin-left: 0px"><input type="text" name="" id="time1" value="<?php echo date('Y-m-d H:i:s',$time1); ?>">-<input type="text" name="" id="time2" value="<?php echo date('Y-m-d H:i:s',$time2); ?>"><span id="sure" style="margin-left: 5px;cursor:pointer">确认</span></div>
        <div id="sy"><span style='padding: 5px 0px 5px 20px'><?php if($cate=='one'){echo "个人";}else{echo "全部";} ?>流量剩余金额：<span style='color:red;font-size:14px;'><?php echo round($arr_ye['flow'],2); ?>元</span></span></div>
    </div>
    <div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <table width="50%" style="float: left;">   
            <thead>
                <tr><th colspan="5" align="center">流量充值金额(共<span style="font-size: 16px;color: red;"><?php echo $total_cz; ?></span>元)</th></tr>
                <tr>
                    <th align="center">uid</th>
                    <th align="center">充值号码</th>
                    <th align="center">充值金额</th>
                    <th align="center">充值时间</th>
                    <th align="center">充值状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($arr_cz AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['uid'];?></td>
                        <td align="center"><?php echo $v['phone']; ?></td>
                        <td align="center"><?php echo $v['flow_num']; ?>元</td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                        <td align="center"><?php if($v['status'] == 1){echo "充值成功";} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <table width="50%" style="float: left;">
            <thead>
                <tr><th colspan="3" align="center">流量兑换金额(共<span style="font-size: 16px;color: red;"><?php echo $total_dh; ?></span>元)</th></tr>
                <tr>
                    <th align="center">uid</th>
                    <th align="center">兑换金额</th>
                    <th align="center">兑换时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($arr_dh AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['uid'];?></td>
                        <td align="center"><?php echo $v['flow']; ?>元</td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </table>
</div>
<script type="text/javascript">
    $(function(){
        $('#llcx input[name="llcx"]').click(function(){
            var val = $(this).val();
            var user = $("input[name='user']").val();
            var user_type = $('#user_type option:selected').val();
            if(val == "one"){
                window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val;
            }else if(val == "all"){
                window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val;
            }
        })

        $('#sure').click(function(){
            var val = $('#llcx input[name="llcx"]:checked').val();
            var user = $("input[name='user']").val();
            var user_type = $('#user_type option:selected').val();
            var time1 = $("#time1").val();
            var time2 = $("#time2").val();
            window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val+"/"+time1+"/"+time2;
        })

        laydate.render({
          elem: '#time1' //指定元素
          ,type: 'datetime'
        });
        laydate.render({
          elem: '#time2' //指定元素
          ,type: 'datetime'
        });
    })
</script>
<?php }else if($type == 'glhcx_submit'){ ?>
    <div class="table-list lr10">
    <table width="100%" cellspacing="0">
        <table width="35%" style="float: left;">   
            <thead>
                <tr>
                    <th align="center" colspan="3">扫码充值</th>
                    <th align="center">赠送8元</th>
                    <th align="center">参与红包活动</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($brr AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['uid'];?></td>
                        <td align="center" style="width: 30%"><?php echo $v['mobile'];?></td>
                        <td align="center" style="width: 30%"><?php echo $v['username']; ?></td>
                        <td align="center"><?php $cc = $this->db->GetOne("SELECT `uid` FROM `@#_member_account` WHERE `uid` = '$v[uid]' AND `content` = '新注册用户首次充值送8元'");if($cc){echo "是";} ?></td>
                        <td align="center"><?php $time_f=date('Ymd',time());$dd = $this->db->GetOne("SELECT `uid` FROM `@#_red_pachet` WHERE `uid` = '$v[uid]' AND `current_count` = '$time_f'");if($dd){echo "是";} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
         <table width="35%" style="float: left;">   
            <thead>
                <tr>
                    <th align="center" colspan="3">公众号充值</th>
                    <th align="center">赠送8元</th>
                    <th align="center">参与红包活动</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($crr AS $v) {  ?>      
                    <tr>
                        <td align="center"><?php echo $v['uid'];?></td>
                        <td align="center" style="width: 30%"><?php echo $v['mobile'];?></td>
                        <td align="center" style="width: 30%"><?php echo $v['username']; ?></td>
                        <td align="center"><?php $cc2 = $this->db->GetOne("SELECT `uid` FROM `@#_member_account` WHERE `uid` = '$v[uid]' AND `content` = '新注册用户首次充值送8元'");if($cc2){echo "是";} ?></td>
                        <td align="center"><?php $time_f2=date('Ymd',time());$dd2 = $this->db->GetOne("SELECT `uid` FROM `@#_red_pachet` WHERE `uid` = '$v[uid]' AND `current_count` = '$time_f2'");if($dd2){echo "是";} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </table>

</div>
<script type="text/javascript">
    $(function(){
        $('#llcx input[name="llcx"]').click(function(){
            var val = $(this).val();
            var user = $("input[name='user']").val();
            var user_type = $('#user_type option:selected').val();
            if(val == "one"){
                window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val;
            }else if(val == "all"){
                window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val;
            }
        })

        $('#sure').click(function(){
            var val = $('#llcx input[name="llcx"]:checked').val();
            var user = $("input[name='user']").val();
            var user_type = $('#user_type option:selected').val();
            var time1 = $("#time1").val();
            var time2 = $("#time2").val();
            window.location.href = '<?php echo G_MODULE_PATH; ?>/test/user_details/'+user_type+"/"+user+"/llcx_submit/"+val+"/"+time1+"/"+time2;
        })

        laydate.render({
          elem: '#time1' //指定元素
          ,type: 'datetime'
        });
        laydate.render({
          elem: '#time2' //指定元素
          ,type: 'datetime'
        });
    })
</script>
<?php }else{ ?>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center">UID</th>
            <th align="center">用户名</th>
            <th align="center">手机号</th>
            <th align="center">地址</th>
            <th align="center">邮编</th>
            <th align="center">收货人</th>
            <th align="center">收货人手机</th>
            <th align="center">收货人座机</th>
            <th align="center">添加时间</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($data AS $v) {	?>		
            <tr>
                <td align="center"><?php echo $uid; ?></td>
                <td align="center"><?php echo $user_data['username']; ?></td>              
                 <td align="center"><?php echo $user_data['mobile']; ?></td>
                <td align="center"><?php echo $v['sheng'].$v['shi'].$v['xian'].$v['jiedao']; ?></td>
                <td align="center"><?php echo $v['youbian']; ?></td>
                <td align="center"><?php echo $v['shouhuoren'];?></td>
                 <td align="center"><?php echo $v['mobile'];?></td>
                <td align="center"><?php echo $v['tell'];?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']); ?></td>
            </tr>
        <?php } ?>
  	</tbody>
</table>
</div><!--table-list end-->
<?php } ?>

<script>
    function haha(obj){
        var s_id = obj.value;  
        if ( obj.checked == true){

        //Action for checked
            //alert(s_id);
            post_ajax(s_id,1);
        }else{

        //Action for not checked
            //alert(s_id);
            post_ajax(s_id,0);
        }
    }
    function post_ajax(id,s){
        $.ajax({  
            type:'post',    
            url:'/index.php/admin/dingdan/chang_redbag',  
            data:{
                id : id,  
                s : s
            }, 
            dataType:'json',  
            success:function(data){  
                console.log(data)
            }  
        }); 
    }
</script>
<script>
    $(function(){
        var uid = '<?php echo $uid; ?>';
        var elems = Array.prototype.slice.call(document.querySelectorAll('.ios-switch>input'));
        elems.forEach(function(html) {
            new Switchery(html);

            $(html).change(function () {
                var _type = $(this).data('privacy-type');
                var _status = $(this).is(":checked") ? "0" : "4";
                if(_type == 3){
                    if(_status == '0' ){
                        $(this).siblings('label').text('短信/密码登录');
                    } else {
                        $(this).siblings('label').text('短信验证登录');
                    }
                    $.getJSON("/index.php/mobile/home/switch_login_ht",{'uid':uid},function (json) {
                        
                        if( json == 1 ){
                            layer.msg("更新成功!");
                        }else{
                            layer.msg("更新失败!");
                        }
                    })
                }

            });
        });
    })
</script>
<div class="layui-layer-move"></div>
</body>
</html> 