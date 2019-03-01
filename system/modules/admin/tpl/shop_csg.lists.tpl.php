<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>后台首页</title>

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">

<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>

<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 

<style>

body{ background-color:#fff}

tr{ text-align:center}
.calendar {
    top: 0px!important;
}

#posttime1,#posttime2{
        width: 160px;
        background: #fff url(/statics/plugin/laydate/calendar.gif) 140px no-repeat;
        cursor: pointer;           
        height: 28px;
        border: 1px solid #B4B4B4;
    
        
    }
</style>

</head>

<body>
<div class="bk10"></div>

<form action="#" method="post" name="myform">

<div class="table-list lr10">

        <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                    <th width="5%">ID</th>                          

                    <th width="25%">商品标题</th>  

                    <th width="8%">商品价格</th>  

                    <th width="8%">单次价格</th>             

                    <th width="15%">管理</th>

				</tr>

        </thead>

        <tbody>				

        	<?php foreach($shoplist as $v) { ?>

            <tr>

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['name'],30);?></span>


                <td><?php echo $v['price']; ?>元</td>

                <td><?php if($v['free'] == 1){echo "<em style='font-size:16px;color:#f60;'>免费</em>";}else{echo $v['money']."元";} ?></td>

                <td class="action">

                <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_csg_add/<?php echo $v['id'];?>">修改</a>

                <span class='span_fenge lr5'>|</span>

                <a class="del" href="javescript:;" style="cursor: pointer;" id="<?php echo $v['id'];?>">删除</a>

				</td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

    </form>

	


    	<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

</div>

<script>

$(function(){
	
    $(".del").click(function(){
        var del = confirm("确定要删除吗?");
        if(del == true){
            var id = $(this).attr('id');
            $.ajax({
                data: {'id':id},
                type: 'post',
                url: '/index.php/admin/content/shop_csg_del',
                success:function(json){
                    if(json == 1){
                         window.location.reload();
                    }
                }
            })
        }
    });

	

});




</script>

</body>

</html> 