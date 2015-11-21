<?php

$where = array('where'=>array(
    'username'=>'user-test',
    'password'=>'pwd',
    'email' => 'email@fff.co'
));

//echo json_encode($where);die;

define('DEBUG', true);

require './Core/init.php';

