<?php
namespace Home\Model; 
use Think\Model;
class DressListModel extends Model {
	protected $connection = 'mysql://welaine:weweliane@127.0.0.1:3306/release_welaine';
    public $_size_list = array(
        "1"=>"S",
        "2"=>"M",
        "3"=>"XS",
        "4"=>"XL",
        "5"=>"定制量身"
    );
    public $_date_list = array(
        "1"=>'今天',
        "2"=>'明天',
        "3"=>'后天'
    );
    public $_time_list = array(
       "1"=>'09:00 - 12:00',
       "2"=>'14:00 - 16:00',
       "3"=>'17:00 - 19:00'
    );
    public $_dress_style_list = array(
		"1"=>"套装",
		"2"=>"上衣",
		"3"=>"裤子",
		"4"=>"衬衣",
		"5"=>'领带',
		"6"=>"口袋巾",
		"7"=>"马甲",
		"8"=>"连衣裙现货价",
		"9"=>"裙子",
		"10"=>"旗袍",
		"11"=>"裤裙",
		"12"=>"上衣标准码现货价",
		"13"=>"裤子标准码",
		"14"=>"上衣定制码",
		"15"=>"裤子定制码",
		"16"=>"连衣裙标准码",
		"17"=>"连衣裙定制码",
		"18"=>"裙子标准码",
		"19"=>"裙子定制码",
		"20"=>"下衣标准码",
		"21"=>"下衣定制码",
		"22"=>"西裤",
		"23"=>"阔腿裤",
		"24"=>"长裤",
		"25"=>"印花上衣",
		"26"=>"红色连衣裙",
		"27"=>"印花连衣裙",
		"28"=>"风车刺绣连衣裙",
		"29"=>"金色半裙",
		"30"=>"金色上衣",
		"31"=>"黑色长裤",
		"32"=>"粉红色连衣裙",
		"33"=>"粉红色西装外套",
		"34"=>"粉红色长裤",
		"35"=>"蓝色连衣裙",
		"36"=>"拼色连衣裙",
		"37"=>"白色背心",
		"38"=>"蓝色牛仔半裙",
		"39"=>"深蓝色连衣裙"
    );
    public $_express_price = 1000;
    protected $tablePrefix = 'ecm_'; 
    public $_table = "dress_list";
    private $aes_key = "asdf123asd[po!23%";
    protected $_validate = array(
         array('company_name','4,64','公司名称不正确,必须在4-30个字节',0,'length'),
         array('device_type',array(1,2,3,4,5),'设备类型值的范围不正确！',1,'in'),
         array('num','integer','数量必须为整数',1),
         array('order_type',array(1,2,3,4,5),'订单类型值的范围不正确！',1,'in'),
         array('phone','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码错误！','0','regex',1),
         array('email','email','Email格式错误！',1),
         array('user_id','integer','用户ID必须为整数',1),
    );
	public function get_style_name_by_id($id)
	{
		return $this->_dress_style_list[$id];
	}
    public function get_dress_info($id)
    {
        $model = D($this->_table);
        $rows = $model->where(array('id'=>$id))->select();
        if(count($rows) != 1)
        {
            return false;
        }
        return $rows[0];
    }
    public function date_id_2_str($date_id,$time_id)
    {
        $time = time() + $id *3600*24;
        $date_str = date("Y-m-d",$time);
        $str = $date_str." ".$this->_time_list[$time_id];
        return $str;
    }
    //计算价格
    public function calc_dress_price($parts,$patt_arr)
    {
        $tmp_arr = explode("|", $parts);
        $total_price = 0;
		foreach($patt_arr as $k=>$v)
		{
			$str = $tmp_arr[$v];
			$arr = explode("#",$str);
			$total_price += $arr[1];
		}
		return $total_price;
        foreach($tmp_arr as $k=>$v)
        {
            $tmp_tmp_arr = explode("#", $v);
            $id = $tmp_tmp_arr[0];
            $price = $tmp_tmp_arr[1];
            foreach($patt_arr as $vv)
            {
              if($vv == $id)
              {
                $total_price += $price;
                break;
              }
            }
        }
        return $total_price;
    }
    public function calc_dress_priceV2($parts,$patt_arr)
    {
		$tmp_arr = explode("|", $parts);
		$total_price = 0;
		foreach($patt_arr as $k=>$v)
		{
			$str = $tmp_arr[$v];
			$tmp = explode("#",$str);			
			$total_price += $tmp[1];
		}
		return $total_price;
    }

}
