<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 01.09.13
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */

class SingleUser {
    private $DB;

    function __construct() {
        $this->DB = Registry::get('DB');
    }

    function find($login) {
        $login = $login ? $this->DB->StrFilter($login) : '';
        if(!$this->DB->Row("select count(*) from users where user_login = $login")) {
            return array("error"=>"Пользователь не найден");
        }
        $query = $this->DB->Query("select * from users where user_login = $login");
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return array("error"=>"",'data'=>$data);
    }

    function findById($user_id) {
        $user_id = $user_id ? $this->DB->IntFilter($user_id) : 0;
        if(!$this->DB->Row("select count(*) from users where user_id = $user_id")) {
            return array("error"=>"Пользователь не найден");
        }
        $query = $this->DB->Query("select * from users where user_id = $user_id");
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return array("error"=>"",'data'=>$data);
    }

    function findAll($filter = '',$page = '') {
        $filters = array(10=>"admin",2=>'moderator',1=>'user');
        $where = '';
        $end = HOME."/users/list";
        if($filter) {
            if(!in_array($filter,$filters)) {
                return array('error'=>'Фильтр не найден');
            }

            $user_level = array_search($filter,$filters);
            $where = " where user_level = '$user_level'";
            $end.= "/filter/$filter";
        }

        $count = $this->DB->Row("select count(*) from users $where");
        $pager = new Pager($count,10,$end."/page/",$page);
        $q = "select * from users $where order by user_id limit ".$pager->get_start().", ".$pager->on_page."";
        $query = $this->DB->Query($q);
        $res = array();
        while($m = $query->fetch(PDO::FETCH_ASSOC)) {
            $m['status'] = Utility::getUserStatus($m['user_level']);
            $res[] = $m;
        }
        return array('error'=>'','data'=>$res,'navig'=>$pager->print_nav(),'count'=>$count);
    }

    function getAttr($attr_name = '',$user_id = 0,$attr_array = null) {
        if($attr_array == null) {
            return !$attr_name ?
                    $this->DB->Query("select * from users where user_id = '".$this->DB->IntFilter($user_id)."'")->fetch(PDO::FETCH_ASSOC)
                :
                $this->DB->Row("select $attr_name from users where user_id = '".$this->DB->IntFilter($user_id)."'");
        } else {
            $string = implode(",",$attr_array);
            return $this->DB->Query("select $string from users where user_id = '".$this->DB->IntFilter($user_id)."'")->fetch(PDO::FETCH_ASSOC);

        }
    }
}