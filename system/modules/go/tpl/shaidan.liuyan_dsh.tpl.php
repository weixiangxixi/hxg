<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<script src="/statics/templates/yungou/js/mobile/jquery-1.9.1.min.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
body{ background-color:#fff}
input{
	width: 80%;
	height: 100%;
	padding: 5px;
	font-size: 14px;
	text-align: center;
	color: #f60;
}
</style>
</head>
<body>
<script>
function hueifu(id){
	if(confirm("您确认要删除该条留言")){
		window.location.href="<?php echo G_MODULE_PATH;?>/shaidan_admin/hf_del/"+id;
	}
}
</script>
<script type="text/javascript">
	$(function(){
		$(".action a:first-child").click(function(){
			var re = /^[0-9]*[0-9]$/i; //校验是否为数字
			var id = $(this).attr('id');
			var score = $(this).parent().parent().prev().children('input').val();
			if(re.test(score) && score%10===0) {
				$.ajax({
					data: {'id':id,'score':score},
					type: 'post',
					url: '/index.php/go/shaidan_admin/shtg_hf',
					success:function(msg){
						window.location.reload();
					}
				})
			}else{
				alert("请输入整十的福分");
			}
		})
	})
</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mgr_table">
	<tr class="thead" align="center">
		<td width="5%" height="30">ID</td>
		<td width="10%">晒单回复会员ID</td>
		<td width="40%">晒单回复内容</td>
		<td width="10%">晒单回复时间</td>
		<td width="10%">奖励福分</td>
		<td width="10%">管理</td>
	</tr>
	<?php foreach($shaidan as $v) { ?>
	<tr align="center" class="mgr_tr">
		<td height="30"><?php echo $v['id'];?></td>		
		<td><?php echo $v['sdhf_userid']; ?></td>
		<td><?php echo _strcut($v['sdhf_content'],50);?></td>
		<td><?php echo date("Y-m-d h:i",$v['sdhf_time']);?></td>
		<td>
			<input type="text" name="score" placeholder="请输入福分" />
		</td>
		<td class="action"><span style="margin-right: 10px">[<a id="<?php echo $v['id']; ?>" href="javascript:;">通过</a>]</span>|<span style="margin-left: 10px">[<a onClick="hueifu(<?php echo $v['id'];?>)" href="javascript:;">删除</a>]</span></td>
	</tr>
	<?php } ?> 
</table>

<?php if($total>$num) {?> 

<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

<?php } ?> 	
</div>
</body>
</html> 
