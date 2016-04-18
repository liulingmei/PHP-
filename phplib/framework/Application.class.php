<?php 
/**
 * @brief Application入口
 * @author: zhaobin
 */
class Application{

	protected $rootActionConfig;

	protected $context;


	public function __construct(){
		$this->context = new Context();
	}

	public function setRootActionConfig(array $rootActionConfig){
		$this->rootActionConfig = $rootActionConfig;
		return $this;
	}


	public function getRootActionConfig(){
		return $this->rootActionConfig;
	}

	public function getContext(){
		return $this->context;
	}

	public function setContext(Context $context){
		$this->context = $context;
		return $this;
	}

	public function execute($isDebug=false){
		if($this->context->initial($this->rootActionConfig,$isDebug)){
			return $this->context->callRootAction();
		}else{
			$errmsg = 'inintial the action context failed: rootActionConfig['.var_export($this->rootActionConfig,true).']';
			trigger_error($errmsg,E_USER_ERROR);
			return false;
		}
	}
}