<?php
class View {
    /** @var  UserModel $_user */
    public  $user;
    public $start_time;
    public $title = "Форум абитуриентов";

    public function __construct() {
        $this->start_time = microtime(true);
        //$p = isset($_COOKIE['usr_pass']) ? $_COOKIE['usr_pass'] : '';
        //$id = isset($_COOKIE['usr_id']) ? $_COOKIE['usr_id'] : 0;
        $this->user = (object)Registry::get('User');
        if(!($this->user instanceof UserModel)) {
            $this->user = null;
       }
    }

    public function render($file,$head='',$foot='') {
        ob_start();
        if($head)
            require_once (ROOT.'/application/views/'.$head);

        require_once (ROOT.'/application/views/'.$file);

        if($foot)
            require_once (ROOT.'/application/views/'.$foot);
        return ob_get_clean();
    }
}
