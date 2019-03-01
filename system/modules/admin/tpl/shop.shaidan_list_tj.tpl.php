<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>晒单添加 - {wc:$webname}触屏版</title>
    <meta content="app-id=518966501" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<link href="{G_TEMPLATES_CSS}/mobile/comm.css" rel="stylesheet" type="text/css" />
<link href="/statics/templates/yungou/css/mobile/shai/login.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/statics/templates/yungou/css/mobile/shai/bootstrap.min.css">
<script src="/statics/templates/yungou/css/mobile/shai/jquery190.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" href="/statics/templates/yungou/css/mobile/shai/style.css">
<link rel="stylesheet" href="/statics/templates/yungou/css/mobile/shai/jquery.fileupload.css">
<script src="/statics/templates/yungou/css/mobile/shai/jquery.ui.widget.js"></script>
<script src="/statics/templates/yungou/css/mobile/shai/jquery.iframe-transport.js"></script>
<script src="/statics/templates/yungou/css/mobile/shai/jquery.fileupload.js"></script>
<script src="/statics/templates/yungou/css/mobile/shai/bootstrap.min.js"></script>
</head>
<body style="padding-top:0px;" style="background-color: #f4f4f4;">
<script language="javascript" type="text/javascript">
function showdiv() {
    document.getElementById("bg").style.display ="block";
    document.getElementById("show").style.display ="block";
}
function hidediv() {
    document.getElementById("bg").style.display ='none';
    document.getElementById("show").style.display ='none';
}
</script>
<style type="text/css">
        #bg{ display: none;  position: absolute;  top: 0%;  left: 0%;  width: 100%;  height: 100%;  background-color: #f4f4f4;  z-index:1001;  -moz-opacity: 0.7;  opacity:.70;  filter: alpha(opacity=70);}
        #show{display: none;  position: absolute;  top: 40%;  left: 22%;  width: 53%;  height: 49%;  padding: 8px;  z-index:1002;  overflow: auto;}
        .loading {
            clear: both;
            width: 100%;
            display: block;
            height: 90px;
            line-height: 40px;
            text-align: center;
            color: #999;
            font-size: 45px;
            box-shadow:none;
            background: none;
        }

        .loading b {
            background: url(data:image/gif;base64,R0lGODlhEAAQAPIAAP///wCA/8Lg/kKg/gCA/2Kw/oLA/pLI/iH/C05FVFNDQVBFMi4wAwEAAAAh/h1CdWlsdCB3aXRoIEdJRiBNb3ZpZSBHZWFyIDQuMAAh/hVNYWRlIGJ5IEFqYXhMb2FkLmluZm8AIfkECQoAAAAsAAAAABAAEAAAAzMIutz+MMpJaxNjCDoIGZwHTphmCUWxMcK6FJnBti5gxMJx0C1bGDndpgc5GAwHSmvnSAAAIfkECQoAAAAsAAAAABAAEAAAAzQIutz+TowhIBuEDLuw5opEcUJRVGAxGSBgTEVbGqh8HLV13+1hGAeAINcY4oZDGbIlJCoSACH5BAkKAAAALAAAAAAQABAAAAM2CLoyIyvKQciQzJRWLwaFYxwO9BlO8UlCYZircBzwCsyzvRzGqCsCWe0X/AGDww8yqWQan78EACH5BAkKAAAALAAAAAAQABAAAAMzCLpiJSvKMoaR7JxWX4WLpgmFIQwEMUSHYRwRqkaCsNEfA2JSXfM9HzA4LBqPyKRyOUwAACH5BAkKAAAALAAAAAAQABAAAAMyCLpyJytK52QU8BjzTIEMJnbDYFxiVJSFhLkeaFlCKc/KQBADHuk8H8MmLBqPyKRSkgAAIfkECQoAAAAsAAAAABAAEAAAAzMIuiDCkDkX43TnvNqeMBnHHOAhLkK2ncpXrKIxDAYLFHNhu7A195UBgTCwCYm7n20pSgAAIfkECQoAAAAsAAAAABAAEAAAAzIIutz+8AkR2ZxVXZoB7tpxcJVgiN1hnN00loVBRsUwFJBgm7YBDQTCQBCbMYDC1s6RAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P4wykmrZULUnCnXHggIwyCOx3EOBDEwqcqwrlAYwmEYB1bapQIgdWIYgp5bEZAAADsAAAAAAAAAAAA=);
            background-size: 35px auto;
            background-repeat: no-repeat;
            width: 40px;
            height: 40px;
            display: inline-block;
            margin-right: 5px;
            position: relative;
            top: 2px;
        }
        a.orgBtn {
    background: rgb(255, 102, 0);
}
.grayBtn, .orgBtn {
    display: block;
    width: 100%;
    box-sizing: border-box;
    height: 35px;
    line-height: 40px;
    text-align: center;
    font-size: 18px;
    margin-right: 10px;
    color: rgb(255, 255, 255) !important;
    border-radius: 5px;
}
</style>
    <div class="h5-1yyg-v1" id="content">

<!-- 栏目页面顶部 -->


<!-- 内页顶部 -->
    <header class="header" style="position: fixed;width: 100%;z-index: 99999999;">

    <h1 style="width: 600px;text-align: center;float: none;top: 0px;left: 0px;font-size: 25px;margin-left: 20%" class="fl">
        <span style="display: block;height: 49px;line-height: 49px;">
            <a style="font-size: 20px;line-height: 49px;" href="/index.php/mobile/mobile/dataserver/<?php echo $data['id']; ?>" target="view_window">
                <?php echo $data['title']; ?>(第<?php echo $data['qishu']; ?>期) -- UID：<?php echo $data['q_uid']; ?>
            </a>
        </span>

        <!--<img src="{G_UPLOAD_PATH}/{wc:fun:Getlogo()}"/>
        -->
        <!--<img src="/statics/templates/yungou/images/sjlogo.png"/>
        -->
    </h1>

    <a id="fanhui" class="cefenlei" onclick="history.go(-1)" href="javascript:;">
        
        <img width="30" height="30" src="/statics/templates/yungou/images/mobile/fanhui.png">
    </a>

    <div class="fr head-r" style="position: absolute;right: 6px;top: 10px;">

        <!--<a href="{WEB_PATH}/mobile/user/login" class="z-Member"></a>
    -->
    <a href="{WEB_PATH}/mobile/mobile" class="z-shop" style="background-position: 2px -73px;"></a>

</div>

</header>

    <form action="" method="POST" id="postsingle_form" style="padding-top: 55px;width: 600px;margin-left: 20%">
        <section >
            <div class="registerCon">
                <ul>
                    <li>
                        <dl><input name="title" type="text" placeholder="标题"></dl>
                        <dl style="margin-top:5px;"><textarea style="background: #fff none repeat scroll 0 0;border: 1px solid #ddd;border-radius: 5px;color: #ccc;display: inline-block;font-size: 16px;padding: 0 5px;position: relative;width: 100%;" name="content" rows='5'  placeholder="描述您的晒单内容"></textarea></dl>
                    </li>
                    <li>
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>添加图片</span>
                            <input id="fileupload" type="file" name="files[]" multiple>
                        </span>
                        <input type="hidden" name="fileurl_tmp" id="fileurl_tmp">
                        <div id="files" class="files"></div>
                    </li>
                    <li><a href="javascript:;" onclick="$('#postsingle_form').submit();" id="btnLogin" class="nextBtn  orgBtn">晒单</a>(由于手机拍照图片较大，上传晒图请耐心等待)</li>
                </ul>
            </div>
        </section>
    </form>

<script language="javascript" type="text/javascript">
$(function () {
    'use strict';
    var url = '/index.php/mobile/home/file_upload/';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            hidediv();
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
                var s = $('#fileurl_tmp').val();
                if(s.length > 0){
                    $('#fileurl_tmp').val(s+';'+file.url);
                }else{
                    $('#fileurl_tmp').val(file.url);
                }
            });
        },
    
          progressall: function (e, data) {
            showdiv();
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

</script>

    </div>
    <div id="bg"></div>
<div id="show" class="h5-1yyg-v1">
    <div id="divPayBox" class="paymainbox">
        <div class="loading"><b></b></div>
    </div>
</div>
</body>
</html>
