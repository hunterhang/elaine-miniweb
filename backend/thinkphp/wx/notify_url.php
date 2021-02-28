<?php
require "WxServiceApi.php";

function object_to_array($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v)
    {
        if (gettype($v) == 'resource')
        {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array')
        {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}
function request_post($url = '',$ispost=true, $post_data = array()) {
//初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
}

$find_data = array(
            "appid"     => "wx66ff2fc56ab60b17",
            "appsecret" => "460a7d2e37d9e77c881a41c411bfd967",
            "mch_id"    => "1429470902", //商户ID
            "notify_url" => "http://www.welaine.com/wx/notify_url.php",
            "nonce_str" =>"aNPUieeU49WtTHsOXO1hrxuRbQTNQJPCUpy5oP4e0sM",
            "paysecret" =>"12345678901234567890123456789000"
        );
$wxProxy = new WxpayService($find_data["mch_id"], $find_data["appid"], $find_data["appsecret"],$find_data["paysecret"]);

$res = $wxProxy->notify();
if($res !== false)
{
	error_log("wxpay callback!===");
    error_log(var_export($res,true));
    //更新订单状态
    $data = object_to_array($res);
//    $query_string = http_build_query($data);
//    $url = "http://www.welaine.com/index.php?app=user_order&act=update_order_status";
	$url = "http://www.welaine.com/Order/index/updateOrderStatus";
    request_post($url,true,$data);
	error_log("finished wxpay callback");
};
