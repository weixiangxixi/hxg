<?php
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/vendor/aliyuncs/oss-sdk-php/samples/Common.php';

	use OSS\OssClient;
	use OSS\Core\OssException;

	$bucket = Common::getBucketName();

	//阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
	
	// 文件名称
	$object = $_GET['object'];
	// 文件内容
	$img = $_GET['img'];
	try{
	    $ossClient = Common::getOssClient();
	    $ossClient->uploadFile($bucket, $object, $img);
	} catch(OssException $e) {
	    printf(__FUNCTION__ . ": FAILED\n");
	    printf($e->getMessage() . "\n");
	    return;
	}
	print("ok");