<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 30.08.13
 * Time: 23:44
 * To change this template use File | Settings | File Templates.
 */

class Login {
    private $email,$password;
    /** @var  PDO $_db */
    private $_db;

    public function __construct($e,$p) {
        $this->_db = Registry::get('db');
        $this->email = isset($e) ? $e : '';
        $this->password = isset($p) ? md5($p) : '';
    }

    private function validate() {
        if(empty($this->email) || empty($this->password)) {
            return 'Не заполнены все поля!';
        }
    }

    public function register() {
        $errors = $this->validate();
        if($errors) {
            return array('error'=>$errors);
        }

        if($this->_db->query("select count(*) from users where user_email = ".$this->_db->quote($this->email)." and user_password = ".$this->_db->quote($this->password)."")->fetchColumn()) {
            $usr_id = $this->_db->query("select user_id from users where user_email = ".$this->_db->quote($this->email)." and user_password = ".$this->_db->quote($this->password)."")->fetchColumn();
            setcookie('usr_id',$usr_id,time()+3600*24*365,'/');
            setcookie('usr_pass',$this->password,time()+3600*24*365,'/');
            return array('error'=>'','msg'=>'Вы успешно авторизовались!');
        } else {
            return array('error'=>'Неверный данные для входа!');
        }
    }

}