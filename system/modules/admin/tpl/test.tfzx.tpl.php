<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/laydate/laydate.js"></script> 
<style type="text/css">
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
<div class="header-data lr10">
    <span class="lr10"> </span><span class="lr10"> </span>
   <form action="" method="post">
 时间搜索: <input name="time1" type="text" id="posttime1" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($time1)?date("Y-m-d H:i:s",$time1):''?>"/> -  
          <input name="time2" type="text" id="posttime2" class="input-text posttime"  readonly="readonly" value="<?php echo !empty($time2)?date("Y-m-d H:i:s",$time2):''?>"/>
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
    <input class="button" type="submit" name="sososubmit" value="搜索">
</form>
</div>
<div class="table-list lr10">
    <table width="100%" cellspacing="0">
    	
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">中奖-充值(>1000)</th></tr>
            </thead>
            <tbody>
               <?php foreach ($je_t as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money']; ?></span>元</td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">消费-充值(>100)</th></tr>
            </thead>
            <tbody>
               <?php foreach ($xf_t as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money']; ?></span>元</td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
        <table width="30%" style="float: left;">   
            <thead>
                <tr><th align="center">UID</th><th align="center">充值-中奖(>1000)</th></tr>
            </thead>
            <tbody>
               <?php foreach ($sd_t as $key => $val) { ?>
                <tr>
                    <td align="center"><?php echo $val['uid']; ?></td>
                    <td align="center"><span style="color: red;font-size: 16px"><?php echo $val['money']; ?></span>元</td>
                </tr>
                <?php } ?>
    
            </tbody>
        </table>
    </table>
</div>

</body>
</html> 