<?php
/**
 * 请求类
 * ============================================================================
 * api说明：
 * createSign() 签名生成方法
 * HttpPost( )/CURl请求
 * signCheck() 返回的参数进行验证签名
 * ============================================================================
 *
 */

 date_default_timezone_set('PRC'); //设置timezone 为中国东八区
class RequestHandler
{

    public $cfg ;
    

    function __construct()
    {
        $this->cfg = new Config();
        //file_put_contents("log.txt", date("Y-m-d H:i:s", time()) . ",时间:construct" . PHP_EOL, FILE_APPEND);
    }


    /**
     * 获取sign签名方法
     * @return string
     */
    public function createSign($parms)
    {
        $signPars = "";
        ksort($parms);
        foreach ($parms as $k => $v) {
            if ("" != $v && "sign" != $k) {
                $signPars .= $k . "=" . $v;
            }
        }

        $secret = $this->cfg->C('secret');
        $sign = md5($signPars . $secret); //sign签名生成
        return $sign;

    }


    /**
     * CURL 请求接口
     * @param $url
     * @param $param
     * @return mixed
     */
    public function HttpPost($url, $param)
    {


      
        $param['timestamp'] = 	time()*1000;  //统一给参数添加时间戳参数
        $reqUrl = $this->cfg->C('reqUrl');
        $param['sign'] = $this->createSign($param); //生成签名参数
        
        $ch = curl_init();//启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $reqUrl . $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置请求超时时间
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //设置请求方式为POST请求
        curl_setopt($ch, CURLOPT_POST, 1); //发送一个常规的POST请求。
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param)); //将params 转成 a=1&b=2&c=3的形式
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl获取页面内容, 不直接输出


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $data = curl_exec($ch); // 已经获取到内容，没有输出到页面上。

        curl_close($ch);
        return $data;

    }

    /**
     * signCheck 接口数据返回验签
     * 对返回的数据继续进行验签方法/并真正返回数据给前端使用
     * 当请求接口的数据中带有result 有返回sign字段时使用验签
     */
    public function  signCheck($response){
        $responses=json_decode($response, true);
        if(!isset($responses['result'])){
            return $response;
        }else{
            $result=$responses['result']; //得到result部分进行验签
            $resSign=$result['sign'];  //获取接口返回的sign
            unset($result['sign']);   //去除result的sign 将其加密生成签名
            $sign=$this->createSign($result);
            if($sign !=$resSign){ //判断接口传回的签名的是否和该签名一直、保证数据传输时不被篡改
               return json_encode(array("msg"=>"sign 验签失败",'code'=>5));
            }else{
                return  $response;
            }
        }
        
      
    }
}

?>