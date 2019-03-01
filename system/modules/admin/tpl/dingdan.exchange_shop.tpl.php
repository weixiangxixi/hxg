<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 
<style>
tbody tr{ line-height:30px; height:30px;} 
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
<div class="header lr10" style="height: 70px">
	<?php echo $this->headerment();?>
    <a href="/index.php/admin/test/dadan" style="float:right;margin-right:20px;font-size:18px;"><input class="button" value="打印订单"></a>
	<span class="lr10"> </span><span class="lr10"> </span>
</div>
<div class="header-data lr10">
    <form action="" method="post">
时间搜索: <input name="posttime1" type="text" id="posttime1" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($posttime1)?date("Y-m-d H:i:s",$posttime1):''?>"/> -  
      <input name="posttime2" type="text" id="posttime2" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($posttime2)?date("Y-m-d H:i:s",$posttime2):''?>"/>
        <script type="text/javascript">
           laydate.render({
              elem: '#posttime1' //指定元素
              ,type: 'datetime'
            });
            laydate.render({
              elem: '#posttime2' //指定元素
              ,type: 'datetime'
            });     
                        
        </script>
	  
    <select name="user_type" style="height: 34px;line-height: 16px;border: 1px solid #B4B4B4;background: #fff;border-radius: 3px;">
        <option value="uid" <?php if($user_type=='uid'){echo "selected";} ?>>用户uid</option>
        <option value="mobile" <?php if($user_type=='mobile'){echo "selected";} ?>>手机号码</option>
        <option value="username" <?php if($user_type=='username'){echo "selected";} ?>>用户昵称</option>
    </select>
    <input type="text" name="user" value="<?php echo $user; ?>" class="input-text" style="width: 160px" />
	<input type ="submit" value=" 搜索 " name="paixu_submit" class="button"/>
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">兑换用户</th>
            <th align="center">兑换件数</th>
            <th align="center">兑换时间</th>
            <th align="center">订单状态</th>
            <th align="center">管理</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>
      		<?php $username = get_user_name($v[uid]); ?>
            <tr>
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center"><?php echo _strcut($v['title'],0,25);?></td>              
                <td align="center"><?php echo $username; ?></td>
                <td align="center"><?php echo $v['amount']; ?>件</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
                <td align="center"><?php if(empty($v['company'])){echo "未发货";}else{echo "已发货";}?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_exchange_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
            <?php } ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
    
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>

</body>
</html> 