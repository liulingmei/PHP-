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
		    APP_PATH .'/module'.PATH_SEPARATOR.
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
             // 默认分为几个库
	const  DB_SPLIT_NUM = 6;
	static $arrDbServer = array(
		// 读写分离
		"Db_USER_W" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
		),
		"Db_USER_R" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'test',
			),
		),

		// mysql 分库
		'Db_USER_0_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_0'
			),
		),
		'Db_USER_1_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_1'
			),
		),
		'Db_USER_2_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_2'
			),
		),
		'Db_USER_3_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_3'
			),
		),
		'Db_USER_4_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_4'
			),
		),
		'Db_USER_5_W'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_5'
			),	
		),

		'Db_USER_0_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_0'
			),
		),
		'Db_USER_1_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_1'
			),
		),
		'Db_USER_2_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_2'
			),
		),
		'Db_USER_3_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_3'
			),
		),
		'Db_USER_4_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_4'
			),
		),
		'Db_USER_5_R'=>array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port'=>3306,
				'host'=>'127.0.0.1',
				'db=>test_5'
			),	
		),
	);
}
