<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
span a{
	   
	background-color: #161E22;
    display: inline-block;
    padding: 0px 20px;
    margin: 0px;
    cursor: pointer;
    border: 1px solid #BBB;
    overflow: visible;
    font: bold 12px 微软雅黑,arial,helvetica,sans-serif;
    text-decoration: none;
    white-space: nowrap;
    color: #fff;
    transition: background-color 0.2s ease-out 0s;
    background-clip: padding-box;
    border-radius: 3px;
    box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.3), 0px 2px 2px -1px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.3) inset;
    height: 25px;

}
</style>
</head>
<body>
<div class="header lr10" style="padding-top: 5px">
	<?php echo $this->headerment();?>
</div>
<div class="header lr10" style="padding-top: 5px">
	<span style="font-size: 16px">输入订单号：<input type="text" name="ddh" style="width: 200px;height: 22px;"><a style="line-height: 28px;margin-left: 5px;">确认</a></span>
</div>

<script type="text/javascript">
	$('a').click(function(){
		var xx = $("input[name='ddh']").val();
		$(this).attr('href','yitian/'+xx);
	})
</script>
</body>
</html> 