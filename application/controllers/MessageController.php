<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 07.09.13
 * Time: 21:44
 * To change this template use File | Settings | File Templates.
 */

class MessageController implements IController {
    function indexAction() {
        header("location:".HOME);
    }

    function deleteAction() {
        $fc = FrontController::getInstance();

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? $params[0] : 0;

        $View = new View();

        /** @var MessageModel $Message */
        $Message = MessageModel::findById($message_id);

        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }else {
            if($View->user->level < 2) {
                throw new Exception("Доступ запрещен");
            }
        }

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($Message->topic_id);

        /** @var ForumModel $Forum */
        $Forum = $Topic->getForum();

        if(isset($_GET["confirm"])) {
            //если сообщение - создатель темы
            if($Message->getId() == $Topic->message_id) {
                $Topic->delete();
            }

            $Message->delete();
            header("location:".HOME."/topic/show/".Utility::makeUrl($Topic->name,$Topic->getId()));
            exit;
        }

        $View->message = $Message;
        $View->topic = $Topic;
        $View->forum = $Forum;

        $View->title = 'Удаление сообщения';
        $result = $View->render('panel/message_delete.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function editAction() {
        $fc = FrontController::getInstance();

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? $params[0] : 0;

        $View = new View();

        /** @var MessageModel $Message */
        $Message = MessageModel::findById($message_id);

        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }else {
            if($View->user->level < 2) {
                if($View->user->getId() != $Message->user_id) {
                    throw new Exception("Доступ запрещен");
                }
            }
        }

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($Message->topic_id);

        /** @var ForumModel $Forum */
        $Forum = $Topic->getForum();

        //post handler
        if(isset($_POST['submit'])) {
            if(isset($_POST["message_text"])) {
                $message_text = $_POST["message_text"];
                if(empty($message_text)) {
                    header("location:".HOME."/message/edit/".$message_id."/?failed");
                    exit;
                }
                $message_text = mb_substr($message_text,0,10000,"UTF-8");
                $Message->text = $message_text;
                $Message->save($View->user);
                header("location:".HOME."/message/find/".$message_id);
                exit;
            } else {
                header("location:".HOME."/message/edit/".$message_id."/?failed");
                exit;
            }
        }

        $View->message = $Message;
        $View->topic = $Topic;
        $View->forum = $Forum;

        $View->title = 'Редактирование сообщения';
        $result = $View->render('panel/message_edit.php','head.php','foot.php');
        $fc->setBody($result);

        /*$Message = new Message();
        $View->message_data = $Message->get($message_id);

        $Topic = new Topic();
        $View->topic_data = $Topic->get($View->message_data['topic_id']);

        if(isset($_POST['submit'])) {
            $userData = $View->getUserData();
            $res = $Message->update($_POST['message_text'],$message_id,$userData['user_id'],$userData['user_login']);
            if($res['error_status']) {
                header("location:".HOME."/message/edit/".$message_id."/?failed");
            } else {
                if(isset($params[1]))
                    header("location:".base64_decode($params[1]));
                else
                    header("location:".HOME."/topic/show/".Utility::UrlTranslit($$View->topic_data['topic_name'])."-".$View->topic_data['topic_id']);
            }
        }


        $User = new SingleUser();
        $data = $User->findById($View->message_data['user_id']);
        $View->user_data = $data['data'];*/

    }

    function findAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? (int)$params[0] : 0;
        $Message = MessageModel::findById($message_id);
        $Topic = TopicModel::findById($Message->topic_id);
        $View = new View();

        //$Message = new Message();
        //$messageData = $Message->get($params[0]);

        //$Topic = new Topic();
        //$topicData = $Topic->get($messageData['topic_id']);

        //$User = Registry::get('User');
        //$userData = $User->getData();
        $topic_url_name = Utility::UrlTranslit($Topic->name)."-".$Topic->getId();
        if($View->user->level == 0) {
            $count = 10;
        } else {
            $count = $View->user->settings_count_of_page;
        }
        $getPage = Utility::getPageByIdMessage($Message->create_date,$count,$Topic->getId());

        header("location:".HOME."/topic/show/$topic_url_name/?page=$getPage#msg_".$Message->getId());

    }

    function edit_processAction() {

    }
}