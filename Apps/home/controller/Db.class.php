<?php
namespace Apps\home\controller;


class Db extends \Core\controller\ApiController{
    
	//初始化action白名单
	public function _initActions(){
	    parent::_initActions();
		$this->whiteActions[] = 'index';
        
	}
	
	public function index(){
	  $m = model('user');
	  $res = $m->where(array('id'=>1))->find();
	  dump($res);
	}
	
	
    
    
}