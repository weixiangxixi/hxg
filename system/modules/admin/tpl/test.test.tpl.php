<!DOCTYPE html>
<html>
<head>
	<title></title>

</head>
<body>
	<div style="width: 1500px;height: 500px">
		<div style="width: 14%;height: 100%;float: left;border: 1px solid #f00;">
			<form action="" method="post" style="margin-top: 10px">
				<input type="text" name="uid2" placeholder="可输入用户手机号" />
				<select name="uid" id="select">
					<?php foreach ($data as $key => $val): ?>
						<option value="<?php echo $val['uid'] ?>" <?php if($uid == $val['uid']){echo "selected";} ?>>
							<?php if(!empty($val['mobile'])){echo $val['mobile'];}else{echo $val['email'];} ?>
						</option>
					<?php endforeach ?>
				</select>
				<input style="margin-top: 4px;" type="submit" name="" value="查询" />
			</form>
			<div style="text-align: center">
				经验：<?php echo $jingyan; ?>
			</div>
			<div style="text-align: center">
				账户余额：<?php echo $huiyuan['money']; ?>
			</div>
			
		</div>
		<div style="width: 26%;height: 100%;float: left;border: 1px solid #f00;">
			<div style="width: 50%;float: left;text-align: center">微信充值记录(手机号)</div>
			<div style="width: 50%;float: left;text-align: center">充值时间</div>
			<?php foreach ($wx_data as $key => $val): ?>
				<?php $wx_money += $val['money']; $total1 += 1;?>
				<div style="width: 45%;float: left;text-align: center;border: 1px solid #0f0"><?php echo $val['money']; ?>元</div>
				<div style="width: 53%;float: left;text-align: center;border: 1px solid #00f"><?php echo date("Y-m-d H:i:s",$val['update_time']); ?></div>
			<?php endforeach ?>	
			<div style="text-align: center">
				微信充值总额：<?php echo $wx_money; ?>
			</div>	
			<div style="text-align: center">
				共<?php echo $total1; ?>条
			</div>	
		</div>
		<div style="width: 26%;height: 100%;float: left;border: 1px solid #f00;">
			<div style="width: 55%;float: left;text-align: center">支付宝充值记录(手机号)</div>
			<div style="width: 45%;float: left;text-align: center">充值时间</div>
			<?php foreach ($zfb_data as $key => $val): ?>
				<?php $zfb_money += $val['money']; $total2 += 1;?>
				<div style="width: 45%;float: left;text-align: center;border: 1px solid #0f0"><?php echo $val['money']; ?>元</div>
				<div style="width: 50%;float: left;text-align: center;border: 1px solid #00f"><?php echo date("Y-m-d H:i:s",$val['update_time']); ?></div>
			<?php endforeach ?>
			<div style="text-align: center">
				支付宝充值总额：<?php echo $zfb_money; ?>
			</div>
			<div style="text-align: center">
				共<?php echo $total2; ?>条
			</div>
		</div>
		<div style="width: 26%;height: 100%;float: left;border: 1px solid #f00;">
			<div style="width: 55%;float: left;text-align: center">转账充值记录</div>
			<div style="width: 45%;float: left;text-align: center">充值时间</div>
			<?php foreach ($zz as $key => $val): ?>
				<?php $zz_money += $val['money']; $total3 += 1;?>
				<div style="width: 45%;float: left;text-align: center;border: 1px solid #0f0"><?php echo $val['money']; ?>元</div>
				<div style="width: 50%;float: left;text-align: center;border: 1px solid #00f"><?php echo date("Y-m-d H:i:s",$val['time']); ?></div>
			<?php endforeach ?>
			<div style="text-align: center">
				转账总额：<?php echo $zz_money; ?>
			</div>
			<div style="text-align: center">
				共<?php echo $total3; ?>条
			</div>
		</div>
		<div style="width: 6%;height: 100%;float: left;border: 1px solid #f00;">
			<div style="text-align: center;">充值总额：<?php echo $wx_money+$zfb_money+$zz_money; ?>元</div>
			
		</div>
		<div style="width: 150px;">已领红包金额：<?php echo $money_yes; ?></div>
		<div style="width: 150px;">未领红包金额：<?php echo $money_no; ?></div>
		<div><?php echo $count; ?></div>
		<!-- <div><?php var_dump($uq_brr); ?></div> -->
		<div><?php var_dump($drr); ?></div>
		<div><?php var_dump($xrr); ?></div>
	</div>

</body>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>


</html>