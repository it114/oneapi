<?php
namespace Apps\home\controller;

use \Core\controller\ApiController;

class Fake extends ApiController{
    
	//初始化action白名单
	public function _initActions(){
	    parent::_initActions();
		$this->whiteActions[] = 'index';
        
	}
	
	public function index(){
	   echo 'hello index';
	}
	
	
    
    
}