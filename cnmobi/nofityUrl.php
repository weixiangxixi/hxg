<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 */
require ('config/config.php');
require ('class/RequestHandler.class.php');
class nofityUrl
{

    public $cfg ;
    private $RequestHandler;

    function  __construct()
    {
        $this->cfg = new Config();
        $this->RequestHandler = new RequestHandler();

    }

    /**
     * 支付成功回调方法
     * 支付成功之后接口会请求该方法并返回result(可参照接口文档) 
     */
    public function index()
    {
        $response = $_REQUEST;
		$resSign=$response['sign'];

		if(!$response){
			echo "error";
			die();
		}
        $data = $_REQUEST;
      
        unset($response['sign']);
        $sign=$this->RequestHandler->createSign($response);
        if($sign==$resSign){
            $handler = fopen('result.txt','a+');
            $content = "================".date("Y-m-d H:i:s")."===================\n";
            if(is_string($data) === true){
                $content .= $data."\n";
            }
            if(is_array($data) === true){
                forEach($data as $k=>$v){
                    $content .= "key: ".$k." value: ".$v."\n";
                }
            }
            $flag = fwrite($handler,$content);
            fclose($handler);
            return $flag;
        }else{

        }
    }
}

$req = new nofityUrl();
$req->index();
?>