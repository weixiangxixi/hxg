<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title></title>

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

<style type="text/css">

tr{height:40px;line-height:40px}

.dingdan_content{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}

.dingdan_content li{ float:left;width:310px;}

.dingdan_content_user{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}

.dingdan_content_user li{ line-height:25px;}

.api_b{width:80px; display:inline-block;font-weight:normal}

.yun_ma{ word-break:break-all; width:200px; background:#fff; overflow:auto; height:100px; border:5px solid #09F; padding:5px;}

select{height: 30px; padding-left: 15px; padding-right: 15px; border-radius: 5px;margin-top: 10px; margin-bottom: 10px;}
.input-text{border-radius: 3px; margin-top: 10px; margin-bottom: 10px;}

</style>

</head>

<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
	<a href="/index.php/admin/test/dadan" style="float:right;margin-right:20px;font-size:18px;"><input class="button" value="打印订单"></a>
</div>
<div class="header-title lr10">
	
	<b>订单详情</b>

</div>

<div class="bk10"></div>

<div class="table-list lr10">

<!--start-->

		<div class="dingdan_content_user">

			<li><b class="api_b">用户ID：</b> <?php echo $user['uid'];?></li>

			<li><b class="api_b">用户昵称：</b> <?php echo $user['username'];?></li>

			<li><b class="api_b">用户邮箱：</b><?php echo $user['email'];?></li>		

			<li><b class="api_b">用户手机：</b><?php echo $user['mobile'];?></li>

			<li><b class="api_b">用户Q Q：</b><?php echo $user_dizhi['qq'];?></li>					

			<li><b class="api_b">兑换时间：</b><?php echo date("Y-m-d H:i:s",$record['create_time']);?></li>	

            <li><b class="api_b">收货信息：</b><?php 

			if($user_dizhi){
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;收货地址:".$user_dizhi['sheng'].' - '.$user_dizhi['shi'].' - '.$user_dizhi['xian'].' - '.$user_dizhi['jiedao'];

				echo "<br>";

				//echo "&nbsp;&nbsp;&nbsp;邮编:".$user_dizhi['youbian'];				

				//echo "<br>";

				echo "&nbsp;&nbsp;&nbsp;收货人:".$user_dizhi['shouhuoren'];
				
				echo "<br>";

				echo "&nbsp;&nbsp;&nbsp;电话:".$user_dizhi['mobile'];

				echo "<br>";
				

				//echo "&nbsp;&nbsp;&nbsp;QQ:".$user_dizhi['qq'];
				

			}else{

				echo "该用户未填写收货信息,请自行联系买家！";

			}

			?></li>			

		</div>	

		<div class="dingdan_content_user" style="margin-left: 20px;">

			<li><b class="api_b" style="text-align: center;font-size: 16px;">商品名称：</b><span style="text-align: center;font-size: 18px;color: red;margin-left: 20px"><?php echo $record['title']; ?></span></li>
			<li><b class="api_b" style="text-align: center;font-size: 16px;">兑换数量：</b><span style="text-align: center;font-size: 18px;color: red;margin-left: 20px"><?php echo $record['amount']; ?></span></li>


		</div>			

		<div class="bk10"></div>     

		<div class="dingdan_content_user">

			<form action="" method="post">

			<input type="hidden" name="code" value="<?php echo $record['id']; ?>"/>

			<li><b class="api_b">物流公司:</b>

			<select name="company">

				<option value="惠享配送" <?php if($record['company'] == '惠享配送'){ echo 'selected';}; ?>>惠享配送</option>

				<option value="京东快递" <?php if($record['company'] == '京东快递'){ echo 'selected';}; ?>>京东快递</option>

				<option value="圆通快递" <?php if($record['company'] == '圆通快递'){ echo 'selected';}; ?>>圆通快递</option>

				<option value="顺丰快递" <?php if($record['company'] == '顺丰快递'){ echo 'selected';}; ?>>顺丰快递</option>

				<option value="韵达快递" <?php if($record['company'] == '韵达快递'){ echo 'selected';}; ?>>韵达快递</option>				

			 </select>

			</li>

			<li><b class="api_b">快递单号:</b><input type="text" name="company_code" value="<?php echo $record['company_code']; ?>" class="input-text wid150"> 填写物流公司快递单号</li>

			<li><b class="api_b">快递运费:</b><input type="text" name="company_money" value="<?php echo $record['company_money']; ?>"  class="input-text wid150"> 元 </li>

			

			<li><input type="submit" class="button" value="  更新  " name="submit" /></li>		

			</form>

		</div>



</div><!--table-list end-->


<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script class="resources library" src="/statics/templates/yungou/js/mobile/area1.js" type="text/javascript"></script>

</body>

</html> 