<?php
namespace Apps\user\controller;

use \Core\controller\ApiController;

class indexController extends ApiController{
    
	//初始化action白名单
	public function _initActions(){
		$this->whiteActions[] = 'index';
        
	}
	
	public function index(){
	   echo 'hello';
	}
    
    
}