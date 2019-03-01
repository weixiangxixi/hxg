<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script> 
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}

.xxxx {
	display: block;
	height: 120px;
	margin: 10px 10px;
	width: 100%;
}
.xxxx p {
	width: 100%;
}
.xxxx p:first-child span:first-child {
	border: 1px solid #B4B4B4;
	padding: 5px 10px;
	margin-top: 20px;
}
.xxxx p:first-child span {
	width: 10%;
	border: 1px solid #B4B4B4;
	border-left-style: none;
	padding: 5px 10px;
	display: inline-block;
	font-size: 18px;
	text-align: center;
}
.xxxx p:nth-child(2) span:first-child {
	border: 1px solid #B4B4B4;
	padding: 5px 10px;
	border-top-style: none;
}
.xxxx p:nth-child(2) span {
	width: 10%;
	border: 1px solid #B4B4B4;
	border-left-style: none;
	border-top-style: none;
	padding: 5px 10px;
	display: inline-block;
	font-size: 18px;
	text-align: center;
}

.show {
	display: block;
	margin: 10px 10px;
	float: left;
	width: 450px;
}
.show p:first-child {
	border: 1px solid #B4B4B4;
	width: 100px;
}
.show p:nth-child(2) {
	width: 80px;
}
.show p:nth-child(3) {
	width: 160px;
}
.show p {
	float: left;
	text-align: center;
	border: 1px solid #B4B4B4;
	border-left-style: none;
	font-size: 16px;
	padding: 10px 10px;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
  	<tr>
			<td width="220" align="right">搜索条件：
				<select name="sousuo">
					<option value="id" <?php if($type == 'id'){echo "selected";} ?>>会员uid</option>
					<option value="nickname" <?php if($type == 'nickname'){echo "selected";} ?>>会员昵称</option>
					<option value="email" <?php if($type == 'email'){echo "selected";} ?>>会员邮箱</option>
					<option value="mobile" <?php if($type == 'mobile'){echo "selected";} ?>>会员手机</option>
				</select>
			</td>
			<td width="120"><input type="text" name="content" class="input-text" value="<?php echo $user; ?>"></td>
			<td>		
            <input type="submit" class="button" name="submit" value="确认搜索" >
            </td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>

</table>
</form>
</div>
<div class="xxxx">
	<p><span>UID</span><span>用户名</span><span>邮箱</span><span>手机</span><span>账户余额</span><span>充值金额</span><span>操作</span></p>
	<p><span><?php if(empty($data['uid'])){echo "无";}else{echo $data['uid'];} ?></span><span><?php if(empty($data['username'])){echo "无";}else{echo $data['username'];} ?></span><span><?php if(empty($data['email'])){echo "无";}else{echo $data['email'];} ?></span><span><?php if(empty($data['mobile'])){echo "无";}else{echo $data['mobile'];} ?></span><span><?php if(empty($data['money'])){echo "无";}else{echo $data['money'];} ?></span><span><input type="text" name="" value="" style="width: 100%;height: 20px;text-align: center;font-size: 16px" placeholder="输入充值金额"></span><span><a href="" style="cursor: pointer;">充值</a></span></p>
</div>
<?php foreach ($arr as $key => $val) { ?>
<div class="show">
	<p>通过线下充值</p><p><?php echo $val['money']; ?>元</p><p><?php echo date("Y-m-d H:i:s",$val['time']); ?></p>
</div>
<?php } ?>

</div>
<script type="text/javascript">
	$('.xxxx input').keyup(function(){
		var x = $(this).val();
		var y = $.trim(x);
		var href = "/index.php/member/member/xxcz/"+'<?php echo $data["uid"]; ?>/'+y;
		if(y != ''){
			console.log(1);
			$('.xxxx a').attr("href",href);
		}
	})
</script>
</body>
</html> 