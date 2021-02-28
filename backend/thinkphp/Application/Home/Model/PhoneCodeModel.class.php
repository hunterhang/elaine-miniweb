<?php
namespace Home\Model; 
use Think\Model;
class PhoneCodeModel extends Model {
	protected $tablePrefix = 'ecm_'; 
	protected $_table = "phone_code";
	private $aes_key = "asdf123asd[po!23%";
	private $_sms_appid = "1400078543";
	private $_sms_appkey = "b1dfb0624e8fae7612b75d70c5dcd0ec";
	private $_sms_url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms";

	public function insert($openid,$phone,$code){
		$model = D($this->_table);
		$data = array(
			"openid"=>$openid,
			"phone"=>$phone,
			"code"=>$code,
			"ttl"=>300	
		);
		$result = $model->add($data,array(),true);
		if($result > 0)
		{
			return 0;
		}
		return -1;
	}

	public function check_code($openid,$phone,$code)
	{
		$db = D($this->_table);
		$date = date("Y-m-d H:i:s",time() - 300);
		$rows = $db->where(array("openid"=>$openid,"phone"=>$phone,"code"=>$code,"created"=>array("egt",$date)))->select();	
		if(count($rows) == 0)
		{
			return false;
		}
		$model = new \Home\Model\UserModel();
		$model->update_user_phone($openid,$phone);
		return true;
	}	

	//https://cloud.tencent.com/document/product/382/5808
	public function send_sms($phone,$content,$type)
	{
		$rand = rand();
		$url = $this->_sms_url."?sdkappid=".$this->_sms_appid."&random=".$rand;
		
		$time = time();
		$sig = hash("sha256", "appkey=".$this->_sms_appkey."&random=".$rand ."&time=".$time."&mobile=".$phone);
		$data = array(
			"tel"=>array(
				"nationcode"=>"86",
				"mobile"=>$phone
			),
			"type"=>0,
			"msg"=>$content,
			"sig"=>$sig,
			"time"=>$time,
			"extend"=>"",
			"ext"=>""	
		);		
		return $this->sendCurlPost($url,$data);
	}

	/**
     * 发送请求
     *
     * @param string $url      请求地址
     * @param array  $dataObj  请求内容
     * @return string 应答json字符串
     */
    public function sendCurlPost($url, $dataObj)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($curl);
        if (false == $ret) {
            // curl_exec failed
            $result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
        } else {
            $rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp
                        . " " . curl_error($curl) ."\"}";
            } else {
                $result = $ret;
            }
        }
        curl_close($curl);
        return $result;
    }


}
