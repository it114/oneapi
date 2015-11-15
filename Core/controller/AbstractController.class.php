<?php
namespace Core\controller;

use Core\util\Response;

/**
 * 抽象控制器，抽取通用逻辑
 * @author andy
 * TODO 通用缓存逻辑写这里
 */
abstract  class AbstractController 
{

    protected $whiteActions = array();
    
    abstract public function _initActions();
    
    public function _valid_action( $action ) {
        if( !in_array( $action, $this->whiteActions) ) {
            exit( 'action:'.$action.' is not exists !' );
        }
    }
    
    abstract public  function _initAuth();
    
    public function _empty(){
        echo '404 not found !';
    }
    
    /**
     * 返回字符串
     * @param unknown $code 状态码
     * @param unknown $msg 消息
     * @param unknown $data 返回数据
     * @param string $type  返回数据类型
     */
    protected function responseString( $code, $msg, $data,$type='json') {
       if(!$type) $type ='json';
       switch ($type) {
           case 'json':
               Response::jsonResponse($code,$msg,$data);
               break;
       }
       
       trigger_error('unkonwn return data type :'.$type);
    }
    
    
}