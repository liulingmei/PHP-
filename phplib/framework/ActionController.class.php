<?php 
/**
 * @author:zhaobin
 * @brief:操作控制器 提供URI路由行动
 */

class ActionController extends Action{

	protected $hashMapping    = array();
	protected $ruleConfig     = array();
	protected $prefixMapping  = array();
	protected $regexMapping   = array();

	public function initial(array $config){
		$this->hashMapping   = isset($config['hash_mapping'])   ? $config['hash_mapping']   : array(); 
		$this->ruleConfig    = isset($config['ruleconfig'])     ? $config['ruleconfig']    : array();
		$this->prefixMapping = isset($config['prefixmapping'])  ? $config['prefixmapping'] : array();
		$this->regexMapping  = isset($config['regexmapping'])   ? $config['regexmapping']  : array();
		return true;
	}

	public function execute(Context $context, array $actionParams = array()){
		$info = $this->getDispatchedActionInfo($context);
		if($info){
			return $context->callAction($info[0]->actionClassName, $actionParams);
		}
		return false;
	}

	private function getDispatchedActionInfo(Context $context){
		if(!empty($_SERVER['PATH_INFO'])){
			$uri = $_SERVER['PATH_INFO'];
		}else if(isset($_SERVER['REQUEST_URI'])){
			$uri = $_SERVER['REQUEST_URI'];	
		}else{
			$uri = '';
		}
		//echo $uri;exit;
		$ignoreDirs = isset($this->ruleConfig['begindex']) ? intval($this->ruleConfig['begindex']) : 0;
		$parsedUri  = $this->parseRequestUri($uri, $ignoreDirs);
		
		// 去除parseUri中的dir_suffix
		if(isset($this->ruleConfig['dir_suffix'])){
			$parsedUri = str_replace($this->ruleConfig['dir_suffix'], '', $parsedUri);
		}

		if(isset($this->hashMapping[$parsedUri])){
			$actionConfig = $this->hashMapping[$parsedUri];
			$actionParams = isset($actionConfig[1]) ? $actionConfig[1] : array();
			$action = $context->getAction($actionConfig[0], $actionParams);
			return array($action, null);
		}

		header('HTTP/1.1 404 Not Found');
		exit;
	}

	private function parseRequestUri($uri, $ignoreDirs = 0){
		if(!isset($ignoreDirs) || $ignoreDirs < 0){
			$ignoreDirs = 0;
		}
		$path = explode('?', $uri);
		$path = explode('/', $path[0]);
		$dirs = array();

		foreach ($path as $v) {
		 	$v = trim($v);
		 	if($v == ''){
		 		continue;
		 	}
		 	$dirs[]  = $v;
		}

		$dirs = array_slice($dirs, $ignoreDirs);
		$uri = '/' . implode('/', $dirs);
		return strtolower($uri);
	}
}