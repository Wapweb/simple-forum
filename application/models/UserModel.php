<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 01.04.14
 * Time: 18:50
 */

class UserModel extends ActiveRecord{
    protected  static $_tableName = "users";
    protected static $_primaryKey = "user_id";

    public $login;
    public $password;
    public $level;
    public $name = "";
    public $email;
    public $register_date;
    public $register_agent;
    public $register_ip;
    public $blocked = 0;
    public $avatar = "";
    public $settings_quick_answer = 1;
    public $settings_who_online = 1;
    public $settings_count_of_page = 10;
    public $settings_message_avatar = 1;


    /*private static $_columns = array(
        "user_id",
        "user_login",
        "user_password",
        "user_level",
        "user_name",
        "user_email",
        "user_register_date",
        "user_register_ip",
        "user_register_agent",
        "user_blocked",
        "settings_quick_answer",
        "settings_who_online",
        "settings_count_of_page"
    );*/


    protected function pack() {
        $this->_row["user_id"] = $this->getId();
        $this->_row["user_login"] = $this->login;
        $this->_row["user_password"] = $this->password;
        $this->_row["user_level"] = $this->level;
        $this->_row["user_name"] = $this->name;
        $this->_row["user_email"] = $this->email;
        $this->_row["user_register_date"] = $this->register_date;
        $this->_row["user_register_ip"] = $this->register_ip;
        $this->_row["user_register_agent"] = $this->register_agent;
        $this->_row["user_blocked"] = $this->blocked;
        $this->_row["settings_quick_answer"] = $this->settings_quick_answer;
        $this->_row["settings_who_online"] = $this->settings_who_online;
        $this->_row["settings_count_of_page"] = $this->settings_count_of_page;
        $this->_row["user_avatar"] = $this->avatar;
    }

    protected  function unpack($data) {
        $this->setId($data["user_id"]);
        $this->name = $data["user_name"];
        $this->login = $data["user_login"];
        $this->level = $data["user_level"];
        $this->password = $data["user_password"];
        $this->email = $data["user_email"];
        $this->register_agent = $data["user_register_agent"];
        $this->register_date = $data["user_register_date"];
        $this->register_ip = $data["user_register_ip"];
        $this->blocked = $data["user_blocked"];
        $this->settings_quick_answer = $data["settings_quick_answer"];
        $this->settings_who_online = $data["settings_who_online"];
        $this->settings_count_of_page = $data["settings_count_of_page"];
        $this->avatar = $data["user_avatar"];
    }

    /**
     * @param string $end
     * @param int $page
     * @param string $filter
     * @param bool $pagination
     * @param int $on_page
     * @return UserModel[]|bool
     */
    public static function findAll($end = "",$page =0,$filter= "",$pagination = true,$on_page = 10,$desc = true) {
        $condition = "";
        if(!empty($filter)) {
            $filters = array("admin"=>10,"moderator"=>2,"user"=>1);

            if(isset($filters[$filter])) {
                $condition = "WHERE user_level = {$filters[$filter]}";
            } else {
                throw new Exception("404 - Страница не найдена");
            }
        }
        return parent::findAll($end,$page,$condition,$pagination,$on_page,$desc);
    }

    public static function searchUsersByLogin($userLogin) {
        $userLogin = self::$_db->quote("%".$userLogin."%");
        $condition = "WHERE user_login LIKE $userLogin";
        return parent::findAll("",0,$condition,false);
    }

    /**
     * @param $id
     * @return UserModel
     */
    public static function findById($id) {
        return parent::findById($id);
    }

    public function getUserType() {
        return Utility::getUserStatus($this->level);
    }

    /**
     * @param $id
     * @param $password
     * @return null|UserModel
     */
    public static function getUserFromCookie($id,$password) {
        self::$_db =Registry::get("db");
        $id = (int)$id;
        $password = self::$_db->quote($password);
        $count = self::$_db->query("SELECT COUNT(*) FROM ".self::$_tableName." WHERE user_id = $id AND user_password = $password")->fetchColumn();
        if($count > 0) {
            return self::findById($id);
        }
        $guestUser = new UserModel();
        $guestUser->login = "GUEST";
        $guestUser->level = 0;
        return $guestUser;
    }

    /**
     * @param $login
     * @return UserModel|bool
     */
    public static function getUserByLogin($login) {
        self::$_db =Registry::get("db");
        $login = self::$_db->quote($login);
        $count = self::$_db->query("SELECT COUNT(*) FROM ".self::$_tableName." WHERE user_login = $login")->fetchColumn();
        if(!$count) {
           return false;
        }

        $fetchData = self::$_db->query("SELECT * FROM ".self::$_tableName." WHERE user_login = $login LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $user = new UserModel();
        $user->unpack($fetchData);
        return $user;
    }

    /**
     * @param $new_pass
     * @param $new_pass_repeat
     * @param $old_pass
     * @return array
     */
    public function changePassword($new_pass,$new_pass_repeat,$old_pass) {
        if(!($new_pass) || !($new_pass_repeat) || !($old_pass)) {
            return array("error"=>'Заполните все поля!');
        }

        $errors = '';
        if($new_pass_repeat != $new_pass) {
            $errors.= 'Новый и старый пароль не совпадают!<br>';
        }
        if(md5($old_pass) != $this->password) {
            $errors.= 'Старый пароль введен неверно!<br>';
        }

        if(mb_strlen($new_pass,'UTF-8') < 4 || mb_strlen($new_pass,'UTF-8') > 16) {
            $errors.= 'Длина пароля от 4 до 16 символов!<br>';
        }

        if($errors) {
            return array('error'=>$errors);
        }
        $np = md5($new_pass);
        $this->password = $np;
        $res = $this->save();
        if($res !== false) {
            setcookie('usr_pass',$this->password,time()+3600*24*365,'/');
            setcookie('usr_id',$this->getId(),time()+3600*24*365,'/');
            return array('error'=>'','msg'=>'Пароль сменен!');
        } else {
            return array('error'=>'Ошибка изменения пароля. Обратитесь к администратору!');
        }
    }

    public static function getTableName() {
        return self::$_tableName;
    }

    public static function getPrimaryKey() {
        return self::$_primaryKey;
    }


} 