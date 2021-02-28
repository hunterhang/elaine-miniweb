<?php
namespace Home\Controller;
use Think\Controller;
class CrontabController extends Controller {
	public function index(){
			echo "crontab,hunter";
	}
	public function updateOnlineProduct()
	{
		$start_date = $date." 00:00:00" ;
		$end_date = $date." 23:59:59";
		$sql1 = sprintf("update ecm_dress_list set is_online=1 where start_time >= \"%s\" and start_time <= \"%s\";",$start_date,$end_date);
		$sql2 = sprintf("update ecm_img_show set is_online=1 where start_time >= \"%s\" and start_time <= \"%s\";",$start_date,$end_date);	
		$m1 = new \Home\Model\DressListModel();
		$m2 = new \Home\Model\ImgShowModel();
		$db1 = D($m1->_table);
		$db2 = D($m2->_table);
		//$db1->where(array('start_time'))
	
	}

	
}
