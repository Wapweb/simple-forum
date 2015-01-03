<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 21.06.13
 * Time: 10:25
 * To change this template use File | Settings | File Templates.
 */

class User {
    private $user_id,$user_password,$DB,$user_data;
    public function __construct($id,$pass,$auth = false) {
        $this->DB = Registry::get('DB');
        $this->user_id = $id ? abs(intval($id)) : 0;
        $this->user_password = $pass ? $this->DB->StrFilter($pass) : '';

        $this->user_data = $this->getUserData();
    }
    public  function Check() {
        if($this->user_password && $this->user_id) {
            if($this->DB->Row("select count(*) from users where user_id = ".$this->user_id." and user_password = ".$this->user_password."")) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getUserData() {
        $result = array('user_id'=>0,'user_level'=>0,'user_login'=>'','settings_count_of_page'=>Utility::$msg_on_page,'settings_who_online'=>0,'settings_quick_answer'=>0);
        if(!$this->Check()) {
            return $result;
        }
        $DB = Registry::get('DB');
        $result = $DB->Query("select * from users where user_id = ".$this->user_id." and user_password = ".$this->user_password."")->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getData() {
        return $this->user_data;
    }

    public function getUserId() {
        return $this->user_data['user_id'];
    }
    public function getUserLevel() {
        return $this->user_data['user_level'];
    }

    public function getUserLogin() {
        return $this->user_data['user_login'];
    }

    function changePassword($new_pass,$new_pass_repeat,$old_pass,$current_user = true) {
        if($current_user) {
            if(!($new_pass) || !($new_pass_repeat) || !($old_pass)) {
                return array("error"=>'Заполните все поля!');
            }

            $errors = '';
            if($new_pass_repeat != $new_pass) {
                $errors.= 'Новый и старый пароль не совпадают!<br>';
            }
            $userData = $this->getUserData();
            if(md5($old_pass) != $userData['user_password']) {
                $errors.= 'Старый пароль введен неверно!<br>';
            }

            if(mb_strlen($new_pass,'UTF-8') < 4 || mb_strlen($new_pass,'UTF-8') > 16) {
                $errors.= 'Длина пароля от 4 до 16 символов!<br>';
            }

            if($errors) {
                return array('error'=>$errors);
            }
            $np = md5($new_pass);
            $query = $this->DB->Query("update users set user_password = ".$this->DB->StrFilter($np)." where user_id = '".$userData['user_id']."'");
            if($query !== false) {
                setcookie('usr_pass',$np,time()+3600*24*365,'/');
                setcookie('usr_id',$userData['user_id'],time()+3600*24*365,'/');
                return array('error'=>'','msg'=>'Пароль сменен!');
            } else {
                return array('error'=>'Ошибка изменения пароля. Обратитесь к администратору!');
            }
        }
    }

    public function update(array $settings) {
        $string = '';
        $count = 0;
        foreach($settings as $key=>$value) {
            $string.= "$key = ".$this->DB->StrFilter($value).($count == count($settings)-1 ? "" : ",");
            $count++;
        }
        $query = "update users set $string where user_id = '".$this->getUserId()."'";
        $res = $this->DB->Query($query);
        if($res === false) {
            //throw new Exception("Ошибка обновления пользовательских данных");
            return array('error_status'=> 1,'error_msg'=>'Ошибка обновления настроек');
        }
        return array('error_status'=>0, 'error_msg'=>'','success_msg'=>'Настройки обновлены');
    }

}