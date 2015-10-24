<?php
defined('IN_QA') or exit('Access Denied');

global $_G_VARS;
//独立数据库配置，可以覆盖掉系统的数据库配置
$_G_VARS['gconfig']['db']['db_type']      = 'mysql';
$_G_VARS['gconfig']['db']['db_host']      = 'www.0068.wang';
$_G_VARS['gconfig']['db']['db_username']  = '';
$_G_VARS['gconfig']['db']['db_password']  = '';
$_G_VARS['gconfig']['db']['db_port']      = '3306';
$_G_VARS['gconfig']['db']['db_name']      = '';
$_G_VARS['gconfig']['db']['charset']      = 'utf8';
$_G_VARS['gconfig']['db']['table_prefix'] = '';

//接口配置管理
$api_config = array(
            'index'=>array(
                'index'=>array(
                    'enable'=>true,//接口是否正常使用
                    'cache'=>true,//是否开启缓存
                    'auth'=>false,//接口是否需要授权登录
                    'exp'=>3600, //缓存有效期过期时间
                ),
            ),
            'public'=>array(
                'request'=>array(
                    'enable'=>true,//接口是否正常使用
                    'cache'=>false,//是否开启缓存
                    'auth'=>false,//接口是否需要授权登录
                    'exp'=>3600, //缓存有效期过期时间
                ),
                'consume'=>array(
                    'enable'=>true,//接口是否正常使用
                    'cache'=>false,//是否开启缓存
                    'auth'=>false,//接口是否需要授权登录
                    'exp'=>3600, //缓存有效期过期时间
                ),
                'hlist'=>array(
                    'enable'=>true,//接口是否正常使用
                    'cache'=>true,//是否开启缓存
                    'auth'=>false,//接口是否需要授权登录
                    'exp'=>3600, //缓存有效期过期时间
                ),
                'test'=>array(
                    'enable'=>true,//接口是否正常使用
                    'cache'=>true,//是否开启缓存
                    'auth'=>false,//接口是否需要授权登录
                    'exp'=>3600, //缓存有效期过期时间
                ),
                
                
            ),
		);

$_G_VARS['gconfig']['hongbao']['apiconfig'] = $api_config;