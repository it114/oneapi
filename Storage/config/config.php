<?php
$retConfig = array(
    'dev'=>array(
        'db'=>array(//主库配置
            'db_type'=>'mysql',
            'db_driver'=>'pdo',
            'db_host'=>'127.0.0.1',
            'db_username'=>'test',
            'db_password'=>'test',
            'db_port'=> 3306,
            'db_name' => 'oneapi',
            'charset'=>'utf8',
            'presistent'=>true,//pdo是否为持久链接
            'table_prefix' => '',
        ),
        'db_slave'=>array(
            array(//从库和主库的配置不一定要都写全，一定要ip不同
                'db_type'=>'mysql',
                'db_driver'=>'pdo',
                'db_host'=>'127.0.0.1',
                'db_username'=>'test',
                'db_password'=>'test',
                'db_port'=> 3306,
                'db_name' => 'oneapi',
                'charset'=>'utf8',
                'presistent'=>true,//pdo是否为持久链接
                'table_prefix' => '',
            ),
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
    )
);

return $retConfig;



