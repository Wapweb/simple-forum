<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 15.04.14
 * Time: 15:06
 */

class FileModel extends ActiveRecord {
    protected  static $_tableName = "files";
    protected static $_primaryKey = "file_id";

    public $name;
    public $message_id = 0;
    public $user_id;
    public $path;
    public $create_date;


    protected function pack() {
        $this->_row[self::$_primaryKey] = $this->getId();
        $this->_row["file_name"] = $this->name;
        $this->_row["message_id"] = (int)$this->message_id;
        $this->_row["file_path"] = $this->path;
        $this->_row["file_date_create"] = $this->create_date;
        $this->_row["user_id"] = $this->user_id;
    }

    protected function unpack($data) {
        $this->setId((int)$data[self::$_primaryKey]);
        $this->name = $data["file_name"];
        $this->message_id = (int)$data["message_id"];
        $this->path = $data["file_path"];
        $this->create_date  =$data["file_date_create"];
        $this->user_id = $data["user_id"];
    }

    public static function getTableName() {
        return self::$_tableName;
    }

    public static function getPrimaryKey() {
        return self::$_primaryKey;
    }
} 