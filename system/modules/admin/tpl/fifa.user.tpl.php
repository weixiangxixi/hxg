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

	<a href="/index.php/admin/fifa/history">列表</a>

</div>

<div class="bk10"></div>


<div class="table-list lr10">

<!--start-->

  <table width="100%" cellspacing="0">

    <thead>

		<tr>

            <th width="50px" align="center">用户名</th>

            <th width="50px" align="center">用户手机</th>

            <th width="200px" align="center">参与次数</th>

            <th width="50px" align="center">获得金额</th>

            <th width="50px" align="center">邀请用户</th>

            <th width="50px" align="center">邀请次数</th>

            <th width="100px" align="center">管理</th> 
		</tr>

    </thead>

    <tbody>

    	<?php 

		for($j=0;$j<count($pay_list);$j++){

		?>

		<tr>
			
          	<td align="center"><?php echo $pay_list[$j]['username']; ?></td>
          
			<td align="center"><?php echo $pay_list[$j]['mobile']; ?></td>	

			<td align="center"><?php $c = $this->db->GetList("SELECT * from `@#_fifa_guess_list` WHERE `uid` = ".$pay_list[$j]['uid']);echo count($c); ?></td>	

			<td align="center"><?php echo $pay_list[$j]['money']; ?></td>	

          	<td align="center"><a href="/admin/fifa/yaoqing/<?php echo $pay_list[$j]['uid']; ?>">查看</a></td>	

			<td align="center"><?php echo $pay_list[$j]['num']; ?></td>	   	

			<td align="center">			</td>
		</tr>

       <?php }  ?>

	

  	</tbody>

	

</table>

</div><!--table-list end-->

<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>


</body>

</html> 