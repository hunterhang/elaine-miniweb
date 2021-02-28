<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
	public function index(){
			echo "admin,hunter";
	}
	public function day_share()
	{
		$img_url = isset($_GET['img_url'])?$_GET['img_url']:false;
		$cmd="sh /data/www/welaine_thinkphp/tool/publish_img.sh ".$img_url." 2>&1"; 
		echo $cmd;
		$result = shell_exec($cmd);
		var_dump($result);
		return 0;
		$date = isset($_GET['date'])?$_GET['date']:false;
		$is_online = isset($_GET['online'])?$_GET['online']:false;
		$title = isset($_GET['title'])?$_GET['title']:false;
		if($title == false || $date == false || $is_online == false || $img_url == false)
		{
			echo "param error,date,online,img_url,title can not be empty!";
			return -1;
		}
		$m = new \home\Model\ImgShowModel();
		$db = D($m->_table);
		$data =array("is_online"=>$online,"img"=>$img_url,"title"=>$title,"start_time"=>$date); 	
		$result = $db->save($data);
		if($result == true)
		{
			echo "add success!";
			return 0;
		}else{
			echo "publish failed!";
			return -2;
		}
	}
	
}
