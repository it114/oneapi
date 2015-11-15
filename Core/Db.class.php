<?php
namespace Core;
use Core\util\Config;
/**
 * ThinkPHP 数据库中间层实现类
 */
class Db {

    static private  $instance   =  array();     //  数据库连接实例
    static private  $_instance  =  null;   //  当前数据库连接实例
    
    /**
     * 取得数据库类实例
     * @static
     * @access public
     * @param mixed $config 连接配置
     * @return Object 返回数据库驱动类
     */
    static public function getInstance($config=array()) {
        $md5    =   md5(serialize($config));
        if(!isset(self::$instance[$md5])) {
            // 解析连接参数 支持数组和字符串
            $options    =   self::parseConfig($config);
            // 兼容mysqli
            if('mysqli' == $options['type']) $options['type']   =   'mysql';
            // 如果采用lite方式 仅支持原生SQL 包括query和execute方法
            $class  =   !empty($options['lite'])?  'Core\db\Lite' :   'Core\\db\\driver\\'.ucwords(strtolower($options['type']));
            if(class_exists($class)){
                self::$instance[$md5]   =   new $class($options);
            }else{
                // 类没有定义
                //TODO E(L('_NO_DB_DRIVER_').': ' . $class);
            }
        }
        self::$_instance    =   self::$instance[$md5];
        return self::$_instance;
    }
    
    /**
     * 数据库连接参数解析
     * @static
     * @access private
     * @param mixed $config
     * @return array
     */
    static private function parseConfig($config){
        if(!empty($config)){
            if(is_string($config)) {
                return self::parseDsn($config);
            }
            $config =   array_change_key_case($config);
            $config = array (
                'type'          =>  $config['db_type'],
                'username'      =>  $config['db_user'],
                'password'      =>  $config['db_pwd'],
                'hostname'      =>  $config['db_host'],
                'hostport'      =>  $config['db_port'],
                'database'      =>  $config['db_name'],
                'dsn'           =>  isset($config['db.db_dsn'])?$config['db.db_dsn']:null,
                'params'        =>  isset($config['db.db_params'])?$config['db.db_params']:null,
                'charset'       =>  isset($config['db.db_charset'])?$config['db.db_charset']:'utf8',
                'deploy'        =>  isset($config['db.db_deploy_type'])?$config['db.db_deploy_type']:0,
                'rw_separate'   =>  isset($config['db.db_rw_separate'])?$config['db.db_rw_separate']:false,
                'master_num'    =>  isset($config['db.db_master_num'])?$config['db.db_master_num']:1,
                'slave_no'      =>  isset($config['db.db_slave_no'])?$config['db.db_slave_no']:'',
                'debug'         =>  isset($config['db.db_debug'])?$config['db.db_debug']:APP_DEBUG,
                'lite'          =>  isset($config['db.db_lite'])?$config['db.db_lite']:false,
            );
        }else {
            $config = array (
                'type'          =>  Config::get('db.db_type'),
                'username'      =>  Config::get('db.db_username'),
                'password'      =>  Config::get('db.db_password'),
                'hostname'      =>  Config::get('db.db_host'),
                'hostport'      =>  Config::get('db.db_port'),
                'database'      =>  Config::get('db.db_name'),
                'dsn'           =>  Config::get('db.db_dsn'),
                'params'        =>  Config::get('db.db_params'),
                'charset'       =>  Config::get('db.db_charset'),
                'deploy'        =>  Config::get('db.db_deploy_type'),
                'rw_separate'   =>  Config::get('db.db_rw_separate'),
                'master_num'    =>  Config::get('db.db_master_num'),
                'slave_no'      =>  Config::get('db.db_slave_no'),
                'debug'         =>  DEBUG,
                'lite'          =>  Config::get('db_lite'),
            );
        }
        //dump($config);
        return $config;
    }

    /**
     * DSN解析
     * 格式： mysql://username:passwd@localhost:3306/DbName?param1=val1&param2=val2#utf8
     * @static
     * @access private
     * @param string $dsnStr
     * @return array
     */
    static private function parseDsn($dsnStr) {
        if( empty($dsnStr) ){return false;}
        $info = parse_url($dsnStr);
        if(!$info) {
            return false;
        }
        $dsn = array(
            'type'      =>  $info['scheme'],
            'username'  =>  isset($info['user']) ? $info['user'] : '',
            'password'  =>  isset($info['pass']) ? $info['pass'] : '',
            'hostname'  =>  isset($info['host']) ? $info['host'] : '',
            'hostport'  =>  isset($info['port']) ? $info['port'] : '',
            'database'  =>  isset($info['path']) ? substr($info['path'],1) : '',
            'charset'   =>  isset($info['fragment'])?$info['fragment']:'utf8',
        );
        
        if(isset($info['query'])) {
            parse_str($info['query'],$dsn['params']);
        }else{
            $dsn['params']  =   array();
        }
        return $dsn;
     }

    // 调用驱动类的方法
    static public function __callStatic($method, $params){
        return call_user_func_array(array(self::$_instance, $method), $params);
    }
}
