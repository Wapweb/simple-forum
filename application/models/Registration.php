<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 30.08.13
 * Time: 20:30
 * To change this template use File | Settings | File Templates.
 */

class Registration {
    private $pass,$pass_repeat,$email,$login;
    /** @var  PDO $_db */
    private $_db;

    public function __construct($p,$p_r,$e,$l) {
        $this->pass = $p;
        $this->pass_repeat = $p_r;
        $this->email = $e;
        $this->login = $l;
    }

    private function validate() {
        $errors = '';
        if(empty($this->pass) || empty($this->pass_repeat) || empty($this->email) || empty($this->login)) {
            $errors.= "Заполните все поля!<br>";
        }
        if($this->pass != $this->pass_repeat) {
            $errors.= "Пароли не совпадают!<br>";
        }
        if($errors) {
            return $errors;
        }

        if(!preg_match("/^[a-zA-Z0-9]{3,50}$/", $this->login)) {
            $errors.= "Логин может содержать только буквы латинского алфавита и/или цифры. Длина логина от 3 до 50 символов<br>";
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            $errors.= "Невалидный email!<br>";
        }
        if(mb_strlen($this->pass,'UTF-8') < 4 || mb_strlen($this->pass,'UTF-8') > 16) {
            $errors.= "Пароль дожлен содержать от 4 до 16 символов!<br>";
        }
        return $errors;
    }

    public function save() {
        $validate = $this->validate();
        if($validate) {
            return array('error'=>$validate);
        }
        $this->_db = Registry::get('db');
        $errors = '';

        if($this->_db->query("select count(*) from users where user_email = ".$this->_db->quote($this->email)."")->fetchColumn() > 0) {
            $errors.= 'Данный <strong>Email</strong> зарегестрирован на форуме!<br>';
        }
        if($this->_db->query("select count(*) from users where user_login = ".$this->_db->quote($this->login)."")->fetchColumn() > 0) {
            $errors.= 'Данный <strong>Логин</strong> зарегестрирован на форуме!';
        }
        if($errors)  return array('error'=>$errors);


        $this->pass = md5($this->pass);
        $save = $this->_db->query("insert into users set
            user_email =".$this->_db->quote($this->email).",
            user_name = '',
            user_avatar = '',
            user_register_date = '".time()."',
            user_register_ip = '".intval(ip2long($_SERVER['REMOTE_ADDR']))."',
            user_register_agent = ".$this->_db->quote($_SERVER['HTTP_USER_AGENT']).",
            user_password = '".$this->pass."',
            user_login = ".$this->_db->quote($this->login)."
        ");
        if($save === false) {
            return array('error'=>'Ошибка добавления пользователя в базу данных. Обратитесь к администратору сайта');
        }
        setcookie('usr_id',$this->_db->lastInsertId(),time()+3600*24*365,'/');
        setcookie('usr_pass',$this->pass,time()+3600*24*365,'/');
        return array('error'=>'','msg'=>'Вы успешно зарегестрировались!');

    }
}