<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 02.04.14
 * Time: 21:34
 */

class ForumModel extends ActiveRecord {
    protected  static $_tableName = "forums";
    protected static $_primaryKey = "forum_id";

    public $name;
    public $catalog_id;

    public function __construct() {
       // self::$_db = Registry::get("db");
    }

    protected function pack() {
        $this->_row[self::$_primaryKey] = $this->getId();
        $this->_row["catalog_id"] = $this->catalog_id;
        $this->_row["forum_name"] = htmlspecialchars($this->name);
    }

    protected function unpack($data) {
        $this->setId($data[self::$_primaryKey]);
        $this->name = $data["forum_name"];
        $this->catalog_id = $data["catalog_id"];
    }

    /**
     * @return TopicModel[]|bool
     */
    public function getTopics($end="",$page=0,$pagination = false,$on_page) {
        $condition = "WHERE ".self::$_primaryKey." = ".$this->getId()."";
        return TopicModel::findAll($end,$page,$condition,$pagination,$on_page);
    }

    /**
     * @return bool|CatalogModel
     */
    public function getCatalog() {
        return CatalogModel::findById($this->catalog_id);
    }

    /**
     * @return string
     */
    public static function getPrimaryKey() {
        return self::$_primaryKey;
    }
} 