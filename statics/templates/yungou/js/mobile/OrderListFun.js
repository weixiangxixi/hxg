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

                if($('.lxkf0').hasClass('current')){
                    var jj = '?type=pt';
                }else if($('.lxkf2').hasClass('current')){
                    var jj = '?type=scq';
                }else{
                    var jj = '?type=no';
                }			 

               // GetJPData("http://m.1yyg.com", "getUserOrderList", j(),

			    GetJPData(Gobal.Webpath, "shopajax", "getUserOrderList_p_pt"+j()+jj,

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
                          
                            if(o[l].money==o[l].yunjiage*2){
                                var xxx = "fl z-Limg double";
                            }else{
                                var xxx = "fl z-Limg";
                            }
							
                         
                            m += "<li><a class=\""+xxx+"\" href=\""+Gobal.Webpath+'/mobile/mobile/item/' + o[l].shopid +"\"><img src=\""+"http://f.weimicm.com/" + o[l].thumb + "@!thumb_200_200\" border=0 alt=\"\"/></a><div class=\"u-gds-r gray9\"><p class=\"z-gds-tt\"><a href=\""+Gobal.Webpath+"/mobile/mobile/item/" + o[l].shopid +"\" class=\"gray6\">";
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

            var pl = function() {

                h.hide();

                b.show();                

                GetJPData(Gobal.Webpath, "shopajax", "getUserOrderList_pl"+j(),

                function(p) {                

                    if (p.code == 0) {

                        if (c.isCount == 1) {

                            c.isCount = 0;

                            f = p.count

                        }

                        var o = p.listItems;

                        var n = o.length;

                        var m = '<ul id="ulListBox" class="z-minheight">';

                        

                        for (var l = 0; l < n; l++) {                        
                          
                            if(o[l].money==o[l].yunjiage*2){
                                var xxx = "fl z-Limg double";
                            }else{
                                var xxx = "fl z-Limg";
                            }
                            
                         
                            m += "<li class=\"pl\" id=\""+o[l].orid+"\"><input type=\"checkbox\" id=\"" + o[l].id + "\"  name='rid' value='" + o[l].id + "'><label for=\"" + o[l].id + "\"></label><a class=\""+xxx+"\" href=\"javascript:;\"><img src=\""+"http://f.weimicm.com/" + o[l].thumb + "@!thumb_200_200\" border=0 alt=\"\"/></a><div class=\"u-gds-r gray9\"><p class=\"z-gds-tt\"><a href=\"javascript:;\" class=\"gray6\">";
                            if(o[l].zhigou!="1")
                            {
                            m += "(第" + o[l].qishu + "期)"+ o[l].shopname + "</a></p><p>幸运码：<em class=\"orange\">"+ o[l].q_user_code +"</em></p><p>揭晓时间：" + o[l].q_end_time + "</p>";
                            }
                            else
                            {
                                m += "(直购)"+ o[l].shopname + "</a></p><p>购买时间：" + o[l].time + "</p>";
                                
                            }
                            
                            if(o[l].str1 == 0){
                                if(o[l].dizhi_id==0){
                                    m += '<a href="javascript:;" class="z-gds-btn ">完善收货地址</a>'
                                }
                            }
                            

                            var q = parseInt(o[l].orderState);


                            m += '</div><a href="javascript:;"><b class="z-arrow"></b></a></li>'
							$('#pss').val('1');
                        }

                        m += '</ul>';

                        $('#ulListBox').replaceWith(m); 
                        if (c.EIdx < f) {

                            e = false;

                            h.show()

                        }

                        loadImgFun()
                    } 

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

            this.getInitPL = function() {

                pl()

            };

        };

        g = new d();

        g.getInitPage()
    
        $('.g-float-panel').on('click','#batch_edit',function(){
            c.FIdx = 0;
            pl_g = new d();
            pl_g.getInitPL();
         
            $('#batch_cancel').show();
            $('.lxkf0,.lxkf2').hide();
          	
          	
          	$("#batch_commit").removeClass("btn-batch-commit");
          	$("#batch_commit").addClass("btn-batch-commit1");
          	$("#batch_all").removeClass("btn-batch-all");
          	$("#batch_all").addClass("btn-batch-all1");
          	$("#batch_commit").attr("onclick","test();");
            
          	
          
        })

        $('.lxkf0').on('click',function(){
            $(this).addClass('current');
            $('.lxkf2').removeClass('current');
            $("#ulListBox").find("li").remove(); 
            c = {

                FIdx: 0,

                EIdx: i,

                isCount: 1

            };
            g = new d();
            g.getInitPage()
          
        })

        $('.lxkf2').on('click',function(){
            $(this).addClass('current');
            $('.lxkf0').removeClass('current');
            $("#ulListBox").find("li").remove(); 
            c = {

                FIdx: 0,

                EIdx: i,

                isCount: 1

            };
            g = new d();
            g.getInitPage()
          
        })

        $("#batch_cancel").click(function(){
            window.location.reload();
        })
      

   
     
      	$('.clearfix').on('click','ul li a',function(){
     
    	if($(this).siblings('input[type="checkbox"]').prop('checked')==false){
         	$(this).siblings('input[type="checkbox"]').prop('checked',true); 
       }else{
         	$(this).siblings('input[type="checkbox"]').prop('checked',false); 
       }

       $('#ulListBox input[type="checkbox"]').each(function(){
          var x = $(this).prop('checked');
          console.log(x);
       })
         	
         
        }) 
    
/*

        $('.clearfix').on('click','ul li',function(){
          
            var cls = $(this).attr('class');
      //    console.log($(this));
            if(cls == 'pl'){
                $(this).attr('class','pl_sel');
              	$(this).siblings().attr('class','pl');
          //    	$(this).children('input[type="checkbox"]').prop('checked',true);
            }else{
                $(this).attr('class','pl');
           //   	$(this).children('input[type="checkbox"]').prop('checked',false);
            }
          
          
        })

*/
     
      
 
      
        $('#batch_all').click(function(){
          	$('#batch_invert').css('display','block');
          	$('#batch_invert').css('color','#f60');
         	 $('#batch_invert').css('border','1px solid #f60');
            $('.pl').each(function(){
           //     $(this).attr('class','pl_sel');
              	$('#ulListBox input[type="checkbox"]').prop('checked',true);
            })
        })
      
      	$('#batch_invert').on('click',function(){
        	$('#ulListBox input[type="checkbox"]').prop('checked',false);
			
        })
      
		/*
        $("#batch_commit").click(function(){
            var oid = '';
            $('.pl_sel').each(function(){
                var id = $(this).attr('id');
                if(oid == ''){
                    oid += id;
                }else{
                    oid += ","+id;
                }
            })
            if(oid != ''){
                //跳转填写地址
            }
            console.log(oid);
        })
		*/
        h.click(function() {

            if (!e) {

                e = true;

                g.getNextPage()

            }

        }).show();
        $(window).scroll(function() {
            if ($(document).height() - $(this).scrollTop() - $(this).height() < 500 && !e) {
                e = true;
                g.getNextPage();
                
            }
        })


    };

    Base.getScript(Gobal.Skin + "/js/mobile/Comm.js", a)

});