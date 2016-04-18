<?php
/**
 * @author:zhaobin
 * @brief :action基础类
 */

class Context{

	protected $actions = array();
	protected $rootAction;

	private $actionStack;
	private $actionExecedStack;
	private $actionExecedStackPos;

	public function initial(array $rootActionConfig,$isDebug = false){
		
		if(count($rootActionConfig) < 2){
			$errmsg = 'Root Action Config invalid'.var_export($rootActionConfig,true);
			trigger_error($errmsg,E_USER_ERROR);
			return false;
		}

		// Debug
		$this->isDebug = $isDebug;
		$this->actionStack = array();
		$this->actionExecedStack = array();
		$this->actionExecedStackPos = 1;

		$action = $this->getAction($rootActionConfig[0],$rootActionConfig[1]);
		
		if(!$action){
			$errmsg = 'Create root Action failed:actionName['.var_export($rootActionConfig[0],true).']';
			trigger_error($errmsg,E_USER_ERROR);
			return false;
		}

		$this->rootAction = $action;
		return true;

	}

	public function getAction($actionClassName, $initObject = NULL){
		if(array_key_exists($actionClassName, $this->actions)){
			return $this->actions[$actionClassName];
		}

		$action = Action::getAction($actionClassName, $initObject);

		if(!$action){
			return NULL;
		}
		$this->actions[$actionClassName] = $action;
		return $action;
	}

	public function callAction($actionClassName, array $actionParams = array()){
		$action = $this->getAction($actionClassName);
		
		if(!$action){
			return false;
		}

		$ret = $action->execute($this,$actionParams);

		return $ret;
	}


	public function callRootAction(array $actionParams = array()){
		return $this->callAction($this->rootAction->actionClassName , $actionParams);
	}
}