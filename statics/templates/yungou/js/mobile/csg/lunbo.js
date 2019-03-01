(function(){
    var oid = '';
    //竖向滚动揭晓
    var rolbox=$("#anMarquee");
    var updaterol = function(info,len,i){
        var html = '<p>恭喜:' +info.username +'获得'+info.title + '</p>';

        var tmpHeight = (i+1) * liHeight;
        rolbox.append(html);
        rolbox.css({
            "Height" :tmpHeight
        });

        if(i==(len-1)){
            move(0,len);
        }
    };

    var nthread = function() {
        $.getJSON("/index.php/mobile/game/lunbo",function(infos){
            console.log(infos);
            if(infos.error == '0') {
                var list =  infos.listItems;
                var len=list.length;
                for(var i=0;i<len;i++){
                    updaterol(list[i],len,i);
                }
            } else {
                setTimeout(nthread, 2000);
            }
        });
    };
    var liHeight=rolbox.outerHeight();
    rolbox.find("p").css("height",liHeight);
    nthread();

    function move(j,len){
        if(j==(len-1)){
            rolbox.animate({marginTop:-(j+1)*liHeight+"px"},1500,function(){//动画效果 并在回调函数中将最后一个li移除
                rolbox.children("p:lt("+len+")").remove();
                rolbox.css("height",liHeight);
                rolbox.css("marginTop",0);
                setTimeout(nthread, 2000);
            });
        }else{
            rolbox.animate({marginTop:-(j+1)*liHeight+"px"},1500,function(){//动画效果 并在回调函数中将最后一个li移除
                move(j+1,len);
            });
        }
    }
})();