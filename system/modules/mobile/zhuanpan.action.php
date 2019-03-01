<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my');
System::load_app_fun('user');
System::load_sys_fun('user');
System::load_sys_fun("send");
System::load_sys_fun('global');

class zhuanpan extends base {
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");
	}

	public function init(){
      
      	$member=$this->userinfo;
      
      	$uid = $member['uid'];
      	if(!$member){
        	$uid = 9999999;
        }
		//分享部分代码
        require_once("system/modules/mobile/jssdk.php");

        $wechat = $this->db->GetOne("select * from `@#_wechat_config` where id = 1");

        $jssdk = new JSSDK($wechat['appid'], $wechat['appsecret']);

        $signPackage = $jssdk->GetSignPackage();

		include templates("mobile/zhuanpan","index");
	}
  	//我的奖品
	public function getPersonprize(){
    	include templates("mobile/zhuanpan","prize");
    }
  	//获取商品
  	public function luckyDraw(){
      	echo '{"1":{"name":"88\u798f\u5206","score":"88","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1537243186.png@!thumb_50_50","item":"1"},"2":{"name":"\u6e05\u98ce\u62bd\u7eb84\u5305","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1535611771.png@!thumb_50_50","item":"2"},"3":{"name":"\u8d22\u5bcc\u91d1\u67612g","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1535611336.png@!thumb_50_50","item":"3"},"4":{"name":"\u91d1\u9f99\u9c7c\u6cb9","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1535611809.png@!thumb_50_50","item":"4"},"5":{"name":"888\u798f\u5206","score":"888","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1537242958.png@!thumb_50_50","item":"5"},"6":{"name":"\u82f9\u679cXS Max","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1537241062.png@!thumb_50_50","item":"6"},"7":{"name":"\u5c0f\u7c73\u6392\u63d2","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1535611799.png@!thumb_50_50","item":"7"},"8":{"name":"\u7d22\u5c3c\u7535\u89c6","score":"0","imgUrl":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1535784536.png@!thumb_50_50","item":"8"}}';
    }
  	//会员信息
  	public function getMemberinfo(){
    	echo '{"day_lucky":"1","lucky_mun":2,"success":1}';
    }
  	//点击转盘抽奖
  	public function luckyDrawClick(){
    	echo '{"code":100,"item":{"item":"3","scale":"15000","gid":"26","img":"http:\/\/dyyyyg.oss-cn-shenzhen.aliyuncs.com\/lucky\/1537243186.png@!thumb_300_300","name":"88\u798f\u5206","score":"888","type":"2","num":"883464","recharge_money":"0","invite_num":"0","card":null},"score":"88","type":"2"}';
    }
  	//规则
  	public function getRule(){
      	header("Content-Type: text/html;charset=utf-8");
    	$rule['text'] = '<p><strong><span style="font-size:16px;font-family:宋体">​活动规则：</span></strong></p><p><br></p><p>
        <span style="font-size:16px;font-family:宋体"></span></p><p><span style="font-size:16px;font-family:宋体"></span></p><p>
        <span style="font-size: 14px;">一、活动期间，每位会员每天可免费获得2次参与转盘机会，将您的专属二维码分享到朋友圈后，有2人以上扫码或识别二维码进入活动页面，您即可再获得一次参与转盘机会。
        <span style="font-size: 14px; font-family: 宋体;">（本次活动只面向在惠享购上当月有充值记录的用户）</span></span></p><p><br><span style="font-size: 14px;"></span></p><p>
        <span style="font-size: 14px;">二、获得实物者，请将您的奖品专属二维码分享到朋友圈，通过您的二维码识别或扫码进入活动页面的人数达到要求，您即可成功领取此奖品。
        <span style="font-size: 14px; color: rgb(255, 0, 0);"><strong>（在活动时间内分享二维码并达到所要求的扫码人数，才可成功领取，活动结束后的分享无效。）</strong></span></span></p><p><br></p><p>
        <span style="font-size: 14px;">三、获得实物奖品一律免费包邮，请及时在获得商品相应处填写地址，以便收货！获得福分，将自动充入您的惠享购账号中。奖品数量有限，送完为止！（截至2018年10月8日未完善地址的，视为自动放弃中奖资格）</span></p><p><br></p><p>
        <span style="font-size: 14px;">四、本次活动奖品：</span></p><p>
        <span style="font-size: 14px;">1、苹果XS Max 256</span></p><p>
        <span style="font-size: 14px;">2、索尼电视KD-55X8566F 55英寸</span></p><p>
        <span style="font-size: 14px;">3、平安银行 财富投资金条2g</span></p><p>
        <span style="font-size: 14px;">4、小米排插</span></p><p>
        <span style="font-size: 14px;">5、金龙鱼 食用油</span></p><p>
        <span style="font-size: 14px;">6、清风抽纸纸巾4包</span></p><p>
        <span style="font-size: 14px;">7、惠享购888福分</span></p><p>
        <span style="font-size: 14px;">8、惠享购88福分</span></p><p><br></p><p>
        <span style="font-size: 14px;">五、通过恶意注册或其他不正当手段等参与本活动的，将取消其参与及领奖资格，且有权收回其通过本活动获得的奖品，并保留追究恶意刷奖者的法律责任的权利！</span></p><p><br></p><p>
        <span style="font-size: 14px;">六、惠享购对本次活动拥有最终解释权。</span></p>';
      	echo json_encode($rule);
    }
}