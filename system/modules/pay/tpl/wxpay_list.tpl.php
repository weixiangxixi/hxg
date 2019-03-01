<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header-title lr10">
	<b>微信公众号支付</b> 
	<span class="lr10"> | </span> 
	<a href="<?php echo WEB_PATH; ?>/pay/pay/add_wxpay" style="color:#ff0000; font-weight:bold"> 添加</a> 
	<span class="lr10"> | </span>
	
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th width="100px" align="center">商户名称</th>
            <th width="100px" align="center">添加时间</th>
            <th width="100px" align="center">状态</th>
            <th width="100px" align="center">管理</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($data as $pay): ?>
		<tr>
			<td align="center"><?php echo $pay['name']; ?></td>	
			<td align="center"><?php echo date("Y-m-d H:i:s",$pay['create_time']); ?></td>
			<td align="center" id="<?php echo $pay['id']; ?>"><?php if($pay['status'] == 1){echo "开启";}else{ echo "<span class='close' style='cursor:pointer' title='点击开启'>关闭</span>";} ?></td>
			<td align="center"><a id="<?php echo $pay['id']; ?>" href="####" class="delete">删除</a></td>
		</tr>
		<?php endforeach; ?>
  	</tbody>
</table>
</div><!--table-list end-->

<script>
	$('.close').click(function(){
		var xx = confirm("你确定开启吗?");
		var id = $(this).parent().attr('id');
		if(xx){
			window.location.href = "<?php echo G_MODULE_PATH; ?>/pay/open_wxpay/"+id;
		}
	})
	$('.delete').click(function(){
		var xx = confirm("你确定删除吗?");
		var id = $(this).attr('id');
		if(xx){
			window.location.href = "<?php echo G_MODULE_PATH; ?>/pay/delete_wxpay/"+id;
		}
	})
</script>
</body>
</html> 