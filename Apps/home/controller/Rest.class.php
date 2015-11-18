<?php
namespace Apps\home\controller;


class Rest extends \Core\controller\RestController{
    
	//初始化action白名单
	public function _initActions() {
	    parent::_initActions();
		$this->whiteActions[] = 'read';
        
	}
	
	//http://127.0.0.1/oneapi/index.php/home/rest/read/id/1
	public function read_get_json(){
	  echo '<br> <h2>请求参数</h2><br/>';
	  print_r($_GET);//REST 方式传递的参数放在了$_GET
	  echo '<br><h2>查询数据库</h2><br>';
	  $m = model('user');
	  $res = $m->where(array('id'=>$_GET['id']))->find();
	  dump($res);
	}
	
	
    
    
}