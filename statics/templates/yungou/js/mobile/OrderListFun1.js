$(function() {

    var a = function() {

        var b = $("#divOrderLoading");

        var h = $("#btnLoadMore");

        var f = 0;

        var i = 10;

        var c = {

            FIdx: 0,

            EIdx: i,

            isCount: 1

        };

        var g = null;

        var e = false;

        var d = function() {

            var j = function() {

                return "/" + c.FIdx + "/" + c.EIdx + "/" + c.isCount

            };

            var k = function() {

                h.hide();

                b.show();				 

               // GetJPData("http://m.1yyg.com", "getUserOrderList", j(),

			    GetJPData(Gobal.Webpath, "shopajax", "getUserOrderList"+j(),

                function(p) {				 

                    if (p.code == 0) {

                        if (c.isCount == 1) {

                            c.isCount = 0;

                            f = p.count

                        }

                        var o = p.listItems;

                        var n = o.length;

                        var m = "";

						

                        for (var l = 0; l < n; l++) {						 

                            // m += "<li onclick=\"location.href='"+Gobal.Webpath+"/mobile/user/buyDetail/" 
                            // + o[l].shopid +'\'"><a class="fl z-Limg" href="'+Gobal.Webpath+'/mobile/mobile/item/' 
                            // + o[l].shopid +'"><img src="' + Gobal.LoadPic + '" src2="'+Gobal.imgpath+'/uploads/' 
                            // + o[l].thumb + '" border=0 alt=""/></a><div class="u-gds-r gray9"><p class="z-gds-tt"><a href="'+Gobal.Webpath
                            // +'/mobile/mobile/item/' + o[l].shopid +'" class="gray6">(第' + o[l].qishu + "期)" + o[l].shopname 
                            // + '</a></p><p>幸运码：<em class="orange">' + o[l].q_user_code 
                            // + "</em></p><p style='margin:0px;'>揭晓时间：" + o[l].q_end_time + "</p>";
                          //  console.log(o[l].cateid);
                            if(o[l].money==o[l].yunjiage*2){
                                var xxx = "fl z-Limg double";
                            }else{
                                var xxx = "fl z-Limg";
                            }
						//原代码	
                        // 	m += "<li><a class=\""+xxx+"\" href=\""+Gobal.Webpath+'/mobile/mobile/item/' + o[l].shopid +"\"><img src=\"" + Gobal.LoadPic + "\" src2=\""+Gobal.imgpath+"/uploads/" + o[l].thumb + "\" border=0 alt=\"\"/></a><div class=\"u-gds-r gray9\"><p class=\"z-gds-tt\"><a href=\""+Gobal.Webpath+"/mobile/mobile/item/" + o[l].shopid +"\" class=\"gray6\">";
                            m += "<li><input type=\"checkbox\" name='rid' value='" + o[l].id + "'><a class=\""+xxx+"\" href=\""+Gobal.Webpath+'/mobile/mobile/item/' + o[l].shopid +"\"><img src=\"" + Gobal.LoadPic + "\" src2=\""+Gobal.imgpath+"/uploads/" + o[l].thumb + "\" border=0 alt=\"\"/></a><div class=\"u-gds-r gray9\"><p class=\"z-gds-tt\"><a href=\""+Gobal.Webpath+"/mobile/mobile/item/" + o[l].shopid +"\" class=\"gray6\">";
                            
                          if(o[l].zhigou!="1")
                            {
                            m += "(第" + o[l].qishu + "期)"+ o[l].shopname + "</a></p><p>幸运码：<em class=\"orange\">"+ o[l].q_user_code +"</em></p><p>揭晓时间：" + o[l].q_end_time + "</p>";
                            }
                            else
                            {
                                m += "(直购)"+ o[l].shopname + "</a></p><p>购买时间：" + o[l].time + "</p>";
                                
                            }
                            //var q = parseInt(o[l].orderState);
                            
                            if(o[l].str1 == 0){
                                if(o[l].dizhi_id==0){
                                    m += '<a href="'+Gobal.Webpath+'/mobile/home/orderdetail/'+o[l].orid+'" class="z-gds-btn ">完善收货地址</a>'
                                }else{ 
                                    var q = o[l].status;
                                    var result=q.split(",");
                                     if(result[2]=='未完成' || result[2]=='待收货'){          
                                       if(result[1]=='未发货'){ 
                                       m += '<a href="/mobile/home/orderdetail/' + o[l].orid + '" class="z-gds-btn z-gds-btnDis">等待发货</a>'
                                       } 
                                       if(result[1]=='已发货'){
                                         m += "<a href='/mobile/home/orderdetail/" + o[l].orid + "' class=\"z-gds-btn z-gds-btnDis\">已发货,物流公司:"+o[l].company+",快递单号:"+o[l].company_code+"</a><a onclick = 'shouhuo(2," + o[l].orid + ")'   class=\"z-gds-btn qrfh\">确认收货</a>";
                                       }  
                                     } 
                                    if(result[2]=='已完成'){
                                         m += "<a href='/mobile/home/orderdetail/" + o[l].orid + "' class=\"z-gds-btn z-gds-btnDis\">订单已完成</a>"
                                      }   
                                        if(result[2]=='已作废'){
                                               m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">已作废</a>'
                                     }  
                                }
                            }else{
                                if(o[l].str2 == 0){
                                    m += '<a href="'+Gobal.Webpath+'/mobile/test/sck_pay/'+o[l].orid+'" class="z-gds-btn ">点击充值到账户</a>'
                                }else{
                                    m += '<a class="z-gds-btn" style="background:#b3b3b3">已充值到账户</a>'
                                }
                            }
                            

                            var q = parseInt(o[l].orderState);

                            // if (q == 1) {

                            //     m += '<a href="orderdetail-' + o[l].orderNo + '.html"  class="z-gds-btn">完善收货地址</a>'

                            // } else {

                            //     if (q == 2) {

                            //         m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">正在备货</a>'

                            //     } else {

                            //         if (q == 3) {

                            //             m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">已发货</a><a  href="orderdetail-' + o[l].orderNo + '.html" class="z-gds-btn">确认收货</a>'

                            //         } else {

                            //             if (q == 10) {

                            //                 if (parseInt(o[l].IsPostSingle) == 0) {

                            //                     m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">订单已完成</a><a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">已晒单</a>'

                            //                 } else {

                            //                     m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">订单已完成</a><a href="postSingle-' + o[l].codeID + '.html" class="z-gds-btn">去晒单</a>'

                            //                 }

                            //             } else {

                            //                 if (q == 11) {

                            //                     m += '<a href="javascript:void(0);" class="z-gds-btn z-gds-btnDis">订单已取消</a>'

                            //                 }

                            //             }

                            //         }

                            //     }

                            // }

                            m += '</div><a href="/mobile/home/orderdetail/' + o[l].orid + '"><b class="z-arrow"></b></a></li>'

                        }

                        if (c.FIdx > 0) {

                            b.prev().removeClass("bornone")

                        }

                        b.before(m).prev().addClass("bornone");						

                        if (c.EIdx < f) {

                            e = false;

                            h.show()

                        }

                        loadImgFun()

                    } else {

                        if (p.code == 10) {

                            location.reload()

                        } else {

                            if (c.FIdx == 0) {

                                b.before(Gobal.NoneHtml)

                            }

                        }

                    }

                    b.hide()

                })

            };

            this.getInitPage = function() {

                k()

            };

            this.getNextPage = function() {

                c.FIdx += i;

                c.EIdx += i;

                k()

            }

        };

        h.click(function() {

            if (!e) {

                e = true;

                g.getNextPage()

            }

        }).show();

        g = new d();

        g.getInitPage()

    };

    Base.getScript(Gobal.Skin + "/js/mobile/Comm.js", a)
   

});