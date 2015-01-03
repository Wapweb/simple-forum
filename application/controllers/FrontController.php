<?php

class FrontController {
    protected $_controller, $_action, $_params, $_body;
    static $_instance;
    private $_start_time;

    public static function getInstance() {
        if(!(self::$_instance instanceOf self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->_start_time = microtime(true);
        $request = $_SERVER['REQUEST_URI'];

        $splits = explode('/',trim($request,'/'));

        $this->_controller = !empty($splits[0]) ? ucfirst($splits[0]).'Controller': 'IndexController';
        $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';

        if(!empty($splits[2])) {

            $cnt=count($splits);

            if($cnt%2==0) {
                $keys = $values = array();
                for($i=2;$i<$cnt;$i++) {
                    if($i%2==0)
                        $keys[] = $splits[$i];
                    else
                        $values[] = $splits[$i];
                }
                $this->_params = array_combine($keys,$values);
            }
        }
    }

    public function GetSimpleParams($start = 2,$old_detect = false) {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/',trim($request,'/'));

        if($old_detect) {
            if(!empty($splits[2])) {
                if(in_array($splits[1],array(2010,2011)))
                    $start = 3;
            }
        }

        $result = array();
        if(isset($splits[$start])) {
            for($i=$start;$i<count($splits);$i++) $result[] = $splits[$i];
            return $result;
        }
        return array();
    }

    public function route() {

        if(!file_exists(ROOT.DIRECTORY_SEPARATOR.'application/controllers/'.$this->getController().'.php')) {
            throw new Exception('Module not found');
        }


        if(class_exists($this->getController())) {
            $rc = new ReflectionClass($this->getController());
            if($rc->implementsInterface('IController')) {
                if($rc->hasMethod($this->getAction())) {
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    $method->invoke($controller);
                } else {
                    throw new Exception('Wrong Action');
                }
            } else {
                throw new Exception('Wrong Interface');
            }
        } else {
            throw new Exception('Wrong Controller');
        }
    }

    public function getParams() {
        return $this->_params;
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        return $this->_action;
    }

    public function getBody() {
        return $this->_body;
    }

    public function setBody($body) {
        $this->_body = $body;
    }

    public function GetStartTime() {
        return $this->_start_time;
    }

    public function SetStartTime() {
        $this->_start_time = microtime(true);
    }
}