var audio;
$(function() {

    audio =  new Audio("/statics/public/zhuanpan/music.mp3");
    $(function(){
        audio.loop = true;
        audio.play();
    });
    
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