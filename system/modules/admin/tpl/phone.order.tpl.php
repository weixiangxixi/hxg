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
.calendar, .calendar table{margin-top: 100px;}
</style>
</head>
<body>
<div class="header lr10" style="height: 60px">
	<?php echo $this->headerment();?>
	<span class="lr10"> </span><span class="lr10"> </span>
    <form action="" method="post">
 时间搜索: <input name="posttime1" type="text" id="posttime1" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($posttime1)?date("Y-m-d H:i:s",$posttime1):''?>"/> -  
 		  <input name="posttime2" type="text" id="posttime2" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($posttime2)?date("Y-m-d H:i:s",$posttime2):''?>"/>
			<script type="text/javascript">
					date = new Date();
					Calendar.setup({
								inputField     :    "posttime1",
								ifFormat       :    "%Y-%m-%d",
								showsTime      :    true,
								timeFormat     :    "24"
					});
					Calendar.setup({
								inputField     :    "posttime2",
								ifFormat       :    "%Y-%m-%d",
								showsTime      :    true,
								timeFormat     :    "24"
					});
							
			</script>

			<select name="phone">
			<option value="<?php echo $mobile; ?>"><?php if(!empty($mobile)){echo $mobile;}else{echo "请选择手机号码";} ?></option>
			<?php foreach ($arr as $val) { ?>
			<option><?php echo $val; ?></option>
			<?php } ?>
			</select>
			<!-- <input type="text" name="yonghuzhi" class="input-text wid100" value="<?php echo !empty($yonghuzhi)?$yonghuzhi:''; ?>"/> -->
			<input class="button" type="submit" name="sososubmit" value="搜索">
			<a href="<?php echo G_MODULE_PATH; ?>/phone/set_exel">导出exel</a>
</form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="60%" cellspacing="0" >
    <thead>
		<tr>
            <th align="center">商品标题</th>
            <!-- <th align="center">购买用户</th> -->
            <th align="center">商品总价</th>
            <th align="center">秒款</th>
            <!-- <th align="center">提交时间</th> -->
		</tr>
    </thead>
    <tbody>

		<?php foreach($list AS $key=>$v) {	?>		
            <tr>
                <td align="center" width="40%">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>">
                <?php if($v['dh'] == 1 || $v['hd'] == 1){echo '<em style="font-size:16px;color:red;">'.$v['shopqishu'].'</em>';}else if($v['cateid'] == '177'){echo "<em style='font-size:16px;color:red;'>(双11半价)</em>第(".$v['shopqishu'].")期";}else{echo "第(".$v['shopqishu'].")期";} ?><?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
              
                <td align="center">￥<?php $shop=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=".$v['shopid']); if($shop['cateid']=='170'){echo $shop['str3'];}else{echo $v['price'];} ?>元<?php if($v['dh'] == 1){echo '<em style="font-size:16px;color:red">（'.$v['amount'].'件）</em>';} ?></td>
                <td class="money" width="30%" align="center"><input type="" name="" value="<?php echo $v['second_money']; ?>" onblur="a(this.value,<?php if($v['dh'] == 1 || $v['hd'] == 1){echo 0;}else{echo $v['shopid'];} ?>);" style="height: 30px;width: 70%;text-align: center;border-color: #ff0000"/>元</td>
                <!-- <td align="center"><?php echo $v['time'];?></td> -->
            </tr>
            <?php } ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
<div style="position: fixed;top: 90%;left: 62%;">
<span style="font-size: 16px;">总金额：<span style="font-size: 20px;color: red" id="total">0</span>元</span>
</div>
<div id="pages" style="position: fixed;top: 92%;left: 62%;"><span style="font-size: 16px">共<span style="font-size: 18px;color: red" id="total2">0</span>条</span></div>
</div><!--table-list end-->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	$(function(){
		var y = 0;
		var z = 0;
		$('td.money').each(function(index,element) {
			var x = $('td.money').eq(index).find('input').val();
			if(x != ''){
				y = parseInt(y) + parseInt(x);
				z = parseInt(z) + parseInt(1);
			}
		})
		$('#total').text(y);
		$('#total2').text(z);
	})
	function a(value,id){
		if(id == 0){
			alert("这个是兑换商品，修改密码请联系管理人员");
			return false;
		}
		$.ajax({
			data:{'money':value,'id':id},
			type: 'POST',
			url: '<?php echo WEB_PATH.'/admin/phone/ajax_second_money';?>',
			success:function(data){
				console.log(data);
			}
		});
		var y = 0;
		var z = 0;
		$('td.money').each(function(index,element) {
			var x = $('td.money').eq(index).find('input').val();
			if(x != ''){
				y = parseInt(y) + parseInt(x);
				z = parseInt(z) + parseInt(1);
			}
		})
		$('#total').text(y);
		$('#total2').text(z);
	}
</script>

<script>
</script>
</body>
</html> 