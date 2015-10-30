<?php

class userModel extends basemodel {
    
    function __construct(){
        parent::__construct('member');
    }
    
    public function reg($params = array(),$reg_type = 'phone'){
        if(!$params || !$params['pwd'] ) {
            $this->last_msg ='参数缺少';
            return false;
        }
        $data = array();
        if($reg_type == 'phone') {
            if(!$params['phone']) {
                $this->last_msg ='参数缺少phone';
                return false;
            }
            $data['phone'] = $params['phone'];
        } else if($reg_type == 'email') {
            if( !$params['email']) {
                $this->last_msg ='参数缺少email';
                return false;
            }
            $data['email'] = $params['email'];
        } else if($reg_type == 'username'){
            if(!$params['username']) {
                $this->last_msg ='参数缺少username';
                return false;
            }
            $data['username'] = $params['username'];
        } else {
            $this->last_msg ='不合法的注册';
            return false;
        }
        //查看是否已经注册
        $exists = $this->get($data,array('id','email','phone','username'));
        if($exists){
            $this->last_msg ='用户已经存在';
            return false;
        }
        $salt = rand(100,999);
        $pwd = quickapp_md5($params['password'].$salt);
        $data['password'] = $pwd;
        $data['salt'] = $salt;
        $result = $this->insert($data);
        if($result) {
            $this->last_msg ='注册成功';
            return true;
        } else {
            $this->last_msg ='注册失败';
            return false;
        }
    }
}