<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 03.09.13
 * Time: 19:53
 * To change this template use File | Settings | File Templates.
 */

class Category {
    private $DB;
    public function __construct($pdo = null) {
        $this->DB = $pdo instanceof PDO ? $pdo :Registry::get('DB');
    }

    public function create($cname) {
        if(!$cname) {
            return array('error'=>'Неверное имя раздела!');
        }

        $cname = $this->DB->StrFilter(mb_substr($cname,0,255,'UTF-8'));
        $query = $this->DB->Query("insert into catalogs set catalog_name = $cname");
        if($query === false) {
            return array('error'=>'Ошибка создание раздела. Обратитесь к администратору!');
        }
        return array('error'=>'','msg'=>'Раздел успешно создан!');
    }

    public function delete($catalog_id) {
        $catalog_id = $this->DB->IntFilter($catalog_id);
        if(!$this->check($catalog_id)) {
            return array('error'=>'Неверный идентификатор раздела!');
        }

        $query = $this->DB->Query("delete from catalogs where catalog_id = '$catalog_id'");
        if($query === false) {
            return array('error'=>'Ошибка удаления раздела. Обратитесь к администратору!');
        }

        //удаляем форумы, топики, сообщения и вложения
        $fquery = $this->DB->Query("select forum_id from forums where catalog_id = '$catalog_id'");
        while($m = $fquery->fetch(PDO::FETCH_ASSOC)) {
            $tquery = $this->DB->Query("select topic_id from topics where forum_id = '".$m['forum_id']."'");
            while($s = $tquery->fetch(PDO::FETCH_ASSOC)) {

                $fquery = $this->DB->Query("select file_name from messages_files where topic_id = '".$s['topic_id']."'");
                while($f = $fquery->fetch(PDO::FETCH_ASSOC)) {
                    unlink(ROOT."/data/forum_files/".$f['file_name']);
                }
                $this->DB->Query("delete from messages_files where topic_id = '".$s['topic_id']."'");
                $this->DB->Query("delete from messages where topic_id = '".$s['topic_id']."'");
            }
            $this->DB->Query("delete from topics where forum_id = '".$m['topic_id']."'");
        }
        $this->DB->Query("delete from forums where catalog_id = '$catalog_id'");
        return array('error'=>'','msg'=>'Раздел успешно удален!');
    }

    public function update($catalog_id,$catalog_name) {
        $catalog_id = $this->DB->IntFilter($catalog_id);
        if(!$this->check($catalog_id)) {
            return array('error'=>'Неверный идентификатор раздела!');
        }
        if(!$catalog_name) {
            return array('error'=>'Пустое название раздела!');
        }

        $catalog_name = $this->DB->StrFilter(mb_substr($catalog_name,0,255,'UTF-8'));

        $query = $this->DB->Query("update catalogs set catalog_name = $catalog_name where catalog_id = '$catalog_id'");
        if($query === false) {
            return array('error'=>'Ошибка изменения раздела. Обратитесь к администратору!');
        }
        return array('error'=>'','msg'=>'Раздел успешно изменен!','catalog_url'=>Utility::UrlTranslit($catalog_name)."-".$catalog_id);
    }

    public function get($catalog_id) {
        $catalog_id = $this->DB->IntFilter($catalog_id);
        if(!$this->check($catalog_id)) {
            throw new Exception('Неверный идентификатор раздела!');
        }

        return $this->DB->Query("select * from catalogs where catalog_id = '$catalog_id'")->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $data = array();
        $query = $this->DB->Query("select * from catalogs");
        while($m = $query->fetch(PDO::FETCH_ASSOC)) $data[] = $m;
        return $data;
    }

    public function getAllwithForums() {
        $data = array();
        $query = $this->DB->Query("select * from catalogs");
        while($m = $query->fetch(PDO::FETCH_ASSOC)) {
            //$data[] = $m;
            $forum_data = array();
            $q2 = $this->DB->Query("select * from forums where catalog_id = '".$m['catalog_id']."'");
            while($s = $q2->fetch(PDO::FETCH_ASSOC)) $forum_data[] = $s;
            //$data['catalog_data'][]['forum_data'] = $forum_data;
            $m['forum_data'] = $forum_data;
            $data[] = $m;
        }
        return $data;
    }

    public function check($catalog_id) {
        $catalog_id = intval($catalog_id);
        if(!$this->DB->Row("select count(*) from catalogs where catalog_id = '$catalog_id'")) {
            return false;
        }
        return true;
    }
}