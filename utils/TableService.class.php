<?php
/**
 *@author:zhaobin
 *
 */

class TableService {

	//获取数据库实例,是否分布
	public function getDbName($isMaster=false, $hashKey=NULL,$db='Db_USER'){
		$table_suffix = "";
		// 
		if($hashKey !== NULL){
			$dbNO = Utils::getHash($hashKey, DbConfig::DB_SPLIT_NUM);
			$db .= '_'.$dbNO;
			$table_suffix .='_'.$dbNO; 
		}

		if($isMaster){
			$db .="_W";
		}else{
			$db .='_R';
		}
		return array('db'=>$db,"table_suffix"=>$table_suffix);
	}
}