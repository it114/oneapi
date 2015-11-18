<?php

namespace Core\util;

class Router
{
    public   $app;
    public   $controller;
    public   $action;
    private  $routeType;
    
    public function __construct($routeType) {
        $this->routeType = $routeType;
    }
    
    public function start(){
        if(empty($this->routeType)) {//默认兼容模式
         echo '1';   $this->routeType = 1;
        }
        if($this->routeType == 1) {//r=app/controller/action形式
           $route = Request::getGet('r'); 
           $route = explode('/', $route);
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
        } else if($this->routeType ==2) {//其他方式。。。
            //获取出除域名和参数之外的路径 ，例如 /oneapi/index.php
            $script_name = Request::getScriptUrl();
            //获取完整的路径，包含"?"之后的字符串/demo/index.php/group/module... ,eg :/oneapi/index.php/home/db/index
            $uri = Request::getRequestUri();
            
            //去除url包含的当前文件的路径信息,只截取文件后的参数
            if ( $uri && @strpos($uri,$script_name,0) !== false ){
                $uri = substr($uri, strlen($script_name));///home/db/index
            } else {
                //网址中省略引导文件名时
                $script_name = dirname($_SERVER['SCRIPT_NAME']);
                if ( $uri && @strpos($uri, $script_name, 0) !== false ){
                    $uri = substr($uri, strlen($script_name));
                }
            }
            
            $uri = ltrim($uri, '/');
            //去除问号后面的查询字符串
            if ( $uri && false !== ($pos = @strrpos($uri, '?')) ) {
                $uri = substr($uri,0,$pos);
            }
            
            //分割数组
            $uriInfoArray = explode('/', $uri);//'/'可以替换为其他的url分隔符           
            
            //去除后缀例如.html .json等的名称
            $pathinfo = trim($_SERVER['PATH_INFO'],'/');
            if($pathinfo) {
                $suffix = strtolower(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION));
            }
            if($suffix) {
                Config::set('url.url_suffix', $suffix);
            } 
            if ($uri&&($pos = strrpos($uri,Config::get('url.url_suffix'))) > 0) {
                $url = substr($url,0,$pos);
            }
            
            //解析app 、controller、method和参数（放入$_GET）
            if (isset($uriInfoArray[0]) && $uriInfoArray[0] == true) {
                //获取controller及action名称
                $this->app = $uriInfoArray[0];
                $this->controller    = (isset($uriInfoArray[1]) && $uriInfoArray[1] == true) ? $uriInfoArray[1] : Config::get('url.default_controller');
                $this->action    = (isset($uriInfoArray[2]) && $uriInfoArray[2] == true) ? $uriInfoArray[2] : Config::get('url.default_action');
                
                //变量重组,将网址(URL)中的参数变量及其值赋值到$_GET全局超级变量数组中
                if (($totalNum = sizeof($uriInfoArray)) > 4) { //dump($uriInfoArray);
                    for ($i = 3; $i < $totalNum; $i += 2) {
                        if (!isset($uriInfoArray[$i]) || !$uriInfoArray[$i] || !isset($uriInfoArray[$i + 1])) {
                            continue;
                        }
                        $_GET[$uriInfoArray[$i]] = $uriInfoArray[$i + 1];//dump($_GET);
                    }
                }
            }            
        }
        //统一处理
        if(!$this->app) {//app
            $this->app = Config::get('url.default_app');
        }
        if(!$this->controller) {//控制器
            $this->controller = Config::get('url.default_controller');
        }
        if(!$this->action) {//动作
            $this->action = Config::get('url.default_action');
        }
        //定义
        if( !defined('APP_NAME') ) define('APP_NAME', strtolower($this->app)); 
        if( !defined('CONTROLLER_NAME') ) define('CONTROLLER_NAME', ucfirst($this->controller));
        if( !defined('ACTION_NAME') ) define('ACTION_NAME', $this->action);
        
    }
    
}