<?php
/**
 * 系统配置文件
 */

// 是否开启debug
define('IS_DEBUG',true);

// 当前目录的上一层目录
define('APP_PATH',dirname(__FILE__).'/..');

// 加载目录结构
$appIncludePath = APP_PATH .'/db/'. PATH_SEPARATOR .
				  APP_PATH .'/utils/'. PATH_SEPARATOR ;

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $appIncludePath);

// 开启错误报告
if(defined('IS_DEBUG') && IS_DEBUG ){
	ini_set('display_errors',1);
}

// 显示所有错误 
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

// 系统预定义常量
class CommonConst{
	const ENVIRONMENT = 'DEV';
}

// 数据库
class DbConfig{
	const  CONNECTION_TIMEOUT = 3;
	const  RETRY_TIMES = 3;
	static $arrDbServer = array(
		"Db_USER_W" => array(	
			array(
				'username' => 'root',
				'password' => '12345',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
		),
		"Db_USER_R" => array(	
			array(
				'username' => 'root',
				'password' => '12345',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
			array(
				'username' => 'root',
				'password' => '12345',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
		),
	);

}
