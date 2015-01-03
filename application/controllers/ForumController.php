<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 06.09.13
 * Time: 19:47
 * To change this template use File | Settings | File Templates.
 */

class ForumController implements IController {

    function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $forum_id = Utility::getUrlId($params[0]);
        $View = new View();

        /*$Forum = new Forum();
        $data_forum = $Forum->get($forum_id);

        $Category = new Category();
        $data_catalog = $Category->get($data_forum['catalog_id']);

        $Topic = new Topic();
        $topic_data = $Topic->getAll($data_forum['forum_id']);


        $View->forum_data = $data_forum;
        $View->catalog_data = $data_catalog;
        $View->topic_data = $topic_data;*/

        /** @var ForumModel $Forum */
        $Forum = ForumModel::findById($forum_id);

        $page = isset($params[1]) ? $params[1] : 1;
        $end = HOME."/forum/show/".Utility::UrlTranslit($Forum->name)."-".$Forum->getId();

        $Catalog = $Forum->getCatalog();
        $Topics = $Forum->getTopics($end,$page,true,20);

        $View->navigation = TopicModel::$navigation;

        $View->forum = $Forum;
        $View->catalog = $Catalog;
        $View->topics = $Topics;
        $View->forums = ForumModel::findAll("",0,"",false,10,false);
        $View->title = $Forum->name;

        $result = $View->render('forum_show_alternative.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function createAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }
        $params = $fc->GetSimpleParams();
        $category_id = isset($params[0]) ? (int)$params[0] : 0;

        $Category = CatalogModel::findById($category_id);
        $View->category = $Category;
        $View->title = "Новый форум";

        if(isset($_POST["forum_name"])) {
            $forum_name = trim($_POST["forum_name"]);
            if(empty($forum_name)) {
                header("location:".HOME."/forum/create/".$category_id."/?failed");
                exit;
            }

            $Forum = new ForumModel();
            $Forum->name = $forum_name;
            $Forum->catalog_id = $category_id;
            $Forum->save();

            header("location:".HOME."/forum/show/".Utility::makeUrl($Forum->name,$Forum->getId()));
            exit;
        }


        $result = $View->render('panel/forum_create.php','head.php','foot.php');
        $fc->setBody($result);

    }

    function editAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $forum_id = isset($params[0]) ? (int)$params[0] : 0;

        $View->title = "Редактирование форума";
        /** @var ForumModel $Forum */
        $Forum = ForumModel::findById($forum_id);
        $View->forum = $Forum;

        if(isset($_POST['forum_name'])) {
            $forum_name = trim($_POST["forum_name"]);
            if(empty($forum_name)) {
                header("location:".HOME."/forum/edit/".$forum_id."/?failed");
                exit;
            }

            $Forum->name = $forum_name;
            $Forum->save();
            header("location: ".HOME."/forum/edit/".$forum_id."/?success");
            exit;
        }


        $result = $View->render('panel/forum_edit.php','head.php','foot.php');
        $fc->setBody($result);

    }


    function deleteAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $forum_id = isset($params[0]) ? (int)$params[0] : 0;

        /** @var ForumModel $Forum */
        $Forum = ForumModel::findById($forum_id);

        if(isset($_GET["confirm"])) {
            $Forum->delete();
            header("location:".HOME);
            exit;
        }

        $View->forum = $Forum;

        $result = $View->render('panel/forum_delete.php','head.php','foot.php');
        $fc->setBody($result);
    }
}