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
<div class="table-list lr10">
  	<table width="40%"  style="float:left;">
      
            <tbody>
              <td align="center"></td>
              	<td align="center">UID</td>
              	<td align="center">用户名</td>
                <td align="center">手机号码</td>
                <td align="center">赠送状态</td>
                <?php foreach($str as $key=>$val){ ?>
                <tr>
                  <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><?php echo $val['uid']; ?></td>
                  	<td align="center"><?php echo $val['username']; ?></td>
                  	<td align="center"><?php echo $val['mobile']; ?></td>
                  	<td align="center"><?php if($val['state'] == 1){echo "<a href='/index.php/admin/test/close_vip_song/".$val['uid']."'><span style='color:red'>已开启</span></a>";}else{echo "<a  href='/index.php/admin/test/open_vip_song/".$val['uid']."'>关闭</a>";} ?></td>
                </tr>
                <?php } ?>
                
            </tbody>
    </table>
    <!--<table width="40%" style="float:left;margin-left:50px">
      
            <tbody>
               
                <tr>
                    <td align="center"></td>
                    <td align="center">UID</td>
                  	<td align="center">用户名</td>
                  	<td align="center">手机号码</td>
                  	<td align="center">赠送状态</td>
                </tr>
                <?php foreach($data as $key=>$val){ ?>
                <tr>
                    <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><?php echo $val['uid']; ?></td>
                  	<td align="center"><?php echo $val['username']; ?></td>
                  	<td align="center"><?php echo $val['mobile']; ?></td>
                  	<td align="center"><?php if($val['song_state'] == 1){echo "<a href='/index.php/admin/test/close_vip_song/'".$val['uid'].">已开启</a>";}else{echo "<a  href='/index.php/admin/test/open_vip_song'>关闭</a>";} ?></td>
                </tr>
                <?php } ?>
                
            </tbody>--!>
    </table>
</div>

</body>
</html> 