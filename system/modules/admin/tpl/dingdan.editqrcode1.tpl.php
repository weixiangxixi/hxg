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
	<span class="lr10"> </span><span class="lr10"> </span>
    <p><button style="color:red;font-size: 18px;" class="create_user" onclick="add_user();" >增加新用户</button></p>
    <script type="text/javascript">
        function add_user(){
            if(confirm('确定要新建吗?')) 
            { 
                $.post("/index.php/admin/dingdan1/add_wxpay_user", { id: "1"} );
                location.reload();
            } 
        }
    </script>

</div> 
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">用户</th>
            <th align="center">10</th>
            <th align="center">20</th>
            <th align="center">30</th>
            <th align="center">50</th>
            <th align="center">100</th>
            <th align="center">200</th>
            <th align="center">300</th>
            <th align="center">500</th>
            <th align="center">1000</th>
            <th align="center">2000</th>
            <th align="center">5000</th>
            <th align="center">启用</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($codelist AS $v) {  ?>      
            <tr>
                <td align="center">用户<?php echo $v["zhi"];?> </td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/10">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/20">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/30">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/50">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/100">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/200">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/300">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/500">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/1000">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/2000">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img/<?php echo $v['zhi']; ?>/5000">修改</a></td>
                <td align="center"><?php if($v['open']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha(this);" />';} ?></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<div class="btn_paixu"></div>




</div><!--table-list end-->

<div id="jincai" style="left:40%;top:200px;height:50px;width:200px;display:none;position:absolute;background: #d5dfe8">
    <div class="close" style="color:red;position:absolute;right:10px;top:10px;cursor:pointer;">关闭</div>
    <form id='callAjaxForm'>
        <table style="border:1px solid #ccc; " class="dataTable" cellpadding=0 cellspacing=0 align="center">
            <tbody>

                <tr>
                    <td>
                        <input name="id" class="sid" type="hidden" value=""> 
                        <input type="number" id="money" name="money" value="" style="width: 60px;">
                    </td> 
                    <td><input type="submit" id="post_data"  value="赠送金额"></td>     
                </tr>

            </tbody>
        </table>
    </form>
</div>

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
            url:'/index.php/admin/dingdan1/open_qrcode',  
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