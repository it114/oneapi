<?php
$retConfig = array(
    'dev'=>array(
        'url'=>array(
            'default_app'=>'home',
            'default_controller'=>'index',
            'default_action'=>'index',
            'default_url_suffix'=>'.json',
            'url_type'=>2,//url类型 1、兼容模式，类似r=app/controller/action形式；2、pathinfo的rest风格格式index.php/app/controller/action
        ),       
        'token_on' => false,//是否开启表单令牌验证
        'cache'=>array(
            'default_get_cache_time' =>300,//默认get请求的数据缓存时间
            'default_get_cache_path' => '',//默认接口数据缓存文件夹
            'default_cache_time'=>300,//数据库读取数据默认缓存 5分钟
            'data_cache_type'=> 'File' ,//数据缓存类型,支持:File|Db|Apc|Memcache,
            'data_cache_path'=> DATA_CACHE_PATH,//缓存目录  缓存路径设置 (仅对File方式缓存有效)
            'data_cache_prefix'=>'_',
            'db_cache_subdir'=> true,//使用子目录缓存
            'data_path_level' =>  1,        // 子目录缓存级别,
            'data_cache_check' =>false,//是否开启数据缓存校验
            'data_cache_compress' => false, //缓存数据是否压缩
            'db_fields_cache' => true,//是否缓存字段
        ),
        'db'=>array(//主库配置
            'db_type'=>'mysql',
            'db_driver'=>'pdo',
            'db_host'=>'127.0.0.1',
            'db_username'=>'test',
            'db_password'=>'test',
            'db_port'=> 3306,
            'db_name' => 'common-server',
            'db_charset'=>'utf8',
            'presistent'=>true,//pdo是否为持久链接
            'table_prefix' => 'qj_',
            'db_deploy_type'=>0,//是否分布式部署 1，是，0 不是
            'db_rw_separate'=>false,// // 数据库读写是否分离 主从式有效      
            'db_master_num' =>  1, // 读写分离后 主服务器数量
            'db_slave_no'=>  '', // 指定从服务器   
            'db_fields_cache' =>true,//是否开启字段缓存
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



