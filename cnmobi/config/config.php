<?php
class Config{
    private $cfg = array(
        //测试商户号，商户需改为自己的
        'merchantNo'=>'8085189022921628',    
         //#测试密钥，商户需改为自己的   
        'secret'=>'509126390999',  
        //接口请求地址，固定不变，无需修改
        'reqUrl'=>'https://pay.cnmobi.cn/pay/',     
        //通知回调地址，目前默认是****，商户在测试支付和上线时必须改为自己的，且保证外网能访问到
        'nofityUrl'=>'http://m.weimicm.com/index.php/pay/xypay_web_url2/wxnofity' 
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
