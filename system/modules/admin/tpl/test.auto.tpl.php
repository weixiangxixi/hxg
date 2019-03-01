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

<div class="header-data lr10" style="margin-top: 20px">
	<input type ="submit" value=" 压力测试1(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show'"/>
	<input type ="submit" value=" 压力测试2(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show_2'"/>
	<input type ="submit" value=" 压力测试3(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show_3'"/>
	<input type ="submit" value=" 压力测试4(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show_4'"/>
	<input type ="submit" value=" 压力测试5(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show_5'"/>
	<input type ="submit" value=" 压力测试6(小) " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show_6'"/>
    <input type ="submit" value=" 压力测试1(大) " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show'"/>
    <input type ="submit" value=" 压力测试2(大) " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show_2'"/>
    <input type ="submit" value=" 压力测试3(大) " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show_3'"/>
    <input type ="submit" value=" 压力测试4(大) " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show_4'"/>
    <input type ="submit" value=" 压力测试5(大) " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show_5'"/>
    <input type ="submit" value=" 压力测试3 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/admin/autob/show'"/>
    <input type ="submit" value=" 压力测试4 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/admin/autoc/show'"/>
    <input type ="submit" value=" 批量导入会员 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/member/member/daorumember'"/>
    <input type ="submit" value=" 压力测试注册 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/admin/auto_register/show'"/>
</div>

</body>
</html> 