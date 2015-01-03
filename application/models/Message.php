<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 07.09.13
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */

class Message {
    private $DB;
    public function __construct($pdo = null) {
        $this->DB = $pdo instanceof PDO ? $pdo : Registry::get('DB');
    }

    public function get($message_id) {
        $message_id = $this->DB->IntFilter($message_id);
        if(!$this->DB->Row("select count(*) from messages where message_id = '$message_id'")) {
            throw new Exception('Неверный идентификатор сообщения!');
        }

        return $this->DB->Query("select * from messages where message_id = '$message_id'")->fetch(PDO::FETCH_ASSOC);
    }

    public function update($message_text,$message_id,$edit_user_id,$edit_user_login) {
        Utility::checkId('message_id','messages',$message_id);

        if(empty($message_text)) {
            return array('error_status'=>1);
        }

        $query = $this->DB->Query("update messages set
        message_text = ".$this->DB->StrFilter($message_text).",
        message_update_count = message_update_count+1,
        message_update_date = '".time()."',
        message_last_update_id = '".$this->DB->IntFilter($edit_user_id)."',
        message_last_update_login = ".$this->DB->StrFilter($edit_user_login)."
        where message_id = '".$this->DB->IntFilter($message_id)."'");
        if($query == false) {
            throw new Exception("Ошибка редактирования сообщения. Обратитесь к администратору");
        }
        return array('error_status'=>0);
    }


    public function add($topic_id,$user_id,$user_login,$message_text) {
        Utility::checkId('topic_id','topics',$topic_id);
        $errors = '';
        if(empty($message_text))
            $errors.= 'Вы не ввели текст сообщения!<br>';

        if($errors)
            return array('error_msg'=>$errors);

        $topic_id = abs(intval($topic_id));
        $message_text = $this->DB->StrFilter(mb_substr($message_text,0,10000,'UTF-8'));
        $user_id = abs(intval($user_id));
        $user_login = $this->DB->StrFilter($user_login);
        $message_ip = abs(intval(ip2long($_SERVER['REMOTE_ADDR'])));
        $message_agent = $this->DB->StrFilter($_SERVER['HTTP_USER_AGENT']);

        $this->DB->Query("insert into messages set
            topic_id = '$topic_id',
            user_id = '$user_id',
            user_login = $user_login,
            message_ip = '$message_ip',
            message_agent = $message_agent,
            message_text = $message_text,
            message_create_date = '".time()."',
            message_update_date = '0',
            message_last_update_id = '0',
            message_last_update_login = ''
            ") or die('fatal error');

        return array('error_msg'=>'','succes_msg'=>'Сообщение успешно добавлено!');

    }
    public function getAttr($attr_name,$message_id) {
        if(!$attr_name || !$message_id) {
            throw new Exception('Неверные параметры');
        }
        $message_id = abs(intval($message_id));
        return $this->DB->Row("select $attr_name from messages where message_id = '$message_id'");
    }

    public function getAll($topic_id,$topic_url,$count_of_page) {
        $topic_id = intval($topic_id);
        $count = $this->DB->Row("select count(*) from messages where topic_id = '$topic_id'");
        if(!$count) {
            return array('data'=>array(),'count' => 0);
        }
        $pager = new Pager($count,$count_of_page,HOME."/topic/show/$topic_url/?page=");

        $query = $this->DB->Query("select * from messages where topic_id = '$topic_id' LIMIT ".$pager->get_start().", ".$pager->on_page);
        $data = array();
        $SingleUser = new SingleUser();
        while ($m = $query->fetch(PDO::FETCH_ASSOC)){
            $m['user_data'] = $SingleUser->getAttr('',$m['user_id'], array('user_avatar','user_register_date'));
            $data[] = $m;
        }
        return array('data'=>$data,'count' => $count,'navig'=>$pager->print_nav(),'curr_page'=>$pager->get_page());
    }

}