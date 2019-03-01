<?php
	// 1. 生成原始的二维码(生成图片文件)
  function scerweima($url=''){	
  	require_once 'phpqrcode.php';		
  	$value = $url;					
  	//二维码内容		
  	$errorCorrectionLevel = 'L';	
  	//容错级别 	
  	$matrixPointSize = 5;			
  	//生成图片大小  		
  	//生成二维码图片	
  	$filename = "statics/templates/yungou/images/mobile/qrcode/".microtime().'.png';	
  	QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);    	
  	$QR = $filename;				
  	//已经生成的原始二维码图片文件    	
  	$QR = imagecreatefromstring(file_get_contents($QR));    	
  	//输出图片  	
  	imagepng($QR, 'qrcode12.png');  	
  	imagedestroy($QR);	
  	return '<img src="qrcode12.png" alt="使用微信扫描支付">';   
  } 
//调用查看结果
echo scerweima('https://www.baidu.com');

