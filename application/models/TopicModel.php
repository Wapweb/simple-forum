<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 10.04.14
 * Time: 22:33
 */

class TopicModel extends ActiveRecord{
    protected  static $_tableName = "topics";
    protected static $_primaryKey = "topic_id";

    public $name;
    public $message_id;
    public $forum_id;
    public $user_id;
    public $user_login;
    public $create_date;
    public $last_date;

    protected function pack() {
        $this->_row[self::$_primaryKey] = (int)$this->getId();
        $this->_row["forum_id"] = (int)$this->forum_id;
        $this->_row["message_id"] = (int)$this->message_id;
        $this->_row["user_id"] = (int)$this->user_id;
        $this->_row["user_login"] = $this->user_login;
        $this->_row["topic_create_date"] = $this->create_date;
        $this->_row["topic_name"] = htmlspecialchars($this->name);
        $this->_row["topic_last_date"] = (int)$this->last_date;
    }

    protected function unpack($data) {
        $this->name = $data["topic_name"];
        $this->message_id = $data["message_id"];
        $this->forum_id = $data["forum_id"];
        $this->user_id = $data["user_id"];
        $this->user_login = $data["user_login"];
        $this->create_date = $data["topic_create_date"];
        $this->setId($data[self::$_primaryKey]);
        $this->last_date = $data["topic_last_date"];
    }


    /**
     * @param string $end
     * @param int $page
     * @param bool $pagination
     * @param int $on_page
     * @return MessageModel[]|bool
     */
    public function getMessages($end="",$page=0,$pagination=true,$on_page=10) {
        $condition = "WHERE ".self::$_primaryKey." = ".$this->getId()."";
        return MessageModel::findAll($end,$page,$condition,$pagination,$on_page,false);
    }

    /**
     * @param string $end
     * @param int $page
     * @param bool $pagination
     * @param int $on_page
     * @return MessageModel[]|bool
     */
    public function getMessagesJoinUserModel($end="",$page=0,$pagination=true,$on_page=10) {
        $condition = "WHERE ".self::$_primaryKey." = ".$this->getId()."";
        $query = "SELECT t1.*,t2.user_avatar FROM ".MessageModel::getTableName()." as t1
                    JOIN ".UserModel::getTableName()." as t2 USING(".UserModel::getPrimaryKey().")
                        WHERE t1.".self::$_primaryKey." = ".$this->getId()."
                            ORDER BY t1.".MessageModel::getPrimaryKey()." ASC
        ";
        return MessageModel::findAllFromQuery($end,$page,$condition,$pagination,$on_page,false,$query);
    }

    public static function findAllActive($end="",$page=0,$pagination=true,$on_page=10) {
        $condition = "";
        $query = "SELECT * FROM ".self::$_tableName." ORDER BY topic_last_date DESC
        ";
        return TopicModel::findAllFromQuery($end,$page,$condition,$pagination,$on_page,false,$query);
    }

    /**
     * @return bool|ForumModel
     */
    public function getForum() {
        return ForumModel::findById($this->forum_id);
    }

    /**
     * @return bool|MessageModel
     */
    public function getLastMessage() {
        $query = "
            SELECT * FROM ".MessageModel::getTableName()."
                WHERE ".self::$_primaryKey." = ".$this->getId()."
                    ORDER BY ".MessageModel::getPrimaryKey()." DESC LIMIT 1
        ";
        return MessageModel::findOneObjectByQuery($query);
    }

    /**
     * @param $message_text
     * @return array|bool
     */
    public function saveTopic($message_text) {
        $errors = '';
        $temp = "";
        if(isset($this->name)) {
            $temp = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$this->name);
            if(strlen($temp) == 0) {
                $errors.= "В названии темы не должны содержаться одни пробелы!<br>";
            }
        }
        if(isset($message_text)) {
            $temp = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$message_text);
            if(strlen($temp) == 0) {
                $errors.= "В сообщении не должны содержаться одни пробелы!<br>";
            }
        }

        $this->name = !$this->name ? $errors.= 'Введите название темы!<br>' : mb_substr($this->name,0,200,'UTF-8');
        $message_text = !$message_text ? $errors.= 'Введите ссобщение темы!<br>' : mb_substr($message_text,0,10000,'UTF-8');
        if($errors) {
            return array('error_status'=>1,'error_msg'=>$errors);
        }
        $this->create_date = time();
        $this->last_date = $this->create_date;
        $result = $this->save();
        if($result === false) {
            return array('error_status'=>1,'error_msg'=>'Ошибка создания темы! Обратитесь к администратору');
        }


        $text = nl2br($message_text);

        $Message = new MessageModel();
        $Message->topic_id =$this->getId();
        $Message->user_id = $this->user_id;
        $Message->user_login = $this->user_login;
        $Message->ip = ip2long($_SERVER['REMOTE_ADDR']);
        $Message->agent = $_SERVER['HTTP_USER_AGENT'];
        $Message->text = $text;
        $Message->create_date = time();

        $result = $Message->save();
        if($result === false) {
            return array('error_status'=>1,'error_msg'=>'Ошибка создания темы! Обратитесь к администратору');
        }

        $this->message_id = $Message->getId();
        $result = $this->save();

        return $result === false
            ?
            array('error_status'=>1,'error_msg'=>'Ошибка создания темы! Обратитесь к администратору')
            :
            array('error_status'=>0,'success_msg'=>'Тема успешно создана','url'=>HOME."/topic/show/".Utility::UrlTranslit($this->name."-".$this->getId()));
    }

    public static function getPrimaryKey() {
        return self::$_primaryKey;
    }

    public static function getTableName() {
        return self::$_tableName;
    }

} 