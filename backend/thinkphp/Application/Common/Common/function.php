<?php
function aa()
{
}
function _GET($name)
{
	$val = isset($_GET[$name]) && $_GET[$name] != "" ?$_GET[$name]:false;
	return $val;
}
function _LOG($content)
{
    $logSize = 10000000; //日志大小
    // $log = "log.txt";
    $log = "./mini_app.log";
    if(file_exists($log) && filesize($log) > $logSize){
        unlink($log);
    }
    // linux的换行是 \n  windows是 \r\n
    // FILE_APPEND 不写第三个参数默认是覆盖，写的话是追加 
    if(is_array($content))
    {
        $content = var_export($content,true);
    }
    file_put_contents($log,date('Y-m-d H:i:s')."\n".$content."\n",FILE_APPEND);
}
function _URL(){
    //获取当前完整url,为了清晰，多定义几个变量,分几行写
    $scheme = $_SERVER['REQUEST_SCHEME']; //协议
    $domain = $_SERVER['HTTP_HOST']; //域名/主机
    $requestUri = $_SERVER['REQUEST_URI']; //请求参数
    //将得到的各项拼接起来
    $currentUrl = $scheme . "://" . $domain . $requestUri;
    return $currentUrl; //传回当前url
}
function _POST($name)
{
	$val = isset($_POST[$name]) && $_POST[$name] != "" ?$_POST[$name]:false;
	return $val;
}
function _POST_BODY()
{
	 _LOG($body);
	return file_get_contents('php://input'); 
}
function RSP($ret,$msg,$result)
{
	$data = json_encode(array('ret'=>$ret,'msg'=>$msg,'result'=>$result));
	echo $data;
	_LOG("====================================");
    _LOG(_URL());
    _LOG($data);
    _LOG("=====================================");
}
function JSON_DATA()
{
	$data=json_decode(file_get_contents('php://input'),true);
	_LOG($data);
	return $data;
}

/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
function send_mail($to, $name, $subject = '', $body = '', $attachment = null){
	$config = C('THINK_EMAIL');
	vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
	$mail             =  new \PHPMailer(); //PHPMailer对象
	$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP();  // 设定使用SMTP服务
	$mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
	$mail->SMTPSecure = 'ssl';                 // 使用安全协议
	$mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
	$mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
	$mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
	$mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
	$mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
	$replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
	$replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
	$mail->AddReplyTo($replyEmail, $replyName);
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$mail->AddAddress($to, $name);
	if(is_array($attachment)){ // 添加附件
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	return $mail->send() ? true : $mail->ErrorInfo;
}
