<?php
echo file_get_contents("http://www.welaine.com/admin/Index/publish_day_share");
/**
define("ROOT_PATH","/data/www/ecmall/");
define("IN_ECM",1);
define("DB_PREFIX","");
date_default_timezone_set("PRC");
include '/data/www/ecmall/eccore/model/mysql.php';
$mysql = new cls_mysql();
$mysql->connect("127.0.0.1","root","");
$mysql->select_database("wxmall");
$date = date("Y-m-d");
$start_date = $date." 00:00:00" ;
$end_date = $date." 23:59:59";
$sql1 = sprintf("update ecm_dress_list set is_online=1 where start_time >= \"%s\" and start_time <= \"%s\";",$start_date,$end_date);
$sql2 = sprintf("update ecm_img_show set is_online=1 where start_time >= \"%s\" and start_time <= \"%s\";",$start_date,$end_date);
$obj1 = $mysql->query($sql1);
$obj2 = $mysql->query($sql2);
$num1 = $mysql->affected_rows($obj1);
$num2 = $mysql->affected_rows($obj2);
printf("sql1:%s,affected_num:%d\n",$sql1,$num1);
printf("sql2:%s,affected_num:%d\n",$sql2,$num2);
**/
