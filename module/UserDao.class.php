<?php
/**
 *@author:zhaobin
 * UserDao
 */
class UserDao {
	private static $instance = NULL;
	
	private $tableName = 'user';
	private function __clone(){}

	// 防止实例化
	private function __construct(){
	}

	public function getInstance(){
		if(!isset(self::$instance)){
			$class = __CLASS__;
			self::$instance = new $class;
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
		$this->mysql = DbWrapper::getInstance($this->dbArr['db']);
	}
	/**
	 * insert demo
	 * 根据id分库分表
	 */
             public function insetCommon($id,$condition){
             	$arr = $this->getDb(true, $id);
             	$strSql = $this->mysql->buildInsertSqlStr($condition,$this->tableName.$arr['table_suffix']);
             	$res = $this->mysql->doUpdateQuery($strSql);
             	return $res;
             }
             // select 
             public function select($condition,$page= 0,$pagesize=0,$sort=array('id',-1)){
             	$arr = $this->getDb(false);
             	$res =$this->mysql->querySelectAll($condition,$this->tableName,$sort,$page,$pagesize);
             	return $res;
             }
             // update
             public function update($condition){
             	$arr = $this->getDb(true);
             	$res = $this->mysql->queryUpdateById($condition,$this->tableName);
             	return $res;
             }
}
