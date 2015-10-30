<?php

namespace Core;

use \Core\util;

if (!defined('IN_ONEAPI')) {
    exit();
}

class OneAPI {
    
    static public  function  start() {
        $config = new  \Config('dev');
        $config->init();
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
         $prefix_name =  strstr($class, '\\', true);
         if(isset(self::$namespace_map[$prefix_name])) {
             $classname = str_replace('\\','/', $classname);
             $file  = self::$namespace_map[$prefix_name].$classname.'.class.php';
             if(is_file($file)) {
                 self::loadFile($file);
             } else {
                 trigger_error('not find class file '.$file);
             }
         } else {
             if(function_exists('custom_autoloader')) {
                 call_user_func('custom_autoloader', $class);
             } else {
                 trigger_error('Unkonw namespace , Unable to load file :'.$class);
             }
             //trigger_error('Unknow namespace :'.$prefix_name.' with $class :'.$class);
         }
     }
     
 }