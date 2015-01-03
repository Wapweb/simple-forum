<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 11.04.14
 * Time: 0:19
 */

class MessageModel extends ActiveRecord{
    protected  static $_tableName = "messages";
    protected static $_primaryKey = "message_id";

    public $topic_id;
    public $user_id;
    public $user_login;
    public $ip;
    public $agent;
    public $text;
    public $create_date;
    public $update_date = 0;
    public $update_count =0;
    public $last_update_id = 0;
    public $last_update_login =0;
    public $rating = 0;

    public $user_avatar;


    public function __construct() {}


    /*
     *     public static function findAll($end = "",$page =0,$filter= "",$pagination = true,$on_page = 10) {
        $condition = "";
        if(!empty($filter)) {
            $filters = array("admin"=>10,"moderator"=>2,"user"=>1);

            if(isset($filters[$filter])) {
                $condition = "WHERE user_level = {$filters[$filter]}";
            } else {
                throw new Exception("404 - Страница не найдена");
            }
        }
        return parent::findAll($end,$page,$condition,$pagination,$on_page);
    }
     */


    /**
     * @return bool|TopicModel
     */
    public function getTopic() {
        return TopicModel::findById($this->topic_id);
    }

    /**
     * @return FileModel[]|bool
     */
    public function getFiles() {
        $condition = "WHERE ".self::$_primaryKey." = ".$this->getId()."";
        return FileModel::findAll("",0,$condition,false);
    }

    public function delete() {
        self::$_db->query("
            DELETE FROM ".FileModel::getTableName()."
                WHERE ".self::$_primaryKey." = '".$this->getId()."'
        ");
        return parent::delete();
    }

    /**
     * @return bool
     */
    public function save(UserModel $userWhoUpdate = null) {
        //update topic set new topic_last_date
        self::$_db->query("
                    UPDATE ".TopicModel::getTableName()."
                    SET `topic_last_date` = '".$this->create_date."'
                        WHERE ".TopicModel::getPrimaryKey()." = '".$this->topic_id."'
                    ");
        if($userWhoUpdate) {
            $this->update_date = time();
            $this->last_update_id = $userWhoUpdate->getId();
            $this->last_update_login = $userWhoUpdate->login;
            $this->update_count++;
        }
        return parent::save();
    }

    protected function pack() {
        $this->_row[self::$_primaryKey] = (int)$this->getId();
        $this->_row["topic_id"] = (int)$this->topic_id;
        $this->_row["user_id"] = (int)$this->user_id;
        $this->_row["user_login"] = htmlspecialchars($this->user_login);
        $this->_row["message_ip"] = (int)$this->ip;
        $this->_row["message_agent"] = htmlspecialchars($this->agent);
        $this->_row["message_text"] = htmlspecialchars($this->text);
        $this->_row["message_create_date"] = (int)$this->create_date;
        $this->_row["message_update_date"] = (int)$this->update_date;
        $this->_row["message_update_count"] = (int)$this->update_count;
        $this->_row["message_last_update_id"] = (int)$this->last_update_id;
        $this->_row["message_last_update_login"] = $this->last_update_login;
        $this->_row["message_rating"] = (int)$this->rating;
    }

    protected function unpack($data) {
        $this->setId($data[self::$_primaryKey]);
        $this->topic_id = $data["topic_id"];
        $this->user_id = $data["user_id"];
        $this->user_login = $data["user_login"];
        $this->ip = $data["message_ip"];
        $this->agent = $data["message_agent"];
        $this->text = $data["message_text"];
        $this->create_date = $data["message_create_date"];
        $this->update_date = $data["message_update_date"];
        $this->update_count = $data["message_update_count"];
        $this->last_update_id = $data["message_last_update_id"];
        $this->rating = $data["message_rating"];
        $this->last_update_login = $data["message_last_update_login"];

        $this->user_avatar = isset($data["user_avatar"]) ? $data["user_avatar"] : "";
    }

    public static function getTableName() {
        return self::$_tableName;
    }

    public static function getPrimaryKey() {
        return self::$_primaryKey;
    }
} 