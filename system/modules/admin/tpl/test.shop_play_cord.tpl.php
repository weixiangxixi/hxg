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
    input.button {
    background-color: #161E22;
    display: inline-block;
    padding: 0px 20px;
    margin: 0px;
    cursor: pointer;
    border: 1px solid #BBB;
    overflow: visible;
    font: bold 12px 微软雅黑,arial,helvetica,sans-serif;
    text-decoration: none;
    white-space: nowrap;
    color: #fff;
    transition: background-color 0.2s ease-out 0s;
    background-clip: padding-box;
    border-radius: 3px;
    box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.3), 0px 2px 2px -1px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.3) inset;
    height: 30px;
}
.header-data {
    border: 1px solid #3C8DBC;
    background: #FFFCED none repeat scroll 0% 0%;
    padding: 8px 10px;
    line-height: 20px;
    margin-top: 20px;
}
table{
    float: left;
    margin-right: 2%;
    margin-top: 10px;
}
span{
    margin-left: 10px;
}
</style>
</head>
<body>
<div class="header lr10" style="height: 70px">
    <?php echo $this->headerment();?>
    <span class="lr10"> </span><span class="lr10"> </span>
</div>
<div class="header-data lr10">
    <?php foreach ($data as $key => $val) { ?>
    <input type="submit" value="<?php echo $val['name']; ?>" name="" class="button" onclick="window.location.href = '/index.php/admin/test/shop_play_cord/<?php echo $val[cateid]; ?>'" <?php if($cateid == $val['cateid']){echo "style='background-color:#af3926'";} ?>>
    <?php } ?>
</div>
<form action="#" method="post" name="myform">

<div class="table-list lr10">
    <?php foreach ($str as $key => $val) { ?>
    <table width="22%" cellspacing="0">
        <thead>

                <tr>
                    <th width="80%">商品标题</th>    

                    <th width="20%">打单</th>             

                </tr>

        </thead>

        <tbody>             
            <tr>
                <td><span><?php echo _strcut($val['title'],30);?></span>

                <td><input type="checkbox" name="" style="margin-left: 40%;width: 20px;height: 20px" onclick="window.location.href = '/index.php/admin/test/do_shop_play_cord/<?php echo $val['sid'].'/'.$val['cateid']; ?>'" <?php if($val['cord'] == 1){echo "checked";} ?>></td>

                </td>

            </tr>
        </tbody>
    </table>
    <?php } ?>

</div></form>
</body>
</html> 