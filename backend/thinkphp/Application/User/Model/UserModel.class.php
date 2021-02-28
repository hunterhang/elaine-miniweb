<?php
namespace User\Model; 
use Think\Model;
class UserModel extends Model {
	protected $tablePrefix = 'ecm_'; 
	public $_table = "user";
	private $aes_key = "asdf123asd[po!23%";
	protected $_validate = array(
		 array('username','4,30','用户名长度不正确,必须在4-30个字节',0,'length'),
		 array('pwd','6,64','密码长度不正确,必须在6-30个字节',0,'length'),
		 array('email','email','Email格式错误！',2),	 
		 array('phone','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码错误！','0','regex',1)
	);
	private $_channel = array(
		"gQG08DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyU19hWEVjUGRlcjQxMDAwMDAwN2wAAgQ0BB1aAwQAAAAA"=>"season",
		"gQHs8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyYXhsZEZqUGRlcjQxMDAwME0wN2wAAgSZBB1aAwQAAAAA"=>"haibao_ditui",
		"gQHbS9xLzAyMmJURUVLUGRlcjQxMDAwMGcwN1MAAgQxBR1aAwQAAAAA"=>"weixintui"
	);
	function get_channel_name($key)
	{
		return isset($this->_channel[$key])?$this->_channel[$key]:"unknown";
	}
	function encode($string = '') {
		$strArr = str_split(base64_encode($string));
		$strCount = count($strArr);
		foreach (str_split($this->aes_key) as $key => $value)
			$key < $strCount && $strArr[$key].=$value;
		return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
	}

	function decode($string = '') {
		$strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
		$strCount = count($strArr);
		foreach (str_split($this->aes_key) as $key => $value)
			$key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
		return base64_decode(join('', $strArr));
	}
	function update_user_phone($openid,$phone)
	{
		$db = D($this->_table);
		$result = $db->where(array("openid"=>$openid))->save(array("phone"=>$phone));
		if($result >0)
		{
			return 0;
		}
		return -1;
	}
}
