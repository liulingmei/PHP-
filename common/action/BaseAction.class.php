<?php  
/**
 * Action基础类
 * @author:zhaobin
 */

class BaseAction extends Action{
	
	protected $requests = array();

	public function initial($initObject){
		$this->requests = array_merge($_GET,$_POST);
		return true;
	}

	public function execute(Context $context, array $actionParams = array()){
		try{
			$ret = $this->doExecute();
		}catch(Exception $e){
			CLog::warning('class[%s] msg[Exception caught when execute] errmsg[%s] line[%d] file[%s]',get_class($this),$e->getMessage(), $e->getLine(), $e->getFile());
			$this -> setErr($e -> getCode(), $e -> getMessage());
			trigger_error('Exception caught when execute ['
				. get_class($this) . '] errmsg: ' . $e->getMessage() . ' line ' . $e->getLine() . ' file ' . $e->getFile());
		}
		return $ret;
	}

	protected function setErr($errCode,$errMsg){
		$this->context_params['errno'] = $errno;
		$this->context_params['errmsg'] = $errmsg;
	}

	protected function doExecute(){
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				return $this->doPost();

			case 'PUT':
				return $this->doPut();

			case 'DELETE':
				return $this->doDelete();

			default:
				return $this->doGet();
		}
	}

	protected function doPost(){
		return true;
	}

	protected function doPut(){
		return true;
	}

	protected function doDelete(){
		return true;
	}

	protected function doGet(){
		return true;
	}
}