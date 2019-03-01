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
    	<option value="time1"> 按参与时间倒序 </option>
        <option value="time2"> 按参与时间正序 </option>
        
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
        	<th align="center">场次</th>
            <th align="center">参与用户手机</th>
            <th align="center">参与用户名</th>
            <th align="center">获得话费</th>
            <th align="center">获得红包</th>
            <th align="center">参与时间</th>
            <th align="center">红包充值状态</th>
            <th align="center">话费充值状态</th>
            <th align="center">管理</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"> </td>           
                <td align="center"><?php $user=$this->db->GetOne("SELECT * FROM `@#_member` where uid=".$v['uid']); echo $user['mobile']; ?></td>
                <td align="center"><?php $user=$this->db->GetOne("SELECT * FROM `@#_member` where uid=".$v['uid']); echo $user['username']; ?></td>
                <td align="center">元</td> 
                <td align="center">元</td> 
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                <td align="center">已到账</td>
                <td align="center"><?php if($v['redbag_status']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha(this);" />';} ?></td>
                <td align="center"></td>
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
            url:'/index.php/admin/dingdan5/huafei_status',  
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

</body>
</html> 