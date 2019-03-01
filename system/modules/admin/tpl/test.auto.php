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
	<input type ="submit" value=" 压力测试1 " name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/auto/show'"/>
    <input type ="submit" value=" 压力测试2 " name="czjl_submit" class="button" onclick="window.location.href='/index.php/admin/autos/show'"/>
    <input type ="submit" value=" 压力测试3 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/admin/autob/show'"/>
    <input type ="submit" value=" 压力测试4 " name="gmjl_submit" class="button" onclick="window.location.href='/index.php/admin/autoc/show'"/>
</div>

</body>
</html> 