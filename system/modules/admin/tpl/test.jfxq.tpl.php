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
<div class="table-list lr10">
    <table width="100%" cellspacing="0">
    	
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">签到总积分(>4000)</th></tr>
            </thead>
            <tbody>
               <?php foreach ($brr as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['total']; ?></span></td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">当天消费积分(>500)</th></tr>
            </thead>
            <tbody>
               <?php foreach ($ccc as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['gm']; ?></span></td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">签到次数</th><th align="center">注册时间</th><th align="center">注册天数</th></tr>
            </thead>
            <tbody>
               <?php foreach ($cs as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['num']; ?></span></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['time']; ?></span></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['ts']; ?></span></td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
    </table>
</div>

</body>
</html> 