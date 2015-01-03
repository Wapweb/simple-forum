<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 19.04.14
 * Time: 14:35
 */

class MailController implements IController {
    private $_view;
    private $_fc;

    function __construct() {
        $this->_view = new View();
        if($this->_view->user->level ==0) {
            throw new Exception("Доступ запрещен");
        }
        $this->_fc = FrontController::getInstance();
    }

    function  indexAction() {
        $this->_view->title = "Почта";

        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $end = HOME."/mail/index";

        $inboxEmail = EmailModel::findAllInbox($end,$page,$this->_view->user->getId());
        $this->_view->emails = $inboxEmail;
        $this->_view->navigation = EmailModel::$navigation;

        $result = $this->_view->render("mail/index.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function composeAction() {
        $params = $this->_fc->GetSimpleParams();
        $re = isset($params[0]) ? (int)($params[0]) : 0;
        if($re) {
            $email = EmailModel::findById($re);
            $userRecipient = UserModel::findById($email->sender_id);
            $this->_view->email = $email;
            $this->_view->userRecipient = $userRecipient;
        }
        $this->_view->title = "Новое сообщение";
        $result = $this->_view->render("mail/compose.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function sentAction() {
        $this->_view->title = "Отправленные сообщения";

        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $end = HOME."/mail/sent/";

        $sentMails = EmailModel::findAllSent($end,$page,$this->_view->user->getId());
        $this->_view->emails = $sentMails;
        $this->_view->navigation = EmailModel::$navigation;

        $result = $this->_view->render("mail/sent.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function sendAction() {
        $recipient_login = isset($_POST["recipient"]) ? $_POST["recipient"] : "";
        $recipientUser = UserModel::getUserByLogin($recipient_login);
        if($recipientUser != false) {
            if(isset($_POST["message_text"]) && isset($_POST["title"])) {
                if(!empty($_POST["message_text"]) && !empty($_POST["title"])) {
                    $Email = new EmailModel();
                    $Email->read = 0;
                    $Email->recipient_id = $recipientUser->getId();
                    $Email->sender_id = $this->_view->user->getId();
                    $Email->create_date = time();
                    $Email->title = $_POST["title"];
                    $Email->body = $_POST["message_text"];
                    $Email->save();
                    header("location:".HOME."/mail/sent");
                    exit;
                }
            }
        } else {
            header("location:".HOME."/mail/compose/?not_found");
            exit;
        }
        header("location:".HOME."/mail/compose/?error");
        exit;
    }

    function search_recipientAction() {
        $this->_view->findUsers = UserModel::searchUsersByLogin($_POST["login"]);
        $result = $this->_view->render("mail/user_live_search.php");
        $this->_fc->setBody($result);
    }

    function messageAction() {
        $params = $this->_fc->GetSimpleParams();
        $mail_id = isset($params[0]) ? (int)$params[0] : 0;
        $check = Utility::checkId("mail_id","mail",$mail_id,false);
        if(!$check) {
            throw new Exception("404 - Страница не найдена");
        }

        /** @var PDO $db */
        $db = Registry::get("db");
        $q = "SELECT CONT(*) FROM ".EmailModel::getTableName()."
            WHERE ".EmailModel::getPrimaryKey()." = '$mail_id' AND
            (sender_id = ".$this->_view->user->getId()." OR recipient_id = ".$this->_view->user->getId().")";
        $is_my = $db->query("
        SELECT COUNT(*) FROM ".EmailModel::getTableName()."
            WHERE ".EmailModel::getPrimaryKey()." = '$mail_id' AND
            (sender_id = ".$this->_view->user->getId()." OR recipient_id = ".$this->_view->user->getId().")
        ")->fetchColumn();

        if(!$is_my) {
            throw new Exception("Доступ запрещен");
        }

        /** @var EmailModel $email */
        $email = EmailModel::findById($mail_id);

        $this->_view->email = $email;
        $result = $this->_view->render("mail/message.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }


}