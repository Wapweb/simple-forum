<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 01.04.14
 * Time: 18:41
 */

abstract class ActiveRecord {
    /** @var PDO $_db */
    protected  static $_db;
    protected  static $_tableName;
    protected static $_primaryKey;
    public static $navigation = "";
    private static $columns = array();
    protected  $_row;
    private $_id;

    public function __construct() {
        self::$_db = Registry::get("db");
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save() {
        $id = static::getId();
        static::pack();
        $query = "";

        if($id > 0)
            $query = "UPDATE ".static::$_tableName." SET";
        else
            $query = "INSERT INTO ".static::$_tableName." SET";

        $parameters = array();
        $count = count($this->_row);
        $i=0;
        foreach($this->_row as $key => $value) {
            $i++;
            if($key != static::$_primaryKey) {
                $query.= " `{$key}` = :{$key}";
                if($i < $count) $query.= ",";
                $parameters[":$key"] = $value;
            }
        }

        if($id) {
            $query.= " WHERE ".static::$_primaryKey." = :".static::$_primaryKey;
            $parameters[":".static::$_primaryKey] = $this->_row[static::$_primaryKey];
        }

        $sth = self::$_db->prepare($query);
        $result = $sth->execute($parameters);
        if($result === false) {
            $arr = $sth->errorInfo();
            throw new Exception($arr[2]);
        }
        if(!$id)
            $this->setId(self::$_db->lastInsertId());

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public  function delete() {
        if(!$this->_id) {
            return false;
        }

        $sth = self::$_db->prepare("DELETE FROM ".static::$_tableName." WHERE ".static::$_primaryKey." = :id");
        $sth->bindParam(":id",$this->_id);
        $result = $sth->execute();
        if($result === false) {
            $arr = $sth->errorInfo();
            throw new Exception($arr[2]);
        }
        return true;
    }

    public static function deleteById($id) {
        $id = (int)$id;
        if(!$id) {
            throw new Exception("Невозможно удалить несуществующий объект");
        }

        //$sth = self::$_db->prepare("DELETE FROM ".static::$_tableName." WHERE ".static::$_primaryKey." = :id");
        //$sth->bindParam(":id",$id);
        //$result = $sth->execute();
        $result = self::$_db->exec("DELETE FROM ".static::$_tableName." WHERE ".static::$_primaryKey." = '".$id."'");
        if($result == 0) {
            throw new Exception("Невозможно удалить несуществующий объект");
        }
        return true;
    }

    /**
     * @param string $end
     * @param $page
     * @param bool $pagination
     * @param int $on_page
     * @param string $condition
     * @return array
     */
    public static function findAll($end = "",$page = 1,$condition = "",$pagination = true,$on_page = 10,$desc = true) {
        self::$_db = Registry::get("db");
        $page = (int)$page;
        $on_page = abs(intval($on_page));
        $desc = $desc ? "DESC" : "ASC";
        if($pagination) {
            $count = self::$_db->query("SELECT COUNT(*) FROM ".static::$_tableName." $condition")->fetchColumn();
            if(!$count) {
                return array();
            }
            $pager = new Pager($count,$on_page,$end."/?page=",$page);
            $q = "SELECT * FROM ".static::$_tableName." $condition order by ".static::$_primaryKey." $desc LIMIT ".$pager->get_start().", ".$pager->on_page."";
            $sth = self::$_db->query("SELECT * FROM ".static::$_tableName." $condition order by ".static::$_primaryKey." $desc LIMIT ".$pager->get_start().", ".$pager->on_page."");
            $className = get_called_class();
            $list = array();
            while($fetchData = $sth->fetch(PDO::FETCH_ASSOC)) {
                $obj = new $className();
                $obj->unpack($fetchData);
                $list[] = $obj;
            }
            self::$navigation = $pager->print_nav();
            return $list;
        } else {
            $count = self::$_db->query("SELECT COUNT(*) FROM ".static::$_tableName." $condition")->fetchColumn();
            if(!$count) {
                return array();
            } else {
                $sth = self::$_db->query("SELECT * FROM ".static::$_tableName." $condition order by ".static::$_primaryKey." $desc");
                $className = get_called_class();
                $list = array();
                while($fetchData = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $obj = new $className();
                    $obj->unpack($fetchData);
                    $list[] = $obj;
                }
                return $list;
            }
        }
    }

    /**
     * @param string $end
     * @param $page
     * @param bool $pagination
     * @param int $on_page
     * @param string $condition
     * @throws Exception
     * @return array|bool
     */
    public static function findAllFromQuery($end = "",$page = 1,$condition = "",$pagination = true,$on_page = 10,$desc = true,$query) {
        self::$_db = Registry::get("db");
        $page = (int)$page;
        $on_page = abs(intval($on_page));
        $desc = $desc ? "DESC" : "ASC";
        if($pagination) {
            $count = self::$_db->query("SELECT COUNT(*) FROM ".static::$_tableName." $condition")->fetchColumn();
            if(!$count) {
                return array();
            }
            $pager = new Pager($count,$on_page,$end."/?page=",$page);
            $q = "$query LIMIT ".$pager->get_start().", ".$pager->on_page."";
            $sth = self::$_db->query("$query LIMIT ".$pager->get_start().", ".$pager->on_page."");
            $className = get_called_class();
            $list = array();
            while($fetchData = $sth->fetch(PDO::FETCH_ASSOC)) {
                $obj = new $className();
                $obj->unpack($fetchData);
                $list[] = $obj;
            }
            self::$navigation = $pager->print_nav();
            return $list;
        } else {
            $q =
            $count = self::$_db->query("SELECT COUNT(*) FROM ".static::$_tableName." $condition")->fetchColumn();
            if(!$count) {
                return array();
            } else {
                $sth = self::$_db->query("SELECT * FROM ".static::$_tableName." $condition order by ".static::$_primaryKey." $desc");
                $className = get_called_class();
                $list = array();
                while($fetchData = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $obj = new $className();
                    $obj->unpack($fetchData);
                    $list[] = $obj;
                }
                return $list;
            }
        }
    }


    /**
     * @param $id
     * @throws Exception
     * @return bool|object
     */
    public static function findById($id){
        $id = (int)$id;
        self::$_db = Registry::get("db");
        $sth = self::$_db->prepare("SELECT * FROM ".static::$_tableName." where ".static::$_primaryKey." = :id LIMIT 1");
        $sth->bindParam(":id",$id,PDO::PARAM_INT);
        $sth->execute();

        if(!$sth->rowCount()) {
            throw new Exception("Запись не найдена");
            //return false;
        }

        $fetchData = $sth->fetch(PDO::FETCH_ASSOC);
        $className = get_called_class();
        $obj = new $className();
        $obj->unpack($fetchData);

        return $obj;
    }

    /**
     * @param string $query
     * @param bool $thorw
     * @return bool|object
     * @throws Exception
     */
    public static function findOneObjectByQuery($query,$throw = true){
        self::$_db = Registry::get("db");
        $sth = self::$_db->query($query);

        if(!$sth->rowCount()) {
            if($throw) {
                throw new Exception("Запись не найдена");
            } else {
                return false;
            }
        }

        $fetchData = $sth->fetch(PDO::FETCH_ASSOC);
        $className = get_called_class();
        $obj = new $className();
        $obj->unpack($fetchData);

        return $obj;
    }

    public static function getRowCount($condition = "") {
        /** @var PSO _db */
        self::$_db = Registry::get("db");
        $count = self::$_db->query("SELECT COUNT(*) FROM ".static::$_tableName." $condition")->fetchColumn();
        return $count;
    }

    private static function setTableColumns()  {
        $res = self::$_db->query("SELECT * from ".self::$_tableName." LIMIT 0");
        for ($i = 0; $i < $res->columnCount(); $i++) {
            $col = $res->getColumnMeta($i);
            self::$columns[] = $col['name'];
        }
    }

    public function getId() {
        return $this->_id;
    }
    public function setId($id) {
        $this->_id = (int)$id;
    }

    protected abstract  function pack();
    protected abstract function unpack($data);

} 