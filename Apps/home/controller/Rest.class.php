<?php
namespace Apps\home\controller;


class Rest extends \Core\controller\RestController{
    
	//初始化action白名单
	public function _initActions() {
	    parent::_initActions();
		$this->whiteActions[] = 'read';
        
	}
	
	public function read_get_json(){
	  $m = model('user');
	  $res = $m->where(array('id'=>1))->find();
	  dump($res);
	}
	
	
    
    
}