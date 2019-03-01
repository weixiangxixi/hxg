<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
a {
  cursor:pointer;
}
.text2 {
    width: 80px;
    cursor: pointer;
}
.text3 {
    width: 160px;
    cursor: pointer;
}
.calendar {
    top: 0px!important;
}
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
<div class="header lr10">
  <a href="/index.php/admin/fifa/history">列表</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/setting">活动设置</a>
</div>
    <div class="bk10"></div>


<div class="table_form lr10">	
<form action="" method="post" id="myform">
<table width="100%" class="lr10">
  <tr>
    <td width="100">开启竞猜</td>
    <td>
     <input name="status" value="1" type="radio" <?php if($config['status']==1)echo "checked";?>> 开启
	 <input name="status" value="0" type="radio" <?php if($config['status']==0)echo "checked";?>> 关闭 	 
	 <span class="lr10"> </span>
	 <font color="red"></font>
	</td>
  </tr>
  <tr>
    <td>总金额</td>
    <td><input type="number" class="input-text wid150" name="money" value="<?php echo $config['money']; ?>"/></td>
  </tr> 
  <tr>
    <td>初始竞猜人数</td>
    <td><input type="number" class="input-text" name="num1" value="<?php echo $config['num1']; ?>"/>-<input type="number" class="input-text" name="num2" value="<?php echo $config['num2']; ?>"/></td>
  </tr> 
  
	<tr>
    	<td width="100"></td> 
   		<td> <input type="submit" value=" 提交 " name="dosubmit" class="button"></td>
    </tr>
</table>
</form>

</div>
  




</body>
</html> 