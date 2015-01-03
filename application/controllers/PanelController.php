<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 26.04.14
 * Time: 19:33
 */

class PanelController  implements IController{
    /** @var FrontController $_fc */
    private $_fc;
    /** @var  View $_view */
    private $_view;


    function __construct() {
        $this->_fc = FrontController::getInstance();
        $this->_view = new View();

        if($this->_view->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }
    }

    function indexAction() {
        $this->_view->title = "Панель управления";
        $result = $this->_view->render("panel/index.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function categoriesAction() {
        $this->_view->title = "Разделы";
        $end = HOME."/panel/categories/";
        $params = $this->_fc->getParams();
         $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $Categories = CatalogModel::findAll($end,$page ,"",true);
        $navigation = CatalogModel::$navigation;

        $this->_view->categories = $Categories;
        $this->_view->navigation = $navigation;

        $result = $this->_view->render("panel/categories.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function forumsAction() {
        $this->_view->title = "Форумы";

        $end = HOME."/panel/forums/";
        $params = $this->_fc->getParams();
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $Forums = ForumModel::findAll($end,$page,"",true);
        $navigation = ForumModel::$navigation;

        $this->_view->forums = $Forums;
        $this->_view->navigation = $navigation;

        $result = $this->_view->render("panel/forums.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function topicsAction() {
        $params = $this->_fc->getParams();
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $filter = isset($params["forum"]) ? (int)$params["forum"] : 0;

        $this->_view->selected_forum_id = 0;

        if($filter == 0) {
            $end = HOME."/panel/topics/";
            $Topics = TopicModel::findAll($end,$page,"",true);
        } else {
            $end = HOME."/panel/topics/forum/$filter";
            /** @var ForumModel $SelectedForum */
            $SelectedForum = ForumModel::findById($filter);
            $this->_view->selected_forum_id = $SelectedForum->getId();
            $Topics = $SelectedForum->getTopics($end,$page,true,10);
        }

        if(isset($_POST["forum"])) {
            $forum_id = (int)$_POST["forum"];
            if(!$forum_id) {
                header("location:".HOME."/panel/topics");
                exit;
            }
            header("location:".HOME."/panel/topics/forum/$forum_id");
            exit;
        }

        $navigation = TopicModel::$navigation;

        $this->_view->topics = $Topics;
        $this->_view->navigation = $navigation;
        $this->_view->forums = ForumModel::findAll("",0,"",false);

        $result = $this->_view->render("panel/topics.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function messagesAction() {
        $end = HOME."/panel/messages/";
        $params = $this->_fc->getParams();
         $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $Messages = MessageModel::findAll($end,$page,"",true);
        $navigation = MessageModel::$navigation;

        $this->_view->messages = $Messages;
        $this->_view->navigation = $navigation;

        $result = $this->_view->render("panel/messages.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    function usersAction() {
        $end = HOME."/panel/users";
        $params = $this->_fc->getParams();
         $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $Users = UserModel::findAll($end,$page,"",true);
        $navigation = UserModel::$navigation;

        $this->_view->users = $Users;
        $this->_view->navigation = $navigation;

        $result = $this->_view->render("panel/users.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

} 