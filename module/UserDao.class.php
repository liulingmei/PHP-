<?php
/**
 *@author:zhaobin
 * UserDao
 */
class UserDao {
	private static $instance = NULL;
	
	private function __clone(){}

	// 防止实例化
	private function __construct(){}

	public function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new static;
		}
		return self::$instance;
	}

	/**
	 * 链接数据库
	 * @param bool $isMaster 是否需要主库
	 * @param null $hash 分库依据
	 * @return 分表分库数据
	 */ 	
	private function getDb($isMaster = false ,$hash = NULL){
		$this->dbArr = TableService::getDbName($isMaster,$hash,'Db_USER');
		$this->mysql = DbWrapper::instance($this->dbArr['db']);
	}
	/**
	 * insert demo
	 * 根据id分库
	 */
             public function insetCommon($id,$condition){
             	$arr = $this->getDb(true, $id);
             	$strSql = $this->mysql->buildInsertSqlStr($condition,'user'.$arr['table_suffix']);
             	$res = $this->mysql->doUpdateQuery($strSql);
             	if($res){
             		return true;
             	}else{
             		$error = sprintf('Module[dbproxy]    errcode[%d]  errmsg[%s]  sql[%s]',$this->getErrno(),$this->getErrorMsg(),$this->getSqlStr());
             		throw new Exception($error);
             	}
             }
}
