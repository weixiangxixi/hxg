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

</head>
<body>

<div class="table-list lr10">
	<?php if(!$time){ ?>
    <table width="100%" cellspacing="0">
    	<?php foreach ($crr as $key => $val) { ?>
        <table width="10%" style="float: left;">   
            <thead>
                <tr><th colspan="2" align="center"><a href="<?php echo G_MODULE_PATH; ?>/test/lottery_details/<?php echo $val['time2']; ?>" style="color: #fff"><?php echo $val['time']; ?></a></th></tr>
            </thead>
            <tbody>
               
                <tr>
                    <td align="center">参与人数</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['count2']; ?></span>人(次数<?php echo $val['count']; ?>次)</td>
                </tr>
                <tr>
                    <td align="center">奖品</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money_yes']; ?></span>元</td>
                </tr>
                <!-- <tr>
                    <td align="center">未领红包</td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money_no']; ?></span>元</td>
                </tr> -->
               
            </tbody>
        </table>
        <?php } ?>
    </table>
    <?php }else{ ?>
    	<table width="100%" cellspacing="0">
	        <table width="50%" style="float: left;">   
	            <thead>
	            	<tr><th colspan="4" align="center"><?php echo substr($time, 0, 4)."-".substr($time, 4, 2)."-".substr($time, 6); ?></th></tr>
	                <tr><th colspan="1" align="center">用户id</th>
	                <th colspan="1" align="center">红包金额</th>
	                <th colspan="1" align="center">获得红包时间</th></tr>
	            </thead>
	            <tbody>
	            	<?php foreach ($data as $key => $val) { ?>
	            	<?php $arr = explode(',', $val['amount']); $money = 0; foreach ($arr as $k => $v) {
	            		$money += $v;
	            	} ?>
	                <tr>
	                    <td align="center"><?php echo $val['uid']; ?></td>
	                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $money; ?></span>元(<?php echo $val['amount']; ?>)</td>
	                    <td align="center"><?php echo date("Y-m-d H:i:s",$val['create_time']); ?></td>
	                </tr>
	            	<?php } ?>
	            </tbody>
	        </table>
	    </table>
    <?php } ?>
</div>

</body>
</html> 