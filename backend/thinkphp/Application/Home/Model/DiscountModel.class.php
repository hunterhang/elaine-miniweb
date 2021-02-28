<?php
namespace Home\Model; 
use Think\Model;
class DiscountModel extends Model {
    protected $tablePrefix = 'ecm_'; 
    public $_table = "discount";
	public $_amount_for_phone_check = 5000;
	public function addDiscountOfPhoneCheck($openid)
	{
		$db = D($this->_table);
		$rows = $db->where(array("openid"=>$openid,"type"=>"phone_check"))->select();		
		if(count($rows) == 1)
		{
			return 0;
		}
		$data = array("openid"=>$openid,"amount"=>$this->_amount_for_phone_check,"type"=>"phone_check");
		$result  = $db->data($data)->add();
		if($result > 0)
		{
			return 1;
		}
		return -1;
	}	
}
