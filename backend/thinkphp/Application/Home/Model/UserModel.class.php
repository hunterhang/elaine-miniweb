<?php
namespace Home\Model; 
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = 'ecm_'; 
    public $_table = "user";
    public function set_user_info($openid,$access_token,$refresh_token,$nickname,$headimgurl,$addr)
    {
        $model = D($this->_table);
        $rows = $model->where(array('openid'=>$openid))->select();
        $data = array(
            "openid" => $openid, 
            "access_token" => $access_token, 
            "refresh_token" => $refresh_token, 
            "access_token_created" => time(), 
            "refresh_token_created" => time(), 
            "wxname" => $nickname,
            "icon"=>$headimgurl,
            "addr"=>$addr,
            "login_times"=>isset($rows[0]["login_times"])?$rows[0]["login_times"]+1:1
        );
		$mm = D($this->_table);
		if(empty($rows))
		{
			$num = $mm->data($data)->add();
			if($num == 1)
			{
				return 0;
			}else{
				return -1;
			}
		}else{
			$num = $mm->where(array('openid'=>$openid))->save($data);
			
		}
		return 0; 
    }

    public function update_token($openid,$access_token,$refresh_token)
    {
        $model = D($this->_table);
        $rows = $model->where(array('openid'=>$openid))->select();
        $data = array(
            "openid" => $openid, 
            "access_token" => $access_token, 
            "access_token_created" => time(), 
            "refresh_token"=>$refresh_token,
            "refresh_token_created"=>time(),
            "login_times"=>isset($rows[0]["login_times"])?$rows[0]["login_times"]+1:1
        );
        $num = $model->where(array('openid'=>$openid))->save($data);
        if($num == 1)
        {
            return true;
        }
        return false;
    }

    public function get_user_info($openid)
    {
        $model = D($this->_table);
        $rows = $model->where(array('openid'=>$openid))->select();
        if(count($rows) != 1)
        {
            return false;
        }
        return $rows[0];
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
