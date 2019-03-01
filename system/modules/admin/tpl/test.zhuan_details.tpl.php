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
        	<?php foreach ($str_s as $key => $val) { ?>
            <table width="10%" style="float: left;">   
                <thead>
                    <tr><th colspan="2" align="center"><a href="<?php echo G_MODULE_PATH; ?>/test/zhuan_details/<?php echo $key; ?>" style="color: #fff"><?php echo $key; ?></a></th></tr>
                    <tr><th>ID</th><th>转账总金额</th></tr>
                </thead>
                <tbody>
                    <?php $total_money=0; foreach ($val as $k => $v) { $total_money+=$v; ?>
                    <tr>
                        <td align="center"><a href="<?php echo G_MODULE_PATH; ?>/test/zhuan_details/<?php echo $k.'/'.$key; ?>"><?php echo $k; ?></a></td>
                        <td align="center"><span style="color: red;font-size: 16px"><?php echo $v; ?></span>元</td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td align="center">总计</td>
                        <td align="center"><span style="color: red;font-size: 16px"><?php echo $total_money; ?></span>元</td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
        </table>
    <?php }else{ ?>
        <table width="100%" cellspacing="0">
            <table width="40%" style="float: left;">   
                <tbody>
                    <thead>
                        <tr><th colspan="4" align="center"><?php echo $time; ?></th></tr>
                        <tr><th colspan="1" align="center">用户id</th>
                        <th colspan="1" align="center">转账金额</th>
                        <th colspan="1" align="center">转账详情</th>
                        <th colspan="1" align="center">转账时间</th></tr>
                    </thead>
                    <?php foreach ($arr as $key => $val) { ?>
                        <tr>
                            <td align="center"><?php echo $val['uid']; ?></td>
                            <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money']; ?></span>元</td>
                            <td align="center"><?php echo $val['content']; ?></td>
                            <td align="center"><?php echo date('Y-m-d H:i:s',$val['time']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </table>
    <?php } ?>
</div>

</body>
</html> 