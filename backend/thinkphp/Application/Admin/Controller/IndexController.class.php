<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
	private $_username = "admin";
	private $_password = "admin@12";
	public function upload_show_img()
	{
		if($_POST['username'] != $this->_username || $_POST['password'] != $this->_password)
		{
			echo "用户名 or 密码 错误！";
			return ;
		}
		$title = isset($_POST["title"])?$_POST["title"]:false;
		$date = isset($_POST["date"])?$_POST["date"]:false;
		//检查 是否重复
		$m = new \Home\Model\ImgShowModel();
		$db =D($m->_table);
		$row = $db->where(array("title"=>$title,"start_time"=>$date,"_logic"=>"OR"))->select();
		if(!empty($row))
		{
			echo "标题或者时间已经存在，请确认！";
			return ;
		}
		if($_FILES["imgfile"]["size"] == 0)
		{
			echo "请选择图片!";
			return ;
		}
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize=3145728;//设置上传文件最大，大小
        $upload->exts= array('jpg','gif','png','jpeg');//后缀
        $upload->rootPath ='./ui/img_show/';//上传目录
        $upload->autoSub     = false;
		//$upload->saveName = $_FILES['imgfile']['name'];
		$filename = $_FILES['imgfile']['name'];
		$arr = explode(".",$filename);
		$filename = $arr[0];
		$upload->saveName = $filename;
		$info= $upload->upload();//执行上传方法
        // 上传文件
        $ret = 0;
        $msg = "success";
		if(!$info) {// 上传错误提示错误信息
			$ret = -1;
			$msg = $upload->getError();
			echo "上传失败！可能是图片名称已经存在！</br>";
			return ;
		}
		$data = array(
			"title"=>$title,
			"start_time"=>$date,
			"is_online"=>$_POST["is_online"],
			"ui/img_show/".$_FILES["imgfile"]["name"]
		);
		$result = $db->add($data);
		var_dump($result);
		echo $msg."</br>";
		echo "文件名:"."http://www.welaine.com/ui/img_show/".$_FILES['imgfile']['name'];
		return 0;

	}
	public function GetImgHistory()
	{
		$m = new \Home\Model\ImgShowModel();
		$db = D($m->_table);
		$data = $db->order("id desc")->limit(1)->select();
		echo json_encode(array("ret"=>0,"msg"=>"success","data"=>$data[0]));
	}

	public function publish_day_share()
	{
		$model = new \Home\Model\ImgShowModel();
		$db = D($model->_table);
		$start_time  = date("Y-m-d 00:00:00");
		$end_time = date("Y-m-d 23:59:59");
		$map["start_time"] = array(array('egt',$start_time),array('elt',$end_time),'and');
		$result = $db->where($map)->save(array("is_online"=>1));	
		if($result > 0)
		{
			echo date("Y-m-d H:i:s")."###update success!result=$result\n";
		}else{
			echo date("Y-m-d H:i:s")."###no data updated!\n";
		}

		$model = new \Home\Model\DressListModel();
		$db = D($model->_table);
		$start_time  = date("Y-m-d 00:00:00");
		$end_time = date("Y-m-d 23:59:59");
		$map["start_time"] = array(array('egt',$start_time),array('elt',$end_time),'and');
		$result = $db->where($map)->save(array("is_online"=>1));	
		if($result > 0)
		{
			echo date("Y-m-d H:i:s")."###update success!result=$result\n";
		}else{
			echo date("Y-m-d H:i:s")."###no data updated!\n";
		}


	
	}

	
}
