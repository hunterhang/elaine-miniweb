<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	 public function index(){
		//echo "Sorry, the system upgrading, please look forward to it,any problem please email 113172721@qq.com.";
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"/dist/index.html#/\"";
		echo "</script>";
	 }
	 public function get_list()
	 {
		 $rows = D("index_page")->order(array('id'=>"desc"))->select();
		 RSP(0,"success",array("list"=>$rows));
	 }
	 public function getRecentCustomize()
	 {
		$res = array();
		//$db = new \Home\Model\DressListModel();
		$db = D("goods_show");
		$ret = 0;
		$msg = "success";
		$title;
		$index_img;
		$parts_arr = array();
		$body_img_list = array();
		$is_designer = 0;
		$id = 0;
		$row = array();
		do{
			$id = _GET("id");
			$rows = array();
			if($id == false)
			{
				$row = $db->where(array('is_online'=>1,'cid'=>3))->order(array('id'=>desc))->find();
			}else{
				$row = $db->where(array("id"=>$id))->order(array('id'=>desc))->find();
			}
			if(empty($row))
			{
					$ret = -1;
					$msg = "data empty!";
					break;
			}
			$row["bg_img_list"] = explode("|",$row["bg_img_list"]);
			/**
			var_dump($row);
			$src_part = $db->_dress_style_list;
			for($i = 0;$i<count($tmp_arr);$i++)
			{
				$tmp = explode("#", $tmp_arr[$i]);
				$name = $src_part[$tmp[0]];
				array_push($parts_arr,array('id'=>$tmp[0],'price'=>$tmp[1],'name'=>$name));
			}
			$dress_desc = $row["dress_desc"];
			$img_desc_arr = explode("#", $dress_desc);
			$img_prefix = $img_desc_arr[0];
			for($i = 1;$i <= $img_desc_arr[1];$i++)
			{
				if($i == 1)
				{
					$index_img = "http://www.welaine.com/ui/dress/".$img_prefix.".png";
				}
				if($i < 10)
				{
					$index = "0".$i;
				}
				$img_url = "http://www.welaine.com/ui/dress/".$img_prefix."_".$index.".png";
				array_push($body_img_list, $img_url);
			}
			
			$title = $row["title"];
			$id = $row["id"];
			$is_designer = $row["is_designer"];
			**/
		}while(0);
		RSP($ret,$msg,$row);
		return 0;
	 }
	 public function getDetail()
	 {
		$res = array();
		$m = new \Home\Model\DressListModel();
		$db = D("goods_show");
		$ret = 0;
		$msg = "success";
		$title;
		$index_img;
		$parts_arr = array();
		$body_img_list = array();
		$is_designer = 0;
		$id = 0;
		$row = array();
		do{
			$id = _GET("id");
			$rows = array();
			if($id == false)
			{
				$ret = -1;
				$msg = "id is empty!";
				break;
			}else{
				$row = $db->where(array("id"=>$id))->order(array('id'=>desc))->find();
			}
			if(empty($row))
			{
					$ret = -1;
					$msg = "data empty!";
					break;
			}
			$row["bg_img_list"] = explode("|",$row["bg_img_list"]);

			$row["express_price"] = $m->_express_price;
		}while(0);
		RSP($ret,$msg,$row);
		return 0;
	 }
	 public function getDateList()
	 {
		 $db = new \Home\Model\DressListModel();
		 $date_list = $db->_date_list;
		 $time_list = $db->_time_list;
		 $arr = array('ret'=>0,'msg'=>"success","data"=>array('date_list'=>$date_list,'time_list'=>$time_list));
		 echo json_encode($arr);
		 return 0;
	 }
	 public function getSizeList()
	 {
		 $db = new \Home\Model\DressListModel();
		 $arr = array('ret'=>0,'msg'=>"success","data"=>array('size_list'=>$db->_size_list));
		 echo json_encode($arr);
		 return 0;
	 }
	 public function getStyleList()
	 {
	 	$db = new \Home\Model\DressListModel();
	 	$arr = array('ret'=>0,'msg'=>"success","data"=>$db->_dress_style_list);
		 echo json_encode($arr);
		 return 0;
	 }

	 public function get_customize_list()
	 {
	 	$start = _GET("start");
		$limit = _GET("limit")+1;
		$db = new \Home\Model\DressListModel();
		$db = D("goods_show");
		$is_more = 0;
		do{
			//$rows = $db->field("id,title,img,visits,order_num")->where(array('is_online'=>1,'is_designer'=>0))->order(array('id'=>desc))->limit("$start,$limit+1")->select();
			//$rows = $db->field("id,title,main_img as img,visits,order_num")->where(array('is_designer'=>0,'id'=>array("EGT",33),'is_online'=>1))->order(array('id'=>desc))->limit("$start,$limit+1")->select();
			$rows = $db->field("id,title,main_img as img,visits")->where(array('cid'=>3,'is_online'=>1))->order(array('id'=>desc))->limit("$start,$limit+1")->select();
		}while(0);
		if(count($rows) == $limit)
		{
			$is_more = 1;
			array_pop($rows);
		}
		$arr = array(
			'ret'=>0,
			'msg'=>'success',
			'data'=>$rows,
			'is_more'=>$is_more
		);
		echo json_encode($arr);
		return 0;
	 }
	 public function getCustomizeInfo()
	 {
	 	$id = $_GET["id"];
	 	$ret = 0;
		$msg = "success";
		$img = "";
		$parts_arr = array();
		$db = new \Home\Model\DressListModel();
		$db = D("dress_list");
		do{
			if($id == NULL)
			{
				$ret = -1;
				$msg = "id is empty";
				break;
			}
			$where = array();
			$rows = $db->field("img,parts")->where(array('id'=>$id))->select();
			if(count($rows) == 0)
			{
				$ret = -2;
				$msg = "param error,no id";
				break;
			}
			$row = $rows[0];
			$img = $row["img"];
			$dress_style_str = $row["parts"];
			$tmp_arr = explode("|", $dress_style_str);
			$src_part = $db->_dress_style_list;
			for($i = 0;$i<count($tmp_arr);$i++)
			{
				$arr = explode("#", $tmp_arr[$i]);
				$name = $src_part[$arr[0]];
				$id = $arr[0];
				$price = $arr[1];
				array_push($parts_arr,array('id'=>$id,'price'=>$price,'name'=>$name));
			}
			
		}while(0);
		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>array("img"=>$img,"patt_arr"=>$parts_arr,"express_price"=>$db->_express_price)
		);
		echo json_encode($arr);
	 }
	 public function GetUserDesignInfo()
	 {
		$id = _GET("id");
		$model = new \Home\Model\UserDesignModel();
		$db = D($model->_table);
		$ret =0;
		$msg ="success";
		$data = array();
		do{
			$data = $db->where(array('id'=>$id))->select();
			if(empty($data))
			{
				$ret = -1;
				$msg = "data empty!";
				break;	
			}
			$data = $data[0];

		}while(0);
	 }
	 public function getUserProductInfo()
	 {
		$id = _GET("id");
		$ret = 0;
		$msg = "success";
		$data = array();
		$parts_arr = array();
		$express_price = 0;
		do{
			$model = new \Home\Model\UserDesignModel();
			$db = D($model->_table);
			if($id == false || $id == 0)
			{
				$data = $db->where(array('status'=>1))->order(array('id'=>"desc"))->limit(1)->select();
			}else{
				$data = $db->where(array('id'=>$id))->select();
			}
			
			if(empty($data))
			{
				$ret = -1;
				$msg = "empty data";
				break;
			}
			$data = $data[0];
			$d_model = new \Home\Model\DressListModel();
			$src_part =$d_model->_dress_style_list;
			$tmp_arr = explode("|", $data['parts']);
			for($i = 0;$i<count($tmp_arr);$i++)
			{
				$tmp = explode("#", $tmp_arr[$i]);
				$name = $src_part[$tmp[0]];
				array_push($parts_arr,array('id'=>$tmp[0],'price'=>$tmp[1],'name'=>$name));
			}
			$express_price = $d_model->_express_price;
		}while(0);
		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>$data,
			'patt'=>$parts_arr,
			'express_price'=>$express_price
		);
		echo json_encode($arr);
	 }
	 public function getPatternList()
	 {
		$ret = 0;
		$msg = "success";
		$model = new \Home\Model\DressListModel();
		$src_part = $model->_dress_style_list;
		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>$src_part	
		);
		echo json_encode($arr);
	 }
	 
	 public function getOrderShowInfo()
	 {
	 	$date = _GET("date");
	 	$time = _GET("time");
	 	$size = _GET("size");
	 	$ret = 0;
		$msg = "success";
		$date_obj = array();
		$time_obj = array();
		$size_obj = array();
		$id_obj = array();
		$patt_obj = array();
		$db = new \Home\Model\DressListModel();
		do{
			$size_list = $db->_size_list;
			if($size == -1)
			{
				$ret = -1;
				$msg = "size is empty!";
				break;
			}
			$size_obj = array('id'=>$size,'name'=>$size_list[$size]);

			$time_list = $db->_time_list;
			if($time != -1)
			{
				$time_obj = array('id'=>$time,'name'=>$time_list[$time]);
			}

			$date_list = $db->_date_list;
			if($date != -1)
			{
				$date_obj = array('id'=>$date,'name'=>$date_list[$date]);
			}
		}while(0);

		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>array("date"=>$date_obj,"time"=>$time_obj,"size"=>$size_obj)
		);
		echo json_encode($arr);
	 }


	 public function getDressMatchList()
	 {
		$start = $_GET["start"];
		$limit = $_GET["limit"] + 1;
		$model = new \Home\Model\ImgShowModel();
		$db = D($model->_table);
		$is_more = 0;
		do{
			$rows = $db->field("id,title,img,visits")->where(array('is_online'=>1))->order(array('id'=>desc))->limit("$start,$limit")->select();
			foreach($rows as $k=>$v)
			{
				$rows[$k]["img"] = "http://www.welaine.com/".$v["img"];
			}
			if(count($rows) >= $limit)
			{
				$is_more = 1;
				array_pop($rows);
			}
		}while(0);
		$arr = array(
				'ret'=>0,
				'msg'=>'success',
				'data'=>$rows,
				'is_more'=>$is_more
		);
		echo json_encode($arr);
		return 0;
	 }

	 public function upload_old()
	 {
		$upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize=3145728;//设置上传文件最大，大小
        $upload->exts= array('jpg','gif','png','jpeg');//后缀
        $upload->rootPath ='./Public/Uploads/';//上传目录
        $upload->savePath      =  '/'; // 设置附件上传（子）目录
        $upload->autoSub     = true;
        $upload->subName     = array('date','Ymd');
        $upload->saveName = array('uniqid','');//设置上传文件规则
        $info= $upload->upload();//执行上传方法
	    // 上传文件 
	    $ret = 0;
	    $msg = "success";
	    $data = array();
	    do{
	    	 if(!$info) {// 上传错误提示错误信息
	        	$ret = -1;
				$msg = $upload->getError();
				break;
	    	}
	        //获取上传文件信息
            foreach ($info as $file){
                $image = $file['savepath'].$file['savename'];
                $size = $file['size'];
                $dir=  $file['savepath'];
                $filename=$file['savename'];
                
            }
             //图片物理目录删除、改名图片用
            $path= './Public/Uploads'.$dir;
            
            $img =new \Think\Image();//实例化
            $img->open($path.$filename);//打开物理图片
			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg

            $expect_height = 180;
            //固定高度为 115
			$width = $img->width(); // 返回图片的宽度
			$height = $img->height();
			$rate = $height/$expect_height;
			$width = ceil($width/$rate); //取得图片的长宽比  190是要输出的图片的宽度
			$height = $expect_height;
			$img->thumb($width, $height)->save($path.'/thumb_'.$filename);
			$data = array(
				"src"=>"http://www.welaine.com".$path.$filename,
				"thumb"=>"http://www.welaine.com".$path.'/thumb_'.$filename
			);
	        
	    }while(0);
		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>$data
		);
		echo json_encode($arr);
		return 0;
	 }
	 public function upload()
	 {
		$rate = 0.8;//width/height
		$upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize=3145728;//设置上传文件最大，大小
        $upload->exts= array('jpg','gif','png','jpeg');//后缀
        $upload->rootPath ='./Public/Uploads/';//上传目录
        $upload->savePath      =  '/'; // 设置附件上传（子）目录
        $upload->autoSub     = true;
        $upload->subName     = array('date','Ymd');
        $upload->saveName = array('uniqid','');//设置上传文件规则
        $info= $upload->upload();//执行上传方法
	    // 上传文件 
	    $ret = 0;
	    $msg = "success";
	    $data = array();
	    do{
	    	 if(!$info) {// 上传错误提示错误信息
	        	$ret = -1;
				$msg = $upload->getError();
				break;
	    	}
	        //获取上传文件信息
            foreach ($info as $file){
                $image = $file['savepath'].$file['savename'];
                $size = $file['size'];
                $dir=  $file['savepath'];
                $filename=$file['savename'];
            }
             //图片物理目录删除、改名图片用
            $path= './Public/Uploads'.$dir;
            
            $img =new \Think\Image();//实例化
            $img->open($path.$filename);//打开物理图片
			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg

			$width = $img->width(); // 返回图片的宽度
			$height = $img->height();
			$x = 0;
			$y = 0;
			$w = 0;
			$h = 0;
			if($width/$height > $rate)
			{
				$h = $height;
				$w = ceil($height * $rate);	
				$y = 0;
				$x = ceil(($width - $w)/2);
			}else{
				$w = $width;
				$h = ceil($width*(1/$rate));
				$x = 0;
				$y = ceil(($height - $h)/2);
			}
			$expect_height = 180;
			$expect_width = ceil($expect_height * $rate);
            //固定高度为 115
			$img->crop($w,$h,$x,$y)->thumb($expect_width, $expect_height)->save($path.'/thumb_'.$filename);
			$data = array(
				"src"=>"http://www.welaine.com".$path.$filename,
				"thumb"=>"http://www.welaine.com".$path.'/thumb_'.$filename
			);
	        
	    }while(0);
		$arr = array(
			'ret'=>$ret,
			'msg'=>$msg,
			'data'=>$data
		);
		echo json_encode($arr);
		return 0;
	 }
	public function submit_product_form()
	{
		$title = $_POST["title"];
		$img = $_POST["img"];
		$style = $_POST["selected_style"];
		$desc = $_POST["desc"];
		$openid = _POST("openid");
		$phone = _POST("phone");
		$nickname = _POST("nickname");
		$db = new \Home\Model\UserDesignModel();
		$db = D("user_design");
		$data = array(
			"title"=>$title,
			"img"=>$img,
			"style"=>$style,
			"desc"=>$desc,
			"openid"=>$openid,
			"phone"=>$phone
		);
		$last_id = $db->add($data);
		$ret = send_mail("27624764@qq.com","tips","$nickname uploaded product,waiting for checking","image:".$img);
		$ret = send_mail("113172721@qq.com","tips","$nickname uploaded product,waiting for checking","image:".$img);
		echo json_encode(array('ret'=>0,'msg'=>'success'));
		return 0;
	}
	public function reportVisit()
	{
		$id = _GET("id");
		$openid = _GET("openid");
		$category_id = _GET("category_id");
		$model = new \Home\Model\ProductVisitModel();
		$db = D($model->_table);
		$data = array(
			"product_id"=>$id,
			"openid"=>$openid,
			"category_id"=>$category_id
		);
		$ret = 0;
		$msg = "success";
		do{
			$rows = $db->where(array("product_id"=>$id,"openid"=>$openid,"category_id"=>$category_id))->select();
			if(count($rows) >= 1)
			{
				$ret = 0;
				$msg = "report before";
				break;
			}
			$last_id = $db->add($data);
			if($last_id > 0 && $category_id == 1)
			{
				$mm = new \Home\Model\UserDesignModel();
				$db = D($mm->_table);
				$db->where(array("id"=>$id))->save(array('visits'=>array('exp','visits+1')));
			}
			if($last_id > 0 && $category_id == 2)
			{
				$mm = new \Home\Model\ImgShowModel();
				$db = D($mm->_table);	
				$db->where(array("id"=>$id))->save(array('visits'=>array('exp','visits+1')));
			}
			if($last_id > 0 && $category_id == 3)
			{
				$mm = new \Home\Model\DressListModel();
				$db = D($mm->_table);	
				$db->where(array("id"=>$id))->save(array('visits'=>array('exp','visits+1')));
			}
		}while(0);
		echo json_encode(array('ret'=>$ret,'msg'=>$msg));
		return 0;
	}
	public function getDiscount()
	{
		$openid = isset($_GET["openid"])?$_GET["openid"]:false;
		$dis_m = new \Home\Model\DiscountModel();
		$db = D($dis_m->_table);
		$data = $db->field("id,amount")->where(array("openid"=>$openid,"is_valid"=>1))->select();
		$ret = 0;
		$msg = "success";
		$discount_id =0 ;
		$amount = 0;
		do{
			if(empty($data))
			{
				$ret = -1;
				$msg = "no valid discount!";
				break;
			}
			$discount_id = $data[0]["id"];
			$amount = $data[0]["amount"];
		}while(0);
		echo json_encode(array("ret"=>$ret,"msg"=>$msg,"discount_id"=>$discount_id,"amount"=>$amount));
	}
	public function get_product_list()
	{
		$openid = _GET("openid");
		$ret = 0;
		$msg = "success";
		$data = array();
		do{
			if($openid == false)
			{
				$ret = -1;
				$msg = "输入参数错误，openid is empty";
				break;
			}
			$db = new \Home\Model\UserDesignModel();
			$db = D("user_design");
			$data = $db->field("id,title,img,status,visits,income")->where(array('openid'=>$openid))->order(array('id'=>desc))->select();
		}while (0);
		echo json_encode(array('ret'=>0,'msg'=>'success','data'=>$data));
		return 0;
	}

	public function get_designer_product_list()
	{
		$openid = _GET("openid");
		$start  = _GET("start");
		$limit = _GET("limit")+1;
		
		$ret = 0;
		$msg = "success";
		$is_more = 0;
		$data = array();
		do{
			//$db = new \Home\Model\UserDesignModel();
			//$db = D("user_design");
			//$data = $db->field("id,title,img,income,visits")->where(array('status'=>1))->order(array('id'=>desc))->limit($start,$limit)->select();
			//$db = new \Home\Model\DressListModel();
			$db = D("goods_show");
			$data = $db->field("id,title,main_img as img,visits")->where(array('cid'=>1,'is_online'=>1))->order(array('id'=>desc))->limit($start,$limit)->select();
			if(count($data) >= $limit)
			{
				$is_more = 1;
				array_pop($data);
			}else{
				$is_more = 0;
			}
		}while (0);
		echo json_encode(array('ret'=>$ret,'msg'=>$msg,'data'=>$data,'is_more'=>$is_more));
		return 0;
	}
	public function get_recommend_designer_info()
	{
		$ret = 0;
		$msg = "success";
		$list = array();
		//$obj = array("url"=>"http://www.welaine.com/ui/dress/VOL0014_01.png","type"=>1);
		$obj = array();
		array_push($list,$obj);
		//$obj = array("url"=>"https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=m0554lhkx5s&auto=0","poster"=>"http://www.welaine.com/ui/video/test1_poster.png","type"=>2);

		$obj = array("url"=>"http://www.welaine.com/ui/video/test1.mp4","poster"=>"http://www.welaine.com/ui/video/test1_poster.png","type"=>2);
		array_push($list,$obj);
		$arr = array('ret'=>0,'msg'=>$msg,'list'=>$list);
		echo json_encode($arr);
	}
	public function SendMail()
	{
		$ret = send_mail("113172721@qq.com","test","aaaa","asdf");
		if($ret)
		{
			echo "mail success!";
		}
	}

	public function move_data()
	{

		$m = new \Home\Model\DressListModel();
		$db = D("dress_list");
		$data = $db->select();
		$db1 = D("goods_show");
		foreach($data as $k=>$v)
		{
			$info = array();
			$info["id"] = $v["id"];
			$info["title"] = $v["title"];
			$info["main_img"] = str_replace("http://www.welaine.com","",$v["img"]);
			$arr = explode("#",$v['dress_desc']);
			$str = "";
			if(count($arr) == 2)
			{
				$size = $arr[1];
				for($i = 0;$i<$size;$i++)
				{
					$str .= "/ui/dress/".$arr[0]."_0".($i +1).".png|";
				}
			}else{
				$str = "/ui/dress/".$arr[0].".png";	
			}
			$info["bg_img_list"] =substr($str,0,-1);
			//get_style_name_by_id
			$arr = explode("|",$v["parts"]);
			$str = "";
			foreach($arr as $kk=>$vv)
			{
				$tmp = explode("#",$vv);
				$name = $m->get_style_name_by_id($tmp[0]);
				$price = $tmp[1];
				$str .= $name."#".$price."|";
			}
			$info["style_list"] = substr($str,0,-1);
			$info["start_time"] = $v["start_time"];
			$info["visits"] = $v["visits"];
			$info["cid"] = $v["is_designer"] == 0?3:1; 
			$info["is_recommend"] = 0;
			$info["is_online"] = $v["is_online"];
			$info["created"] = $v["created"];
			$info["tag1"] = "";
			$info["tag2"] = "";
			$db1->add($info);
			print_r($info);
		}

	}
}
