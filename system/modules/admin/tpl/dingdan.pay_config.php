<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<style>
tbody tr{ line-height:30px; height:30px;} 
a {
  cursor:pointer;
}
</style>
</head>
<body>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
    <div class="bk10"></div>


 <div class="header-data lr10">

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">通道</th>
            <th align="center">链接</th>
            <th align="center">启用</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($codelist AS $v) {  ?>      
            <tr>
                <td align="center"><?php echo $v["pay_name"];?> </td>
                <td align="center"><?php echo $v['pay_link']; ?></td>              
                
                <td align="center"><?php if($v['status']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha(this);" />';} ?></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<div class="btn_paixu"></div>




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
            url:'/index.php/admin/dingdan/change_payc',  
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