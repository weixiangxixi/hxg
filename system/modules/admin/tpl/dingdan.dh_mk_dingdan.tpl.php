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
.header-data li{ line-height:40px;}
.soso_message{ text-align:center; height:80px; line-height:80px;  border-top:5px solid #FFBE7A;border-bottom:5px solid #FFBE7A;}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>

<div class="bk10"></div>
<div class="table-list lr10">
<?php if(is_array($record)): ?>
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">兑换件数</th>
            <th align="center">兑换日期</th>
            <th align="center">订单状态</th>
            <th align="center">管理</th>
		</tr>
    </thead>
    <tbody>
    	<?php foreach($record as $v): ?>
        	<tr>
                <td align="center"><?php echo $v['code'];?></td>
                <td align="center"><?php echo $v['title'];?></td>              
                <td align="center"><?php echo $v['amount']; ?>人次</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
                <td align="center"><?php echo $v['status'];?></td>
                <td align="center"><a onclick="fh(<?php echo $v['id']; ?>)" style="cursor: pointer;">确认发货</a></td>
            </tr>
        <?php endforeach; ?>
  	</tbody>
</table>
 <?php endif; ?>
<?php if(!$record): ?>
	<div class="soso_message">
    	未搜索到信息.....
    </div>
<?php endif; ?>
<div class="btn_paixu"></div>
    
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->

<script>
    function fh(id){
        $.ajax({
            url: '/index.php/admin/dingdan/dh_mk_fahuo/'+id,
            success:function(msg){
                window.location.reload();
            }
        })
    }
</script>
</body>
</html> 