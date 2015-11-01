<?php

namespace Core\util;

class Route 
{
    public   $app;
    public   $controller;
    public   $action;
    private  $routeType;
    
    public function __construct($routeType) {
        $this->routeType = $routeType;
    }
    
    public function init(){
        if(empty($this->routeType)) {//默认兼容模式
           $route = Request::getGet('r'); 
           $len  = count($route);
           if($len == 1){
               $this->app = $route[0];
           }
           if($len == 2){
               $this->app = $route[0];
               $this->controller = $route[1];
           }
           if($len == 3){
               $this->app = $route[0];
               $this->controller = $route[1];
               $this->action =  $route[2];
           }
        } else if($this->routeType == 'pathinfo ') {//其他方式。。。
            
        }
        die('fdsfdsfdsdsds');
        //统一处理
        if(!$this->app) {//app
            $this->app = 'user';
        }
        if(!$this->controller) {//控制器
            $this->controller = 'default';
        }
        if(!$this->action) {//动作
            $this->action = 'default';
        }
        //定义
        if( !defined('APP_NAME') ) define('APP_NAME', strtolower($this->app)); 
        if( !defined('CONTROLLER_NAME') ) define('CONTROLLER_NAME', ucfirst($this->controller));
        if( !defined('ACTION_NAME') ) define('ACTION_NAME', $this->action);
        
    }
    
}