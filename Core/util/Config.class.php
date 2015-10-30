<?php

namespace Core\util;

class Config 
{
    protected $_level;//配置文件级别，系统级别还是app级别。app可以覆盖系统级别的。可选值 app 、sys
    protected $_env;//开发环境是发布环境还是调试环境 有两种 dev 和 release
    protected static $_configValues; //全局的配置
    protected $_configContent;
    //系统
    public function __construct($env) 
    {
        if(!in_array($env, array('dev','release'))) {
            trigger_error('env is invalid ,require app or sys .');
        }
        $this->_env = $env;
        $this->init();
    }
    
    protected function init(){
        //load system config file content .
        $sysFile = STORAGE_PATH.'config'.DIRECTORY_SEPARATOR.'config.php';
        if(is_file($sysFile)) {
            $content =  require $sysFile;
            if(array_key_exists($this->_env, $content)) {
               self::$_configValues = $content[$this->_env]; 
            }
        }
            
        $file = APPS_PATH.APP_NAME.DIRECTORY_SEPARATOR.'config.php';
        if(is_file($file)) {
            $content =  require $file;
            if(array_key_exists($this->_env, $content)) {
                $appConfig = $content[$this->_env]; 
                if(isset(self::$_configValues)) {
                    //app中的配置覆盖系统的默认配置不支持二维数组
                    foreach($appConfig as $k=>$v){
                        if(isset(self::$_configValues[$k])) {
                            self::$_configValues[$k] = $v;//app配置覆盖系统配置
                        }
                    }
                } else {
                    self::$_configValues = $appConfig;
                }
            }
        }
    }
    
    public static function set($key, $value){
        $arr = explode('.', $key);
        switch(count($arr)){
            case 1 :
                self::$_configValues[ $arr[0] ] = $value;
                break;
            case 2 :
                self::$_configValues[ $arr[0] ][ $arr[1] ] = $value;
                break;
            case 3 :
                self::$_configValues[ $arr[0] ][ $arr[1] ][ $arr[2] ] = $value;
                break;
            default: return false;
        }
        return true;
    }
    
    public static function get($key=NULL){
        if( empty($key)) return self::$_configValues;
        $arr = explode('.', $key);
        switch(count($arr)){
            case 1 :
                if( isset(self::$_configValues[ $arr[0] ])) {
                    return self::$_configValues[ $arr[0] ];
                }
                break;
            case 2 :
                if( isset(self::$_configValues[ $arr[0] ][ $arr[1] ])) {
                    return self::$_configValues[ $arr[0] ][ $arr[1] ];
                }
                break;
            case 3 :
                if( isset(self::$_configValues[ $arr[0] ][ $arr[1] ][ $arr[2] ])) {
                    return self::$_configValues[ $arr[0] ][ $arr[1] ][ $arr[2] ];
                }
                break;
            default: break;
        }
        return NULL;
    }
    
}