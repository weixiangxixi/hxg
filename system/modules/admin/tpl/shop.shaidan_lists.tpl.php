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

<div class="header-data lr10" style="margin-top: 20px">
    <?php foreach ($arr as $key => $val) { ?>
    <input type ="submit" value="<?php echo $val['name']; ?>" name="zhye_submit" class="button" onclick="window.location.href='/index.php/admin/content/goods_shaidan_list/<?php echo $val[cateid]; ?>'"/>
    <?php } ?>
</div>
<div class="bk10"></div>

<form action="#" method="post" name="myform">

<div class="table-list lr10">

	<?php if($this->segment(4)=='money' || $this->segment(4)=='moneyasc'): ?>

        <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                	<th width="5%">排序</th>

                    <th width="5%">ID</th>                          

                    <th width="25%">商品标题</th>  

                    <th width="8%">所属栏目</th>             

                    <th width="10%">已参与/总需</th>

                    <th width="5%">单价/元</th>

                    <th width="10%">期数/最大期数</th>

                    <th width="10%">商品价格</th>

				</tr>

        </thead>

        <tbody>				

        	<?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span>

                <?php if($v['xsjx_time'])echo "<font color='red'>限时</font>"; ?>

                </td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

                <td><?php echo $v['money']; ?></td>


            </tr>

            <?php } ?>

        </tbody>

     </table>

	<?php endif; ?>

	

	

    <?php if($this->segment(4)=='maxqishu'): ?>

        <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                	<th width="5%">排序</th>

                    <th width="5%">ID</th>                          

                    <th width="25%">商品标题</th>  

                    <th width="8%">所属栏目</th>             

                    <th width="10%">已参与/总需</th>

                    <th width="5%">单价/元</th>

                    <th width="10%">期数/最大期数</th>         

				</tr>

        </thead>

        <tbody>				

        	<?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span>

                <?php if($v['xsjx_time'])echo "<font color='red'>限时</font>"; ?>

                </td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>          


            </tr>

            <?php } ?>

        </tbody>

     </table>

	<?php endif; ?>

	

	

	

	

    <?php if($this->segment(4)=='xianshi'): ?>

    <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                	<th width="5%">排序</th>

                    <th width="5%">ID</th>        

                    <th width="25%">商品标题</th>            

                    <th width="10%">已参与/总需</th>

                    <th width="5%">单价/元</th>

                    <th width="10%">期数/最大期数</th>          

                    <th width="20%">限时揭晓时间</th>


				</tr>

        </thead>

        <tbody>				

        	<?php foreach($shoplist as $v) { ?>

            <tr>

              <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>  

                <td><?php echo $v['id'];?></td>

                <td><span  ><?php echo _strcut($v['title'],30);?></span></td>

                <td><font color="#ff0000"><?php echo $v['canyurenshu'];?></font> / <?php echo $v['zongrenshu'];?></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

                <td><?php echo ($v['xsjx_time']!=0) ? date("Y-m-d H",$v['xsjx_time']) : '否';?></td>

            </tr>

            <?php } ?>

        </tbody>

     </table>

    <?php endif; ?>

	

	<?php if($this->segment(4)!='xianshi' && $this->segment(4)!='maxqishu' && $this->segment(4)!='moneyasc' && $this->segment(4)!='money'): ?>

        <table width="100%" cellspacing="0">

     	<thead>

        		<tr>

                    <th width="5%">ID</th>        

                    <th width="25%">商品标题</th>    

                    <th width="8%">所属栏目</th>             

                    <th width="5%">单价/元</th>

                    <th width="10%">期数/最大期数</th>
				</tr>

        </thead>

        <tbody>				

        	<?php foreach($shoplist as $v) { ?>

            <tr>

                <td><?php echo $v['id'];?></td>

                <td><span style=""><a href="<?php echo G_ADMIN_PATH; ?>/content/shaidan_list/<?php echo $v['sid']; ?>"><?php echo _strcut($v['title'],30);?></a></span></td>

                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>

                <td><?php echo $v['yunjiage'];?></td>

                <td><?php echo $v['qishu'];?>/<?php echo $v['maxqishu'];?></td>

            
            </tr>

            <?php } ?>

        </tbody>

     </table>     

    <?php endif; ?>	

    </form>

	

   <div class="btn_paixu">

  	<div style="width:80px; text-align:center;">

          <input type="button" class="button" value=" 排序 "

        onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/goods_listorder/dosubmit';myform.submit();"/>

    </div>

  </div>

    	<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

</div>

<script type="text/javascript">
    $(function(){
        $(".del").click(function(){
            var id = $(this).attr('id');
            var url = '<?php echo G_ADMIN_PATH; ?>/content/goods_del/' + id;
            var del = confirm("确定要删除吗?");
            if(del == true){
                window.location.href = url;
            }
        })
    })
</script>



<?php if($this->segment(4)=='maxqishu'): ?>

<!--期数修改弹出框-->

<style>

#paywindow{position:absolute;z-index:999; display:none}

#paywindow_b{width:372px;height:360px;background:#2a8aba; filter:alpha(opacity=60);opacity: 0.6;position:absolute;left:0px;top:0px; display:block}

#paywindow_c{width:360px;height:338px;background:#fff;display:block;position:absolute;left:6px;top:6px;}

.p_win_title{ line-height:40px;height:40px;background:#f8f8f8;}

.p_win_title b{float:left}

.p_win_title a{float:right;padding:0px 10px;color:#f60}

.p_win_content h1{font-size:25px;font-weight:bold;}

.p_win_but,.p_win_mes,.p_win_ctitle,.p_win_text{ margin:10px 20px;}

.p_win_mes{border-bottom:1px solid #eee;line-height:35px;}

.p_win_mes span{margin-left:10px;}

.p_win_ctitle{overflow:hidden;}

.p_win_x_b{float:left; width:73px;height:68px;background-repeat:no-repeat;}

.p_win_x_t{ font-size:18px; font-weight:bold;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma;color:#f00; text-align:center}

.p_win_but{ height:40px; line-height:40px;}

.p_win_but a{ padding:8px 15px; background:#f60; color:#fff;border:1px solid #f50; margin:0px 15px;font-family: "Helvetica Neue",\5FAE\8F6F\96C5\9ED1,Tohoma; font-size:15px; }

.p_win_but a:hover{ background:#f50}

.p_win_text a{ font-size:13px; color:#f60}

.pay_window_quit:hover{ color:#f00}

</style>

<div id="paywindow">

	<div id="paywindow_b"></div>

	<div id="paywindow_c">

		<div class="p_win_title"><a href="javascript:void();" class="pay_window_quit">[关闭]</a><b>：：：重设期数</b></div>

		<div class="p_win_content">			

			<div class="p_win_mes">

            	 <b>标题:　　　</b><span id="max_title">...</span>   		

            </div>

			<div class="p_win_mes">

            	 <b>原最大期数:</b><span id="max_qishu">0</span>　期

            </div>

			<div class="p_win_mes">

            	 <b>新最大期数:</b><input type="text" id="max_new_qishu" class="input-text" style="width:50px;margin-left:10px;">需要大于原最大期数

            </div>

			<div class="p_win_mes">

            	 <b>新商品售价:</b><input type="text" id="max_one_money" class="input-text" style="width:50px;margin-left:10px;">商品每一次购买的价格

            </div>

			<div class="p_win_mes">

            	 <b>新商品总价:</b><input type="text" id="max_new_money" class="input-text" style="width:50px;margin-left:10px;">商品的总价格		

            </div>

            <div class="p_win_mes">    	    	

    			 	<input type="button" value=" 更新并新建一期 " class="button" id="max_button" onclick="set_shop_qishu(this)">              

            </div>	

		</div>

	</div>

</div>



<script>

$(function(){

	var width = ($(window).width()-372)/2;

	var height = ($(window).height()-360)/2;

	$("#paywindow").css("left",width);

	$("#paywindow").css("top",height);

		

	$(".pay_window_quit").click(function(){

		$("#paywindow").hide();								 

	});

	

		

	$(".pay_window_show").click(function(){

		var gid    = $(this).attr("gid");

		var gtitle = $(this).attr("gtitle");

		var gqishu = $(this).attr("gqishu");

		var gmoney = $(this).attr("gmoney");

		var gonemoney = $(this).attr("gonemoney");

		

		$("#max_one_money").val(gonemoney);

		$("#max_new_money").val(gmoney);

		

		$("#max_new_qishu").val(parseInt(gqishu)+100);

		$("#max_qishu").text(gqishu);

		$("#max_title").text(gtitle);

		$("#max_button").attr("onclick","set_shop_qishu("+gid+")");		

		$("#paywindow").show();	

	

	});

	

});



function set_shop_qishu(T){

	

	var yqishu = parseInt($("#max_qishu").text());

	var tqishu = parseInt($("#max_new_qishu").val());

	var money = parseInt($("#max_new_money").val());

	var onemoney = parseInt($("#max_one_money").val());

	

	if(!money || !onemoney || (money < onemoney)){

		window.parent.message("商品价格填写不正确!",8,2);

		return;

	}

	if(tqishu <= yqishu){	

		window.parent.message("新期数不能小于原来的商品期数",8,2);

		return;

	}

	if(!tqishu){	

		window.parent.message("新期数不能为空",8,2);

		return;

	}

	

	$.post("<?php echo G_MODULE_PATH; ?>/content/goods_max_qishu/dosubmit/",{"gid":T,"qishu":tqishu,"money":money,"onemoney":onemoney},function(datas){

		var data = jQuery.parseJSON(datas);		

		if(data.err == '-1'){		

			window.parent.message(data.meg,8,2);		

			return;

		}else{			

			window.parent.message(data.meg,8,2);

			window.parent.btn_iframef5();

			return;

		}		

		

	});

}

</script>

<!--期数修改弹出框-->

<?php endif; ?>

</body>

</html> 