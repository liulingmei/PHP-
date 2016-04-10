<?php
/**
 * UserService
 *@author:zhaobin
 */

class UserService {
	private static $instance = NULL;
	private $UserDao = '';

	protected function __construct(){
		$this->UserDao = UserDao::getInstance();
	}

	public function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new UserService;
		}
		return self::$instance;
	}


	// check
	/**
	 * usernmae password age sex
	 */
	private  function _validData($condition){
		if($condition['username']  && false == Utils::check_string($condition['username'],25,1)){
			throw new Exception("input username[".$condition['username']."] not valid");
		}
		if($condition['password'] && false == Utils::check_string($condition['password'],32,1)){
			throw new Exception("input password[".$condition['password']."] not vaild");
		}
		if($condition['sex']  && false == Utils::check_string($condition['sex'],6,1)){
			throw new Exception("input sex [".$condition['sex']."] not vaild" );
		}
		if($condition['age'] && false == Utils::chcek_int($condition['age'])){
			throw new Exception("input age [".$condition['age']."] not vaild");
		}
	}

	public function selectUser($condition,$page =1,$pagesize =10,$sort=array('id',-1)){
		$this->_validData($condition);
		$res = $this->UserDao->select($condition,$page,$pagesize,$sort);
		return $res;
	}
}