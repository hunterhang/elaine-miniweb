<?php
namespace User\Controller;
use Think\Controller;
define("TOKEN", "check_token_by_hunter");//定义你公众号自己设置的token
define("APPID", "wx66ff2fc56ab60b17");//填写你微信公众号的appid 千万要一致啊
define("APPSECRET", "460a7d2e37d9e77c881a41c411bfd967");//填写你微信公众号的appsecret  千万要记得保存 以后要看的话就只有还原了  保存起来 有益无害
class IndexController extends Controller {
	public function register()
	{
		$res = array();
		$db = new \User\Model\UserModel();
		$db = D("User");
		$ret = 0;
		$msg = "success";
		do{
			$rows = $db->where(array('username'=>$_POST['username']))->select();
			if(count($rows) >0)
			{
				$ret = -1;
				$msg = "用户名已存在!";
				break;
			}
			if(!$db->create($_POST))
			{
				$ret = -2;
				$msg = $db->getError();
				break;
			}
			$db->pwd = md5($_POST['pwd']);
			$last_insert_id = $db->add();
			if($last_insert_id <= 0)
			{
				$ret = -3;
				$msg = "注册失败，请重试！";
				break;	
			}
		}while(0);
		$result = array('ret'=>$ret,'msg'=>$msg);
		echo json_encode($result);
		return 0;
	}
	public function login()
	{
		$pwd = md5($_POST['pwd']);
		$db = D("User");
		$rows = $db->where(array('username'=>$_POST['username'],'pwd'=>$pwd))->select();
		$ret = 0;
		$msg = "success";
		$user_id = -1;
		$token = "";
		do{
			if(count($rows) <= 0)
			{
				$ret = -1;
				$msg = "密码错误或者用户名不存在!";
				break;
			}
			$user_id = $rows['0']['id'];
			$src_token = $user_id."|".$pwd."|".(time()+3600);
			$token = $db->encode($src_token);
		}while(0);
		$result = array('ret'=>$ret,'msg'=>$msg,'user_id'=>$user_id,'token'=>$token);
		echo json_encode($result);
		return 0;
	}
	public function get_phone_code()
	{
		$phone = isset($_POST["phone"]) && $_POST["phone"] != "" ?$_POST["phone"]:false;
		$openid = isset($_POST["openid"]) && $_POST["openid"] != "" ?$_POST["openid"]:false;
		$ret = 0;
		$msg = "success";
		do{
			if($phone == false || $openid == false)
			{
				$ret = -1;
				$msg = "param empty!";
				break;
			}
			$code = 1111;
			$model = new \Home\Model\PhoneCodeModel();
			$result = $model->insert($openid,$phone,$code);
			if($result != 0)
			{
				$ret = -2;
				$msg = "insert phone code fail!please retry later";
				break;
			}
		}while(0);
		json_encode(array("ret"=>$ret,"msg"=>$msg));
		return ;
	}

	public function check_phone_code()
	{
		$phone = isset($_POST["phone"]) && $_POST["phone"] != "" ?$_POST["phone"]:false;
		$openid = isset($_POST["openid"]) && $_POST["openid"] != "" ?$_POST["openid"]:false;
	
		$code = isset($_POST["code"]) && $_POST["code"] != "" ?$_POST["code"]:false;
		$ret = 0;
		$msg = "success";
		do{
			if($phone == false || $openid == false || $code == false)
			{
				$ret = -1;
				$msg = "param empty!";
				break;
			}
			$model = new \Home\Model\PhoneCodeModel();
			$result = $model->check_code($openid,$phone,$code);
			if($result == false)
			{
				$ret = -2;
				$msg = "code not correct!please retry ";
				break;
			}
		
		}while(0);	
		json_encode(array("ret"=>$ret,"msg"=>$msg));
		return ;
	}
	public function sys_notice()
	{
		$xml = _POST_BODY();
		$data = json_decode(json_encode(simplexml_load_string($xml,null,LIBXML_NOCDATA)),true);
		$ret = 0;
		$msg = "success";
		$msg_type = $data["MsgType"];
		if($msg_type == "event")
		{
			$event_type = $data["Event"];
			$openid = $data["FromUserName"];
			$create_time = $data["CreateTime"];
			file_put_contents('/tmp/xx.log','[event_type '.$event_type.']',FILE_APPEND);
			$model = new \User\Model\UserModel();
			$db = D($model->_table);
			if($event_type == "SCAN")
			{
				//header("Content-Type: text/xml; charset=utf-8");
				$from_user_name = $data["FromUserName"];
				$to_user_name = $data["ToUserName"];
				//file_put_contents('/tmp/xx.log',serialize($GLOBALS)."\n",FILE_APPEND);
				echo $this->rsp_text($from_user_name,$to_user_name);
				return 0;
			}
			if($event_type == "unsubscribe")
			{
				$cancel_time = date("Y-m-d H:i:s",$create_time);
				$res = $db->where(array("openid"=>$openid))->save(array("cancel_time"=>$cancel_time));
				send_mail("27624764@qq.com","tips","$openid unsubscribe your wechat service","unsubscribe,openid:$openid");
				send_mail("113172721@qq.com","tips","$openid unsubscribe your wechat service","unsubscribe,openid:$openid");
				echo "";
				return 0;
			}		
			if($event_type == "subscribe")
			{
				$this->send_phone_url($openid);
				$channel = $data["EventKey"];
				$row =array("cancel_time"=>"0000-00-00 00:00:00","channel"=>$channel);
				$db->where(array("openid"=>$openid))->save($row);
				$from_user_name = $data["FromUserName"];
				$to_user_name = $data["ToUserName"];
				echo $this->rsp_text($from_user_name,$to_user_name);
				send_mail("27624764@qq.com","tips","$openid subscribe your wechat service","subscribe,openid:$openid");
				send_mail("113172721@qq.com","tips","$openid subscribe your wechat service","unsubscribe,openid:$openid");
				return 0 ;
			}
		}
		//不告警	
		if($msg_type == "text")
		{
			$from_user_name = $data["FromUserName"];
			$to_user_name = $data["ToUserName"];
			//echo "We have received your message!";
			echo $this->rsp_text($from_user_name,$to_user_name);
			return 0;
		}

	}
	function test()
	{
	
		$openid = "11-11";
		echo shell_exec("sh /data/www/welaine_thinkphp/Application/User/Controller/send.sh \"$openid\"");	
	}
	function send_phone_url($openid)
	{
		//shell_exec("sh /data/www/welaine_thinkphp/Application/User/Controller/send.sh $openid");	

		shell_exec("/data/www/welaine_thinkphp/Application/User/Controller/send.sh \"$openid\"");	
	}
	function rsp_text($from_user_name,$to_user_name)
	{
		$created = time();
		/**
		$title = "[Preview秀场早知道]Theo VII Studio·何苗、丁瑜:马赛诗人";
		$desc = "追求一种理性的美学，并尝试在版型结构的剪裁、工艺和有趣的设计灵感主题中进行探索。";
		$img = "https://mmbiz.qlogo.cn/mmbiz_jpg/T0gicMJ8B9o5ibWDviaQ5wqrWJrMBTGiaJvIGgttVOAvp7FMVlvNfDSXD0sgJ69j2feWxY7TCP9j0N7vBRbHw6YVlA/0?wx_fmt=jpeg";
		$url = "https://mp.weixin.qq.com/s?__biz=MzI5ODUyODY5MQ==&tempkey=OTM0X0FZbDZ3RnVRbkRkOVFCVHZLTDBqc0VvRzQ5N2NjQjU3WGcwTmZYWnVraDBGeEpabzhmeEVLMmMwZ3FoRWU0N0x5dmZZZVRfUXk2Z1B2d01mWnlYQ1JuYUVrT0szMDlXZE4taWZPdUcwalpTRHlhdFctY0NGYWtNZm45R1M5UV8ySWt4NS1Hc2FOUDVVb01iSW96UkZFY2JKOVFMX1EteURjQjhmbXd%2Bfg%3D%3D&chksm=6ca53b815bd2b297e766cb0b2616b7e36fa555b9e7b96ed6c6fb4a9b5cc56593e1c8c84cdbc3#rd";
		**/
		/**
		$title = "MIAO HO：我在伦敦天气晴";
		$desc = "她曾为国家领导人设计衣服。";
		$img = "https://mmbiz.qlogo.cn/mmbiz_jpg/T0gicMJ8B9o6KT94WPZa4UBq4wrR3PNDf9PZMJFjGxRLgKFy5ibrNK8Kic3zBIPCVuicvJdkJSRguSkRzH3AmpVVKA/0?wx_fmt=jpeg";
		//$url = "https://mp.weixin.qq.com/s?__biz=MzI5ODUyODY5MQ==&tempkey=OTM0X2YzVG4wYjB4NFRsejFnM2ZLTDBqc0VvRzQ5N2NjQjU3WGcwTmZYWnVraDBGeEpabzhmeEVLMmMwZ3FnVGl5WXJMWUVoMTI5d3lnc1ZWT0p4T2lUTEM2Y1NLNDJtWXBxejZCUzVlUUstV2swYWhCMnF5YW9jeDlza0R0alUwNno5eHA2TTBSVFEtbzhBRjVDaS1zd2JyQVJKaFNJd0RKam11NUthSXd%2Bfg%3D%3D&chksm=6ca53bbd5bd2b2ab515d9449fc38eec662d791b54fe3e597757af810db3b60c607ac76af3ce5#rd";
		$url = "http://mp.weixin.qq.com/s/RVnKprSNFtkcLtb8XaVuCw";
		**/
		/**
		$title = "Elainewang品牌故事「我想我们都热爱生活」";
		$desc = "愿与你一见如故。";
		$img = "https://mmbiz.qlogo.cn/mmbiz_jpg/T0gicMJ8B9o5JfKuBOoa0j01EcB0dopWNFT1HOnWAw07siaGrysI1de80nHUbqwmlSf2A1zv4JOPMlGoKJLrQJLg/0?wx_fmt=jpeg";
		$url = "http://mp.weixin.qq.com/s/Ksz2i0KzUcAX4Tkb5navKA";
		**/
		$title = "品牌故事 | elainewang品牌焕新「你好，四月」";
		$desc = "愿给你带来更多惊喜。";
		$img = "https://mmbiz.qlogo.cn/mmbiz_jpg/T0gicMJ8B9o4iaYibZO5cUnXh1YvDRFMcRKFQs4lDYTibQhhU5f4WZWdu6aMgEhe5faJ4iaUMLeEIUHV3XwDLibOI4Tg/0?wx_fmt=jpeg";
		$url = "https://mp.weixin.qq.com/s/bVceTEg37T0Mkr5MCPTuWw";
		$rsp =
"<xml>
<ToUserName><![CDATA[$from_user_name]]></ToUserName>
<FromUserName><![CDATA[$to_user_name]]></FromUserName>
<CreateTime>$created</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>1</ArticleCount>
<Articles>
<item>
<Title><![CDATA[".$title."]]></Title> 
<Description><![CDATA[".$desc."]]></Description>
<PicUrl><![CDATA[$img]]></PicUrl>
<Url><![CDATA[$url]]></Url>
</item>
</Articles>
</xml>";
return $rsp;
	}
}
