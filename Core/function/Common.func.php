<?php

if (!defined('IN_QA')) {
    exit();
}

function getip() {
    static $ip = '';
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

function token($specialadd = '') {
    return '123456';//TODO ,补全这里的算法
}

function stripslashes_deep($var){
    if (is_array($var)) {
        foreach ($var as $key => $value) {
            $var[stripslashes($key)] = stripslashes_deep($value);
        }
    } else {
        $var = stripslashes($var);
        $var = htmlspecialchars($var);//把一些html转换成html实体，例如&（和号）成为 &amp;
        $var = trim($var);
    }
    return $var;
}


function scriptname() {
    global $_G_VARS;
    $_G_VARS['script_name'] = basename($_SERVER['SCRIPT_FILENAME']);
    if(basename($_SERVER['SCRIPT_NAME']) === $_G_VARS['script_name']) {
        $_G_VARS['script_name'] = $_SERVER['SCRIPT_NAME'];
    } else {
        if(basename($_SERVER['PHP_SELF']) === $_G_VARS['script_name']) {
            $_G_VARS['script_name'] = $_SERVER['PHP_SELF'];
        } else {
            if(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $_G_VARS['script_name']) {
                $_G_VARS['script_name'] = $_SERVER['ORIG_SCRIPT_NAME'];
            } else {
                if(($pos = strpos($_SERVER['PHP_SELF'], '/' . $_G_VARS['script_name'])) !== false) {//TODO $scriptName
                    $_G_VARS['script_name'] = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $_G_VARS['script_name'];
                } else {
                    if(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                        $_G_VARS['script_name'] = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
                    } else {
                        $_G_VARS['script_name'] = 'unknown';
                    }
                }
            }
        }
    }
    return $_G_VARS['script_name'];
}

function proces_url(){
    global $_G_VARS;
    //Urlslogin
    //http://127.0.0.1/quick-app/index.php?x=9&y=9 ,$_G_VARS['script_name']为/quick-app/index.php
    $_G_VARS['script_name'] = htmlspecialchars(scriptname());
    //http://127.0.0.1/quick-app/index.php?x=9&y=9   返回/quick-app
    $sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    $_G_VARS['siteroot'] = htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $sitepath);
    if(substr($_G_VARS['siteroot'], -1) != '/') {
        $_G_VARS['siteroot'] .= '/';
    }
    $urls = parse_url($_G_VARS['siteroot']);
    //http://127.0.0.1/quick-app/
    $_G_VARS['siteroot'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];
    //http://127.0.0.1/quick-app/index.php?x=9&y=9
    $_G_VARS['currenturl'] =  $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '') . $_G_VARS['script_name'] . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];
    unset($sitepath);
}

function get_site_root_url(){
    global $_G_VARS;
    $site_root = $_G_VARS['siteroot'] ;
    if(substr($site_root, -1) != '/') {
    	$site_root .= '/';
    }
    return $site_root;
}

function route(){ 
    global $_G_VARS;
    $route = explode('/', $_GET['r']);
    $len  = count($route);
    if($len == 1){
        $_GET['app'] = $route[0];
    }
    if($len == 2){
        $_GET['app'] = $route[0];
        $_GET['ctrl'] = $route[1];
    }
    if($len == 3){
        $_GET['app'] = $route[0];
        $_GET['ctrl'] = $route[1];
        $_GET['act'] =  $route[2];
    }
    //统一处理
    if(!$_GET['app']) {//app
        $_GET['app'] = $_G_VARS['gconfig']['site']['url']['default_app'];
    }
    if(!$_GET['ctrl']) {//控制器
        $_GET['ctrl'] = $_G_VARS['gconfig']['site']['url']['default_ctrl'];
    }
    if(!$_GET['act']) {//动作
        $_GET['act'] = $_G_VARS['gconfig']['site']['url']['default_act'];
    }  
}

/***
 * 
$x = 'app';
$x = explode('/',$x);
print_r($x);
echo '<Br>';

$x = 'app/user/login';
$x = explode('/',$x);
print_r($x);

echo '<Br>';

$x = 'app/user';
$x = explode('/',$x);
print_r($x);

 * @param unknown $params 参数
 * @return boolean|string
 */
function create_url($route,$params=array()){
    if(!$route){
        return false;
    }
    global $_G_VARS;
    $route = explode('/', $route); 
    if(is_array($route)) {
        if(!empty($route[0])) {
            $route['app'] = $route[0];
            unset($route[0]);
        }
        if(!empty($route[1])) {
            $route['ctrl'] =$route[1];
            unset($route[1]);
        }
        if(!empty($route[2])) {
            $route['act'] = $route[2];
            unset($route[2]);
        }
    }
    
    //统一处理
    if(!$route['app']) {//app
        $route['app'] = $_G_VARS['gconfig']['site']['url']['default_app'];
    }
    if(!$route['ctrl']) {//控制器
        $route['ctrl'] = $_G_VARS['gconfig']['site']['url']['default_ctrl'];
    }
    if(!$route['act']) {//动作
        $route['act'] = $_G_VARS['gconfig']['site']['url']['default_act'];
    }
    
    $site_root = get_site_root_url();
    $site_root = $site_root.'?app='.$route['app'] .'&ctrl='.$route['ctrl']. '&act='.$route['act'];
    if ($params && is_array($params)) {
        unset($params['theme']);
        $url .= '&' . http_build_query($params);
    }
    return $site_root.$url;
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return (( float )$usec + ( float )$sec);
}

function dump($data = null, $option = false) {
    //设置页面编码
    if (!headers_sent()) {
        header("Content-Type:text/html; charset=utf-8");
    }

    //当输出print_r()内容时
    if(!$option){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    } else {
        ob_start();
        var_dump($data);
        $output = ob_get_clean();

        $output = str_replace('"', '', $output);
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

        echo '<pre>', $output, '</pre>';
    }
    exit();
}

function quickapp_md5($str, $key = 'quickapp'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * @return boolean
 */
function plat(){
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if( preg_match(
            '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
            $useragent
        )
        ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent,0,4)
          )
      )
      {
        return 'mobile';
      }
      return  'web';
}




