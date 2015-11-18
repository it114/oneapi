<?php
namespace Core\util;
class Response  {

    /**
     * 设置页面编码
     *
     * @access public
     *
     * @param string $encode 编码名称
     *
     * @return void
     */
    public static function set($contentType,$encode = 'UTF-8') {

        header("Content-Type:{$contentType}; charset={$encode}");
        
        return true;
    }
    
    /**
     * 禁用浏览器缓存
     *
     * @access public
     * @return boolean
     */
    public static function noCache() {

        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        return true;
    }

    /**
     * 设置网页生存周期
     *
     * @access public
     *
     * @param integer $seconds 生存周期（单位：秒）
     *
     * @return boolean
     */
    public static function expires($seconds = 1800) {

        $time = date('D, d M Y H:i:s', $_SERVER['REQUEST_TIME'] + $seconds) . ' GMT';
        header("Expires: {$time}");

        return true;
    }

    /**
     * 网址(URL)跳转操作
     *
     * 页面跳转方法，例:运行页面跳转到自定义的网址(即:URL重定向)
     *
     * @access public
     *
     * @param string $url 所要跳转的网址(URL)
     *
     * @return void
     */
    public static function redirect($url) {

        //参数分析.
        if (!$url) {
            return false;
        }

        if (!headers_sent()) {
            header("Location:" . $url);
        }else {
            echo '<script type="text/javascript">location.href="' . $url . '";</script>';
        }

        exit();
    }
    

    /**
     * 优雅输出print_r()函数所要输出的内容
     *
     * 用于程序调试时,完美输出调试数据,功能相当于print_r().当第二参数为true时(默认为:false),功能相当于var_dump()。
     * 注:本方法一般用于程序调试
     *
     * @access public
     *
     * @param mixed $data 所要输出的数据
     * @param boolean $option 选项:true(显示var_dump()的内容)/ false(显示print_r()的内容)
     *
     * @return string
     */
    public static function dump($data = null, $option = false) {

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
    

    /**
     * 返回json数据,供前台调用
     *
     * @access public
     *
     * @param boolean $status 执行状态 : true/false 或 1/0
     * @param string $msg 返回信息, 默认为空
     * @param array $data 返回数据,支持数组。
     *
     * @return string
     */
    public static function jsonResponse($code, $msg = null, $data = array()) {
        $result             = array();
        $result['code']   = $code;
        $result['msg']      = !is_null($msg) ? $msg : '';
        $result['data']     = $data;
        
        //设置页面编码
        header("Content-Type:text/json; charset=utf-8");
        
        exit(json_encode($result));
    }

}