<?php 
/**
 * @author :zhaobin
 * @birfe  :日志记录类
 */

class CLog{

	private static $instance = NULL;

	protected $type;

	protected $level;

	protected $path;

	protected $filename;

	protected $startTime;

	protected $clientIP; 

	// Log level definition
	const LOG_LEVEL_NONE    = 0x00;
	const LOG_LEVEL_FATAL   = 0x01;
	const LOG_LEVEL_WARNING = 0x02;
	const LOG_LEVEL_NOTICE  = 0x04;
	const LOG_LEVEL_TRACE   = 0x08;
	const LOG_LEVEL_DEBUG   = 0x10;
	const LOG_LEVEL_ALL     = 0xFF;


	public static $logLevelMap = array(
		self::LOG_LEVEL_NONE    => 'NONE',
		self::LOG_LEVEL_FATAL   => 'FATAL',
		self::LOG_LEVEL_WARNING => 'WARNING',
		self::LOG_LEVEL_NOTICE  => 'NOTICE',
		self::LOG_LEVEL_TRACE	=> 'TRACE',
		self::LOG_LEVEL_DEBUG   => 'DEBUG',
		self::LOG_LEVEL_ALL     => 'ALL',
	);

	const LOG_TYPE_LOCALLOG	= 'LOCAL_LOG';

	private function __construct(array $conf, $startTime){
		$this->type  = $conf['type'];
		$this->level = $conf['level'];
		$this->path  = $conf['path'];
		$this->filename = $conf['filename'];
	
		$this->startTime = $startTime;
		$this->logId = $this->__logId();
		$this->clientIp = Utils::getClientIP();
	}

	public function getInstance(){
		if(!isset($instance)){
			$startTime = microtime(true) * 1000;
			self::$instance = new CLog($GLOBALS['LOG'],$startTime);
		}
		return self::$instance;
	}

	public static function debug(){
		$args = func_get_args();
		return CLog::getInstance()->writeLog(self::LOG_LEVEL_DEBUG,$args);

	}

	public static function trace(){
		$args = func_get_args();
		return CLog::getInstance()->writeLog(self::LOG_LEVEL_TRACE,$args);
	}

	public static function notice(){
		$args = func_get_args();
		return CLog::getInstance()->writeLog(self::LOG_LEVEL_NOTICE,$args);
	}

	public static function warning(){
		$args = func_get_args();
		return CLog::getInstance()->writeLog(self::LOG_LEVEL_WARNING,$args);
	}

	public static function fatal(){
		$args = func_get_args();
		return CLog::getInstance()->writeLog(self::LOG_LEVEL_FATAL,$args);
	}

	public static function logId(){
		return CLog::getInstance()->logId;
	}

	public static function setLogId($logId){
		CLog::getInstance()->logId = $logId;
	}

	protected function writeLog($level , array $args){
		if($level > $this->level || !isset(self::$logLevelMap[$level])){
			return 0;
		}

		$timeUsed = microtime(true) * 1000 - $this->startTime;
		$fmt = array_shift($args);
		$str = vsprintf($fmt, $args);

		if($level == self::LOG_LEVEL_NOTICE || $level == self::LOG_LEVEL_TRACE) {
			$str = sprintf("%s:@@%s@@host[%s]@@ip[%s]@@logId[%s]@@uri[%s]@@time_used[%d]@@%s\n",
				self::$logLevelMap[$level],
				date('Y-m-d H:i:s',time()),
				$_SERVER['HTTP_HOST'],
				$this->clientIp,
				$this->logId,
				isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
				$timeUsed,
				$str);
		}else{
			$str = sprintf("%s: %s host[%s] ip[%s] logId[%s] time_used[%d] %s\n",
				self::$logLevelMap[$level],
				date('Y-m-d H:i:s',time()),
				$_SERVER['HTTP_HOST'],
				$this->clientIp,
				$this->logId,
				isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
				$timeUsed,
				$str);
		}

		if($this->type === self::LOG_TYPE_LOCALLOG){
			$filename = $this->path . '/' .$this->filename;
			if($level < self::LOG_LEVEL_NOTICE){
				$filename = $filename.'.wf';
			}

			// mkdir
			Utils::mkdirs($this->path);

			file_put_contents($filename, $str, FILE_APPEND | LOCK_EX);

			@chmod($filename, 0777);
		}
	}

	private function __logId(){
		$arr = gettimeofday();
		return (($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF);
	}  
}