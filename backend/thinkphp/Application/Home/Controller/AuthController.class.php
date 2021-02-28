<?php
namespace Home\Controller;
use Think\Controller;
class AuthController extends Controller {
	public function index(){
			echo "auth,hunter";
	}
	public function login_by_code()
	{
		//header('Location: http://www.welaine.com/'+_GET('to'));
		$code = _GET("code");
        $ret = 0;
        $msg = "success";
        $auth_obj = new \Org\Util\Auth();
        //$model = new \Home\Model\AuthModel();
        $ret = 0;
        $openid = "";
        $access_token = "";
        $nickname = "";
        $addr = "";
        $head_img = "";
        $refresh_token = "";
		$is_first_login = false;
        do{
            if($code == false)
            {
				redirect('http://www.welaine.com/#'._GET('to'), 1, '页面跳转中...');
				//header("Location: http://www.welaine.com/login2.html");
				return 0;
            }    

            $config  = $auth_obj->getWxConfig();
            $auth_info   = $auth_obj->get_token($code, $config['appid'], $config['appsecret']);
            if ($auth_info['access_token'] == ''){
                $ret = -2;
                $msg = "wx rsp empty access_token";
                break;
            }
            $access_token = $auth_info['access_token'];
            if ($auth_info['openid'] == '') {
                $ret = -3;
                $msg = "wx rsp empty openid";
                break;
            }
            $openid = $auth_info['openid'];
            $user_info = $auth_obj->get_wx_user_info($access_token, $openid);
            $nickname = $user_info['nickname'];
            $head_img = $user_info['headimgurl'];
            $user_model =  new \Home\Model\UserModel();
			//check is first login
			$user_db = D($user_model->_table);
			$row = $user_db->where(array("openid"=>$openid))->select();
			if(empty($row))
			{
				$is_first_login = true;
			}
			if($is_first_login)
			{
				$discount_model = new \Home\Model\DiscountModel();
				$dis_db = D($discount_model->_table);
				$dis_db->data(array("openid"=>$openid,"amount"=>5000))->add();			

			}
            //注册用户，或者更新refresh_token
            $addr = $user_info['country']."-".$user_info['province']."-".$user_info['city'];
            $refresh_token = $auth_info['refresh_token'];
            $ret = $user_model->set_user_info($openid,$access_token,$refresh_token,$nickname,$head_img,$addr);
            if($ret != 0)
            {
                $ret = -4;
                $msg = "update refresh_token failed!";
                break;
            }
        }while(0);
		//"openid=test_openid; nickname=Guest; user_head=http://i1.umei.cc/uploads/tu/201611/174/29.jpg; v=v1.1; PHPSESSID=u3u10m9rhdlqtcn1ui97a1jdf3";
		setcookie("openid",$openid,time()+3600*24*30,"/");
		setcookie("access_token",$access_token,time()+3600*24*30,"/");
		setcookie("nickname",$nickname,time()+3600*24*30,"/");
		setcookie("user_head",$head_img,time()+3600*24*30,"/");
		setcookie("is_first_login",$is_first_login,time()+3600*24*30,"/");
		setcookie("v","v1.1",time()+3600*24*30,"/");
		//echo "<script type=\"text/javascript\">window.location.href='http://www.welaine.com/'</script>";
		//return ;
		redirect('http://www.welaine.com/#'._GET('to'), 1, '页面跳转中...');
		//header('Location: http://www.welaine.com/'+_GET('to'),True,301);
		return 0;
        $arr = array('ret'=>$ret,'msg'=>$msg,'openid'=>$openid,'access_token'=>$access_token,'nickname'=>$nickname,'user_head'=>$head_img,'is_first_login'=>$is_first_login);
		print_r($_GET);
        echo json_encode($arr);
        return 0;

	}
	public function check_user_login()
	{
		$openid = _GET("openid");
        $access_token = _GET("access_token");
        $ret = 0;
        $msg = "success";
        do{
            if($openid == false || $access_token == false)
            {
                $ret = -1;
                $msg = "input param error";
                break;
            }
            $user_model =  new \Home\Model\UserModel();
            $user_data = $user_model->get_user_info($openid);
            if($user_data == false)
            {
                $ret = -2;
                $msg = "invlid openid";
                break;
            }

            if($user_data["access_token"] != $access_token)
            {
                $ret = -3;
                $msg = "access_token invalid";
                break;
            }
            $auth_obj = new \Org\Util\Auth();
            if (time() - $user_data["refresh_token_created"] > $auth_obj->getRefreshTokenValidTime()) {
                //refresh_token过期,重新登录
                $ret = -4;
                $msg = "refresh_token expired";
                break;
            }
            $refresh_token = $user_data["refresh_token"];
            //1.9小时刷新一次
            if (time() - $user_data["token_created"] > $auth_obj->getTokenValidTime()) {
                $wx_config = $auth_obj->getWxConfig();
                $token_info = $auth_obj->refresh_token($wx_config["appid"], $refresh_token);
                if($token_info == false)
                {
                    $ret = -4;
                    $msg = "refresh_token fail";
                    break;
                }
                $user_model->update_token($openid,$token_info["access_token"],$token_info['refresh_token']);
            }
            
        }while(0);
        $data = array('ret'=>$ret,'msg'=>$msg,'access_token'=>$access_token,'openid'=>$openid);
		echo json_encode($data);
        return 0;
	}
	
	public function check_auth_code()
	{
        $code = _GET("code");
        $ret = 0;
        $msg = "success";
        $auth_obj = new \Org\Util\Auth();
        //$model = new \Home\Model\AuthModel();
        $ret = 0;
        $openid = "";
        $access_token = "";
        $nickname = "";
        $addr = "";
        $head_img = "";
        $refresh_token = "";
		$is_first_login = false;
        do{
            if($code == false)
            {
                $ret = -1;
                $msg = "code is empty!";
                break;
            }    
            $config  = $auth_obj->getWxConfig();
            $auth_info   = $auth_obj->get_token($code, $config['appid'], $config['appsecret']);
            if ($auth_info['access_token'] == ''){
                $ret = -2;
                $msg = "wx rsp empty access_token";
                break;
            }
            $access_token = $auth_info['access_token'];
            if ($auth_info['openid'] == '') {
                $ret = -3;
                $msg = "wx rsp empty openid";
                break;
            }
            $openid = $auth_info['openid'];
            $user_info = $auth_obj->get_wx_user_info($access_token, $openid);
            $nickname = $user_info['nickname'];
            $head_img = $user_info['headimgurl'];
            $user_model =  new \Home\Model\UserModel();
			//check is first login
			$user_db = D($user_model->_table);
			$row = $user_db->where(array("openid"=>$openid))->select();
			if(empty($row))
			{
				$is_first_login = true;
			}
			if($is_first_login)
			{
				$discount_model = new \Home\Model\DiscountModel();
				$dis_db = D($discount_model->_table);
				$dis_db->data(array("openid"=>$openid,"amount"=>5000))->add();			

			}
            //注册用户，或者更新refresh_token
            $addr = $user_info['country']."-".$user_info['province']."-".$user_info['city'];
            $refresh_token = $auth_info['refresh_token'];
            $ret = $user_model->set_user_info($openid,$access_token,$refresh_token,$nickname,$head_img,$addr);
            if($ret != 0)
            {
                $ret = -4;
                $msg = "update refresh_token failed!";
                break;
            }
        }while(0);
        $arr = array('ret'=>$ret,'msg'=>$msg,'openid'=>$openid,'access_token'=>$access_token,'nickname'=>$nickname,'user_head'=>$head_img,'is_first_login'=>$is_first_login);
        echo json_encode($arr);
        return 0;
	}
	public function get_phone_code()
	{
		$phone = _GET("phone"); 
		$openid = _GET("openid"); 
		$ret = 0;
		$msg = "success";
		do{
			if($phone == false || $openid == false)
			{
				$ret = -1;
				$msg = "param empty!";
				break;
			}
			$rand = rand()%10000;
			$code = sprintf("%04d", $rand);
			$sms_content = sprintf("%04d为您的登录验证码，请于5分钟内填写。如非本人操作，请忽略本短信。",$code);
			$model = new \Home\Model\PhoneCodeModel();
			$sms_result = $model->send_sms($phone,$sms_content,1);
			$res = json_decode($sms_result,true);
			if($res["result"] != 0)
			{
				$ret = -2;
				$msg = "系统发送短信异常";
				break;
			}
			$result = $model->insert($openid,$phone,$code);
			if($result != 0)
			{
				$ret = -3;
				$msg = "insert phone code fail!please retry later";
				break;
			}
		}while(0);
		echo json_encode(array("ret"=>$ret,"msg"=>$msg));
		return ;
	}

	public function check_phone_code()
	{
		$phone =_GET("phone"); 
		$openid = _GET("openid"); 
		$code = _GET("code"); 
		$ret = 0;
		$msg = "success";
		do{
			if($phone == false || $openid == false || $code == false)
			{
				$ret = -1;
				$msg = "手机号码或者验证码不能为空!";
				break;
			}
			$model = new \Home\Model\PhoneCodeModel();
			$result = $model->check_code($openid,$phone,$code);
			if($result == false)
			{
				$ret = -2;
				$msg = "验证码错误，请重试！";
				break;
			}
			//下载优惠卷
			$discount_model = new \Home\Model\DiscountModel();
			$result = $discount_model->addDiscountOfPhoneCheck($openid);
			if($result == 0)
			{
				$msg = "您已经领取了优惠卷";
				break;
			}
			if($result == 1)
			{
				$msg = "价值50元的优惠卷已经发到您的帐户，购买商品时将自动扣款！";
				break;
			}
			if($result < 0)
			{
				$ret = -1;
				$msg = "未知错误，请稍后再试！";
				break;
			}
		}while(0);	
		echo json_encode(array("ret"=>$ret,"msg"=>$msg));
		return ;
	}
}
