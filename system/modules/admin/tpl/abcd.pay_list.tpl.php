<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title></title>

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 

<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>

<style>

tbody tr{ line-height:30px; height:30px;} 

</style>

</head>

<body>

<div class="header lr10">

	<a href="/index.php/admin/abcd/goods">列表</a>

</div>

<div class="bk10"></div>


<div class="table-list lr10">

<!--start-->

  <table width="100%" cellspacing="0">

    <thead>

		<tr>

            <th width="50px" align="center">用户名</th>

            <th width="50px" align="center">用户类型</th>

            <th width="200px" align="center">商品名称</th>

            <th width="50px" align="center">商品期数</th>

            <th width="50px" align="center">参与次数</th>

            <th width="50px" align="center">消费金额</th>

            <th width="100px" align="center">时间</th>  

            <th width="100px" align="center">管理</th> 
		</tr>

    </thead>

    <tbody>

    	<?php 

		for($j=0;$j<count($pay_list);$j++){

		?>

		<tr>

			<td align="center">

				<?php  echo $members[$j];?>

			</td>

			<td align="center"><?php $user =  $this->db->GetOne("SELECT * from `@#_member` WHERE `uid` = ".$pay_list[$j]['uid']); if ($user['auto_user']==1) {echo '测试用户';} ?></td>

			<td align="center"><?php echo $pay_list[$j]['shopname']; ?></td>	

			<td align="center"><?php echo $pay_list[$j]['shopqishu']; ?></td>	

			<td align="center"><?php echo $pay_list[$j]['gonumber']; ?></td>	

			<td align="center"><?php echo $pay_list[$j]['moneycount']; ?></td>	

			<td align="center"><?php echo date("Y-m-d H:i:s",$pay_list[$j]['time']); ?></td>	   	

			<td align="center">

				[<a onclick="postData(<?php echo $pay_list[$j]['shopid']; ?>,<?php echo $pay_list[$j]['uid']; ?>);">指定</a>]         


			</td>
		</tr>

       <?php }  ?>

	

  	</tbody>

	

</table>

</div><!--table-list end-->

<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
	function postData(shopid,userid){
    	if(confirm('确定要指定吗?')){
    		$.post("/index.php/admin/abcd/add_goods/", { shopid: shopid, userid: userid },
			    function(data){
			       alert(data);
			});
    	}
	}
</script>

</body>

</html> 