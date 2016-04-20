<?php 
/**
 * @author:zhaobin
 * 带smarty模板的Action类
 */

class TemplateBasedAction extends BaseAction{

	protected $smarty;

	public function initial($initObject){
		
		parent::initial($initObject);
		
		$requests = $this->requests;

		$this->smarty = ResourceFactory::getSmartyInstance();

		return true;
	}

	protected function setTplName($tplName){
		$this->context_params['tplName'] = $tplName; 
	}

	protected function getTplName(){
		return $this->context_params['tplName'];
	}

	protected function assign($key, $value){
		$this->smarty->assign($key, $value);
	}

	protected function display($tplName){
		// 预定义常量
		// Css样式等
		//$this->assign('HOST_STATIC', CommonConst::HOST_SITE);

		$this->smarty->display($tplName);
	}

	protected function fetch($tplName){
		$this->smarty->fetch($tplName);
	}
}