<?php
//独立数据库配置，可以覆盖掉系统的数据库配置
$retConfig = array(
    'dev'=>array(
        'db'=>array(
            'db_type'=>'mysql',
            'db_host'=>'127.0.0.1',
            'db_username'=>'usertest',
            'db_password'=>'usertest',
            'db_port'=> 3306,
            'db_name' => 'oneapi',
            'charset'=>'utf8',
            'table_prefix' => '',
        ),
        'setting'=>array(
            'timezone' => 'Asia/Shanghai',
            'memory_limit' => '256M',

        ),
        'site' => array(
            'url'=>array(
                'default_app'=>'user',
                'default_ctrl'=>'default',
                'default_act'=>'default',
            ),
        ),
    ),
    'release'=>array(
        'db'=>array(
            'db_type'=>'mysql',
            'db_host'=>'127.0.0.1',
            'db_username'=>'release',
            'db_password'=>'release',
            'db_port'=> 3306,
            'db_name' => 'usertest',
            'charset'=>'utf8',
            'table_prefix' => '',
        ),
        'setting'=>array(
            'timezone' => 'Asia/Shanghai',
            'memory_limit' => '256M',

        ),
        'site' => array(
            'url'=>array(
                'default_app'=>'user',
                'default_ctrl'=>'default',
                'default_act'=>'default',
            ),
        ),
    )
);

return $retConfig;



