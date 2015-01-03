<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 07.09.13
 * Time: 21:44
 * To change this template use File | Settings | File Templates.
 */

class TopicController implements IController {
    function createAction() {
        $fc = FrontController::getInstance();
        $View = new View();

        if($View->user->level == 0) {
            $View->error = 'Недостаточно прав';
            $View->title = 'Недостаточно прав';
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
            return;
        }
        $View->title = 'Создание новой темы';
        $params = $fc->GetSimpleParams();

        /** @var ForumModel $Forum */
        $Forum = ForumModel::findById($params[0]);
        $View->forum = $Forum;

        if(isset($_POST['topic_name'])) {
            $Topic = new TopicModel();
            $Topic->name = $_POST['topic_name'];
            $Topic->forum_id = $Forum->getId();
            $Topic->user_id = $View->user->getId();
            $Topic->user_login = $View->user->login;
            $res = $Topic->saveTopic($_POST['message_text']);
            $View->res = $res;
            $result = $View->render('user/topic_create_process.php');
            $fc->setBody($result);
        } else {
            $result = $View->render('user/topic_create.php','head.php','foot.php');
            $fc->setBody($result);
        }

    }

    function activeAction() {
        $fc = FrontController::getInstance();
        $end = HOME."/topic/active";
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $view = new View();
        $view->topics = TopicModel::findAllActive($end,$page,true,10);
        $view->forums = ForumModel::findAll("",0,"",false,10,false);
        $view->navigation = TopicModel::$navigation;

        $view->title = "Активные темы";

        $result = $view->render('index_active.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function allAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        $view = new View();

        $end = HOME."/topic/all";
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

        //$view->catalogs = CatalogModel::findAll("",0,"",false);

        $view->topics = TopicModel::findAll($end,$page,"",true,10);
        $view->forums = ForumModel::findAll("",0,"",false);
        $view->navigation = TopicModel::$navigation;

        /*$Category = new Category();
        $data = $Category->getAllwithForums();
        //$Category->create('Высшее образование');
        $view->data = $data;*/
        $result = $view->render('index_alternative.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $topic_id = Utility::getUrlId($params[0]);
        $View = new View();

       /* $Topic = new Topic();
        $topic_data = $Topic->get($topic_id);

        $Forum = new Forum();
        $data_forum = $Forum->get($topic_data['forum_id']);

        $Category = new Category();
        $data_catalog = $Category->get($data_forum['catalog_id']);

        $Message = new Message();
        $View->page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $topic_url = Utility::UrlTranslit($topic_data['topic_name'])."-".$topic_data['topic_id'];
        $userData = $View->getUserData();
        $data_message = $Message->getAll($topic_data['topic_id'],$topic_url,$userData['settings_count_of_page']);



        $View->forum_data = $data_forum;
        $View->catalog_data = $data_catalog;
        $View->topic_data = $topic_data;
        $View->message_data = $data_message;
        $View->title = $topic_data['topic_name'];*/

        $page = isset($_GET['page']) ? abs(intval($_GET['page'])) : 1;
        $end = HOME."/topic/show/".$params[0]."";

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($topic_id);
        $Forum = $Topic->getForum();
        $Catalog = $Forum->getCatalog();
        $count = 10;
        if($View->user != null) {
            $count = $View->user->settings_count_of_page;
        }
        $Messages = $Topic->getMessagesJoinUserModel($end,$page,true,$count);

        $View->topic = $Topic;
        $View->catalog = $Catalog;
        $View->forum = $Forum;
        $View->messages = $Messages;

        $View->online_users = Utility::getOnlineTopic($topic_id);
        $View->navigation = MessageModel::$navigation;

        $include_tpl = 'topic_show.php';
        if($View->user != null) {
            if($View->user->level > 9) {
                $include_tpl = 'topic_show_admin_alternative.php';
            }
        }

        $View->title = $Topic->name;

        $result = $View->render($include_tpl,'head.php','foot.php');
        $fc->setBody($result);
    }

    function answer_msgAction() {
        $fc = FrontController::getInstance();
        $View = new View();

        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? (int)$params[0] : 0;
        $jsonType = isset($params[1]) ? true : false;

        /** @var MessageModel $Message */
        $Message = MessageModel::findById($message_id);

        if($jsonType == true) {

            if(!$_SERVER['HTTP_REFERER']) {
                throw new Exception("Запрещенный запрос");
            }

            print json_encode(
                array(
                    'user_login'=>
                        '<a href="'.HOME.'/message/find/'.$Message->getId().'">#'.$Message->getId().' '.$Message->user_login.'</a>'
                )
            );

        } else {
            $View->message = $Message;
            $View->title = 'Ответ пользователю - '.$Message->user_login;
            $result = $View->render('user/answer_msg.php','head.php','foot.php');
            $fc->setBody($result);

        }

        /*if($View->getUserLevel() < 1) {
            $View->error = 'Недостаточно прав';
            $View->title = 'Недостаточно прав';
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
            return;
        }

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? $params[0] : 0;
        $json = isset($params[1]) ? ($params[1] == 'json' ? 1 : 0) : 0;
        $Message = new Message();
        $View->message_data = $Message->get($message_id);

        if($json) {
            if(!$_SERVER['HTTP_REFERER']) {
                $View->error = 'запрещенный запрос';
                $result = $View->render('user/user_error.php','head.php','foot.php');
            } else {
                $result = $View->render('user/answer_msg_json.php');
            }
        }
        else {
            $View->topic_id = $Message->getAttr('topic_id',$message_id);
            $Topic = new Topic();
            $View->topic_data = $Topic->get($View->topic_id);
            $View->message_id = $message_id;
            $View->title = 'Ответ пользователю - '.$View->message_data['user_login'];
            $result = $View->render('user/answer_msg.php','head.php','foot.php');
        }
        $fc->setBody($result);*/
    }

    function quote_msgAction() {
        $fc = FrontController::getInstance();
        $View = new View();

        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? (int)$params[0] : 0;

        /** @var MessageModel $Message */
        $Message = MessageModel::findById($message_id);

        require_once ROOT."/application/libs/bbcode/Parser.php";
        $parser = new JBBCode\Parser();
        //$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        $builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
        $parser->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre>{param}</pre>');
        // $builder->setParseContent(false);
        $parser->addCodeDefinition($builder->build());
        $text = nl2br($Message->text);
        $parser->parse($text);
        $out = $parser->getAsHtml();

        $message = "<blockquote><a href='".HOME."/user/profile/".$Message->user_login."'>".$Message->user_login."</a> в <a href='".HOME."/message/find/".$Message->getId()."'>".date("H:i d.m.y",$Message->create_date)."</a> пишет:<br>$out</blockquote>";
        print json_encode(array('out'=>$message));

        /*if($View->getUserLevel() < 1) {
            $View->error = 'Недостаточно прав';
            $View->title = 'Недостаточно прав';
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
            return;
        }

        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? $params[0] : 0;
        $json = isset($params[1]) ? ($params[1] == 'json' ? 1 : 0) : 0;
        $Message = new Message();
        $View->message_data = $Message->get($message_id);
        if($json)
            $result = $View->render('user/quote_msg_json.php');
        else {
            $View->topic_id = $Message->getAttr('topic_id',$message_id);
            $Topic = new Topic();
            $View->topic_data = $Topic->get($View->topic_id);
            $View->title = 'Цитирование пользователя - '.$View->message_data['user_login'];
            $result = $View->render('user/quote_msg.php','head.php','foot.php');
        }
        $fc->setBody($result);*/
    }

    function attach_filesAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }
        $params = $fc->GetSimpleParams();
        $message_id = isset($params[0]) ? (int)$params[0] : 0;
        $topic_page = isset($params[1]) ? (int)$params[1] : 1;
        $topic_name = isset($params[2]) ? $params[2] : "";
        Utility::checkId("message_id","messages",$message_id,true);
        //$Message = MessageModel::findById($message_id);
        $fileArray = isset($_POST["arr"]) ? (array)$_POST["arr"] : array();
        if(count($fileArray) > 0) {
            /** @var PDO $db */
            $db = Registry::get("db");
            foreach($fileArray as $file_id) {
                /** @var FileModel $File */
                $File = FileModel::findById($file_id);
                if($File->user_id == $View->user->getId()) {
                    $File->message_id = $message_id;
                    $File->save();
                }
            }
        }
        $url = HOME."/topic/show/$topic_name/?page=$topic_page";
        echo json_encode(array("redirect_url"=>$url));
    }

    function add_msgAction() {

        $fc = FrontController::getInstance();

        $View  = new View();
        if($View->user->level == 0) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $topic_id = isset($params[0]) ? (int)$params[0] : 0;
       // Utility::checkId("topic_id","topics",$topic_id,true);

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($topic_id);

        $result = array();
        if(isset($_POST)) {
            if(isset($_POST["message_text"])) {
                if(!empty($_POST["message_text"])) {
                    $temp = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$_POST["message_text"]);
                    if(strlen($temp) == 0) {
                        $result["error_msg"]= "В сообщении не должны содержаться одни пробелы!";
                    } else {
                        $message_text = mb_substr($_POST["message_text"],0,10000,"UTF-8");
                        $Message = new MessageModel();
                        $Message->text = $message_text;
                        $Message->topic_id = $topic_id;
                        $Message->user_id = $View->user->getId();
                        $Message->user_login = $View->user->login;
                        $Message->ip = ip2long($_SERVER["REMOTE_ADDR"]);
                        $Message->agent = $_SERVER["HTTP_USER_AGENT"];
                        $Message->create_date = time();
                        $Message->save();

                        $result["error_msg"] = "";
                        $result["message_id"] = $Message->getId();
                        $countMessages = Utility::checkId('topic_id','messages',$topic_id,false);
                        $result["topic_page"] = ceil(($countMessages+1)/$View->user->settings_count_of_page);
                        $result["topic_name"] = Utility::UrlTranslit($Topic->name."-".$Topic->getId());
                    }

                    //echo json_encode($result);

                } else {
                    $result["error_msg"] = "Вы не ввели текст сообщения";
                    //echo json_encode($result);
                }
            } else {
                $result["error_msg"] = "Неверный запрос";
                //echo json_encode($result);
            }

        } else {
            throw new Exception("Неверный запрос");
        }

        if(isset($_POST["json"])) {
            echo json_encode($result);
        } else {
            header("location:".HOME."/topic/show/".Utility::UrlTranslit($Topic->name)."-".$Topic->getId());
        }
        exit;
        /*if(!$_POST['submit']) {
            throw new Exception('неверный запрос');
        }
        $fc = FrontController::getInstance();
        $View = new View();

        if($View->getUserLevel() < 1) {
            $View->error = 'Недостаточно прав';
            $View->title = 'Недостаточно прав';
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
            return;
        }

        $params = $fc->GetSimpleParams();
        $topic_id = isset($params[0]) ? $params[0] : 0;
        $Message = new Message();
        $Topic = new Topic();
        $userData = $View->getUserData();
        $topicData = $Topic->get($topic_id);
       // echo $View->page;
        $View->page = ceil((Utility::checkId('topic_id','messages',$topic_id)+1)/Utility::$msg_on_page);
        $View->topic_url = Utility::UrlTranslit($topicData['topic_name'])."-".$topic_id;
        if(isset($_GET['page'])) {
            $page = abs(intval($_GET['page'])) > 0 ? abs(intval($_GET['page'])) : 1;
            $View->redirect_url = HOME."/topic/show/".$View->topic_url."/?page=".$page."&failed#msg";
        } else
            $View->redirect_url = !$params[1] ? HOME."/topic/show/".$View->topic_url."/?page=".$topicData['curr_page']."&failed#msg" : HOME."/topic/answer_msg/".abs(intval($params[2]))."/?failed";
        $View->result = $Message->add($topic_id,$userData['user_id'],$userData['user_login'],$_POST['message_text']);
        $result = $View->render('user/add_msg.php');
        $fc->setBody($result);*/
    }

    public function editAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $topic_id = isset($params[0]) ? (int)$params[0] : 0;
        $View = new View();

        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($topic_id);
        /** @var ForumModel $Forum */
        $Forum = $Topic->getForum();
        /** @var MessageModel $Message */
        $Message = MessageModel::findById($Topic->message_id);

        $View->title = "Редактирование темы";

        //post handler
        if(isset($_POST["topic_name"])) {
            $topic_name = $_POST["topic_name"];
            $message_text = $_POST["message_text"];
            if(!empty($topic_name) && !empty($_POST["message_text"])) {
                $Topic->name = $topic_name;
                $Message->text = $message_text;

                $Topic->save();
                $Message->save();
                header("location:".HOME."/topic/edit/".$topic_id."/?success");
                exit;
            } else {
                header("location:".HOME."/topic/edit/".$topic_id."/?failed");
                exit;
            }
        }

        $View->topic = $Topic;
        $View->forum = $Forum;
        $View->message = $Message;

        $result = $View->render("user/topic_edit.php","head.php","foot.php");
        $fc->setBody($result);
    }

    public function deleteAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $topic_id = isset($params[0]) ? (int)$params[0] : 0;
        $View = new View();

        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        /** @var TopicModel $Topic */
        $Topic = TopicModel::findById($topic_id);
        /** @var ForumModel $Forum */
        $Forum = $Topic->getForum();

        $View->title = "Удаление темы";

        if(isset($_GET["confirm"])) {
            $Topic->delete();
            header("location:".HOME."/forum/show/".Utility::UrlTranslit($Forum->name)."-".$Forum->getId());
            exit;
        }

        $View->topic = $Topic;
        $View->forum = $Forum;
        $result = $View->render("user/topic_delete.php","head.php","foot.php");
        $fc->setBody($result);
    }
}