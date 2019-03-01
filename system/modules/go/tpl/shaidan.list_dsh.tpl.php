<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<script src="/statics/templates/yungou/js/mobile/jquery-1.9.1.min.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
body{ background-color:#fff}
input{
	width: 80%;
	height: 100%;
	padding: 5px;
	font-size: 14px;
	text-align: center;
	color: #f60;
}
</style>
</head>
<body>
<script>
function shaidan(id){
	if(confirm("确定删除该晒单")){
		window.location.href="<?php echo G_MODULE_PATH;?>/shaidan_admin/sd_del/"+id;
	}
}
</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
 <table width="100%" cellspacing="0">
 	<thead>
	<tr align="center">
		<th width="5%" height="30">ID</th>
		<th width="5%" height="30">UID</th>
		<th width="5%" height="30">昵称</th>
		<th width="15%">晒单标题</th>
		<th width="10%">缩略图</th>
		<th width="30%">晒单内容</th>
		<th width="7%">羡慕嫉妒恨</th>
		<th width="7%">评论</th>
		<th width="7%">奖励福分</th>
		<th width="9%">管理</th>
	</tr>
    </thead>
    <tbody>
	<?php foreach($shaidan as $v) { ?>
	<tr align="center" class="mgr_tr">
		<td height="30"><?php echo $v['sd_id'];?></td>
		<td height="30"><?php echo $v['sd_userid'];?></td>
		<td height="30"><?php echo get_user_name($v['sd_userid']);?></td>
		<td><?php echo $v['sd_title'];?></td>
		<td><img height="30" src="<?php if(!empty($v['sd_thumbs'])){ echo 'http://f.weimicm.com/'.$v['sd_thumbs'].'@!image';}?>" class="pimg"></td>
		<td><?php echo $v['sd_content'];?></td>
		<td><?php echo $v['sd_zhan'];?></td>
		<td><?php echo $v['sd_ping'];?></td>
		<td>
			<input type="text" name="score" placeholder="请输入福分" />
		</td>
		<td class="action"><span style="margin-right: 10px">[<a id="<?php echo $v['sd_id']; ?>" href="javascript:;">通过</a>]</span>|<span style="margin-left: 10px">[<a onClick="shaidan(<?php echo $v['sd_id'];?>)" href="javascript:;">删除</a>]</span></td>		
	</tr>
	<?php } ?> 
    </tbody>
</table>
<?php if($total>$num) {?> 

<div id="pages"><ul><li>共 <?php echo $total; ?> 条</li><?php echo $page->show('one','li'); ?></ul></div>

<?php } ?> 	
<div id="outerdiv" style="position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:2;width:100%;height:100%;display:none;">
    <div id="innerdiv" style="position:absolute;">
        <img id="bigimg" style="border:5px solid #fff;" src="" />
    </div>
</div>
<script type="text/javascript">
	$(function(){
		$(".action a:first-child").click(function(){
			var re = /^[0-9]*[0-9]$/i; //校验是否为数字
			var id = $(this).attr('id');
			var score = $(this).parent().parent().prev().children('input').val();
			if(re.test(score) && score%100===0) {
				$.ajax({
					data: {'id':id,'score':score},
					type: 'post',
					url: '/index.php/go/shaidan_admin/shtg',
					success:function(msg){
						window.location.reload();
					}
				})
			}else{
				alert("请输入整百的福分");
			}
		})
	})
</script>
</div><!--table_list end-->

<script>  
    $(function(){  
        $(".pimg").click(function(){  
            var _this = $(this);//将当前的pimg元素作为_this传入函数  
            imgShow("#outerdiv", "#innerdiv", "#bigimg", _this);  
        });  
    });  

    function imgShow(outerdiv, innerdiv, bigimg, _this){  
        var src = _this.attr("src");//获取当前点击的pimg元素中的src属性  
        $(bigimg).attr("src", src);//设置#bigimg元素的src属性  
      
            /*获取当前点击图片的真实大小，并显示弹出层及大图*/  
        $("<img/>").attr("src", src).load(function(){  
            var windowW = $(window).width();//获取当前窗口宽度  
            var windowH = $(window).height();//获取当前窗口高度  
            var realWidth = this.width;//获取图片真实宽度  
            var realHeight = this.height;//获取图片真实高度  
            var imgWidth, imgHeight;  
            var scale = 0.8;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放  
              
            if(realHeight>windowH*scale) {//判断图片高度  
                imgHeight = windowH*scale;//如大于窗口高度，图片高度进行缩放  
                imgWidth = imgHeight/realHeight*realWidth;//等比例缩放宽度  
                if(imgWidth>windowW*scale) {//如宽度扔大于窗口宽度  
                    imgWidth = windowW*scale;//再对宽度进行缩放  
                }  
            } else if(realWidth>windowW*scale) {//如图片高度合适，判断图片宽度  
                imgWidth = windowW*scale;//如大于窗口宽度，图片宽度进行缩放  
                            imgHeight = imgWidth/realWidth*realHeight;//等比例缩放高度  
            } else {//如果图片真实高度和宽度都符合要求，高宽不变  
                imgWidth = realWidth;  
                imgHeight = realHeight;  
            }  
                    $(bigimg).css("width",imgWidth);//以最终的宽度对图片缩放  
              
            var w = (windowW-imgWidth)/2;//计算图片与窗口左边距  
            var h = (windowH-imgHeight)/2;//计算图片与窗口上边距  
            $(innerdiv).css({"top":h, "left":w});//设置#innerdiv的top和left属性  
            $(outerdiv).fadeIn("fast");//淡入显示#outerdiv及.pimg  
        });  
          
        $(outerdiv).click(function(){//再次点击淡出消失弹出层  
            $(this).fadeOut("fast");  
        });  
    }  
</script> 

</body>
</html> 
