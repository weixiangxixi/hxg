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
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
    <div class="bk10"></div>


<div class="header-data lr10">
	<span class="lr10"> </span><span class="lr10"> </span>
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
            <select class="input-text text2" name="cate">
                <option value="uid" <?php if($cate == 'uid'){echo "selected";} ?>>用户uid</option>
                <option value="username" <?php if($cate == 'username'){echo "selected";} ?>>用户名称</option>
                <option value="mobile" <?php if($cate == 'mobile'){echo "selected";} ?>>用户手机</option>
            </select>
            <input name="sel" type="text" id="sel" class="input-text text3" value="<?php echo !empty($sel)?$sel:''?>"/>
            <input class="button" type="submit" name="sososubmit" value="搜索">
            <p><a href='/index.php/admin/dingdan/wxpay'><input class="button" type="button" value="微信" style="font-size : 60px;height: 60px;float: right;" ></a>
            <a href='/index.php/admin/dingdan1/alipay'><input class="button" type="button" value="支付宝" style="font-size : 60px;height: 60px;float: right;" ></a></p>
</form>

</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
            <th align="center">用户手机</th>
            <th align="center">用户名称</th>
            <th align="center">充值金额</th>
            <th align="center">充值时间</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>

                <td align="center"><?php echo $v['mobile']; ?></td>              
                <td align="center"><?php echo $v['username']; ?></td>
                <td align="center"><span style="font-size: 50px;color:red;"><?php echo $v['money']; ?>元</span></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
            </tr>
            <?php } ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
    <span>总金额：
    <?php 
        $k = 0;
        // foreach ($recordlist as $k => $v) {
        //     if(strpos($v['shopname'],'购物券') !== false){
        //         unset($recordlist[$k]);
        //     }
        // }
        // array_values($recordlist);
        foreach($recordlist as $k=>$v) {
            //$shop=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=".$v['shopid']); 
            if ($v['status']==1) {
                $zjer[$k] = intval($v['money']);
            }
            
        }
        echo array_sum($zjer); 
    ?>元
    </span><br>


<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->

<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>

</html> 