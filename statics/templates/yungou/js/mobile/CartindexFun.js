
$(".tags span").on("click",function(){
    var id = $(this).data("id");
    var num = $(this).data("val");
    var sy = parseInt($(this).parent().prev().children("input:last-child").val());
    if(num > sy){
        $.PageDialog.fail("最多" + sy + "人次");
        num = sy;
    }
    $("#txtNum" + id).val(num).focus();
    $("#txtNum" + id).val(num);
    $("#shuliang" + id).val(num);
    $("#price" + id).text(num);
    $("#arial" + id).text(num);
    $("#orange" + id).text("￥"+num);
    var a = $("#cartBody");
    var q = 0;
    var r = 0;
    $("input:text[name=num]", a).each(function(s) {
         var t = parseInt($(this).val());
         var ss = parseInt($(this).siblings('input').val());
         if (!isNaN(t)) {
             r++;
             q += t * ss;
         }
    });
    $("#total").text(q);
    $("#txtNum" + id).val(num).blur();

    // $(this).parent().parent().prev().find(".z-jian").trigger("click");
    // setTimeout(function(self){
    //   return function(){
    //     $(self).parent().parent().prev().find(".z-jia").trigger("click");
    //   }
    // }(this), 100);
});



 $(function() {
     var a = $("#cartBody");
     var c = $("#divNone");
     var b = function() {
         var o = "";
         var h = $("#divTopMoney");
         var g = $("#divBtmMoney");
         var e = function(t, s, r, q) {
             $.PageDialog.fail(t, s, r, q)
         };
         var n = function(s, r, q) {
             $.PageDialog.confirm(s, r, q)
         };
         if (h.length > 0) {
             h.children("a").click(function() {
                 location.href = Gobal.Webpath + "/mobile/cart/pay" //付款页面
             })
         }
         $("#divBtmMoney dl dd a").click(function() {
             location.href = Gobal.Webpath + "/mobile/cart/pay" //付款页面
         });
         var m = function() {
             var q = 0;
             var r = 0;
             $("input:text[name=num]", a).each(function(s) {
                 var t = parseInt($(this).val());
                 var ss = parseInt($(this).siblings('input').val());
                 if (!isNaN(t)) {
                     r++;
                     q += t * ss;
                 }
             });
             $(".regular-radio:checked").each(function(s) {
                 var t = parseInt($(this).val());
                 var ss = parseInt($(this).parent().siblings('input').attr('yunjiage'));
                 if (!isNaN(t)) {
                    r++;
                    q += t * ss;
                 }
             })
             if (r > 0) {
                 if (h.length > 0) {
                     h.children("span").html(q)
                 }
                 //g.children("p").html('总共购买<span class="orange arial z-user">' + r + '</span>个奖品  合计金额：<span id="total" class="orange arial">' + q + ".00</span> 元")
                 $('<dt class="gray6"><p class="money-total">合计：&nbsp;<em class="orange">'+q+'.00&nbsp;元</em></p><p class="pro-total">总共&nbsp;<em>'+r+'&nbsp;</em>件商品</p></dt>').replaceAll("#divBtmMoney dl dt");
             } else {
                 g.remove();
                 $('#all').remove();
             }
         };
         var d = function() {
             var z = $(this);
             var yjg = z.attr('yunjiage');
             var t = z.attr("id");
             var v = t.replace("txtNum", "");
             var q = z.next().next();
             var r = parseInt(z.next().next().next().val());
             var s, y, w = /^[1-9]{1}\d{0,6}$/;
             var u;
             o = t;
             var x = function() {
                 if (o != t) {
                     return
                 }
                 s = q.val();
                 y = z.val();
                 if (y != "" && s != y) {
                     var B = $(window).width();
                     var A = (B) / 2 - z.offset().left - 127;
                     if (w.test(y)) {
                         u = parseInt(y);
                         if (u <= r) {
                             q.val(y)
                         } else {
                             u = r;
                             e("最多" + u + "人次");
                    
                             z.val(u);
    
                             q.val(u)
                         }
                         p(u, z);

                         j(z, v, u, yjg);
                         i(z, u, r);
                         m()
                     } else {
                         e("只能输正整数哦", z, -75, A);
                         z.val(s)
                     }
                 }
                 setTimeout(x, 200)
             };
             x()
         };
         var p = function(r, u) {
             var t = u.parent().parent().parent();
             var q = t.find("div.z-Cart-tips");
             if (r > 100) {
                 if (q.length == 0) {
                     var s = $('<div class="z-Cart-tips">已超过100人次，请谨慎参与！</div>');
                     t.prepend(s)
                 }
             } else {
                 q.remove()
             }
         };
         var l = function() {
             var q = $(this);
             if (o == q.attr("id")) {
                 o = ""
             }
             if (q.val() == "") {
                 q.val(q.next().next().val())
             }
         };
         var j = function(q, t, r, yjg) {
             var s = function(w) {
                 if (w.code == 1) {
                     var v = $(window).width();
                     var u = (v) / 2 - q.offset().left - 127;
                     e("本期奖品已购买光了", q, -75, u)
                 } else {
                     if (w.code == 0) {
                        var ids = q.parent().prev().find(".proce").attr("ids");
                         //q.parent().prev().html('总共参与：<em class="arial proce" id="arial'+ids+'" ids="'+ids+'">' + r + '</em>人次/<em class="orange arial" id="orange'+ids+'" ids="'+ids+'">￥' + r * yjg + "</em>")
                     }
                     //console.log(yjg);
                 }
             };
             GetJPData(Gobal.Webpath, "ajax", "addShopCart/" + t + "/" + r + "/cart", s)
         };
         var k = function(w, v) {
             var yjg = v.attr('yunjiage');
             var u = v.attr("id");
             var s = u.replace("txtNum", "");
             var r = parseInt(v.next().next().next().val());
             var q = v.next().next();
             var t = parseInt(q.val()) + w;
             if (t > 0 && t <= r) {
                 i(v, t, r);
                 q.val(t);
                 v.val(t);
                 p(t, v);
                 j(v, s, t, yjg);
                 m()
             }
         };
         var k2 = function(w, v) {
             var yjg = v.parent().prev().attr('yunjiage');
             var u = v.parent().prev().attr("id");
             var s = u.replace("txtNum", "");
             var r = parseInt(v.parent().prev().val());
             var t = w;
             if (t > 0 && t <= r) {
                 v.val(t);
                 j(v, s, t, yjg);
                 m()
             }
         };
         var i = function(r, t, s) {
             var q = r.prev();
             var u = r.next();
             if (s == 1) {
                 q.addClass("z-jiandis");
                 u.addClass("z-jiadis")
             } else {
                 if (t == 1) {
                     q.addClass("z-jiandis");
                     u.removeClass("z-jiadis")
                 } else {
                     if (t == s) {
                         q.removeClass("z-jiandis");
                         u.addClass("z-jiadis")
                     } else {
                         q.removeClass("z-jiandis");
                         u.removeClass("z-jiadis")
                     }
                 }
             }
         };
         $("input:text[name=num]", a).each(function(q) {
             var r = $(this);
             r.bind("focus", d).bind("blur", l);
             r.prev().bind("click",
                 function() {
                     k(-1, r)
                 });
             r.next().bind("click",
                 function() {
                     k(1, r)
                 })
         });
         $(".regular-radio:checked").each(function(q) {
             var r = $(this).parent().children('.regular-radio');
             r.click(function(){
                    var m = $(this);
                    var t = parseInt($(this).val());
                    k2(t,m);
                });
         })
         var f = function() {
             var q = $("li", "#cartBody");
             if (q.length < 1) {
                 a.parent().remove();
                 c.show()
             } else {
                 if (q.length < 4) {
                     h.remove()
                 }
             }
         };
         $("a[name=delLink]", a).each(function(q) {
             $(this).bind("click",
                 function() {
                     var r = $(this);
                     var t = r.attr("cid");
                     var s = function() {
                         var u = function(v) {
                             if (v.code == 0) {

                                 r.parent().parent().parent().remove();
                                 m();
                                 f()
                             } else {
                                 e("删除失败，请重试")
                             }
                         };
                         GetJPData(Gobal.Webpath, "ajax", "delCartItem/" + t, u)
                     };
                     n("您确定要删除吗？", s)
                 })
         })
     };

     if (a.length > 0) {
         Base.getScript(Gobal.Skin + "/js/mobile/pageDialog.js", b)
     } else {
         c.show()
     }
 });