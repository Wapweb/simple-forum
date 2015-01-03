<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 01.09.13
 * Time: 18:31
 * To change this template use File | Settings | File Templates.
 */

class Utility {
    public static $msg_on_page = 10;
    public static function getUserStatus($level) {
        $status = array(0=>'Гость',1=>'Пользователь',2=>'Модератор',10=>'Администратор');
        if(!isset($status[$level])) {
            throw new Exception('Ошибка определения статуса пользователя');
        }

        return $status[$level];
    }

    public static function getUserFilter($level) {
        $status = array(2=>'moderator',10=>'admin',1=>'user');
        if(!isset($status[$level])) {
            throw new Exception('Ошибка определения фильтра пользователя');
        }

        return $status[$level];
    }

    public static function getNormalizeFiles()
    {
        $newfiles = array();
        foreach($_FILES as $fieldname => $fieldvalue)
            foreach($fieldvalue as $paramname => $paramvalue)
                foreach((array)$paramvalue as $index => $value)
                    $newfiles[$fieldname][$index][$paramname] = $value;
        return $newfiles;
    }

    public static function getFileExt($file_name) {
        $arr = explode(".",$file_name);
        return end($arr);
    }

    public static function UrlTranslit($text) {
        $text = iconv('UTF-8','windows-1251//TRANSLIT',$text);
        $text = iconv('windows-1251','UTF-8',$text);
        preg_match_all('/./u', $text, $text); $text = $text[0];
        $simplePairs=array('і'=>'i','ї'=>'i','а'=>'a','л'=>'l','у'=>'u','б'=>'b','м'=>'m','т'=>'t','в'=>'v','н'=>'n','ы'=>'y','г'=>'g','о'=>'o','ф'=>'f','д'=>'d','п'=>'p','и'=>'i','р'=>'r','А'=>'A','Л'=>'L','У'=>'U','Б'=>'B','М'=>'M','Т'=>'T','В'=>'V','Н'=>'N','Ы'=>'Y','Г'=>'G','О'=>'O','Ф'=>'F','Д'=>'D','П'=>'P','И'=>'I','Р'=>'R');
        $complexPairs=array('з'=>'z','ю'=>'iu','ц'=>'c','є'=>'ie','к'=>'k','ж'=>'zh','ч'=>'ch','х'=>'kh','е'=>'e','с'=>'s','ё'=>'jo','э'=>'eh','ш'=>'sh','й'=>'jj','щ'=>'shh','ю'=>'ju','я'=>'ja','З'=>'Z','Ц'=>'C','К'=>'K','Ж'=>'ZH','Ч'=>'CH','Х'=>'KH','Е'=>'E','С'=>'S','Ё'=>'JO','Э'=>'EH','Ш'=>'SH','Й'=>'JJ','Щ'=>'SHH','Ю'=>'JU','Я'=>'JA','Ь'=>"",'Ъ'=>"",'ъ'=>"",'ь'=>"");
        $specialSymbols=array("_"=>"-","'"=>"","`"=>"","^"=>"",""=>"-",'.'=>'',','=>'',':'=>'','"'=>'',"'"=>'','<'=>'','>'=>'','«'=>'','»'=>'',''=>'-',);
        $translitLatSymbols=array('a','l','u','b','m','t','v','n','y','g','o','f','d','p','i','r','z','c','k','e','s','A','L','U','B','M','T','V','N','Y','G','O','F','D','P','I','R','Z','C','K','E','S',);
        $charsToTranslit = array_merge(array_keys($simplePairs),array_keys($complexPairs));
        $translitTable = array();
        foreach($simplePairs as $key => $val)
            $translitTable[$key] = $simplePairs[$key];
        foreach($complexPairs as $key => $val)
            $translitTable[$key] = $complexPairs[$key];
        foreach($specialSymbols as $key => $val)
            $translitTable[$key] = $specialSymbols[$key];
        $result = ""; $nonTranslitArea = false;
        foreach($text as $char) {
            if(in_array($char,array_keys($specialSymbols))) {
                $result.= $translitTable[$char];
            }
            elseif(in_array($char,$charsToTranslit)) {
                if($nonTranslitArea) {
                    $result.= ""; $nonTranslitArea = false;
                }
                $result.= $translitTable[$char];
            } else {
                if(!$nonTranslitArea && in_array($char,$translitLatSymbols)) {
                    $result.= ""; $nonTranslitArea = true;
                }
                $result.= $char;
            }
        }
        $result = strtolower(trim($result, '-'));
        $result = preg_replace("/[\/_|+ -]+/", "-",$result);
        return strtolower(preg_replace("/[-]{2,}/", '-', $result));

    }

    public static function getUrlId($string,$ret_arr = false) {

        if(!$string) {
            throw new Exception('Неверный параметр');
        }

        $array = explode('-',$string);
        if(!count($array)) {
            throw new Exception('Невалидный адрес! Обратитесь к администратору сайта');
        }
        $id = end($array);
        if($ret_arr) {
            $name = str_replace('-'.$id,'',$string);
            return array('name'=>$name,'id'=>$id);
        }

        return $id;
    }

    public static function checkId($id_column,$table_name,$id_value,$return_throw = true, $and_condition = "") {
        $db = Registry::get('db');
        $q = "select count(*) from $table_name where $id_column = '".abs(intval($id_value))."' $and_condition";
        $count = $db->query("select count(*) from $table_name where $id_column = '".abs(intval($id_value))."' $and_condition")->fetchColumn();
        if($return_throw){
            if(!$count)
                throw new Exception('Неверный идентификатор');
        }
        else {
            return $count > 0 ? $count : 0;
        }
    }

    public static function checkOnline($controller,$action,$first_param) {
        $db = Registry::get('db');
        /** @var UserModel $User */
        $User = Registry::get('User');
        $user_id = 0;
        $user_login = "";
        if($User != null) {
            $user_id = $User->getId();
            $user_login = $User->login;
        }
        $ip = ip2long($_SERVER['REMOTE_ADDR']);
        $agent = $db->quote($_SERVER['HTTP_USER_AGENT']);
        $db->query("delete from online where `time`+300 < '".time()."' or (`ip` = '".$ip."' and agent = ".$agent.")");
        $topic_id = 0;
        if($controller == 'TopicController' && $action == 'showAction') {
            $topic_id = self::getUrlId($first_param);

        }
        $db->query("insert into online set time = '".time()."', ip = '$ip', user_id = '".$user_id."', user_login = '".$user_login."',topic_id = '$topic_id',agent = ".$agent."");
    }

    public static function getOnlineTopic($topic_id) {
        $db = Registry::get('db');
        $topic_id = abs(intval($topic_id));
        $count = $db->query("select count(*) from online where topic_id = '$topic_id'")->fetchColumn();
        $data = array();
        if(!$count) {
            return array('data'=>$data,'count'=>0);
        }
        $query = $db->query("select * from online where topic_id = '$topic_id' and user_id > '0'");
        while ($m = $query->fetch(PDO::FETCH_ASSOC)) $data[] = $m;
        return array('data'=>$data,'count'=>$count);
    }

    public static function getOnlineCount() {
        $db = Registry::get('db');
        return $db->query("select count(*) from online")->fetchColumn();
    }

    public static function userIsOnline($user_id) {
        $db = Registry::get('db');
        return $db->query("select count(*) from online where user_id = '".abs(intval($user_id))."'")->fetchColumn();
    }

    public static function getPageByIdMessage($msg_date,$count_of_page,$topic_id) {
        $db = Registry::get('db');
        $msg_count = $db->query("select count(*) from messages where  topic_id = '".abs(intval($topic_id))."'  and message_create_date < '".abs(intval($msg_date))."'")->fetchColumn()+1;
        return ceil($msg_count/$count_of_page);
    }

    public static function sizeConvert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public static function includeWidget($widgetName) {
        if(file_exists(ROOT."/application/views/widgets/$widgetName")) {
            require_once ROOT."/application/views/widgets/$widgetName";
        } else {
            throw new Exception("Widget $widgetName not found");
        }
    }

    public static function getNameWithEnd($n, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
    }

    public static function formatDate($date) {
        $day = date("j",$date);
        $week = date("w",$date);
        $month = date("n",$date);

        $weeks = array(
            0=>"Воскресенье",
            1=>"Понедельник",
            2=>"Вторник",
            3=>"Среда",
            4=>"Четверг",
            5=>"Пятница",
            6=>"Суббота"
        );

        $months = array(
            1=>"дек.",
            2=>"фев.",
            3=>"мар.",
            4=>"апр.",
            5=>"май",
            6=>"июн.",
            7=>"июл.",
            8=>"авг.",
            9=>"сен.",
            10=>"окт.",
            11=>"ноя.",
            12=>"дек."
        );
        return $weeks[$week]." ".$day." ".$months[$month]." в ".date("H:i",$date);
    }

    public static function makeUrl($name,$id){
        return Utility::UrlTranslit($name)."-".$id;
    }
}