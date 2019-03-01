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
.header-title b{
	margin-right: 15px;
}
.header-title b span{
	font-size: 18px;
	color: red;
}
</style>

</head>

<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
	<a href="/index.php/admin/test/dadan" style="float:right;margin-right:20px;font-size:18px;"><input class="button" value="打印订单"></a>
</div>
<div class="header-title lr10">
	
	<b>订单详情</b>
	<b>实际充值总金额：<span><?php echo $sj_money; ?></span>元</b>
	<b>充值总金额：<span><?php echo $czje['sum_money']; ?></span>元</b>
	<b>商城卡充值总金额：<span><?php if($sck_money['sum_money'] == ''){echo 0;}else{echo $sck_money['sum_money'];} ?></span>元</b>
	<b>购买总金额：<span><?php echo $gm_money['sum_money']; ?></span>元</b>
	<b>中奖总金额：<span><?php echo $zj_money; ?></span>元</b>
</div>

<div class="bk10"></div>

<div class="table-list lr10">

<!--start-->

		<?php

	

		

			$shopid= $record['shopid'];		

			$shop = $this->db->GetOne("SELECT * FROM `@#_shoplist` where `id`='$shopid'");

			

			$qishu=array();

			

			if(!$shop['q_uid']){				

				$qishu['ren']='还未开奖';

				$qishu['ma']='还未开奖';

			}else{

				$qishu['ren']=get_user_name($user);

				$qishu['ma']=$shop['q_user_code'];

			}

		

		?>

		<div class="dingdan_content">

			<h3 style="clear:both;display:block; line-height:30px;"><?php echo $shop['title']; ?></h3>			

			<li><b class="api_b">剩余次数：</b><?php echo $shop['shenyurenshu']; ?> 人次	</li>		

			<li><b class="api_b">总需次数：</b><?php echo $shop['zongrenshu']; ?>人次 	</li>		

			<li><b class="api_b">商品期数：</b>第 <?php echo $shop['qishu']; ?> 期	</li>

			<li><b class="api_b">商品价格：</b><?php echo $shop['money']; ?></li>

			<li><b class="api_b"><font color="#ff0000">中奖人</font></b><?php echo $qishu['ren']; ?></li>

			<li><b class="api_b"><font color="#ff0000">中奖参与码</font></b><?php echo $qishu['ma']; ?></li>	

			<div class="bk10"></div>

			<li><b class="api_b">购买次数：</b><?php echo $record['gonumber']; ?>人次</li>

			<li class="yun_ma"><b class="api_b">获得参与码：</b><br/>			

			<?php  
				function str_replace_once($needle, $replace, $haystack) {
    				$needle2='>'.$needle;
    				$pos2 = strpos($haystack,$needle2);        
    				$pos = strpos($haystack, $needle);                        
    				if ($pos2) {
      					return $haystack;
    				}elseif($pos === false){                                
      					return '';
    				}else{
      				return substr_replace($haystack, $replace, $pos, strlen($needle));                                        
    				}
				}
                if(time()>1526227200){
                  //$record['goucode'] = str_replace($qishu['ma'],"<font color='red'>$qishu[ma]</font>",$record['goucode']);
                }else{
                  //$record['goucode'] = str_replace_once($qishu['ma'],"<font color='red'>$qishu[ma]</font>",$record['goucode']);
                }
				$record['goucode'] = str_replace($qishu['ma'],"<font color='red'>$qishu[ma]</font>",$record['goucode']);
                
				echo str_replace(',',"&nbsp;&nbsp;&nbsp;&nbsp;",$record['goucode']);

			?>

            </li>	

			</li>

		</div>


			

		<div class="dingdan_content_user" style="margin-left: 20px;">

			<li><b class="api_b">购买人ID：</b> <?php echo $user['uid'];?></li>

			<li><b class="api_b">购买人昵称：</b> <?php echo $user['username'];?></li>

			<li><b class="api_b">购买人邮箱：</b><?php echo $user['email'];?></li>		

			<li><b class="api_b">购买人手机：</b><?php echo $user['mobile'];?></li>

			<li><b class="api_b">购买人Q Q：</b><?php echo $user_dizhi['qq'];?></li>					

			<li><b class="api_b">购买时间：</b><?php echo date("Y-m-d H:i:s",$go_time);?></li>	

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

		<div class="bk10"></div>

        <?php 

			if($record['huode']){			

		?>

        

		<div class="dingdan_content_user">

			<?php $status=explode(",",$record['status']); ?>

			<form action="" method="post">

			<input type="hidden" name="code" value="<?php echo $record['id']; ?>"/>

			<li><b class="api_b">购买方式:</b> <font color="#0c0"><?php echo $record['pay_type']; ?>付款</font></li>

			<li><b class="api_b">当前状态:</b> <font color="#0c0"><?php echo $record['status']; ?></font></li>		

			<li><b class="api_b">订单状态:</b>

				<select name="status">
					<option value="<?php echo $status[1]; ?>"><?php echo $status[1]; ?></option>

					<option value="未发货">未发货</option>

					<option value="已发货">已发货</option>

					<option value="已完成">已完成</option>

					<option value="已作废">已作废</option>										

				 </select>

			</li>

			<li><b class="api_b">物流公司:</b>

			<select name="company">
			    <option value=""></option>
				<option value="惠享购配送" <?php if($record['company'] == '惠享购配送'){ echo 'selected';}; ?>>惠享购配送</option>

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

        <?php } ?>

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
            url:'/index.php/admin/dingdan/edit_dizhi',  
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