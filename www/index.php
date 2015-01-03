<?php
$start = microtime(true);
define('HOME','http://'.$_SERVER['SERVER_NAME']);
$document_root = $_SERVER['DOCUMENT_ROOT'];
$chunks = explode("/",$document_root);
$document_root = str_replace("/".end($chunks),"",$document_root);
define('ROOT',$document_root);
define('PUBLIC_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('ACCESS',1);
error_reporting(0);


date_default_timezone_set("Europe/Kiev");
$location = 'index';


set_include_path(get_include_path()
    .PATH_SEPARATOR.$document_root.'/application/controllers'
    .PATH_SEPARATOR.$document_root.'/application/models'
    .PATH_SEPARATOR.$document_root.'/application/helpers'
    .PATH_SEPARATOR.$document_root.'/application/views');


function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
try {

$ip = get_client_ip();
if($ip == "46.165.192.99") {
	//$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
	//file_put_contents(PUBLIC_ROOT."/block.txt", "ip:$ip; time:".date("H:i d.m.y",time())."; url: $url", FILE_APPEND);
	header('HTTP/1.0 403 Forbidden');
	exit("<h1>Forbidden</h1>");
}

require_once(ROOT.'/application/helpers/Pager.php');

function auto($class) {
    require_once $class.'.php';
}

spl_autoload_register('auto');
session_start();

    //$DB = new Db(array('dsn'=>'mysql:dbname=abit-forum;host=localhost','user'=>'wapweb','password'=>'1111'));
    //$DB->exec('SET NAMES utf8');

    //Registry::set('DB', $DB);

    $db = new PDO("mysql:dbname=DB;host=localhost","USER","USER_PASS");
	$db->exec('SET NAMES utf8');
    Registry::set("db",$db);

    $p = isset($_COOKIE['usr_pass']) ? $_COOKIE['usr_pass'] : '';
    $id = isset($_COOKIE['usr_id']) ? $_COOKIE['usr_id'] : 0;
    $User = UserModel::getUserFromCookie($id,$p);
   // $User = new User($id,$p);

    Registry::set('User', $User);
    $start = microtime(true);
    $front = FrontController::getInstance();
    $front->SetStartTime($start);
    $params = $front->GetSimpleParams();
    Utility::checkOnline($front->getController(),$front->getAction(),isset($params[0]) ? $params[0] : '');

    $front->route();

    echo $front->getBody();
    $end = microtime(true);
   // echo round($end-$start,9);
} catch (Exception $e) {
		header("HTTP/1.0 404 Not Found");
		require ROOT."/application/views/head_error.php";
		require ROOT."/application/views/error.php";
		require ROOT."/application/views/foot.php";
		//echo $e->getMessage();
}
?>
