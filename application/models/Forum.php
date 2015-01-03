<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 06.09.13
 * Time: 18:47
 * To change this template use File | Settings | File Templates.
 */

class Forum {
    private $DB;
    public function __construct($pdo = null) {
        $this->DB = $pdo instanceof PDO ? $pdo :Registry::get('DB');
    }

    public function get($forum_id) {
        $forum_id = $this->DB->IntFilter($forum_id);
        if(!$this->DB->Row("select count(*) from forums where forum_id = '$forum_id'")) {
            throw new Exception('Неверный идентификатор форума!');
        }

        return $this->DB->Query("select * from forums where forum_id = '$forum_id'")->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($catalog_id) {
        $catalog_id = abs(intval($catalog_id));
        if(!$this->check($catalog_id)) {
            throw new Exception('Неверный ID раздела');
        }

        $count = $this->DB->Row("select count(*) from forums where catalog_id = '$catalog_id'");
        if(!$count) {
            return array('data'=>array(),'count' => 0);
        }

        $query = $this->DB->Query("select * from forums where catalog_id = '$catalog_id'");
        $data = array();
        while($m = $query->fetch(PDO::FETCH_ASSOC)) $data[] = $m;
        return array('count'=>$count,'data'=>$data);
    }

    public function create($catalog_id,$fname) {
        if(!$fname) {
            return array('error'=>'Неверное имя раздела!');
        }

        $fname = $this->DB->StrFilter(mb_substr($fname,0,255,'UTF-8'));
        $catalog_id = $this->DB->IntFilter($catalog_id);
        $query = $this->DB->Query("insert into forums set forum_name = $fname, catalog_id = '$catalog_id'");
        if($query === false) {
            return array('error'=>'Ошибка создание форума. Обратитесь к администратору!');
        }
        return array('error'=>'','msg'=>'Форум успешно создан!','forum_url'=>Utility::UrlTranslit($fname)."-".$this->DB->GetLastId());
    }
    
    public function check($catalog_id,$forum = false) {
        $catalog_id = intval($catalog_id);
        return !$forum ? $this->DB->Row("select count(*) from catalogs where catalog_id = '$catalog_id'") : $this->DB->Row("select count(*) from forums where forum_id = '$catalog_id'");
    }

    public function delete($forum_id) {
        $forum_id = $this->DB->IntFilter($forum_id);
        if(!$this->DB->Row("select count(*) from forums where forum_id ='$forum_id'")) {
            throw new Exception('Неверный ID форума');
        }

        $query = $this->DB->Query("delete from forums where forum_id = '$forum_id'");
        if($query === false) {
            return array('error'=>'Ошибка удаления форума. Обратитесь к администратору!');
        }

        //удаляем топики, сообщения и вложения
        $tquery = $this->DB->Query("select topic_id from topics where forum_id = '$forum_id'");
        while($s = $tquery->fetch(PDO::FETCH_ASSOC)) {
            $fquery = $this->DB->Query("select file_name from messages_files where topic_id = '".$s['topic_id']."'");
            while($f = $fquery->fetch(PDO::FETCH_ASSOC)) {
                unlink(ROOT."/data/forum_files/".$f['file_name']);
            }
            $this->DB->Query("delete from messages_files where topic_id = '".$s['topic_id']."'");
            $this->DB->Query("delete from messages where topic_id = '".$s['topic_id']."'");
        }

        return array('error'=>'','msg'=>'Форум успешно удален!');
    }

    public function update($forum_id,$forum_name) {
        $forum_id = $this->DB->IntFilter($forum_id);
        if(!$this->DB->Row("select count(*) from forums where forum_id ='$forum_id'")) {
            throw new Exception('Неверный ID форума');
        }

        if(!$forum_name) {
            return array('error'=>'Пустое название раздела!');
        }

        $forum_name = $this->DB->StrFilter(mb_substr($forum_name,0,255,'UTF-8'));

        $query = $this->DB->Query("update forums set forum_name = $forum_name where forum_id = '$forum_id'");
        if($query === false) {
            return array('error'=>'Ошибка изменения форума. Обратитесь к администратору!');
        }
        return array('error'=>'','msg'=>'Форум успешно изменен!','forum_url'=>Utility::UrlTranslit($forum_name)."-".$forum_id);
    }
}