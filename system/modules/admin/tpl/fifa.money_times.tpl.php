<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
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
input{
    width: 60px;
    text-align: center;
}
</style>
</head>
<body>
<div class="header lr10">
  <a href="/index.php/admin/fifa/history">列表</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/user">用户详情</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/setting">活动设置</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/money_times">奖金设置</a>
</div>

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">场次</th>
            <th align="center">开始时间</th>
            <th align="center">总奖金</th>
            <th align="center">客队赢</th>
            <th align="center">主队赢</th>
            <th align="center">平</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data AS $v) {  ?>      
            <tr>
                <td align="center"><?php echo $v['guest_name']."-".$v['host_name']; ?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['match_time']); ?></td>
                <td align="center"><input type="text" id="<?php echo $v['match_id']; ?>" id2="money" name="<?php echo $v['money']; ?>" value="<?php echo $v['money']; ?>"></td>
                <td align="center"><input type="text" id="<?php echo $v['match_id']; ?>" name="<?php echo $v['guest_num']; ?>" id2="guest_num" value="<?php echo $v['guest_num']; ?>">(<?php echo $v['guest_name']."赢"; ?>)</td>
                <td align="center"><input type="text" id="<?php echo $v['match_id']; ?>" name="<?php echo $v['host_num']; ?>" id2="host_num" value="<?php echo $v['host_num']; ?>">(<?php echo $v['host_name']."赢"; ?>)</td>
                <td align="center"><input type="text" id="<?php echo $v['match_id']; ?>" name="" id2="ping_num" value="<?php echo $v['ping_num']; ?>"></td>
                <td align="center"></td>
            </tr>
            <?php } ?>
    </tbody>
</table>


</div><!--table-list end-->

<script type="text/javascript">
    $(function(){
        $('input').blur(function(){
            var val = $(this).attr('name');
            var x = $(this).val();
            var id = $(this).attr('id');
            var id2 = $(this).attr('id2');

            if(val != x){
                var k = confirm("你确定修改吗？");
                if(k == true){
                    $.ajax({
                        data: {'val':x,'match_id':id,'hh':id2},
                        type: 'post',
                        url: '/index.php/admin/fifa/do_money_times',
                        success:function(msg){
                            window.location.reload();
                        }

                    })
                }else{
                    window.location.reload();
                }
            }
        })
    })
</script>
</body>
</html> 