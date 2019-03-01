<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
    <?php echo $this->headerment();?>
    <span class="lr10"> </span><span class="lr10"> </span>
    <form action="" method="post" style="display:inline-block; ">
    <select name="paixu">
        <option value="time1"> 按购买时间倒序 </option>
        <option value="time2"> 按购买时间正序 </option>
        <option value="num1"> 按购买次数倒序 </option>
        <option value="num2"> 按购买次数正序 </option>
        <option value="money1"> 按购买总价倒序 </option>
        <option value="money2"> 按购买总价正序 </option>
        
    </select>    
    <input type ="submit" value=" 排序 " name="paixu_submit" class="button"/>
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">商品标题</th>
            <th align="center">获得用户</th>
            <th align="center">UID</th>
            <th align="center">获得日期</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recordlist AS $v) {  ?>  
            <?php $username = get_user_name($v['uid']); ?>  
            <tr>
                <td align="center"><?php echo $v['name']; ?></td>
                <td align="center"><?php echo $username; ?></td>
                <td align="center"><?php echo $v['uid']; ?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['update_time']);?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_csg_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<div class="btn_paixu"></div>
    <span>总金额：
    <?php 
        $k = 0;
        foreach ($recordlist as $k => $v) {
            if(strpos($v['shopname'],'购物券') !== false){
                unset($recordlist[$k]);
            }
        }
        array_values($recordlist);
        foreach($recordlist as $k=>$v) {
            $shop=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=".$v['shopid']); 
            $zjer[$k] = intval($shop['money']);
        }
        echo array_sum($zjer); 
    ?>元
    </span>
  <a href="/index.php/admin/test/set_exel"><button>打印订单</button></a>
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>

<script>

</script>
</body>
</html> 