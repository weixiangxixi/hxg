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

			<li><b class="api_b">兑换人ID：</b> <?php echo $user['uid'];?></li>

			<li><b class="api_b">兑换人昵称：</b> <?php echo $user['username'];?></li>

			<li><b class="api_b">兑换人邮箱：</b><?php echo $user['email'];?></li>		

			<li><b class="api_b">兑换人手机：</b><?php echo $user['mobile'];?></li>

			<li><b class="api_b">兑换人Q Q：</b><?php echo $user_dizhi['qq'];?></li>					

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

			<li><b class="api_b" style="text-align: center;font-size: 16px;">商品名称：</b><span style="text-align: center;font-size: 18px;color: red;margin-left: 20px"><?php echo $str['title']; ?></span></li>

			<li><b class="api_b" style="text-align: center;font-size: 16px;">兑换数量：</b><span style="text-align: center;font-size: 18px;color: red;margin-left: 20px"><?php echo $record['amount']; ?></span></li>


		</div>			

		<div class="bk10"></div>     

		<div class="dingdan_content_user">

			<form action="" method="post">

			<input type="hidden" name="code" value="<?php echo $record['id']; ?>"/>

			<li><b class="api_b">当前状态:</b> <font color="#0c0"><?php echo $record['status']; ?></font></li>		

			<li><b class="api_b">订单状态:</b>

				<select name="status">
					<option value="<?php echo $record['status']; ?>"><?php echo $record['status']; ?></option>

					<option value="未发货">未发货</option>

					<option value="已发货">已发货</option>

					<option value="已完成">已完成</option>

					<option value="已作废">已作废</option>										

				 </select>

			</li>

			<li><b class="api_b">物流公司:</b>

			<select name="company">

				<option value="惠享配送" <?php if($record['company'] == '惠享配送'){ echo 'selected';}; ?>>惠享配送</option>

				<option value="京东快递" <?php if($record['company'] == '京东快递'){ echo 'selected';}; ?>>京东快递</option>

				<option value="中国邮政" <?php if($record['company'] == '中国邮政'){ echo 'selected';}; ?>>中国邮政</option>

				<option value="申通快递" <?php if($record['company'] == '申通快递'){ echo 'selected';}; ?>>申通快递</option>

				<option value="圆通速递" <?php if($record['company'] == '圆通速递'){ echo 'selected';}; ?>>圆通速递</option>

				<option value="顺丰速运" <?php if($record['company'] == '顺丰速运'){ echo 'selected';}; ?>>顺丰速运</option>

				<option value="韵达快递" <?php if($record['company'] == '韵达快递'){ echo 'selected';}; ?>>韵达快递</option>

				<option value="天天快递" <?php if($record['company'] == '天天快递'){ echo 'selected';}; ?>>天天快递</option>

				<option value="中通速递" <?php if($record['company'] == '中通速递'){ echo 'selected';}; ?>>中通速递</option>

				<option value="增益速递" <?php if($record['company'] == '增益速递'){ echo 'selected';}; ?>>增益速递</option>										

			 </select>

			</li>

			<li><b class="api_b">快递单号:</b><input type="text" name="company_code" value="<?php echo $record['company_code']; ?>" class="input-text wid150"> 填写物流公司快递单号</li>

			<li><b class="api_b">快递运费:</b><input type="text" name="company_money" value="<?php echo $record['company_money']; ?>"  class="input-text wid150"> 元 </li>

			

			<li><input type="submit" class="button" value="  更新  " name="submit" /></li>		

			</form>

		</div>



		<div class="dingdan_content_user" id="dizhi" style="margin-left: 20px;">

		

			<form method="post" class="dizhi_data">
				<input type="hidden" name="rid" value="<?php echo $record['id']; ?>" >
			<li><b class="api_b">省份:</b>

				<select id="s_province" name="province"></select>

			</li>

			<li><b class="api_b">城市:</b>

			 <select id="s_city" name="city" ></select>

			</li>
			<li><b class="api_b">区/县:</b>

			<select id="s_county" name="county"></select>

			</li>
			<li><b class="api_b">街道地址:</b><input type="text" name="place"  class="input-text wid150"></li>
			<li><b class="api_b">联系人:</b><input type="text" name="user"  class="input-text wid150"></li>

			<li><b class="api_b">手机号码:</b><input type="text" name="phone"  class="input-text wid150"> </li>

			

			<li><input type="botton" class="button" value="修改" name="submit" style="width: 20px;" id="post_dizhi" /></li>		

			</form>

		</div>


</div><!--table-list end-->


<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script class="resources library" src="/statics/templates/yungou/js/mobile/area1.js" type="text/javascript"></script>
<script type="text/javascript">_init_area();</script>
<script type="text/javascript">
var Gid  = document.getElementById ;
var showArea = function(){
	Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" + 	
	Gid('s_city').value + " - 县/区" + 
	Gid('s_county').value + "</h3>"
							}
Gid('s_county').setAttribute('onchange','showArea()');
</script>
<script>
	function edit_dizhi(){
		$('#dizhi').show();
	}
	$("#post_dizhi").click(function(){
		var formdata = $('.dizhi_data').serialize();
		$.ajax({  
            type:'post',    
            url:'/index.php/admin/dingdan/edit_exchange_dizhi',  
            data:formdata, 
            dataType:'json',  
            success:function(data){  
                //console.log(data);
                if (data==0) {
                	location.reload();
                }
            }  
        }); 
	});

</script>

</body>

</html> 