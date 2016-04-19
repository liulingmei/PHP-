<?php 

class Test_ActionIndex extends WebBaseAction {

	public function doGet(){
		$this->assign('name','Carrera');
		$this->setTplName('test/test.tpl');
        $this->buildPage();
	}
}