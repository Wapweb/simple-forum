<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 19.04.14
 * Time: 21:44
 */

class EmailModel extends ActiveRecord {
    protected static $_tableName = "mail";
    protected static $_primaryKey = "mail_id";

    public $recipient_id;
    public $title;
    public $body;
    public $sender_id;
    public $read = 0;
    public $create_date;

    public $user_login;


    protected function pack() {
        $this->_row[self::$_primaryKey] = (int)$this->getId();
        $this->_row["sender_id"] = (int)$this->sender_id;
        $this->_row["recipient_id"] = (int)$this->recipient_id;
        $this->_row["mail_title"] = htmlspecialchars($this->title);
        $this->_row["mail_body"] = htmlspecialchars($this->body);
        $this->_row["mail_create_date"] = (int)$this->create_date;
        $this->_row["read"] = (int)$this->read;
    }

    protected function unpack($data) {
        $this->setId($data[self::$_primaryKey]);
        $this->sender_id = $data["sender_id"];
        $this->recipient_id = $data["recipient_id"];
        $this->title = $data["mail_title"];
        $this->body = $data["mail_body"];
        $this->create_date = $data["mail_create_date"];
        $this->read = $data["read"];

        $this->user_login = isset($data["user_login"]) ? $data["user_login"] : "";
    }

    public static function findAllInbox($end,$page,$recipient_id) {
        $recipient_id = (int)$recipient_id;
        $condition = "WHERE recipient_id = '$recipient_id'";
        $query  = "
            SELECT t1.*,t2.user_login FROM ".self::$_tableName." as t1
                JOIN ".UserModel::getTableName()." as t2 ON
                    t1.sender_id = t2.".UserModel::getPrimaryKey()."
                        WHERE t1.recipient_id = '$recipient_id' ORDER BY ".self::$_primaryKey." DESC
        ";
        return parent::findAllFromQuery($end,$page,$condition,true,10,true,$query);
    }

    public static function findAllSent($end,$page,$sender_id) {
        $sender_id = (int)$sender_id;
        $condition = "WHERE sender_id = '$sender_id'";
        $query  = "
            SELECT t1.*,t2.user_login FROM ".self::$_tableName." as t1
                JOIN ".UserModel::getTableName()." as t2 ON
                    t1.recipient_id = t2.".UserModel::getPrimaryKey()."
                        WHERE t1.sender_id = '$sender_id' ORDER BY ".self::$_primaryKey." DESC
        ";
        return parent::findAllFromQuery($end,$page,$condition,true,10,true,$query);
    }

    /**
     * @param UserModel $user
     * @return bool
     */
    public static function  hasUserNewEmail(UserModel $user) {
        if($user == null) {
            return false;
        }
        $and_condition = " AND `read` = '0'";
        $result = Utility::checkId("recipient_id",self::$_tableName,$user->getId(),false,$and_condition);
        return $result > 0 ? true : false;
    }

    static function getTableName() {
        return self::$_tableName;
    }

    static function getPrimaryKey() {
        return self::$_primaryKey;
    }




} 