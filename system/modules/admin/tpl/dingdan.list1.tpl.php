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
            if($shop['cateid'] == '170'){
                $zjer[$k] = intval($shop['str3']);
            }else{
                $zjer[$k] = intval($shop['money']);
            }
            
        }
        echo array_sum($zjer); 
    ?>元
    </span>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">购买用户</th>
            <th align="center">购买次数</th>
            <th align="center">商品总价</th>
            <th id="order_cate" align="center" style="cursor: pointer;" title="点击切换排序类型" onclick="cate();"><?php if($order_cate == '1'){echo "揭晓日期";}else{echo "购买日期";} ?></th>
            <th align="center">中奖</th>
            <th align="center">订单状态</th>
            <th align="center">红包状态</th>
            <th align="center">管理</th>
		</tr>
    </thead>
    <script type="text/javascript">
        function cate(){
            var order_cate = $('#order_cate').text();
            if(order_cate == '购买日期'){
                window.location.href = '<?php echo G_MODULE_PATH;?>/dingdan/zhongjiang/1';
            }else if(order_cate == '揭晓日期'){
                window.location.href = '<?php echo G_MODULE_PATH;?>/dingdan/zhongjiang/0';
            }
        }
    </script>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>">
                <?php if($v['cateid'] == '177'){echo "<em style='font-size:16px;color:red;'>(双11半价)</em>";} ?>第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                 <td align="center"><?php echo $v['username']; ?></td>
                <td align="center"><?php echo $v['gonumber']; ?>人次</td>
                <td align="center">￥<?php $shop=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=".$v['shopid']); if($shop['cateid'] == '170'){echo $shop['str3'];}else if($v['cateid'] == '177'){echo $shop['money']*2;}else{echo $shop['money'];} ?>元</td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$shop['q_end_time']);?></td>
                 <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                <td align="center"><?php echo $v['status'];?></td>
                <td align="center"><?php if($v['redbag_status']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha(this);" />';} ?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/get_dingdan/<?php echo $v['id']; ?>">详细</a></td>
            </tr>
            <?php } ?>
  	</tbody>
</table>
<div class="btn_paixu"></div>
    
<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>
<script>
    function haha(obj){
        var s_id = obj.value;  
        if ( obj.checked == true){

        //Action for checked
            //alert(s_id);
            post_ajax(s_id,1);
        }else{

        //Action for not checked
            //alert(s_id);
            post_ajax(s_id,0);
        }
    }
    function post_ajax(id,s){
        $.ajax({  
            type:'post',    
            url:'/index.php/admin/dingdan/chang_redbag',  
            data:{
                id : id,  
                s : s
            }, 
            dataType:'json',  
            success:function(data){  
                console.log(data)
            }  
        }); 
    }
</script>
<script>

</script>
</body>
</html> 