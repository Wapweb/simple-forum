<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 02.04.14
 * Time: 21:44
 */

class CatalogModel extends ActiveRecord {

    protected  static $_tableName = "catalogs";
    protected static $_primaryKey = "catalog_id";

    public $name;

    public function __construct() {
        self::$_db = Registry::get("db");
    }

    protected function pack() {
        $this->_row[self::$_primaryKey] = $this->getId();
        $this->_row["catalog_name"] = $this->name;
    }

    protected function unpack($data) {
        $this->setId($data[self::$_primaryKey]);
        $this->name = $data["catalog_name"];
    }

    /**
     * @return ForumModel[]|bool
     */
    public function getForums() {
        $condition = "WHERE ".self::$_primaryKey." = ".$this->getId();
        return ForumModel::findAll("",0,$condition,false);
    }

} 