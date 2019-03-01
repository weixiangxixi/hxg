<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun('global');

class cnmb extends base
{

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class('model');
    }
    public function qrcode(){
    	//echo 1;
		echo $this->scerweima('https://www.baidu.com');
    }
  	
  	// 1. 生成原始的二维码(生成图片文件)
    public function scerweima()
    {	
        require_once 'phpqrcode/phpqrcode.php';	
      	//require_once 'phpqrcode.php';		
        $value = $url;					
        //二维码内容		
        $errorCorrectionLevel = 'L';	
        //容错级别 	
        $matrixPointSize = 5;			
        //生成图片大小  		
        //生成二维码图片	
      	$id = 1;
        $filename = "statics/templates/yungou/images/mobile/qrcode2/".$id.'.png';	
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);    	
        $QR = $filename;				
        //已经生成的原始二维码图片文件    	
        $QR = imagecreatefromstring(file_get_contents($QR));    	
        //输出图片  	
        imagepng($QR, 'a'.$id.'.png');  	
        imagedestroy($QR);	  
    }
    
}