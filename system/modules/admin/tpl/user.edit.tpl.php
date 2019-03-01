<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
</head>
<body>
<div  class="header lr10">
   <?php echo $this->headerment();?>	
 </div>
    <!-- login end-->
<div class="table_form lr10">
<form action="" method="post" id="myform">
<table class="lr10" width="100%">
    <tr>
    	<td width="80">用户名</td> 
   		<td><?php echo $info['username']; ?></td>
    </tr>
    <tr>
    	<td>密码</td>
    	<td><input type="password" name="password" class="input-text" id="password" value=""></input></td>
    </tr>
    <tr>
    	<td>确认密码</td> 
    	<td><input type="password" name="pwdconfirm" class="input-text" id="pwdconfirm" value=""></input></td>
    </tr>
    <tr>
    	<td>E-mail</td>
    	<td><?php echo $info['useremail']; ?></td>
	</tr>
    <tr>
    <td>管理员权限</td>
        <td>
        <div class="row">
        <div class="col-md-12">
        
        
         <?php echo $categoryshtml; ?>
          <div style=" text-align:right;">
          <label class="checkbox-inline "><input id="all" name="checkboxsall"  type="checkbox" value="option1" onclick="cli('checkboxs')">全选</label>
         </div>  
        </div>
        </div>
        
        
        </td>
    </tr>
</table>
   	<div class="bk15"></div>
    <input type="hidden" name="power" value="" class="form-control" id="power"></input>
    <input type="hidden" name="submit-1" />
    <input type="button" value=" 提交 " id="dosubmit" class="button" >
    </div></div>
</form>
</div><!--table-list end-->




<script language="javascript">
  function  cli(Obj)
  {
	 
  var collid = document.getElementById("all");
  var coll = document.getElementsByName(Obj) ;
  if (collid.checked){
	  
     for(var i = 0; i < coll.length; i++)
       coll[i].checked = true;
  }else{
        for(var i = 0; i < coll.length; i++)
          coll[i].checked = false;
  }
  }
  
 function ok()
   {alert(document.getElementById("power").value);
	    var collid = document.getElementById("all");
	    var coll = document.getElementsByName("checkboxs");
			 var str="";
			 var count=coll.length;
			 var j=0;
			 for(var i = 0; i < count; i++)
			 {
				 if(coll[i].checked)
				 {
					 j+=1;
					 str+=coll[i].value;
				 }
			 }
			 if(j==count)
			 {
				 document.getElementById("power").value="all"; 
			 }
			 else
			 {
				 document.getElementById("power").value=str;
			 }
			
			 
   }
  
  </script>

<script type="text/javascript">
var error='';
var bool=false;
var id='';


$(document).ready(function(){		
		
	   document.getElementById('dosubmit').onclick=function(){
		   		bool=false;
				var myform=document.getElementById('myform');
				
				if(myform.password.value){	
					
					if(!myform.pwdconfirm.value){
					 alert("请在次输入密码！");return false;
				    }
					
					if(myform.password.value!=myform.pwdconfirm.value){
						alert("次密码不相等！");return false;
						
						return false;
					}
					
				}
				
				
				
				    var collid = document.getElementById("all");
	                var coll = document.getElementsByName("checkboxs");
			        var str="";
					var topstr="";
			        var count=coll.length;
			        var j=0;
		            for(var i = 0; i < count; i++)
			        {
				        if(coll[i].checked)
				       {
					       j+=1;
				       	   str+=coll[i].value+",";
						    
						  if(topstr.indexOf(coll[i].attributes['topname'].nodeValue+",") == -1 )
                          {
						    topstr+=coll[i].attributes['topname'].nodeValue+",";
						 }
				       }
			        }
			       if(j==count)
			       {
			 	        document.getElementById("power").value="all"; 
			       }    
			         else
		       	    {
				      document.getElementById("power").value=topstr+str.substring(0,str.length-1);
			        }
					
					if(document.getElementById("power").value=='')
					{
						 alert("请选择权限！");return false;
					}
					
					
				
				
				
				
				
					
					document.getElementById('myform').submit();					
				
	   }
				
	
		
});

</script>
</body>
</html> 