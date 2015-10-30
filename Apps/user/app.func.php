<?php
defined('IN_QA') or exit('Access Denied');
//app 公共函授！

function _hongbao_create_token($uid,$plat,$salt){
    return quickapp_md5(TIMESTAMP.$uid,$plat.$salt);
}
