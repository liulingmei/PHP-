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

    public function __destruct(){
        if($this->mysqli){
            $this->mysqli->close();
        }
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

    // 获取mysqli链接
    public function getHeadle(){
        return $this->mysqli;
    }

    // 查看是否工作
    public function isOK(){
        return !empty($this->mysqli);
    }

    public function selectDb($dbname){
        return $this->mysqli->select_db($dbname);
    }
    
    // 开启事务
    public function startTransaction(){

        $sql = 'START TRANSACTION';
        return $this->mysqli->query($sql);
    }

    // 回滚
    public function rollback(){
        return $this->mysqli->rollback();
    }

    // 提交事务
    public function commit(){
        return $this->mysqli->commit();
    }

    // check字符串
    public function realEscapeString($string){
        if($this->mysqli){
            return false;
        }
        if(is_array($string) || is_array($string) || is_object($string)){
            return false;
        }else{
            $string = "'". $this->mysqli->real_escape_string($string). "'";
        }
        return $string;

    }

    // 获取mysql错误代码
    public function getErrno(){
        if($this->mysqli){
            return -1;
        }
        return $this->mysqli->error;
    }

    //获取mysql错误信息
    public function getErrorMsg(){
        if($this->mysqli){
            return 'mysql server not available';
        }else{
            $host = $this->config['host'].':'.$this->config['port'];
            return $host.' , '.$this->mysqli->error; 
        }
    }

    //获取lastinsertid
    public function getLastInsertID(){
        return mysql_insert_id($this->mysqli);
    }

    public function insert(array $arrFields=array(), $table, $replace = false){
        if(!$this->mysqli || count($arrFields) < 0 ) {
            return false;
        }

        $this->ping();

        if($replace){
            $this->lastSql = 'REPLACE INTO '.$table.' ('; 
        } else{
            $this->lastSql = 'INSERT INTO '.$table.' (';
        }

        $strValue = '';
        $needComma = false;
        foreach ($arrFields as $field => $value) {
            if ($needComma) {
                $this->lastSql .= ',';
                $strValues .= ',';
            }
            $needComma = true;
            $this->lastSql .= '`'.$field.'`';
            if(is_string($value)){
                $strValues .= $this->mysqli->real_escape_string($value);
            }else if(is_null($value) || is_array($value) || ){
                continue;
            }else{
                $strValues .= "'$value'"; 
            }
            

        }
        $this->lastSql .= ') VALUES ('.$strValues.')';
        
        // log output sql
        
        $ret = $this->mysqli->query($this->lastSql);

        if(!$ret) {
            return false;
        }

        return true;
    }
    /**
     * 拼接Upadte的sql
     */
    public function buildUpdateSqlStr($arrFields, $table) {
        if(!$this->mysqli  || count($arrFields) < 0){
            return false;
        }

        $this->lastSql = 'UPDATE ' .$table .' SET ';

        $needComma = false;
        foreach ($arrFields as $field => $value) {
            
            if(is_null($value) || is_object($value) || is_array($value)) {
                continue;
            }
            if($needComma) {
                $this->lastSql .= ' AND ';
            }

            $needComma = true;

            $this->lastSql .= '`'.$field.'`' .'='. $this->mysqli->real_escape_string($value); 
        }

        return $this->lastSql;

    }

    /**
     * 拼接insert sql
     */

    public function buildInsertSqlStr($arrFields, $table, $replace = false){

        if($replace){
            $this->lastSql = 'INSERT INTO '.$table . '(';
        }else{
            $this->lastSql = 'REPLACE INTO '.$table . '(';
        }

        $needComma = false;
        $strValues = '';
        foreach ($arrFields as $field => $value) {
            if($needComma){
                $this->lastSql .= ',';
                $strValues .= ','; 
            }
            $needComma = true;
            $this->lastSql .= '`'.$field.'`';
            
            if(is_string($value)){
                $strValues .= "'".$this->mysqli->real_escape_string($value)."'";
            }else if(is_array($value) || is_object($value) || is_null($value)){
                continue;
            }else{
                $strValues .= "'$value'";
            }
            
        }

        $this->lastSql .= ' ) VALUES ( '.$strValues .')';
        return $this->lastSql;
    }

    // 执行获取单条数据
    public function queryFirstRow($strSql){
        
        if(!$this->mysqli){
            return false;
        }
        $this->ping();

        $obj = $this->mysqli->query($strSql);

        if(!$obj){
            return false;
        }

        $result = $obj->fetch_assoc();

        if($result){
            return $result;
        }
        return array();
    }

    // 执行获取多条数据
    public function queryAllRows($strSql){

        if(!$this->mysqli){
            return false;
        }

        $this->ping();

        $obj = $this->mysqli->query($strSql);

        if(!$obj){
            return false;
        }
        $result = array();
        while($row=$obj->fetch_assoc()){
            $result[] = $row; 
        }

        return $result;
    }

    //


    // 执行mysql
    public function doUpdateQuery($strSql){
        if(!$this->mysqli){
            return false;
        }

        $this->ping();

        $this->lastSql = $strSql;

        $result = $this->mysqli->query($this->lastSql);

        return $result;
    }

}
