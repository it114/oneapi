<?php
//独立数据库配置，可以覆盖掉系统的数据库配置
$retConfig = array(
    'dev'=>array(
        'rest_table'=>array(
            'user','road','youji'
        ),
    ),
    'release'=>array(
       
    )
);

return $retConfig;



