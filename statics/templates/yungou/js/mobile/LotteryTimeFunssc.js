$.extend({
    StartTimeOut :
        function(elm, h) {
            var a = new Date();//当前时间
            a.setSeconds(a.getSeconds()+h);//当前时间+揭晓时间间隙
            var m = 0;//初始化分钟为 0
            var p = 0;//初始化秒为 0
            var o = 0;//初始化毫秒个位为 0
            var z= 0;//初始化小时为0
            var l = function() {
                var v = new Date();//当前时间
                if (a > v) {//如果还没到揭晓时间
                    var w = parseInt((a.getTime() - v.getTime()) / 1000);  //相差秒数
                    var u = w % 60; //秒
                    m = parseInt(w / 60);//相差分钟
                    z = parseInt(m/60);//相差小时 当前小时
                    m = parseInt(m % 60);//相差分钟转为当前分钟
                    p = parseInt(u);//当前秒
                    if (u >= p) {
                        o = parseInt((u - p) * 10)
                    } else {
                        o = 0
                    }
                    setTimeout(l, 1000);//每秒更新一次
                }
            };
            var f = 9;//初始化毫秒个位值

            //更新毫秒个位的函数
            var n = function() {
                f--;
                if (f < 0) {
                    f = 9
                }

                $("ul[lottime=" + elm + "] strong em:nth-child(5)").html(f);
                setTimeout(n, 10)
            };
            //更新end


            //结果更新 函数
            var c = function() {
                clearTimeout(l);
                clearTimeout(j);
                clearTimeout(n);
                clearTimeout(i);

                $("ul[lottime=" + elm + "]").addClass("over");
                $("ul[lottime=" + elm + "] p[name='pTime']").html("获取时时彩开奖码...");
            };
            //end

            var j = function() {
                o--;
                if (o < 1) { //个位毫秒小于0
                    if (p < 1) {//秒小于0
                        if (m < 1) {//分钟小于0
                            if(z<1){
                                c();//更新揭晓
                                return;
                            }else{
                                z--;
                            }
                            m=59;
                        } else {
                            m--;//更新分钟数
                        }
                        p = 59;//初始秒数
                    } else {
                        p--;//更新秒数 -1
                    }
                    o = 9;//初始毫秒十位为9
                }
                setTimeout(j, 100);//更新时间函数
            };

            var e = 0, q = 0,x=0;//临时秒，分，时

            var i = function() {
                $("ul[lottime=" + elm + "] strong em:nth-child(4)").html(o);//毫秒十位更新

                /*由于只采用一个setTimeout，所以每个时间元素都要实时更新，以确保新加入元素的计时一致*/
                // if (e != p) {
                    if (p < 10) {
                        $("ul[lottime=" + elm + "] strong em:nth-child(3)").html("0" + p)//更新秒位
                    } else {
                        $("ul[lottime=" + elm + "] strong em:nth-child(3)").html(p)//更新秒位
                    }
                    e = p;
                // }
                // if (q != m) {
                    if (m < 10) {
                        $("ul[lottime=" + elm + "] strong em:nth-child(2)").html("0" + m)//更新分位
                    } else {
                        $("ul[lottime=" + elm + "] strong em:nth-child(2)").html(m)//更新分位
                    }
                    q = m;
                // }
                // if(x != z){
                    if (z < 10) {
                        $("ul[lottime=" + elm + "] strong em:nth-child(1)").html("0" + z)//更新时位
                    } else {
                        $("ul[lottime=" + elm + "] strong em:nth-child(1)").html(z)//更新时位
                    }
                    x = z;
                // }
                /*end*/
                setTimeout(i, 100)
            };

            l();
            j();//动态计算时间
            n();//更新毫秒时间
            i();//动态更新时间
        }
});