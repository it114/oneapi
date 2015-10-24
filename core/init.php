<?php
/**
 * quick app framework
 */
if ('5' != substr(PHP_VERSION, 0, 1)) {
    exit("运行环境要求PHP5支持");
}

defined('IN_QA') or  define('IN_QA', true);
defined('ONEAPI_PATH') ||  define('ONEAPI_PATH', dirname(__FILE__).'/');

if( !defined('ROOT_PATH') ) define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
if( !defined('BASE_PATH') ) define('BASE_PATH', realpath('./').DIRECTORY_SEPARATOR);
if( !defined('CONFIG_PATH') ) define('CONFIG_PATH', BASE_PATH.'data/config/');
if( !defined('ROOT_URL') ) define('ROOT_URL',  rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/').'/');
if( !defined('PUBLIC_URL') ) define('PUBLIC_URL', ROOT_URL . 'public/');


define('MAGIC_QUOTES_GPC', (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || @ini_get('magic_quotes_sybase'));
define('TIMESTAMP', time());
//关闭自动加上的引号，一是为了高效，而是为了程序的兼容性，【数据库操作的地方一定要做特殊处理！！!】
@set_magic_quotes_runtime(0);

//全局范围可访问的数组
$_G_VARS = array();

//核心配置文件 $_gconfig 系统配置变量
$configfile = ROOT_PATH.'/storage/data/config/config.inc.php';

if(!file_exists($configfile)) {
    exit('配置文件缺少，请检查');
}

require $configfile;

//加载核心函数
require_once ROOT_PATH.'/core/classes/quickapp.class.php';
ClassLoader::instance()->load('/core/function/common.func.php');

define('STARTTIME', getmicrotime());

$_G_VARS['gconfig'] = $_gconfig;
$_G_VARS['timestamp'] = TIMESTAMP;
$_G_VARS['charset'] = $_G_VARS['gconfig']['setting']['charset'];
$_G_VARS['token'] = token('12345@6');//TODO JWT 认证使用
$_G_VARS['clientip'] =  getip();
unset($configfile,$_gconfig);

// 如果是调试模式，打开警告输出
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
//关闭自动加上的引号，一是为了高效，而是为了程序的兼容性，【数据库操作的地方一定要做特殊处理！！!】
if (MAGIC_QUOTES_GPC) {
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_SESSION   = array_map( 'stripslashes_deep', $_SESSION );
    
}

//TODO token验证

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




