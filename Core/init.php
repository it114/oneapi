<?php
use Core\util\Config;
/**
 *  app framework
 */
if (version_compare(PHP_VERSION,'5.4.0','<')) {
    exit("require PHP version > 5.4 ");
}

defined('IN_ONEAPI') or  define('IN_ONEAPI', true);
defined('ROOT_PATH') || define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('CORE_PATH') ||  define('CORE_PATH', ROOT_PATH.'Core'.DIRECTORY_SEPARATOR);
defined('LIBRARY_PATH') ||  define('LIBRARY_PATH', ROOT_PATH.'Library'.DIRECTORY_SEPARATOR);
defined('APPS_PATH') ||  define('APPS_PATH', ROOT_PATH.'Apps'.DIRECTORY_SEPARATOR);
defined('STORAGE_PATH') ||  define('STORAGE_PATH', ROOT_PATH.'Storage'.DIRECTORY_SEPARATOR);
//MAGIC_QUOTES_GPC 5.4已经移除了 refer:http://blog.csdn.net/lyjtynet/article/details/6261169
define('MAGIC_QUOTES_GPC',false);
define('TIMESTAMP', time());
//define('STARTTIME', getmicrotime());
define('APP_NAME', 'user');
//关闭自动加上的引号，一是为了高效，而是为了程序的兼容性，【数据库操作的地方一定要做特殊处理！！!】
@set_magic_quotes_runtime(0);
//加载核心函数
require_once CORE_PATH.'OneAPI.php';
\Core\OneAPI::start();
 





//如果是调试模式，打开警告输出
if ($_G_VARS['gconfig']['setting']['debug']) {
    ini_set('display_errors', 'on');   //正式环境关闭错误输出
    if (substr(PHP_VERSION, 0, 3) == "5.3") {
        error_reporting(~E_WARNING & ~E_NOTICE & E_ALL & ~E_DEPRECATED);
    } else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }
} else {
    error_reporting(0);
}
$_G_VARS['ispost'] = $_SERVER['REQUEST_METHOD'] == 'POST';
//cache handler 
if(!in_array($_G_VARS['config']['setting']['cache'], array('mysql', 'file','redis','memcache'))) {
    $_G_VARS['config']['setting']['cache'] = 'mysql';
}
//时区
if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set($_G_VARS['config']['setting']['timezone']);
}
//内存
if(!empty($_G_VARS['config']['memory_limit']) && function_exists('ini_get') && function_exists('ini_set')) {
    if(@ini_get('memory_limit') != $_G_VARS['config']['memory_limit']) {
        @ini_set('memory_limit', $_G_VARS['config']['memory_limit']);
    }
}
//处理url
proces_url();
route();

header('Content-Type: text/html; charset=' . $_G_VARS['charset']);

quickapp::start();

$end_time = getmicrotime();
$use_time =  ($end_time - STARTTIME).'(s)';
//echo "<div style='text-align:center;'>本次加载耗时:$use_time</div>";
if($use_time>3000){
    //TODO 写入数据库
}




