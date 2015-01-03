<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 07.09.13
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */

class Topic {
    private $DB;
    public function __construct($pdo = null) {
        $this->DB = $pdo instanceof PDO ? $pdo : Registry::get('DB');
    }

    public function get($topic_id) {
        $topic_id = $this->DB->IntFilter($topic_id);
        if(!$this->DB->Row("select count(*) from topics where topic_id = '$topic_id'")) {
            throw new Exception('Неверный идентификатор топика!');
        }

        return $this->DB->Query("select * from topics where topic_id = '$topic_id'")->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($forum_id) {
        $forum_id = intval($forum_id);
        $count = $this->DB->Row("select count(*) from topics where forum_id = '$forum_id'");
        if(!$count) {
            return array('data'=>array(),'count' => 0);
        }
        $query = $this->DB->Query("select * from topics where forum_id = '$forum_id'");
        $data = array();
        while ($m = $query->fetch(PDO::FETCH_ASSOC)) $data[] = $m;
        return array('data'=>$data,'count' => $count);
    }

    private  function check($topic_id) {
        $topic_id = intval($topic_id);
        return $this->DB->Row("select count(*) from topics where topic_id  = '$topic_id'");
    }

    public function create($topic_name,$message_text,$forum_id,$user_id,$user_login) {
        $errors = '';
        $topic_name = !$topic_name ? $errors.= 'Введите название темы!<br>' : mb_substr($topic_name,0,200,'UTF-8');
        $message_text = !$message_text ? $errors.= 'Введите ссобщение темы!<br>' : mb_substr($message_text,0,10000,'UTF-8');
        if($errors) {
            return array('error_status'=>1,'error_msg'=>$errors);
        }
        $text = nl2br($message_text);

        /*require_once ROOT."/application/libs/bbcode/Parser.php";

        $parser = new JBBCode\Parser();
        //$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        $builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
        $parser->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre>{param}</pre>');
        // $builder->setParseContent(false);
        $parser->addCodeDefinition($builder->build());

        $parser->parse($text);
        $bbtext = $parser->getAsHtml();*/


        $user_id = abs(intval($user_id));
        $forum_id = abs(intval($forum_id));
        $time = time();
        $query = $this->DB->Query("insert into topics set
                            forum_id = '$forum_id',
                            message_id = '0',
                            topic_name = ".$this->DB->StrFilter($topic_name).",
                            user_id = '$user_id',
                            user_login = ".$this->DB->StrFilter($user_login).",
                            topic_create_date = '".$time."'");

        $topic_id = $this->DB->GetLastId();
        $this->DB->Query("insert into messages set
            topic_id = '$topic_id',
            user_id = '$user_id',
            user_login = ".$this->DB->StrFilter($user_login).",
            message_ip = '".ip2long($_SERVER['REMOTE_ADDR'])."',
            message_agent = ".$this->DB->StrFilter($_SERVER['HTTP_USER_AGENT']).",
            message_text = ".$this->DB->StrFilter($text).",
            message_create_date = '$time',
            message_update_date = '0',
            message_last_update_id = '0'
            ") or die('fatal error');
        $this->DB->Query("update topics set message_id = '".$this->DB->GetLastId()."' where topic_id = '$topic_id'") or die('fatal error');

        //$this->DB->StrFilter($text)
        return $query === false
            ?
                array('error_status'=>1,'error_msg'=>'Ошибка создания темы! Обратитесь к администратору')
            :
                array('error_status'=>0,'success_msg'=>'Тема успешно создана','url'=>HOME."/topic/show/".Utility::UrlTranslit($topic_name."-".$topic_id));
    }

}