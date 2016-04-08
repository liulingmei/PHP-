<?php
/**
 * @author:zhaobin
 * @brief :数据库包装类
 */
require_once('DB.class.php');
class DbWrapper extends Db{
	
	// 参考父类中的Db::getInstance
	public static function getInstance($database){
		return self::_getInstance(__CLASS__, $database);
	}

	private function _getErrorMsg(){
		$str = sprintf('Module[dbproxy]    errcode[%d]  errmsg[%s]  sql[%s]',$this->getErrno(),$this->getErrorMsg(),$this->getSqlStr());
		return $str;
	}

	/**
	 *获取多条数据
	 * condition $param
	 * sort  array('id',-1);
	 */
	public function querySelectAll(array $param=array(), $table, $sort=array('id',-1), $page = 1, $pagesize = 10){
		$sqlStr =  $this->buildSelectStr($param,$table);
		$sqlStr .= $this->_querySelectSort($sort);
		$sqlStr .= $this->_querySelectLimit($page,$pagesize);
		$ret = $this->queryAllRows($sqlStr);
		if(!$ret){
			return $this->_getErrorMsg();
		}
		return $ret;
	}

	private function _querySelectSort($sort){
		$sortStr = $sort[1] > 0 ? '  ASC  '  :'  DESC  ';
		return ' ORDER BY '.$sort[0] . $sortStr;
	}
	private function _querySelectLimit($page,$pagesize){
		if(!$pagesize){
			return '';
		}
		$page = ($page -1)*$pagesize;
		return ' LIMIT '.$page .'  , ' .$pagesize;
	}
	// 获取单条数据
	public function querySelectOne(array $param=array(), $table){
		$sqlStr =  $this->buildSelectStr($param,$table);
		$ret = $this->queryFirstRow($sqlStr);
		if(!$ret){
			return $this->_getErrorMsg();
		}
		return $ret;
	}

	// 根据多条件更新
	public function queryUpdateAll(array $param, $table, $other){
		$sqlStr = $this->buildUpdateSqlStr($param,$table).' WHERE 1=1 ';
		foreach ($other as $key => $value) {
			$sqlStr .=$this->_strToWhere($key,$value);
		}
		$ret = $this->doUpdateQuery($sqlStr);
		if(!$ret){
			return $this->_getErrorMsg();
		}
		return $ret;
	}
	// 根据id更新
	public function queryUpdateById(array $param, $table){
		$id = $param['id'];
		unset($param['id']);
		$sqlStr = $this->buildUpdateSqlStr($param,$table) . 'WHERE id = '.$id;
		$ret = $this->doUpdateQuery($sqlStr);
		if(!$ret){
			return $this->_getErrorMsg();
		}
		return $ret;
	}
	
}