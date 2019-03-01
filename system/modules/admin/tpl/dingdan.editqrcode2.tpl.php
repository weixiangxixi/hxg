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
<script src="<?php echo G_TEMPLATES_JS;?>/mobile/jquery190.js" language="javascript" type="text/javascript"></script>
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
    <p><button style="color:red;font-size: 18px;float:left" class="create_user" onclick="add_user();" >增加新用户</button></p>
    <p><button style="color:red;font-size: 18px;margin-left:30px" class="create_user" onclick="ewm();" >展开二维码</button></p>
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
  <style>
    tr th:nth-child(2),th:nth-child(3),th:nth-child(4),th:nth-child(5),th:nth-child(6),th:nth-child(7),th:nth-child(8),th:nth-child(9){
        display:none;
    }
    tr td:nth-child(2),td:nth-child(3),td:nth-child(4),td:nth-child(5),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9){
        display:none;
    }
  </style>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
     <?php foreach($codelist AS $key=>$val) {  ?> 
    <thead>
        <tr>
            <th align="center" onclick="edit_fenzu(<?php echo $val['list'][0]['id']; ?>)"><?php if($key == 0){echo "未分组";}else{echo "分组".$key;} ?><?php if($val['list'][0]['fenzu_bz'] != ''){echo '（'.$val['list'][0]['fenzu_bz'].'）';} ?></th>
            <th align="center">100</th>
            <th align="center">200</th>
            <th align="center">300</th>
            <th align="center">500</th>
            <th align="center">1000</th>
            <th align="center">2000</th>
            <th align="center">5000</th>
            <th align="center">启用</th>
            <th align="center">收支明细</th>
            <th align="center">轮流收款</th>
            <th align="center">分组</thl>
            <th align="center">操作</thl>
        </tr>
    </thead>
    <tbody>
        <?php foreach($val['list'] AS $k=>$v) {  ?> 
            <tr>
                <td align="center" style="cursor: pointer;" onclick="edit(<?php echo $v['id']; ?>)">用户<?php echo $v["id"]."(".$v["name"].")";?> </td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/100">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/200">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/300">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/500">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/1000">修改</a></td>              
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/2000">修改</a></td>
                <td align="center"><a href="/admin/dingdan1/edit_qrcode_img_sm/<?php echo $v['id']; ?>/5000">修改</a></td>
                <td align="center"><?php if($v['open']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha(this);" />';} ?></td>
                <td align="center">今日：<span style="color:red;font-size: 16px;"><?php echo $v['today']; ?></span>|昨日：<span style="color:red;font-size: 16px;"><?php if(empty($v['yesterday'])){echo '0';}else{echo $v['yesterday'];} ?>元</span></td>
                <td align="center"><?php if($v['status']==1){echo '<input type="checkbox" checked="checked" value="'.$v['id'].'" onclick="haha2(this);" />';}else{echo '<input type="checkbox" value="'.$v['id'].'" onclick="haha2(this);" />';} ?></td>
                <td align="center">
                  <select class="cate" id="<?php echo $v['id']; ?>">
                    <option value='0' <?php if($v['fenzu'] == 0){echo "selected";} ?>>未分组</option>
                    <option value='1' <?php if($v['fenzu'] == 1){echo "selected";} ?>>分组1</option>
                    <option value='2' <?php if($v['fenzu'] == 2){echo "selected";} ?>>分组2</option>
                    <option value='3' <?php if($v['fenzu'] == 3){echo "selected";} ?>>分组3</option>
                    <option value='4' <?php if($v['fenzu'] == 4){echo "selected";} ?>>分组4</option>
                    <option value='5' <?php if($v['fenzu'] == 5){echo "selected";} ?>>分组5</option>
                    <option value='6' <?php if($v['fenzu'] == 6){echo "selected";} ?>>分组6</option>
                    <option value='7' <?php if($v['fenzu'] == 7){echo "selected";} ?>>分组7</option>
                    <option value='8' <?php if($v['fenzu'] == 8){echo "selected";} ?>>分组8</option>
                  </select>
                </td>
                <td align="center" onclick="del(<?php echo $v['id']; ?>)">删除</td>
            </tr>
            <?php } ?>
    </tbody>
   <?php } ?>
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
<div class="bg" style="width: 100%;height: 100%;background-color: #ccc;z-index: 99;display: none;position: fixed;opacity:0.5;top: 0;"></div>
<div class="kk" style="width: 300px;height: 60px;position: absolute;top:50%;left: 50%;display: none;background: #f60;z-index: 999">
    <input type="" name="" style="margin-left: 5%;text-align: center;font-size: 16px;margin-top: 15px" />
    <button style="margin-left: 5px;margin-top: 14px;font-size: 16px;background-color: #ccc;position: absolute;">确定修改</button>
</div>
<script type="text/javascript"> 
    $(".cate").change(function(){
        var cateid = $(this).children("option:selected").val();
        var id = $(this).attr('id');
       
            $.ajax({
                type: 'POST',
                url: '/index.php/admin/dingdan/edit_qrcode_fenzu',
                data: {'id':id,'cateid':cateid},
                success:function(msg){
                    window.location.reload();
                }
            })
        
    })
    function del(id){
        if(confirm("确认删除吗？")){
            $.ajax({
                type: 'POST',
                url: '/index.php/admin/dingdan/del_qrcode',
                data: {'id':id},
                success:function(msg){
                    window.location.reload();
                }
            })
        }
    }
    function edit(id){
        $(".bg").show();
        $(".kk").show();
       
        $("button").click(function(){
            var name = $(".kk input").val();
            $.ajax({
                type: 'POST',
                url: '/index.php/admin/dingdan/edit_qrcode_name',
                data: {'id':id,'name':name},
                success:function(msg){
                    window.location.reload();
                }
            })
        })
        $('.bg').on('click',function(){
            $(".bg").hide();
            $(".kk").hide();
        })
    }
 
    function edit_fenzu(id){
        $(".bg").show();
        $(".kk").show();
       
        $("button").click(function(){
            var name = $(".kk input").val();

            $.ajax({
                type: 'POST',
                url: '/index.php/admin/dingdan/edit_qrcode_fenzu_name',
                data: {'id':id,'name':name},
                success:function(msg){
                    window.location.reload();
                }
            })
        })
        $('.bg').on('click',function(){
            $(".bg").hide();
            $(".kk").hide();
        })
    }

    
</script>
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
            url:'/index.php/admin/dingdan/open_qrcode',  
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

    function haha2(obj){
        var s_id = obj.value;  
        if ( obj.checked == true){
            post_ajax2(s_id,1);
        }else{
            post_ajax2(s_id,0);
        }
    }

    function post_ajax2(id,s){
        $.ajax({  
            type:'post',    
            url:'/index.php/admin/dingdan/open_qrcode_status',  
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
        function ewm(){
            $('tr th').show();
            $('tr td').show();
        }
</script>
</body>
</html> 