<?php
defined('IN_ONEAPI') or exit('Access Denied');

$_gconfig = array();
$_gconfig['db']['db_type'] = 'mysql';
$_gconfig['db']['db_host'] = 'localhost';
$_gconfig['db']['db_username'] = 'test';
$_gconfig['db']['db_password'] = 'test';
$_gconfig['db']['db_port'] = '3306';
$_gconfig['db']['db_name'] = 'smartphp';
$_gconfig['db']['charset'] = 'utf8';
$_gconfig['db']['table_prefix'] = 'qa_';

// --------------------------  CONFIG SETTING  --------------------------- //
$_gconfig['setting']['charset'] = 'utf-8';
$_gconfig['setting']['cache'] = 'mysql';
$_gconfig['setting']['timezone'] = 'Asia/Shanghai';
$_gconfig['setting']['memory_limit'] = '256M';
$_gconfig['setting']['filemode'] = 0644;
$_gconfig['setting']['authkey'] = '7af402e2';
$_gconfig['setting']['super_user'] = '1';
$_gconfig['setting']['debug'] = 1;
$_gconfig['setting']['referrer'] = 0;

// --------------------------  CONFIG UPLOAD  --------------------------- //
$_gconfig['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png');
$_gconfig['upload']['image']['limit'] = 5000;
$_gconfig['upload']['attachdir'] = 'attachment';
$_gconfig['upload']['audio']['extentions'] = array('mp3');
$_gconfig['upload']['audio']['limit'] = 5000;
//config for M/A/C
$_gconfig['site']['url']['default_app']     = 'user';
$_gconfig['site']['url']['default_ctrl']    = 'public';
$_gconfig['site']['url']['default_act']     = 'login';
//缓存
$_gconfig['cache']['file']['cache_path'] ='/storage/cache/file/';//必须 以 '/'结尾
$_gconfig['cache']['file']['expire'] = 36000; //10分钟
//和外界通信的客户端的配置
$_gconfig['site']['client']['plat'] = array('main.api.service'=>array('enable'=>true,'token'=>'','des'=>'和主API通信的配置'));


