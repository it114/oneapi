<?php
/**
 *  app framework
 */
if (version_compare(PHP_VERSION,'5.4.0','<')) {
    exit("require PHP version > 5.4 ");
}

const VERSION = '1.0.0';

if(DEBUG) {
    if(!function_exists('get_microtime')) {
        function get_microtime()
        {
            list($usec, $sec) = explode(' ', microtime());
            return ((float)$usec + (float)$sec);
        }           
    }
    $start_time = get_microtime();
}
defined('IN_ONEAPI') or  define('IN_ONEAPI', true);
defined('DEBUG') or  define('DEBUG', false);
defined('ROOT_PATH') || define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('CORE_PATH') ||  define('CORE_PATH', ROOT_PATH.'Core'.DIRECTORY_SEPARATOR);
defined('LIBRARY_PATH') ||  define('LIBRARY_PATH', ROOT_PATH.'Library'.DIRECTORY_SEPARATOR);
defined('APPS_PATH') ||  define('APPS_PATH', ROOT_PATH.'Apps'.DIRECTORY_SEPARATOR);
defined('STORAGE_PATH') ||  define('STORAGE_PATH', ROOT_PATH.'Storage'.DIRECTORY_SEPARATOR);
defined('DATA_CACHE_PATH') || define('DATA_CACHE_PATH',STORAGE_PATH.'cache'.DIRECTORY_SEPARATOR.'data');

//MAGIC_QUOTES_GPC 5.4已经移除了 refer:http://blog.csdn.net/lyjtynet/article/details/6261169,所以这里不需要，有无须过滤
define('MAGIC_QUOTES_GPC',false);
define('TIMESTAMP', time());
//define('STARTTIME', getmicrotime());
//关闭自动加上的引号，一是为了高效，而是为了程序的兼容性，【数据库操作的地方一定要做特殊处理！！!】
@set_magic_quotes_runtime(0);

//加载核心函数                     
require_once CORE_PATH.'Application.php';
\Core\Application::start();

if(DEBUG) {
    $end_time = get_microtime();
    $use_time =  ($end_time - $start_time).'(s)';
    echo "<div style='text-align:center;'>本次加载耗时:$use_time</div>";
    if($use_time>3000){
        //TODO 写入数据库或者日志文件
    }
}


