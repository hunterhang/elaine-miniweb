<?php
namespace Home\Model; 
use Think\Model;
class ImgShowModel extends Model {
    protected $tablePrefix = 'ecm_'; 
    public $_table = "img_show";
    protected $_validate = array(
         array('company_name','4,64','公司名称不正确,必须在4-30个字节',0,'length'),
         array('device_type',array(1,2,3,4,5),'设备类型值的范围不正确！',1,'in'),
         array('num','integer','数量必须为整数',1),
         array('order_type',array(1,2,3,4,5),'订单类型值的范围不正确！',1,'in'),
         array('phone','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码错误！','0','regex',1),
         array('email','email','Email格式错误！',1),
         array('user_id','integer','用户ID必须为整数',1),
    );
    
}
