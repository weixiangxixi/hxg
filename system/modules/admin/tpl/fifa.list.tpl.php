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
</style>
</head>
<body>
<div class="header lr10">
  <a href="/index.php/admin/fifa/history">列表</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/user">用户详情</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/setting">活动设置</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/money_times">奖金设置</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/index.php/admin/fifa/hmd">黑名单</a>
</div>
    <div class="bk10"></div>


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
            <select class="input-text text2" name="cate">
                <option value="uid" <?php if($cate == 'uid'){echo "selected";} ?>>用户uid</option>
                <option value="username" <?php if($cate == 'username'){echo "selected";} ?>>用户名称</option>
                <option value="mobile" <?php if($cate == 'mobile'){echo "selected";} ?>>用户手机</option>
            </select>
            <input name="sel" type="text" id="sel" class="input-text text3" value="<?php echo !empty($sel)?$sel:''?>"/>
            <input class="button" type="submit" name="sososubmit" value="搜索">
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
            <th align="center">场次</th>
            <th align="center">竞猜</th>
            <th align="center">结果</th>
            <th align="center">时间</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recordlist AS $v) {  ?>      
            <tr>
                <td align="center"><?php echo $v['mobile']; ?></td>              
                <td align="center"><?php echo $v['username']; ?></td>
                <td align="center"><?php $match = $this->db->GetOne("SELECT * FROM `@#_fifa_list` where `match_id`=".$v['match_id']);echo $match['host_name'].'-'.$match['guest_name'];if($match['match_status']=='-1'){if($match['results']==1){echo '('.$match['host_name'].'赢)';}elseif($match['results']==2){echo '('.$match['guest_name'].'赢)';}elseif($match['results']==3){echo '(平局)';}else{}}?></td>
                <td align="center"><?php if($v['guess']==1){echo $match['host_name'].'赢';}elseif($v['guess']==2){echo $match['guest_name'].'赢';}elseif($v['guess']==3){echo '平局';}else{} ?></td>
                <td align="center"><?php if($match['match_status']== '-1'){if($v['guess']==$match['results']){echo '对';}else{echo '错';}}?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                <td align="center">
                </td>
            </tr>
            <?php } ?>
    </tbody>
</table>



<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>
</div><!--table-list end-->
<script type="text/javascript">
    $(function(){
        $('#Page_Next').on('click',function(){
            console.log(1);
        })
    })
</script>



</script>
</body>
</html> 