<?php
namespace Home\Model; 
use Think\Model;
class UserDesignModel extends Model {
    protected $tablePrefix = 'ecm_'; 
	public $_table = "user_design";
	public function get_product_info($product_id)
	{
		$db = D($this->_table);
		$data = $db->where(array("id"=>$product_id))->select();
		if(empty($data))
		{
			return false;
		}
		return $data[0];
	}	
}
