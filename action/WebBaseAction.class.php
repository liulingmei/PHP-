<?php 
/**
 * 要应用的模板继承此类
 * @author : zhaobin
 */

class WebBaseAction extends TemplateBasedAction{

	protected $title =  '你的标题';
	
	protected $keywords =  '你的关键词';

	protected $description = '你的描述';

	protected function buildPage(){
		$this->assign('TITLE', $this->title);
		$this->assign('KEYWORDS', $this->keywords);
		$this->assign('DESCRIPTION', $this->description);
		$this->display($this->getTplName());
	}
}