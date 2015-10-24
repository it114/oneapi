<?php

quickservice::get_classLoader()->load_controller('common.jwt');
class publicController extends jwtController {
     
    public function _init_actions(){
        $this->actions[] = 'login';
        $this->actions[] = 'reg';
        $this->actions[] = 'auth';
    }
    
    public function login(){
                
    }
    
    public function reg(){
        global $_G_VARS;
        if(!$_G_VARS['ispost']) {
            $this->show_json('0','非法请求');
        } else if($_G_VARS['ispost']) {
            $tel = $_POST['tel'];
            $pwd=  $_POST['pwd'];
            $repwd=$_POST['repwd'];
            $valid_num = $_POST['valid_num'];
            if(!$tel || !$pwd || !$repwd || !$valid_num ){
                $this->show_msg('参数输入不完整');
            }
            if($repwd !=$pwd){
                $this->show_msg('两次输入密码不一致');
            }
            if($valid_num != '1234'){//调试期间
                $this->show_msg('验证码不正确');
            }
            $user_model = getmodel('user');
            
            $result = $user_model->reg(array('phone'=>$tel,'password'=>$pwd));
            if($result){
               //注册成功，jwt编码返回
            } else {
                $this->show_json(0,'注册失败');
            }
        }        
    }
       
    /**
     * 第三方帐号登录
     */
    public function auth(){
        
    }
    
    
}