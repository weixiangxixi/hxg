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
    <?php echo $this->headerment();?>
</div>
    <div class="bk10"></div>


<div class="header-data lr10">
    <span class="lr10"> </span><span class="lr10"> </span>
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
                <!--<option value="username" <?php if($cate == 'username'){echo "selected";} ?>>用户名称</option>
                <option value="mobile" <?php if($cate == 'mobile'){echo "selected";} ?>>用户手机</option>-->
            </select>
            <input name="sel" type="text" id="sel" class="input-text text3" value="<?php echo !empty($sel)?$sel:''?>"/>
            <input class="button" type="submit" name="sososubmit" value="搜索">
            <p><a href='/index.php/admin/dingdan2/bank'><input class="button" type="button" value="银行卡" style="font-size : 60px;height: 60px;float: right;" ></a>
            <a href='/index.php/admin/dingdan/wxpay'><input class="button" type="button" value="微信" style="font-size : 60px;height: 60px;float: right;" ></a>
            <a href='/index.php/admin/dingdan1/alipay_sm'><input class="button" type="button" value="支付宝" style="font-size : 60px;height: 60px;float: right;" ></a></p>
</form>
<p id="show_close" style="cursor: pointer;margin-top: 10px">点击展开</p>
<div id="xxxx" style="display: none">
<?php 
echo "<p><a href='/admin/dingdan1/edit_qrcode_alipay'>修改二维码</a></p>";
echo "<p><a href='/admin/dingdan/edit_zhuan_img'>修改转账二维码</a></p>";
if($set_open['open'] == 0){
    echo "<p>当前状态：收款关闭中....操作：&nbsp;&nbsp;&nbsp;<a href='/admin/dingdan/check_wxpay_open/1'>【开启】</a></p>";
}else{
    echo "<p>当前状态：收款开启中....操作：&nbsp;&nbsp;&nbsp;<a href='/admin/dingdan/check_wxpay_open/0'>【关闭】</a></p>";
}
if($song_open['open'] == 0){
    echo "<p>当前状态：充值赠送关闭中....操作：&nbsp;&nbsp;&nbsp;<a href='/admin/dingdan/song_wxpay_open/1'>【开启】</a></p>";
}else{
    echo "<p>当前状态：充值赠送开启中....操作：&nbsp;&nbsp;&nbsp;<a href='/admin/dingdan/song_wxpay_open/0'>【关闭】</a></p>";
}
echo "<p><a onclick='song_ajax(11);'>修改100赠送金额(".$a1['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(12);'>修改200赠送金额(".$a2['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(13);'>修改300赠送金额(".$a3['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(14);'>修改500赠送金额(".$a4['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(15);'>修改1000赠送金额(".$a5['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(16);'>修改2000赠送金额(".$a6['zhi']."元)</a>&nbsp;|&nbsp;
      <a onclick='song_ajax(17);'>修改5000赠送金额(".$a7['zhi']."元)</a>
    </p>";
?>
</div>
<div style="margin-top: 20px"></div>
    <span>总金额：
    <?php 
        $k = 0;
        // foreach ($recordlist as $k => $v) {
        //     if(strpos($v['shopname'],'购物券') !== false){
        //         unset($recordlist[$k]);
        //     }
        // }
        // array_values($recordlist);
        foreach($recordlist as $k=>$v) {
            //$shop=$this->db->GetOne("SELECT * FROM `@#_shoplist` where id=".$v['shopid']); 
            if ($v['status']==1) {
                $zjer[$k] = intval($v['money']);
            }
            
        }
        echo array_sum($zjer); 
    ?>元
    </span><br>
    <?php 
        // echo "<p><a href='/admin/dingdan/wxpay_code/1'>收款二维码更换10</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/19'>收款二维码更换20</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/20'>收款二维码更换30</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/2'>收款二维码更换50</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/3'>收款二维码更换100</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/8'>收款二维码更换200</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/9'>收款二维码更换300</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/4'>收款二维码更换500</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/5'>收款二维码更换1000</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/6'>收款二维码更换2000</a></p>";
        // echo "<p><a href='/admin/dingdan/wxpay_code/10'>收款二维码更换5000</a></p>";
    ?>
</div>
<script type="text/javascript">
    $('#show_close').click(function(){
        var xx = $("#xxxx").css('display');
        if(xx == 'none'){
            $(this).text("点击隐藏");
            $('#xxxx').show();
        }else{
            $(this).text("点击展开");
            $('#xxxx').hide();
        }
        
    })
</script>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <!--<th align="center">用户手机</th>-->
            <th align="center">用户UID</th>
            <th align="center">用户名称</th>
            <th align="center">充值金额</th>
            <th align="center">收款id</th>
            <th align="center">充值时间</th>
            <th align="center">确认时间</th>
            <th align="center">订单状态</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recordlist AS $v) {  ?>      
            <tr>
                <?php $sk_name = $this->db->GetOne("SELECT * FROM `@#_alipay_sm` WHERE `id` = '$v[aid]'");$username = get_user_name($v['uid']); ?>
                <td align="center"><?php echo $v['uid']; ?></td>              
                <td align="center"><?php echo $username; ?></td>
                <td align="center"><span style="font-size: 50px;color:red;"><?php echo $v['money']; ?>元</span></td>
                <td align="center"><?php echo $v['aid']."(".mb_substr($sk_name['name'],-1).")"; ?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
                <td align="center"><?php if(!empty($v['update_time'])){echo date("Y-m-d H:i:s",$v['update_time']);}?></td>
                <td align="center"><?php if($v['status']==0){echo '支付中';}else{echo '已支付';} ?></td>
                <td align="center">
                    <a style="font-size: 50px;color:red;" onclick="post_ajax(<?php echo $v['id']; ?>);"><?php if($v['status']==0){echo '收款';}?></a>
                    <a style="font-size: 50px;color:red;"><?php if($v['status']==1){echo '已收款';}?></a>
                    <?php if($v['status']==1){echo '&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="chehui_ajax('.$v['id'].');">撤回</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="zhui_ajax('.$v['id'].');">追加</a>'.'&nbsp;('.$v['zhuijia'].'次)&nbsp;&nbsp;'; } ?>
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

<script>
    function chehui(id){
        
        console.log(s_id);
        //chehui_ajax(s_id);
    }
    function post_ajax(id){
        //if(confirm('确定要收款吗?')) 
        //{ 
            $.ajax({  
                type:'post',    
                url:'/index.php/admin/dingdan1/check_alipay_sm',  
                data:{
                    id : id
                }, 
                dataType:'json',  
                success:function(data){  
                    console.log(data);
                    location.reload();
                }  
            }); 
        //} 
    }
    function chehui_ajax(id){
        if(confirm('确定要撤回吗?')) 
        { 
            $.ajax({  
                type:'post',    
                url:'/index.php/admin/dingdan1/chehui_wxpay',  
                data:{
                    id : id
                }, 
                dataType:'json',  
                success:function(data){  
                    console.log(data);
                    location.reload();
                }  
            }); 
        } 
    }
    function zhui_ajax(id){
        if(confirm('确定要追加吗?')) 
        { 
            $.ajax({  
                type:'post',    
                url:'/index.php/admin/dingdan1/add_wxpay',  
                data:{
                    id : id
                }, 
                dataType:'json',  
                success:function(data){  
                    console.log(data);
                    location.reload();
                }  
            }); 
        } 
    }

    function song_ajax(id){
        //$('#jincai').show();
        $('.sid').val(id);
        var top = ($(window).height() - $('#jincai').height())/2;   
        var left = ($(window).width() - $('#jincai').width())/2;   
        var scrollTop = $(document).scrollTop();   
        var scrollLeft = $(document).scrollLeft();   
        $('#jincai').css( { position : 'absolute', 'top' : top + scrollTop, left : left + scrollLeft } ).show(); 
    }
    $(function() {
        $(".close").on("click",function(){
            $("#jincai").hide();
        })
    })
    $(function() {
        $("#post_data").click(function(){
            var formData = $("#callAjaxForm").serialize();
            $.ajax({
                url:"/index.php/admin/dingdan/song_money",
                method:"POST",
                dataType:"json",
                data:formData,
                success:function(data){
                    location.reload();      
                }
            });
            return false;  
        });
    });
</script>
<script>

</script>
</body>
</html> 