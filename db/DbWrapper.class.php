<?php
/**
 * @author:zhaobin
 * @brief :数据库包装类
 */

class DbWrapper extends Db{
	
	// 参考父类中的Db::getInstance
	public static function getInstance($database){
		return self::_getInstance(__CLASS__, $database);
	}

	
}