<?php
//独立数据库配置，可以覆盖掉系统的数据库配置
$retConfig = array(
    'dev'=>array(
        'rest_controller'=>array(//这里和数据库表名保持一致，不带数据表前缀 ，全部小写
            'user','road','youji'
        ),
    ),
    'release'=>array(
       
    )
);

return $retConfig;



