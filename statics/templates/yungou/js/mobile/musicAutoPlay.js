var audio;
$(function() {
	// 绕开安卓端禁止媒体自动播放的机制
    audio =  new Audio("http://yyygbh-static.oss-cn-shenzhen.aliyuncs.com/images/mobile/luckyAmuse/muisc.mp3");
    $(function(){
        audio.loop = true;
        audio.play();
    });
    
    // 绕开IOS端禁止媒体自动播放的机制（启用微信分享时不能写在此处）
    /*function autoPlayAudio() {
        wx.config({
            // 微信配置
            debug: false,
            appId: '',
            timestamp: 1,
            nonceStr: '',
            signature: '',
            jsApiList: []
        });
        wx.ready(function() { // 利用wx.ready触发
            audio.play();
            audio.loop = true;
        });
    };
    autoPlayAudio();*/

    // 播放控制
    $("#bgm").click(function () {
        if (audio.paused) {
            audio.play();
            $("#bgm").css("-webkit-animation-play-state", "running");
            return;
        }
        audio.pause();
        $("#bgm").css("-webkit-animation-play-state", "paused");
    });
});