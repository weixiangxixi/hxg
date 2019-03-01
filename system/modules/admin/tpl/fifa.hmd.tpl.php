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

<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="#" method="post">
  <table width="100%" cellspacing="0">
  	 <tr>
			<td width="220" align="right">搜索条件：
				<select name="sousuo">
					<option value="id">会员uid</option>
					<option value="nickname" >会员昵称</option>
					<option value="email">会员邮箱</option>
					<option value="mobile">会员手机</option>
				</select>
			</td>
			<td width="120"><input type="text" name="content" class="input-text"></td>
			<td>		
            <input type="submit" class="button" name="submit" value="确认搜索" >
            </td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php 
			if(!empty($members)){
		?>			
			<tr>
				<td width="100px" align="center">UID</td>
				<td width="100px" align="center">用户名</td>
				<td width="100px" align="center">邮箱</td>
				<td width="100px" align="center">手机</td>
				<td width="100px" align="center">账户金额</td>
				<td width="100px" align="center">黑名单</td>
				<td width="100px" align="center">管理</td>       
			</tr>

		<?php 
			foreach($members as $v){
		?>
			<tr>
				<td align="center"><?php echo $v['uid']; ?></td>
				<td align="center"><?php echo $v['username']; ?></td>	
				<td align="center"><?php echo $v['email']; ?></td>	
				<td align="center"><?php echo $v['mobile']; ?></td>	
				<td align="center"><?php echo $v['money']; ?></td>	
				<td align="center"><?php $xxx = $this->db->GetOne("SELECT * FROM `@#_fifa_hmd` WHERE `uid` = '$v[uid]'");if($xxx){echo "是";}else{echo "否";} ?></td>	
				<td align="center">
					<a href="<?php echo G_MODULE_PATH; ?>/fifa/add_hmd/<?php echo $v['uid'];?>"><?php $xxx = $this->db->GetOne("SELECT * FROM `@#_fifa_hmd` WHERE `uid` = '$v[uid]'");if($xxx){echo "撤销黑名单";}else{echo "添加黑名单";} ?></a>
				</td>            	
			</tr>
		<?php 
			} }
		?>
</table>
</form>
</div>
<script type="text/javascript">
    $(function(){
        $('#Page_Next').on('click',function(){
            console.log(1);
        })
    })
</script>



</script>
</body>
</html> 