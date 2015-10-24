<?php



//为了程序的移植性，统一放在这里，core框架文件可以放在非www目录，增强安全行
define('ROOT_PATH', str_replace("\\", '/', dirname(__FILE__)));

require './core/init.php';

