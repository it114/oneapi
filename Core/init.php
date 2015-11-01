<?php
/**
 *  app framework
 */
if (version_compare(PHP_VERSION,'5.4.0','<')) {
    exit("require PHP version > 5.4 ");
}

defined('IN_ONEAPI') or  define('IN_ONEAPI', true);
defined('DEBUG') or  define('DEBUG', false);
defined('ROOT_PATH') || define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('CORE_PATH') ||  define('CORE_PATH', ROOT_PATH.'Core'.DIRECTORY_SEPARATOR);
defined('LIBRARY_PATH') ||  define('LIBRARY_PATH', ROOT_PATH.'Library'.DIRECTORY_SEPARATOR);
defined('APPS_PATH') ||  define('APPS_PATH', ROOT_PATH.'Apps'.DIRECTORY_SEPARATOR);
defined('STORAGE_PATH') ||  define('STORAGE_PATH', ROOT_PATH.'Storage'.DIRECTORY_SEPARATOR);
//MAGIC_QUOTES_GPC 5.4已经移除了 refer:http://blog.csdn.net/lyjtynet/article/details/6261169
define('MAGIC_QUOTES_GPC',false);
define('TIMESTAMP', time());
//define('STARTTIME', getmicrotime());
//关闭自动加上的引号，一是为了高效，而是为了程序的兼容性，【数据库操作的地方一定要做特殊处理！！!】
@set_magic_quotes_runtime(0);

//加载核心函数                     
require_once CORE_PATH.'Application.php';
\Core\Application::start();


$end_time = getmicrotime();
$use_time =  ($end_time - STARTTIME).'(s)';
//echo "<div style='text-align:center;'>本次加载耗时:$use_time</div>";
if($use_time>3000){
    //TODO 写入数据库
}


