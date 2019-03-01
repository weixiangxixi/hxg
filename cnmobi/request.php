<?php

/**
 * 支付接口调测例子
 * @param  $orderNo      订单号
 * @param  $timestamp    时间戳
 * @param  $name         商品名称
 * @param  $total        商品金额
 * @param  $merchantNo   商户号
 * @param  $nofityUrl    付异步回调地址
 * @params $sign         根据算法生成的签名
 */

 //应用入口文件
require('config/config.php');
require('class/RequestHandler.class.php');
Class Request
{

    private $RequestHandler;
    private $cfg ;

    function __construct()
    {
        $this->RequestHandler = new RequestHandler();
        $this->cfg = new Config();

    }


    /**
     * 请求入口文件
     */
    public function index()
    {
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : 'submitOrderInfo';
        switch ($method) {
            case 'wxScancode':
                $this->wxScancode();
                break;
            case 'wxBscancode':
                $this->wxBscancode();
                break;
            case 'alipayScancode':
                $this->alipayScancode();
                break;
            case 'alipayBscancode':
                $this->alipayBscancode();
                break;
            case 'orderStatus':
                $this->orderStatus();
                break;
            case 'cancelOrder':
                $this->cancelOrder();
                break;
            case 'localOrderQuery':
                $this->localOrderQuery();
                break;
            case 'wxGzh':
                $this->wxGzh();
                break;
            case 'refundOrder':
                $this->refundOrder();
                break;

                
        }
    }

	/**
	*该方法用于查询本地订单状态
	*可以是查询自己项目数据库的订单信息用于支付成功回调查询、这里只做将支付回调成功的数据写入本地文件、
	*/
    public  function  localOrderQuery(){

       $orderNo= $_REQUEST['orderNo'];
       $file_path = "result.txt";
       $content = file_get_contents($file_path);
       $res= strpos($content, $orderNo);
       if($res){
            echo json_encode(array("msg"=>"支付成功","code"=>1));
       }else{
           echo json_encode(array("msg"=>"支付失败","code"=>0));
       } 
    }
    /**
     * 微信扫码支付
     */
    public function wxScancode()
    {
        $params['orderNo'] = $_POST['orderNo'];
        $params['name'] = $_POST['name'];
        $params['total'] = $_POST['total'];
        $params['merchantNo'] = $this->cfg->C('merchantNo'); //获取配置文件里面的商户号
        $params['nofityUrl'] = $this->cfg->C('nofityUrl'); //获取配置文件里面的回调
        $data = $this->RequestHandler->HttpPost('scancode/wx', $params);

        $data = $this->RequestHandler->signCheck($data); //对返回的数据进行验签名 
        echo $data;
    }

    /**
     * 微信刷卡支付
     */
    public function wxBscancode()
    {
        $params['orderNo'] = $_POST['orderNo'];
        $params['name'] = $_POST['name'];
        $params['total'] = $_POST['total'];
        $params['merchantNo'] = $this->cfg->C('merchantNo'); //获取配置文件里面的商户号
        $params['nofityUrl'] = $this->cfg->C('nofityUrl'); //获取配置文件里面的回调
        $params['autoCode'] = $_POST['autoCode'];

        $data = $this->RequestHandler->HttpPost('bscancode/wx', $params);
        $data = $this->RequestHandler->signCheck($data); //对返回的数据进行验签名 
        echo $data;
    }


    /**
     * 支付宝扫码支付
     */
    public function alipayScancode()
    {
        $params['orderNo'] = $_POST['orderNo'];
        $params['name'] = $_POST['name'];
        $params['total'] = $_POST['total'];
        $params['merchantNo'] = $this->cfg->C('merchantNo'); //获取配置文件里面的商户号
        $params['nofityUrl'] = $this->cfg->C('nofityUrl'); //获取配置文件里面的回调
        $data = $this->RequestHandler->HttpPost('scancode/alipay', $params);
        $data = $this->RequestHandler->signCheck($data); //对返回的数据进行验签名 
        echo $data;
    }

    /**
     * 支付宝商家端刷卡
     */
    public function alipayBscancode()
    {
        $params['orderNo'] = $_POST['orderNo'];
        $params['name'] = $_POST['name'];
        $params['total'] = $_POST['total'];
        $params['merchantNo'] = $this->cfg->C('merchantNo'); //获取配置文件里面的商户号
        $params['nofityUrl'] = $this->cfg->C('nofityUrl'); //获取配置文件里面的回调
        $params['autoCode'] = $_POST['autoCode'];
        $data = $this->RequestHandler->HttpPost('bscancode/alipay', $params);
        $data = $this->RequestHandler->signCheck($data); //对返回的数据进行验签名 
        echo $data;
    }

    /**
     * 订单状态查询
     */
    public function  orderStatus(){
        $params['orderNo'] = $_POST['orderNo'];
        $params['merchantNo'] = $this->cfg->C('merchantNo');
        $data = $this->RequestHandler->HttpPost('order/orderStatus', $params);
        echo $data;
    }


    /**
     * 订单撤销
     */
    public function cancelOrder(){
        $params['orderNo'] = $_POST['orderNo'];
        $params['merchantNo'] = $this->cfg->C('merchantNo');
        $data = $this->RequestHandler->HttpPost('order/cancelOrder', $params);
        echo $data;
    }

    /**
     * 微信公众号支付
     */
    public function wxGzh(){
        $params['openID'] = $_POST['openID'];
        $params['orderNo'] = $_POST['orderNo'];
        $params['name'] = $_POST['name'];
        $params['total'] = $_POST['total'];
        $params['returnUrl'] = $_POST['returnUrl'];
        $params['merchantNo'] = $this->cfg->C('merchantNo'); //获取配置文件里面的商户号
        $params['nofityUrl'] = $this->cfg->C('nofityUrl'); //获取配置文件里面的回调
        $data = $this->RequestHandler->HttpPost('wxgzh/api', $params);
        $data = $this->RequestHandler->signCheck($data); //对返回的数据进行验签名 
        echo $data;

    }

    /**
     * 订单退款
     */
    public function refundOrder(){
        $params['orderNo'] = $_POST['orderNo'];
        $params['refundFee'] = $_POST['refundFee'];
        $params['refundReson'] = $_POST['refundReson'];
        $params['merchantNo'] = $this->cfg->C('merchantNo');
        $data = $this->RequestHandler->HttpPost('order/refundOrder', $params);
        echo $data;
        
    }
}

$req = new Request();
$req->index();

?>