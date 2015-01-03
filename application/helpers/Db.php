<?php

class Db {
    private $dsn,$user,$password;
    private $DB;
    public function __construct($connection = array(), PDO $obj = null) {

        if($obj !== null && $obj instanceof PDO) {
            $this->DB = $obj;
        } else {
            $this->dsn = $connection['dsn'];
            $this->user = $connection['user'];
            $this->password = $connection['password'];
            $this->DB = $this->Init();
        }

    }

    public function Query($string) {
        return $this->DB->query($string);
    }

    public function Exec($string) {
        return $this->DB->exec($string);
    }

    public function Row($string) {
        if(is_object($string))
            return $string->fetchColumn();
			
		$res = $this->DB->query($string);
		if($res === false)
			return $res;
	
        return $res->fetchColumn();
    }

    public  function Quote($param) {
        return $this->DB->quote($param);
    }

    public function StrFilter($value) {
        return trim($this->Quote(htmlspecialchars($value)));
    }

    public function IntFilter($value) {
        return abs(intval($value));
    }

    public function GetLastId() {
        return $this->DB->lastInsertId();
    }

    private function Init() {
        try {
            $dbh = "";
            $dbh = new PDO($this->dsn,$this->user,$this->password);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
        return $dbh;
    }
}