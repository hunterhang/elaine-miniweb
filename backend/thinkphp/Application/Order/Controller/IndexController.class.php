<?php
namespace Order\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function getHistoryOrderInfo()
	{
		$openid = $_GET["openid"];
		$db = new \Order\Model\UserOrderModel();
		$db = D("user_order");
		$ret = 0;
		$msg = "success";
		$phone = "";
		$address = "";
		do{
			if($openid == NULL)
			{
				$ret = -1;
				$msg = "openid is empty";
				break;
			}
			$where = array();
			$rows = $db->field("address,phone")->where(array('openid'=>$openid,'address'=>array('neq',''),'phone'=>array('neq','')))->order(array('order_id'=>desc))->limit(1)->select();
			if(count($rows) == 0)
			{
				$ret = -2;
				$msg = "no history orders";
				break;
			}
			$row = $rows[0];
			$phone = $row["phone"];
			$address = $row['address'];
		}while(0);
		$arr = array(
				'ret'=>$ret,
				'msg'=>$msg,
				'data'=>array("phone"=>$phone,"address"=>$address)
		);
		echo json_encode($arr);
	}
	public function getOrderInfo()
	{
		$order_id = _GET("order_id");
		$openid = _GET("openid");
		$user_order_db = new \Order\Model\UserOrderModel();
		$db = D("user_order");
		$ret = 0;
		$dress_id = 0;
		$msg = "success";
		$data = array();
		$dress_info = array();
		$patt_arr = array();
		do{
			if($openid == false || $order_id == false)
			{
				$ret = -1;
				$msg = "input param error!";
				break;
			}
			$row = $db->where(array('openid'=>$openid,'order_id'=>$order_id))->select();
			if(count($row) != 1)
			{
				$ret = -2;
				$msg= "select data fail!";
				break;
			}
			$data = $row[0];
			$dress_id = $data['dress_id'];
			$dress_info = D("goods_show")->where(array('id'=>$dress_id))->find();
			/**
			$dress_list_model = new \Home\Model\DressListModel();

			if($data['is_dress_model'] == "1")
			{
				$db_dress = D($dress_list_model->_table);
				$dress_info = $db_dress->where(array('id'=>$dress_id))->select();	
			}else{
				$design_model = new \Home\Model\UserDesignModel();
				$db_design = D($design_model->_table);
				$dress_info = $db_design->where(array('id'=>$dress_id))->select();	
			}
            $src_part =$dress_list_model->_dress_style_list;
            $tmp_arr = explode("|", $dress_info[0]['parts']);
            for($i = 0;$i<count($tmp_arr);$i++)
            {
                $tmp = explode("#", $tmp_arr[$i]);
                $name = $src_part[$tmp[0]];
                array_push($patt_arr,array('id'=>$tmp[0],'price'=>$tmp[1],'name'=>$name));
            }
			**/
		}while(0);
		$arr = array(
			"ret"=>$ret,
			"msg"=>$msg,
			"order_info"=>$data,
			"product_info"=>$dress_info
			//"patt_arr"=>$patt_arr
			);
		echo json_encode($arr);
	}

	public function getOrderList()
	{
		$openid = $_GET["openid"];
		$status = $_GET["status"];
		$db = new \Order\Model\UserOrderModel();
		$db = D("user_order");
		$ret = 0;
		$msg = "success";
		$phone = "";
		$address = "";
		$data = array();
		do{
			if($openid == NULL)
			{
				$ret = -1;
				$msg = "openid is empty";
				break;
			}
			//$where = array();
			//$data = $db->field("order_id,goods_style,goods_size,product_img")->where(array('openid'=>$openid,'status'=>$status,'is_dress_model'=>1,'is_delete'=>0))->order(array('order_id'=>desc))->select();
			//$sql = "select a.order_id,a.goods_style,a.goods_size,b.img as product_img from ecm_user_order as a left join ecm_dress_list as b on a.dress_id = b.id where a.openid=\"$openid\" and status=$status and dress_id != 0";
			//$Model = new \Think\Model();
			//$data = $Model->query($sql);
			$data = $db->where(array('status'=>$status,"is_delete"=>0,"openid"=>$openid))->order(array('order_id'=>"desc"))->select();
			if(count($data) == 0)
			{
				$ret = -2;
				$msg = "no history orders";
				break;
			}
					}while(0);
		$arr = array(
				'ret'=>$ret,
				'msg'=>$msg,
				'data'=>$data
		);
		echo json_encode($arr);
	}

	public function submit()
	{
		$phone = _POST("phone");
		$addr = _POST("addr");
		$img = _POST("img");
		$phone = _POST("phone");
		$openid = _POST("openid");
		$product_id = _POST("product_id");
		$size_arr = _POST("size");
		$patt_arr =_POST("patt");
		$date = _POST("date");
		$time = _POST("time");
		$show_type = _POST("show_type");	
		$discount_id = _POST("discount_id");
		$dress_model = new \Home\Model\DressListModel();
		$express_price = $dress_model->_express_price;
		$order_model = new \Order\Model\UserOrderModel();
        $user_design_model = new \Home\Model\UserDesignModel();

		//获取货物信息
		$goods_price = 0;
		$total_price = 0;
		$discount_amount = 0;
		$ret = 0;
		$msg = "success";

		//订单信息
		$order_id = _POST("order_id");
        $pay_param	= array();
        $order_no = $order_id;
        $prepay_id = "";
		do{
            //检查参数
            if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
                $ret = -1;
                $msg = "手机号码格式错误";
                break;
            }
            if (strlen($addr) < 10) {
                $ret = -2;
                $msg = "请填写详细地址（至少10个字符）";
                break;
            };
            if(empty($size_arr) || $size_arr["id"] <1 || $size_arr["id"] >5)
            {
            	$ret = -3;
            	$msg = "size param error";
            	break;
            }
            if(empty($patt_arr))
            {
            	$ret = -4;
            	$msg = "参数错误，请选择搭配";
            	break;
            }

            if ($size_arr["id"] == 5)
            {
                if(empty($date) || empty($time))
                {
                    $ret = -5;
                    $msg = "参数错误，未填写预约上门的时间";
                    break;
                };

                $door_time = $dress_model->date_id_2_str($date["id"],$time["id"]);
                if($date["id"] == 1)
                {
                	$time_list_arr = $dress_model->_time_list;
                	$max_time = explode("-",$time_list_arr[$time["id"]]);
	                $max_hour = $max_time[1];
	                $date_str = date("Y-m-d");
	                $time_str = $date_str." ". $max_hour.":00";
	                $timestamp =  strtotime($time_str);
	                if($timestamp - time() <= 3600*2)
	                {
	                    $ret = -6;
	                    $msg = "距离预约上门的时间不到2小时，请修改预约时间";
	                    break;
	                }
                }
                
                if(!(preg_match("/深圳/",$addr)||preg_match("/上海/",$addr)))
                {
                    $ret = -7;
                    $msg = "定制量身目前只支持上海，深圳地区，其它地区敬请期待";
                    break;
                }
            }else{
                if(!(preg_match("/深圳/",$addr)|| preg_match("/上海/",$addr)||preg_match("/北京/",$addr)))
                {
                    $ret = -8;
                    $msg = "目前只支持北京，上海，深圳地区，其它地区敬请期待";
                    break;
                }
            }

			//参数校验
			if($product_id <= 0)
			{
				$ret = -9;
				$msg = "id is not correct";
				break;
			}
            $dress_info = array();
            if($show_type == 1)
            {
				$dress_info = D("goods_show")->where(array('id'=>$product_id))->find();
                //$dress_info = $dress_model->get_dress_info($product_id);
            }else{
                $dress_info = $user_design_model->get_product_info($product_id);
            }
			if($dress_info == false)
			{
				$ret = -10;
				$msg = "该货物不存在，请重新下单!";
				break;
			}
			$parts = $dress_info["style_list"];
			$goods_price = $dress_model->calc_dress_priceV2($parts,$patt_arr);
			//计算价格
			
			$total_price = $goods_price + $express_price;
			$total_price = sprintf("%.2f",$total_price/100);

			if($total_price == 0)
			{
				$ret = -11;
				$msg = "参数错误，请选择搭配!";
				break;
			}
			$db = D($order_model->_table);
			//表示添加订单
			$style = "";
			foreach($patt_arr as $k=>$v)
			{
				$style .= $v.",";
			}
            $style = substr($style,0,strlen($style)-1);
            $param = array(
            	"openid" => $openid, 
            	"goods_style" => $style, 
            	"goods_size" => $size_arr["id"], 
            	"product_img" => $img, 
            	"address" => $addr, 
            	"status" => 0, 
            	"phone" => $phone, 
            	"express_price" => $express_price, 
            	"goods_price" => $goods_price, 
            	"total_price" => $total_price, 
            	//"door_time"=>$door_time,
            	"dress_id"=>$product_id,
            	"is_dress_model"=>$show_type,
                "date"=>$date,
				"time"=>$time
            );
			//查询用户的优惠卷
			if($discount_id > 0)
			{
				$discount_m = new \Home\Model\DiscountModel();
				$dis_db= D($discount_m->_table);
				$dis_data = $dis_db->field("amount")->where(array("openid"=>$openid,"id"=>$discount_id,"is_valid"=>1))->select();
				if(!empty($dis_data))
				{
					$discount_amount = $dis_data[0]["amount"];
				}		
				$param["discount_id"] = $discount_id;
			}
		
            //查询该订单
            $order_info = $db->where(array("openid"=>$openid,"order_id"=>$order_id))->select();
			//下单
			if ($order_id == false || empty($order_info)) {
				$param["created"] = time();
                $order_id = $db->add($param);
                if ($order_id <= 0) {
                    $ret = -12;
                    $msg = "保存订单错误，请稍后重试!";
                    break;
                }
            }else{
            	$order_info = $order_info[0];
				if($order_info["status"] == 1)
				{
					$ret = -13;
					$msg = "该订单已经支付";
					break;
				}
            	$result = $db->where(array("order_id"=>$order_id,"status"=>0,"openid"=>$openid,"discount_id"=>$discount_id))->save($param);
            }
            $auth_obj = new \Org\Util\Auth();
        	if(time() -	 $order_info["prepay_id_created"] > $auth_obj->getPrepayIdValidTime())          	
        	{
        		$prepay_id == false;
        	}else{
        		$prepay_id = $order_info["prepay_id"];
        	}

        	$config = $auth_obj->getWxConfig();
        	$pay_obj = new \Org\Util\WxPay($config["mch_id"],$config["appid"],$config["appsecret"],$config["paysecret"]);
			$timestamp = time();
   
        	if($prepay_id == false)
        	{
                $order_no = $order_id."-".time();
				//$total_price = $total_price*100 - $discount_amount;
				$total_price = sprintf("%.2f",($total_price*100-$discount_amount)/100);
        		$order_arr  = $pay_obj->createJsBizPackage($openid, $total_price, $order_no, "WE衣-订单", $config['notify_url'], $timeStamp,$order_id);
                if (false == $order_arr) {
                    $ret = -14;
                    $msg = "商户下单失败";
                    break;
                }
                //var_dump($order_arr);
                $prepay_id = $order_arr['package'];
                $param = array(
                	"prepay_id"=>$prepay_id,
                	"prepay_id_created"=>timeStamp
                );
                $result = $db->where(array("order_id"=>$order_id,"openid"=>$openid,"status"=>0))->save($param);
                if($result == false)
                {
                	$ret = -15;
                	$msg = "更新订单失败";
                	break;
                }
        	}
 			$pay_param = array(
                "appId"=>$order_arr['appId'],
                "nonceStr"=>$pay_obj->createNonceStr(),
                "package"=>$prepay_id,
                "signType"=>"MD5",
                "timeStamp"=>$timestamp
                );
            $sig = $pay_obj->getSign($pay_param,$config["paysecret"]);
            $pay_param['paySign'] = $sig;
		}while(0);
		$data = array('ret'=>$ret,'msg'=>$msg,"order_id"=>$order_id,"pay"=>$pay_param);
		echo json_encode($data);
		return 0;
	}

    public function del()
    {
        $openid = _GET("openid");
        $order_id = _GET("order_id");
        $ret = 0;
        $msg = "success";
        do{
            if($openid == false || $order_id == false)
            {
                $ret = -1;
                $msg = "input param error";
                break;
            }
            $order_model = new \Order\Model\UserOrderModel();
            $db = D($order_model->_table);
            $param = array("is_delete"=>1);
            $db->where(array("openid"=>$openid,"order_id"=>$order_id))->save($param);
        }while(0);
        $data = array(
            'ret'=>$ret,
            'msg'=>$msg
            );
        echo json_encode($data);
        return 0;
    }
	public function updateOrderStatus()
	{
		error_log("updateOrderStatus");
        error_log(var_export($_POST,true));
		$ip = $_SERVER["REMOTE_ADDR"];
        //只能本机访问
        if($ip != "139.199.179.242")
        {
            echo "forbidden access this url!";
            error_log("forbidden access this url!");
            return ;
        }
        $order_id_str = $_POST["out_trade_no"];
		$tmp = explode("-",$order_id_str);
		$order_id = $tmp[0];
		if($order_id == "")
		{
			echo "order_id error!order_id:".$order_id_str."\n";	
            error_log("forbidden access this url!");
		}
        $openid = $_POST["openid"];
        $model_m  = &m('user_order');
		$order_model = new \Order\Model\UserOrderModel();
		$db = D($order_model->_table);
		$param = array("status"=>1,"pay_info"=>http_build_query($_POST));
		//update user income
		$result = $db->where(array("order_id"=>$order_id,"openid"=>$openid,"status"=>0))->save($param);
		$rows = $db->field('is_dress_model,dress_id,goods_price,discount_id')->where(array('order_id'=>$order_id,'openid'=>$openid,"status"=>1))->select();
		if(count($rows) > 0)
		{
			$id_dress_model = $rows[0]['is_dress_model'];
			$dress_id = $rows[0]['dress_id'];
			$goods_price = $rows[0]['goods_price'];
			$discount_id = $rows[0]['discount_id'];
			error_log("======rows=======");
			error_log(var_export($rows,true));
			if($is_dress_model != 1)
			{
				$mm = new \Home\Model\UserDesignModel();
				$db = D($mm->_table);
				$db->where(array("id"=>$dress_id))->save(array('income'=>array('exp','income+'.ceil($goods_price * 0.3))));
			}else{
				//update dress_list order_num
				$mm = new \Home\Model\DressListModel();
				$db = D($mm->_table);
				$db->where(array("id"=>$dress_id))->save(array('order_num'=>array('exp','order_num+1')));
			}
			error_log("discount_id=".$discount_id);
			if($discount_id > 0)
			{
				//更新优惠卷为不可用
				$dis_m = new \Home\Model\DiscountModel();
				$dis_db = D($dis_m->_table);

				$update_discount  = $dis_db->where(array("openid"=>$openid,"id"=>$discount_id))->save(array("is_valid"=>0));
				error_log("update discount result:".$update_discount);
			}
		}
		$ret = send_mail("27624764@qq.com","tips","someone had pay the order","price:".$rows[0]['goods_price']);
		$ret = send_mail("113172721@qq.com","tips","someone had pay the order","price:".$rows[0]['goods_price']);
		if($result == false)
		{
			echo "update order status fail,order_id:".$order_id_str;
			return ;
		}
		echo "update order status success!order_id:".$order_id_str;
		return ;
	}

}
