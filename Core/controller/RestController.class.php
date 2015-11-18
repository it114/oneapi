<?php
namespace Core\controller;
use Core\controller\ApiController;
use Core\util\Config;
class RestController extends ApiController {
    // 当前请求类型
    protected   $_method        =   ''; 
    // 当前请求的资源类型
    protected   $_type          =   ''; 
    // REST允许的请求类型列表
    protected   $allowMethod    =   array('get','post','put','delete'); 
    // REST默认请求类型
    protected   $defaultMethod  =   'get';
    // REST允许请求的资源类型列表
    protected   $allowType      =   array('html','xml','json','rss'); 
    // REST允许输出的资源类型列表
    protected   $allowOutputType=   array(  
                    'xml' => 'application/xml',
                    'json' => 'application/json',
                    'html' => 'text/html',
                );
    
    public function __construct() {
        $suffix = Config::get('url.url_suffix');
        // 资源类型检测
        if(''== $suffix) { 
            $this->_type   =  'json';
        }elseif(!in_array($suffix,$this->allowType)) {
            $this->_type   = 'json';
        }
        // 请求方式检测
        $method  =  strtolower(REQUEST_METHOD);
        if(!in_array($method,$this->allowMethod)) {
            $method = $this->defaultMethod;
        }
        $this->_method = $method;
    }
    
    /**
     * 
     * @param string $method
     * @param string $args
     */
    public function _rest($method,$args='') { 
        if(method_exists($this,$method.'_'.$this->_method.'_'.$this->_type)) { //read_get_json
            $fun  =  $method.'_'.$this->_method.'_'.$this->_type;
            $this->invokeFunc($fun, $args);
        }elseif($this->_method == $this->defaultMethod && method_exists($this,$method.'_'.$this->_type) ){//read_json
            $fun  =  $method.'_'.$this->_type;
            $this->invokeFunc($fun, $args);
        }elseif($this->_type == $this->defaultType && method_exists($this,$method.'_'.$this->_method) ){//read_get
            $fun  =  $method.'_'.$this->_method;
            $this->invokeFunc($fun, $args);
        }elseif(method_exists($this,'_empty')) {
            // 如果定义了_empty操作 则调用
            //$this->_empty($method,$args);
            echo 'rest method '.$method.' is not exists ';
        }else{
            trigger_error('unkonwn rest action :'.ACTION_NAME);
        }
    }
    
    private function invokeFunc($func,$args) {
        $this->$func($args);
    }
    
    // 发送Http状态信息
    protected function sendHttpStatus($code) {
        static $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if(isset($_status[$code])) {
            header('HTTP/1.1 '.$code.' '.$_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:'.$code.' '.$_status[$code]);
        }
    }

    /**
     * 编码数据
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @return string
     */
    protected function encodeData($data,$type='') {
        if(empty($data))  return '';
        if('json' == $type) {
            // 返回JSON数据格式到客户端 包含状态信息
            $data = json_encode($data);
        }elseif('xml' == $type){
            // 返回xml格式数据
            $data = xml_encode($data);
        }elseif('php'==$type){
            $data = serialize($data);
        }// 默认直接输出
        $this->setContentType($type);
        //header('Content-Length: ' . strlen($data));
        return $data;
    }

    /**
     * 设置页面输出的CONTENT_TYPE和编码
     * @access public
     * @param string $type content_type 类型对应的扩展名
     * @param string $charset 页面输出编码
     * @return void
     */
    public function setContentType($type, $charset=''){
        if(headers_sent()) return;
        if(empty($charset))  $charset ='utf-8';
        $type = strtolower($type);
        if(isset($this->allowOutputType[$type])) //过滤content_type
            header('Content-Type: '.$this->allowOutputType[$type].'; charset='.$charset);
    }

    /**
     * 输出返回数据
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @param integer $code HTTP状态
     * @return void
     */
    protected function response($data,$type='',$code=200) {
        $this->sendHttpStatus($code);
        exit($this->encodeData($data,strtolower($type)));
    }
}
