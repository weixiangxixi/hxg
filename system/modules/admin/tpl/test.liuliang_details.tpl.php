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
<style>
tbody tr{ line-height:30px; height:30px;} 
.calendar {
    top: 0px!important;
}
</style>
</head>
<body>
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
        <div id="select_time" style="margin-left: 10px"><input type="text" name="" id="time1" value="<?php echo date('Y-m-d H:i:s',$time1); ?>">-<input type="text" name="" id="time2" value="<?php echo date('Y-m-d H:i:s',$time2); ?>"><span id="sure" style="margin-left: 5px;cursor:pointer">确认</span></div>
        <div id="sy"><span style='padding: 5px 0px 5px 20px'>流量剩余金额：<span style='color:red;font-size:14px;'><?php echo round($arr_ye['flow'],2); ?>元</span></span></div>
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
                <tr><th colspan="3" align="center">流量兑换金额(共<span style="font-size: 16px;color: red;"><?php echo $total_dh/10; ?></span>元)</th></tr>
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
                        <td align="center"><?php echo $v['flow']/10; ?>元</td>
                        <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </table>
</div>
<script type="text/javascript">
    $(function(){
        $('#sure').click(function(){
            var time1 = $("#time1").val();
            var time2 = $("#time2").val();
            window.location.href = '<?php echo G_MODULE_PATH; ?>/test/liuliang_details/'+time1+"/"+time2;
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
</body>
</html>