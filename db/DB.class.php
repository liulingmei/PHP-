<?php 
/**
 * @author:zhaobin
 * @brief :数据库操作类
 */

class Db {

    //mysqli instance
    protected $mysqli  = null;

    protected $dbname  = '';

    protected $config  = '';

    protected $lastSql = '';


    protected static $instances = array();

    protected static $database = NULL;


    public function __construct($dbname,array $config){
        $this->dbname  = $config['db']; 
        $this->config  = $config;
        $this->lastSql = '';
        $this->mysqli  = $this->createConnection();

    }

    public static function getInstance($database) {
    	return self::_getInstance(__CLASS__, $database);
    }

    protected static function _getInstance($klass, $database) {
        if(CommonConst::ENVIRONMENT == 'AUTO_TESTING') {
            // 自动化检测环境,让所有的事务都在一个集群中使用,保证所有的操作都在一个集群中    
            self::$database = $database;
            if(!isset(self::$instances[$klass]) || !self::$instances[$klass]->ping()){
		self::$instance[$klass] = self::createInstance($klass,$database);
            }else {
                if(isset(Dbconfig::$arrDbServer[$database])){
		  $clusterInfo = DbConfig::$arrDbServer[$database];
		}
                if(is_array($clusterInfo)) {
	          $index = array_rand($clusterInfo);
                  $dbConfig = $clusterInfo[$index];
                }
                self::$instances[$klass]->selectDb($dbConfig['db']);	
            }
            return self::$intances[$klass];
        }else{
            // 其他环境使用集群
            if(!isset(self::$instances[$klass])) {
                self::$instances = array();
            }
            if(!isset(self::$instances[$klass][$database]) || !self::$instances[$klass][$database]->ping()) {
                self::$instances[$klass][$database] = self::createInstance($klass, $database);
            }

            return self::$instances[$klass][$database];
        }
    }

    // ping

    public function ping() {
        if(!$this->mysqli->ping()) {
            $this->close();
            $this->mysqli = $this->createConnection();
        }
        return true;
    }

    // close

    public function close() {
        if($this->mysqli) {
            $this->mysqli->close();
            $this->mysqli = false;
        }
    }

    /**
     * 1. 优先尝试本机房
     * 2. 一个机房最多试 config['retry_times_per_idc']
     * 3. 总共最多试 config['retry_times']
     */

    protected function createConnection(){
        if($this->mysqli) {
            return $this->mysqli;
        }
        $mysqli = mysqli_init();

        if(isset($this->config['connection_timeout'])) {
            $mysqli->options(
                    MYSQLI_OPT_CONNECT_TIMEOUT,
                    $this->config['connection_timeout']
                    );
        }
        $total_tries = 0;
        while($total_tries < $this->config['retry_times']){
            if($mysqli->real_connect($config['host'],
                                     $config['username'],
                                     $config['password'],
                                     $this->dbname,
                                     $config['port'])){
                //  log
                $total_tries++;
                continue;
            }else{
                break;
            }
        }
        if(!$mysqli->set_charset($this->config['charset'])){
            // log
            return false;
        }
        if(!$mysqli->select_db($this->dbname)){
            //log
            return false;
        }
        return $mysqli;
    }


    protected static function createInstance($klass, $database){
        if(isset(DbConfig::$arrDbServer[$database])) {
            $clusterInfo = DbConfig::$arrDbServer[$database];
        }
        if(is_array($clusterInfo)) {
            $index = array_rand($clusterInfo);  
            $dbConfig =  $clusterInfo[$index];
        }

        $charset = 'utf8mb4';

        if(isset($dbConfig['host'])) {
            $connectionTimeout = defined('DbConfig::CONNECTION_TIMEOUT') ? DbConfig::CONNECTION_TIMEOUT : 3;

            $config = array(
                'username' => $dbConfig['username'],
                'password' => $dbConfig['password'],
                'port' => $dbConfig['port'],
                'host'  => $dbConfig['host'],
                'db' => $dbConfig['db'],
                'charset' => $charset,
                'retry_times' => DbConfig::RETRY_TIMES,
                'connection_timeout' => $connectionTimeout,
                );

                $db = new $klass($database, $config);
                if($db->isOK()){
                    return $db;
                }
        }
        return false;
    }

    // 查看是否工作
    public function isOK(){
        return !empty($this->mysqli);
    }

    public function selectDb($dbname){
        return $this->mysqli->select_db($dbname);
    }
}
