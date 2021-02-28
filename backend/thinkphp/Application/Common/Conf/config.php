<?php
return array(
	'DB_TYPE'  => 'mysql',// 数据库类型 
	'DB_HOST'  => '127.0.0.1',// 数据库服务器地址
	'DB_NAME'  => 'release_welaine',// 数据库名称
	'DB_USER'  => 'welaine',// 数据库用户名
	'DB_PWD'  => 'weweliane',// 数据库密码
	'DB_PREFIX'  => 'ecm_',// 数据表前缀
	'DB_CHARSET' => 'utf8',// 网站编码
	'DB_PORT'  => '3306',// 数据库端口
	'APP_DEBUG'     =>  false,// 开启调试模式
    'THINK_EMAIL' => array(
		'SMTP_HOST'   => 'ssl://smtp.163.com', //SMTP服务器
		'SMTP_PORT'   => '465', //SMTP服务器端口
//		'SMTP_USER'   => '122202182@qq.com', //SMTP服务器用户名
//		'SMTP_PASS'   => '113172721@QQ.com', //SMTP服务器密码
		'SMTP_USER'   => '13316825572@163.com', //SMTP服务器用户名
		'SMTP_PASS'   => 'aA_123123', //SMTP服务器密码
		'FROM_EMAIL'  => '13316825572@163.com', //发件人EMAIL
		'FROM_NAME'   => 'hunter', //发件人名称
		'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
		'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
	)
);

