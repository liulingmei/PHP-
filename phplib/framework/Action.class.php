<?php 

abstract class Action{
	public $actionClassName;

	public static function getAction($actionClassName, $initObject){
		if(!is_string($actionClassName) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $actionClassName)){
			$errmsg = 'Action Class Name invalid :['.var_export($actionClassName,true).']';
			trigger_error($errmsg,E_USER_ERROR);
			return null;
		}

		$actionObject = new $actionClassName;
		if(!($actionObject instanceof Action)){
			$errmsg = 'The Object is not an Action Class:actionClassName['.var_export($actionClassName,true).']';
			trigger_error($errmsg,E_USER_ERROR);
			return null; 
		}
		$actionObject->actionClassName = $actionClassName;

		if(!true == $actionObject->initial($initObject)){
			$errmsg = 'Failed to initial action:actionClassName['.var_export($actionClassName,true).']';
			trigger_error($errmsg,E_USER_ERROR);
			return null;
		}

		return $actionObject;
	}

	public abstract function execute(context $context,array $actionParams = array());
}