<?php

class PP{
    private $key;
    private $code;
    private $acode;
    private $mchid;
    private $redirect_uri;
    private $openid;
  	private $uri;
    public function __construct($acode = '')
    {
        $this->key = '604479E7058A43949E6DF479B541E29D';
        $this->code = '3B08F51FD6A24A548C0EC8ECBD448977';
      
      	$array = array(
          'g05ABI7W8Q',
          'dMQVJsQ2Mw',
          'VRM1PU5kP4',
          'e67QqUP2vA',
          'wNrDVhl5DA',
          '8K5YdHjA9Q',
          '2k1YxI5RaQ',
          'plDkYt1Z6w',
          'Ym6qPSlWP0',
          '8K5YdHjAkV',
          'QxaYmfZ54J',
          '0ngZ6hgqlW',
          'aQeBPcJQ0r',
          'KxjYJfKdJJ',
          '5x7Ypf2DAQ',
          'lk54LIl8D3',
          'RvAYwTZxmL',
          'zBxv4h86mG',
          '4jKYBfv3KP',
          'jn4WBhKZd6',
          'g05ABI7WQ4',
          'QxaYmfZ5D2',
          'MxBYRf3V6r',
          'xb4ZQT5PKe',
          '8K5YdHjA3w',
          '0ngZ6hgqJg',
          'KxjYJfKd36',
          'lk54LIl8P1',
          'RvAYwTZxd4',
          'maAe3cNqG3',
          'KxjYJfKdRn',
          'lk54LIl864',
          'GxOYAfg3Vj',
          'PxLYmfrK96',
          '4jKYBfv3bx',
          'Z1PRKsgPgV',
          'Ox9YrfGWGB',
          'BxN5AfdKd0',
          'WOVl6TxZxe',
          'krb04tAR6Z'
        );
      	$n = mt_rand(0,count($array)-1);
      
        $this->mchid = $array[$n];
        $this->acode = $acode;
        $this->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'].'/index.php/mobile/wechat111/gopay';
      	$this->uri = 'https://openapi.qfpay.com';
    }

    private function get_sign($data,$key){
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .='&'.$k . "=" . $v;
        }
        $str= $str1. $key;

        $sign =strtoupper( md5(ltrim($str, '&')));
        return [$sign,ltrim($str1, '&')];
    }


    /**
     * 发起公众号支付
     * @param $txamt '支付金额'
     * @param $mchid '商户号'
     * @param $goods_name '商品名称或标示'
     * @param $sub_openid '微信的openid'
     * @param $txdtm '订单支付金额，单位分'
     * @param $out_trade_no '订单号'
     * @param string $pay_type '支付类型 微信公众号支付:800207'
     * @param string $txcurrcd '币种 港币：HKD ；人民币：CNY'
     * @return bool
     */
    public function payment($txamt, $mchid, $goods_name, $sub_openid, $txdtm, $out_trade_no, $pay_type='800207', $txcurrcd='CNY'){
        $data = [
            'txamt'=>$txamt,
            'mchid'=>$mchid,
            'goods_name'=>$goods_name,
            'sub_openid'=>$sub_openid,
            'txdtm'=>$txdtm,
            'out_trade_no'=>$out_trade_no,
            'txcurrcd'=>$txcurrcd,
            'pay_type'=>$pay_type,
        ];
        list($sign,$json_data) = $this->get_sign($data,$this->key);
        $headers = [
            'X-QF-APPCODE'=>$this->code,
            'X-QF-SIGN' => $sign
        ];
        // $url = 'https://openapi.qfpay.com/trade/v1/payment';
        $url = $this->uri.'/trade/v1/payment';
        $headerArr = array();
        foreach( $headers as $n => $v ) {
            $headerArr[] = $n .':' . $v;
        }
        $result = json_decode( $this->curlRequest($url,$json_data,$headerArr),true);
        if ($result['respcd'] == 0000){
            $pay_params = $result['pay_params'];
            return $pay_params;
        }
        return false;
    }


    public function query($mchid,$out_trade_no)
    {
        $data = [
            'mchid'=>$mchid,
            'out_trade_no'=>$out_trade_no
        ];
        list($sign,$json_data) = $this->get_sign($data,$this->key);
        $headers = [
            'X-QF-APPCODE'=>$this->code,
            'X-QF-SIGN' => $sign
        ];
        $url = 'https://openapi.qfpay.com/trade/v1/query';
        $headerArr = array();
        foreach( $headers as $n => $v ) {
            $headerArr[] = $n .':' . $v;
        }
        $result = json_decode( $this->curlRequest($url,$json_data,$headerArr),true);
        return $result;

    }

    /**
     * Unicode字符转换成utf8字符
     * @param [type] $unicode_str Unicode字符
     * @return [type]       Utf-8字符
     */
    public function unicode_to_utf8($unicode_str) {
        $utf8_str = '';
        $code = intval(hexdec($unicode_str));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord_1 = decbin(0xe0 | ($code >> 12));
        $ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3 = decbin(0x80 | ($code & 0x3f));
        $utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
        return $utf8_str;
    }

    private function curlRequest($url, $params = array(), $header=array(), $time_out = 10, $is_post = true)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置是否返回response header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        //当需要通过curl_getinfo来获取发出请求的header信息时，该选项需要设置为true
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_POST, $is_post);

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }


        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $response = curl_exec($ch);
        //打印请求的header信息
        $request_header = curl_getinfo( $ch, CURLINFO_HEADER_OUT);
        curl_close($ch);

        return $response;
    }

    /******************************/
    /**********  接口  ************/
    /******************************/
    public function getCode()
    {
    	$data['code'] = $this->code;
    	$data['redirect_uri'] = $this->redirect_uri;
    	$data['mchid'] = $this->mchid;
        $sign = strtoupper(md5("app_code={$this->code}&mchid={$this->mchid}&redirect_uri={$this->redirect_uri}{$this->key}"));
    	$url = $this->uri."/tool/v1/get_weixin_oauth_code?app_code={$this->code}&redirect_uri={$this->redirect_uri}&mchid={$this->mchid}&sign={$sign}";
    	header('location:'.$url);
    }
	public function getCode2()
    {
      	$host = 'http://' . $_SERVER['HTTP_HOST'].'/index.php/mobile/wechat111/paygo';
      
    	$data['code'] = $this->code;
    	$data['redirect_uri'] = $host;
    	$data['mchid'] = $this->mchid;
        $sign = strtoupper(md5("app_code={$this->code}&mchid={$this->mchid}&redirect_uri={$host}{$this->key}"));
      
    	$url = $this->uri."/tool/v1/get_weixin_oauth_code?app_code={$this->code}&redirect_uri={$host}&mchid={$this->mchid}&sign={$sign}";
    	header('location:'.$url);
    }
    public function getOpenid()
    {
      	session_start();
    	$data['code'] = $this->acode;
    	$data['mchid'] = $this->mchid;
         $sign = strtoupper(md5("code={$this->acode}&mchid={$this->mchid}{$this->key}"));
        $headers = [
            'X-QF-APPCODE:'.$this->code,
            'X-QF-SIGN:'.$sign
        ];

    	$url = $this->uri."/tool/v1/get_weixin_openid?code={$this->acode}&mchid={$this->mchid}";
    	$res = $this->curlRequest($url, [], $headers, 10, false);
    	$res = json_decode($res, true);
    	if ($res['respcd'] == '0000') {
    		$_SESSION['wechat111_openid'] = $res['openid'];
    		return $this->payMoney($_SESSION['wechat111_money'] * 100,$_SESSION['wechat111_code']);
    	}
    }

    public function payMoney($money,$dingdancode)
    {
      	session_start();
        $data = [
            'txamt'=>$money,
            'mchid'=>$this->mchid,
            'goods_name'=>'微信支付',
            'sub_openid'=>$_SESSION['wechat111_openid'],
            'txdtm'=>date('Y-m-d H:i:s'),
            'out_trade_no'=>$dingdancode,
            'txcurrcd'=>'CNY',
            'pay_type'=>800207,
        ];
       
        ksort($data);
        
        $arr = array();
        foreach ($data as $k => $v) {
        	$arr[] = "{$k}={$v}";
        }

        $sign = strtoupper(md5(join('&', $arr).$this->key));
        $data['sign'] = $sign;
      
        $headers = [
            'X-QF-APPCODE:'.$this->code,
            'X-QF-SIGN:'.$sign
        ];

        $url = $this->uri.'/trade/v1/payment';
        $result = $this->curlRequest($url, $data, $headers);
      	return json_decode($result,true);
        var_dump($result);
        // if ($result['respcd'] == 0000){
        //     $pay_params = $result['pay_params'];
        //     return $pay_params;
        // }
        // return false;
    }
}

