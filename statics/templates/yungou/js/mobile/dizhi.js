$(function(){
        var first = $('#first');
        var second = $('#second');
        var third = $('#third');
        var first2 = $('#first2');
        var second2 = $('#second2');
        var third2 = $('#third2');

        first.on('change', function (ev) {
         
          $('#second').removeAttr('disabled');
          var nd_option_len=$('#second option').length;
          var first_val=first.val();
       
          if(nd_option_len > 1){	//判断所选省份是否含有市级地区
            return false;		//如果所选省份有市级地区,则返回false
          }else{		//如果所选省份没有市级地区，则完成所在地区这一栏的选择
          	$('.addr_box').val(first_val);
            $('#distpicker1').css('display','none');
          }
        })


        second.on('change', function (ev) {
          //  $('#third').css('display','inline-block');
          	var first_val=first.val();
        	var second_val=second.val();
          
           $('#third').removeAttr('disabled');
           	var rd_option_len=$('#third option').length;
          	var rd_addr=first_val+second_val;
      //    	console.log(rd_option_len);
          
          if(rd_option_len > 1){	//判断所选市级地区是否含有区或县
            return false;		//如果所选市级地区含有区或县，则返回false
          }else{		//如果所选市级地区没有区或县，则完成所在地区这一栏的选择
          	$('.addr_box').val(rd_addr);
            $('#distpicker1').css('display','none');
          }
          	
        })

        third.on('change', function (ev) {
          	var first_val=first.val();
            var second_val=second.val();
            var third_val=third.val();
            var final_addr=first_val+second_val+third_val;
            $('.addr_box').val(final_addr);
            $('#distpicker1').css('display','none');
        })

        first2.on('change', function (ev) {
         
          $('#second2').removeAttr('disabled');
          var nd_option_len=$('#second2 option').length;
          var first2_val=first2.val();
       
          if(nd_option_len > 1){  //判断所选省份是否含有市级地区
            return false;   //如果所选省份有市级地区,则返回false
          }else{    //如果所选省份没有市级地区，则完成所在地区这一栏的选择
            $('.addr_box').val(first2_val);
            $('#distpicker2').css('display','none');
          }
        })


        second2.on('change', function (ev) {
          //  $('#third').css('display','inline-block');
            var first2_val=first2.val();
          var second2_val=second2.val();
          
           $('#third2').removeAttr('disabled');
            var rd_option_len=$('#third2 option').length;
            var rd_addr=first2_val+second2_val;
      //      console.log(rd_option_len);
          
          if(rd_option_len > 1){  //判断所选市级地区是否含有区或县
            return false;   //如果所选市级地区含有区或县，则返回false
          }else{    //如果所选市级地区没有区或县，则完成所在地区这一栏的选择
            $('.addr_box').val(rd_addr);
            $('#distpicker2').css('display','none');
          }
            
        })

        third2.on('change', function (ev) {
            var first_val=first2.val();
            var second_val=second2.val();
            var third_val=third2.val();
            var final_addr=first_val+second_val+third_val;
            $('.addr_box').val(final_addr);
            $('#distpicker2').css('display','none');
        })


        $('.addr_box').on('click',function(){
            $('#distpicker1').css('display','block');
            $('#distpicker2').css('display','block');
        })


		//验证部分

		//限制只能输入数字
        function num(obj){
            var reg=/[\d]/;
            return (reg.test(String.fromCharCode(obj)));
        }
  	//	var reg_tel=/^[1][3,4,5,7,8][0-9]{9}$/;     //验证手机号码

		$('.new_type input[name="new_mobile"]').on('keypress',function(){    //限制联系电话只能输入数字
        	return num(event.keyCode);
    	})
    $('.edit_type input[name="edit_mobile"]').on('keypress',function(){    //限制联系电话只能输入数字
          return num(event.keyCode);
      })
  
  		$('.new_confirm').on('click',function(){
        	var name=$('input[name="new_name"]').val();
          	var mobile=$('.new_mobile').val();
          	var area=$('.addr_box').val();
          	var detail_addr=$('.detail_address').val();
            var sheng = $('#first').val();
            var shi = $('#second').val();
            var xian = $('#third').val();
          
          	if(name==''){
              $.PageDialog.fail("您的姓名未填写!")
              	$('input[type="new_name"]').focus();
              	return false;
            }else if(mobile==''){
              $.PageDialog.fail("您的联系方式未填写!")
              	$('.new_mobile').focus();
              	return false;
            }else if(area==''){
              $.PageDialog.fail("所在地区未填写!")
              	$('.addr_box').focus();
              	return false;
            }else if(detail_addr==''){
              $.PageDialog.fail("详细地址未填写!")
              	$('detail_address').focus();
              	return false;
            }
            $.ajax({
              type: 'POST',
              url: '/index.php/mobile/someing/add_addr',
              data: {'name':name,'mobile':mobile,'sheng':sheng,'shi':shi,'xian':xian,'jiedao':detail_addr},
              async: false,
              success:function(msg){
                if(msg == 0){
                  $.PageDialog.fail("请填写完整信息!");return false;
                }else if(msg == 1){
                  $.PageDialog.fail("请填写正确手机号码!");return false;
                }else if(msg == 2){
                  $.PageDialog.fail("<s></s>地址填写成功!");
                  setTimeout(function(){
                    window.location.reload();
                  },1500);
                }else{
                  $.PageDialog.fail("地址填写失败!");
                  setTimeout(function(){
                    window.location.reload();
                  },1500);
                }
              }
            })
        })

      $('.edit_confirm').on('click',function(){
          var id = $('.selectIt input:checked').attr('id');
          if(!id){
            $.PageDialog.fail("请选择地址！");return false;
          }
          var name=$('input[name="edit_name"]').val();
            var mobile=$('.edit_mobile').val();
            var area=$('#edit_box').val();
            var detail_addr=$('#edit_detail').val();
            var sheng = $('#first2').val();
            var shi = $('#second2').val();
            var xian = $('#third2').val();
          
            if(name==''){
              $.PageDialog.fail("您的姓名未填写!");
                $('input[type="edit_name"]').focus();
                return false;
            }else if(mobile==''){
              $.PageDialog.fail("您的联系方式未填写!");
                $('.edit_mobile').focus();
                return false;
            }else if(area==''){
              $.PageDialog.fail("所在地区未填写!");
                $('#edit_box').focus();
                return false;
            }else if(detail_addr==''){
              $.PageDialog.fail("详细地址未填写!");
                $('#edit_detail').focus();
                return false;
            }
            $.ajax({
              type: 'POST',
              url: '/index.php/mobile/someing/do_edit_addr',
              data: {'id':id,'name':name,'mobile':mobile,'sheng':sheng,'shi':shi,'xian':xian,'jiedao':detail_addr},
              dataType: 'json',
              success:function(msg){
                console.log(msg.status);
                if(msg.flag == 1){
                  $.PageDialog.fail(msg.status);
                  setTimeout(function(){
                    window.location.reload();
                  },1500)
                }else{
                  $.PageDialog.fail(msg.status);
                }
              }
            })
        })

      $('.del_btn').click(function(){
          var id = $('.selectIt input:checked').attr('id');
          if(!id){
            $.PageDialog.fail("请选择地址！");return false;
          }
          $.ajax({
            type: 'POST',
            url: '/index.php/mobile/someing/del_addr',
            data: {'id':id},
            dataType: 'json',
            success:function(msg){
              if(msg.flag == 1){
                $.PageDialog.fail(msg.status);
                setTimeout(function(){
                  window.location.reload();
                },1500)
              }else if(msg.flag == 2){
                $.PageDialog.fail(msg.status);
              }else if(msg.flag == 3){
                $.PageDialog.fail(msg.status);
              }
            }
          })
      })
  

})		
		
 // 确认地址
  function confirm(){
      var id = $('.selectIt input:checked').attr('id');
      if(!id){
        $.PageDialog.fail("请选择地址！");return false;
      }
      $.ajax({
        type: 'POST',
        url: '/index.php/mobile/someing/edit_addr',
        data: {'id':id},
        dataType: 'json',
        success:function(msg){
          if(msg.flag == 1){
            $('#pl_name').text(msg.name);
            $('#pl_mobile').text(msg.mobile);
            $('#pl_addr').text(msg.sheng+"-"+msg.shi+"-"+msg.xian+" "+msg.jiedao);
          }else if(msg.flag == 2){
            $.PageDialog.fail(msg.status);
          }
        }
      })
      document.getElementById("mask_bg").style.display="block";
      document.getElementById("confirm").style.display="block";
      document.documentElement.style.overflowY = 'hidden';    //弹窗出现时禁用滚动条
  }

  // 关闭确认弹窗
  function closeconfirm(){
      document.documentElement.style.overflowY = 'scroll'; 
      document.getElementById('mask_bg').style.display="none";
      document.getElementById('confirm').style.display="none";
  } 

  function confirm_btn(){
    var id = $('.selectIt input:checked').attr('id');
    if(!id){
      $.PageDialog.fail("请选择地址！");return false;
    }
    $.ajax({
      type: 'POST',
      url: '/index.php/mobile/someing/do_pl_addr',
      data: {'did':id},
      dataType: 'json',
      success:function(msg){
        if(msg.flag == 0){
          $.PageDialog.fail(msg.status);
          setTimeout(function(){
            window.location.href = "/index.php/mobile/someing/orderlist";
          },1500)
        }else if(msg.flag == 1){
          $.PageDialog.fail(msg.status);
          setTimeout(function(){
            window.location.href = "/index.php/mobile/someing/orderlist";
          },1500)
        }else if(msg.flag == 2){
          $.PageDialog.fail(msg.status);
          setTimeout(function(){
            window.location.href = "/index.php/mobile/someing/orderlist";
          },1500)
        }else if(msg.flag == 3){
          $.PageDialog.fail(msg.status);
          setTimeout(function(){
            window.location.href = "/index.php/mobile/someing/queren";
          },1500)
        }
      }
    })
  }

// 确认地址
  function new_address(){
      document.getElementById("mask_bg").style.display="block";
      document.getElementById("new_addr").style.display="block";
      document.documentElement.style.overflowY = 'hidden';    //弹窗出现时禁用滚动条
  }

  //修改地址
   function edit_address(){
      var id = $('.selectIt input:checked').attr('id');
      if(!id){
        $.PageDialog.fail("请选择地址！");return false;
      }
      $.ajax({
        type: 'POST',
        url: '/index.php/mobile/someing/edit_addr',
        data: {'id':id},
        dataType: 'json',
        success:function(msg){
          if(msg.flag == 1){
            $('#edit_addr input[name="edit_name"]').val(msg.name);
            $('#edit_addr input[name="edit_mobile"]').val(msg.mobile);
            $('#edit_addr .addr_box').text(msg.dq);
            $('#edit_addr .detail_address').text(msg.jiedao);
            $('#first2 option:first-child').replaceWith("<option value='"+msg.sheng+"' selected='true' style='display:none'>"+msg.sheng+"</option>");
            $('#second2 option:first-child').replaceWith("<option value='"+msg.shi+"' selected='true' style='display:none'>"+msg.shi+"</option>");
            $('#third2 option:first-child').replaceWith("<option value='"+msg.xian+"' selected='true' style='display:none'>"+msg.xian+"</option>");
          }else if(msg.flag == 2){
            $.PageDialog.fail(msg.status);
          }
        }
      })
      document.getElementById("mask_bg").style.display="block";
      document.getElementById("edit_addr").style.display="block";
      document.documentElement.style.overflowY = 'hidden';    //弹窗出现时禁用滚动条
  }

  // 关闭确认弹窗
  function closeaddress(){
      document.documentElement.style.overflowY = 'scroll'; 
      document.getElementById('mask_bg').style.display="none";
      document.getElementById('new_addr').style.display="none";
      document.getElementById('edit_addr').style.display="none";
  } 

	function closemask(){
    	closeconfirm();
      	closeaddress();
    }
		
    