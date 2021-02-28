<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends Controller {
	private $_username = "admin";
	private $_password = "admin@12";
	public function index()
	{
		echo "hello";
	}
	//获取代卖列表
	public function get_purchase_agent_list()
	{
		header("Access-Control-Allow-Origin: *");
		$m = new \Home\Model\DressListModel();
		$db = D($m->_table);	
		$rows = $db->where(array("is_designer"=>0))->order(array("id"=>"desc"))->select();
		foreach($rows as $k=>$v)
		{
			$style_list = array();
			$arr = explode("|",$v['parts']);
			foreach($arr as $kk=>$vv)
			{
				$arr1 =explode("#",$vv);
				$name = $m->get_style_name_by_id($arr1[0]);
				$tmp =array("name"=>$name,"id"=>$arr1[0],"price"=>$arr1[1]);
				array_push($style_list,$tmp);
			}
			$rows[$k]["style_list"] = $style_list;
		}
		echo json_encode(array('code'=>0,'msg'=>'success','result'=>array('list'=>$rows)));
	}
	public function get_style_list()
	{
		header("Access-Control-Allow-Origin: *");
		$m = new \Home\Model\DressListModel();
		$style_list = $m->_dress_style_list;
		$list = array();
		foreach($style_list as $k=>$v)
		{
			$arr = array("id"=>$k,"name"=>$v);
			array_push($list,$arr);
		}
		echo json_encode(array('code'=>0,'msg'=>'success','result'=>array('list'=>$list)));
	
	}
	public function get_dress_detail()
	{
		header("Access-Control-Allow-Origin: *");
		$m = new \Home\Model\DressListModel();
		$db = D($m->_table);
		$id = $_GET["id"];
		$row = $db->where(array('id'=>$id))->select();
		if(empty($row))
		{
			echo json_encode(array('code'=>-1,'msg'=>'id not exist!'));
			return 0;
		}
		$row = $row[0];
		$desc = array();
		$arr = explode("#",$row['dress_desc']);
		for($i = 0;$i< $arr[1];$i++)
		{
			$img = "http://www.welaine.com/ui/dress/VOL00009_0".($i+1).".png";
			array_push($desc,$img);
		}
		$style_list = array();
		$arr = explode("|",$row['parts']);
		foreach($arr as $k=>$v)
		{
			$arr1 = explode("#",$v);
			$tmp = array("id"=>$arr1[0],"price"=>$arr1[1]);
			array_push($style_list,$tmp);
		}
		$row["desc"] = $desc;
		$row["item_list"] = $style_list;
		echo json_encode(array('code'=>0,"msg"=>"success","result"=>$row));
	}
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

	public function upload()
	{
		header("Access-Control-Allow-Origin: *");
		$ret = 0;
		$msg = "success";
		$filepath = "";
		do{
			if($_FILES["file"]["size"] == 0)
			{
				$ret = -1;
				$msg = "please select img";
				break;
			}
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize=3145728;//设置上传文件最大，大小
			$upload->exts= array('jpg','gif','png','jpeg');//后缀
			$upload->rootPath ='./ui/dress/';//上传目录
			$upload->autoSub     = false;
			$upload->replace = true;
			$filename = $_FILES['file']['name'];
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
				break;
			}
			$filepath = "http://www.welaine.com/ui/dress/".$_FILES["file"]["name"];
		}while(0);
		echo json_encode(array("code"=>$ret,"msg"=>$msg,"result"=>array("filepath"=>$filepath)));
		return 0;

	}
	public function get_add_img_prefix()
	{
		$id = $_GET["id"];	
		$is_designer = $_GET["is_designer"];
		$prefix = "";
		if($id == 0)
		{
			$m = new \Home\Model\DressListModel();
			$db = D($m->_table);
			$row = $db->where(array('is_designer'=>$is_designer))->order(array("id"=>"desc"))->limit(1)->select();
			$row = $row[0];
			$str = $row["dress_desc"];
			$arr = explode("#",$str);
			$prefix = $arr[0];
		}else{
			$m = new \Home\Model\DressListModel();
			$db = D($m->_table);
			$row = $db->where(array("id"=>$id,"is_designer"=>$is_designer))->order(array("id"=>"desc"))->limit(1)->select();
			$row = $row[0];
			$str = $row["dress_desc"];
			$arr = explode("#",$str);
			$prefix = $arr[0];
		}

		$ret = 0;
		$msg = "success";
		if($prefix == "")
		{
			$ret = -1;
			$msg = "system error";	
		}
		$str = str_replace("VOL","1",$prefix)+1;
		$str = "VOL".substr($str,1);
		echo json_encode(array("code"=>$ret,"msg"=>$msg,"result"=>array("prefix_name"=>$str)));
	}	

	public function form_submit_for_backend()
	{
		//echo json_encode(array('ret'=>0,'msg'=>'success','result'=>array('insert_id'=>1,'num'=>1)));
		//return ;
		$body = _POST_BODY();
		$post_data = json_decode($body,true);
		$data = array();
		$data["title"] = $post_data["title"];
		$data["dress_desc"] = $post_data["desc"];
		$data["is_online"] = $post_data["is_online"];
		$data["start_time"] = $post_data["publish_date"]." 06:00:00";
		$data["visits"] = $post_data["visits"];
		$arr = explode("#",$post_data["desc"]);
		$data["img"] = "http://www.welaine.com/ui/dress/".$arr[0].".png";

		$item_list = $post_data["item_list"];
		
		$str = "";
		foreach($item_list as $k=>$v)
		{
			$str .= "|".$v["id"]."#".$v['price'];
		}
		$data["parts"] = substr($str,1);
		$id = $post_data["id"];

		$m = new \Home\Model\DressListModel();
		$db = D($m->_table);
		$insert_id = 0;	
		$num = 0;
		$ret = 0;
		$msg = "success";
		do{
			//新增
			if($id == 0 || $id == false)
			{
				$insert_id = $db->add($data);
				if($insert_id <= 0)
				{
					$ret = -1;
					$msg = "insert fail!";	
				}
				break;
			}else{//修改
				$num = $db->where(array('id'=>$id))->save($data);
				if($num != 0 && $num != 1)
				{
					$ret = -2;
					$msg = "update fail!";
				}
				break;
			}
		}while(0);
		echo json_encode(array('ret'=>$ret,'msg'=>$msg,'result'=>array('insert_id'=>$insert_id,'update_num'=>$num)));
		
/**
Array
(
    [title] => asdf
    [dress_desc] => VOL00010#1
    [is_online] => 0
    [start_time] => 2024
    [visits] => 1000
)
Array
(
    [0] => Array
        (
            [id] => 2
            [price] => 123
        )

    [1] => Array
        (
            [id] => 17
            [price] => 123
        )

)
*/
	}
}
