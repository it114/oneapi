<?php
namespace Core;

use \Core\util\Config;

class Application {
    
    static public  function  start() {
        // 注册autoload方法
        spl_autoload_register('\Core\Loader::autoload');
        $app = new Application();
        $app->run();
    }
    
    public function __construct(){
        $config = new Config((DEBUG)?'dev':'release');
        //初始化环境
        $this->_initEnv();
        
        //路由,默认r=app/controller/action形式
        $router = new \Core\util\Route();
        $router->init();
        $config->loadAppConfig();
    }
    
    public function run() {
        
        $controller = '\Apps\\'. APP_NAME .'\controller\\'. CONTROLLER_NAME;
        if( !class_exists($controller) ) {
            throw new \Exception("Controller '{$controller}' not found", 404);
        }
        
        $obj = new $controller();
        if( !method_exists($obj, ACTION_NAME) ){
            throw new \Exception("Action '{$controller}::{ACTION_NAME}()' not found", 404);
        }
        $obj->_initActions(); //初始化白名单
        $obj->_valid_action(ACTION_NAME);//验证 操作是否 在白名单
        $obj->_initAuth();//权限
        $obj ->$action();//执行action
        
    } 
    
    protected function _initEnv(){die('_initEnv  111 ');
        //时区设置,默认为中国(北京时区)
        date_default_timezone_set(Config::get('setting.timezone'));
        //设置异常处理
        set_exception_handler(array($this, '_exception'));
        //关闭魔术变量，提高PHP运行效率
        if (get_magic_quotes_runtime()) {
            @set_magic_quotes_runtime(0);
        }
        
        if (DEBUG) {
            ini_set('display_errors', 'on');   //正式环境关闭错误输出
            if (substr(PHP_VERSION, 0, 3) == "5.3") {
                error_reporting(~E_WARNING & ~E_NOTICE & E_ALL & ~E_DEPRECATED);
            } else {
                error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            }
        } else {
            error_reporting(0);
        }
    }
    
    protected function _exception($exception) {
        echo $exception->__toString();
    }
    
    
    
    
    
}

class Loader {
     static $class_map = array();
     static $_class_cache = array();
     static $namespace_map = array(
         'Core'=>CORE_PATH,
         'Library'=>LIBRARY_PATH,
         'Apps'=>APPS_PATH,
     );
     
     public static function loadFile($class_with_full_name=''){
         if(empty($class_with_full_name)) {
             return false;
         }
         
         if(isset(self::$_class_cache[$class_with_full_name])){
             return true;
         }
         if(file_exists($class_with_full_name)){
             include $class_with_full_name;
             self::$_class_cache[$class_with_full_name] = true;
             return true;
         }
         trigger_error('Invalid file :'.$class_with_full_name, E_USER_ERROR);
         return false;
     }
     
     public static function autoload($class){
         if(isset(self::$class_map[$class])) {
            return self::loadFile(self::$class_map[$class]);
         }
         //最简单的自动加载
         $class = str_replace('\\', '/', $class) . '.class.php';
         self::loadFile($class);
//          $prefix_name =  strstr($class, '\\', true);
//          if(isset(self::$namespace_map[$prefix_name])) {
//              $classname = str_replace('\\','/', $classname);
//              $file  = self::$namespace_map[$prefix_name].$classname.'.class.php';
//              if(is_file($file)) {
//                  self::loadFile($file);
//              } else {
//                  trigger_error('not find class file '.$file);
//              }
//          } else {
             
//              if(function_exists('custom_autoloader')) {
//                  call_user_func('custom_autoloader', $class);
//              } else {
//                  trigger_error('Unkonw namespace , Unable to load file :'.$class);
//              }
//              //trigger_error('Unknow namespace :'.$prefix_name.' with $class :'.$class);
//          }
     }
     
 }