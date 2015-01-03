<?php
class IndexController implements IController {
    function indexAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        $view = new View();

        $end = HOME."/topic/all";
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

        //$view->catalogs = CatalogModel::findAll("",0,"",false);

        $view->topics = TopicModel::findAll($end,$page,"",true,10);
        $view->forums = ForumModel::findAll("",0,"",false,10,false);
        $view->navigation = TopicModel::$navigation;

        /*$Category = new Category();
        $data = $Category->getAllwithForums();
        //$Category->create('Высшее образование');
        $view->data = $data;*/
        $result = $view->render('index_alternative.php','head.php','foot.php');
        $fc->setBody($result);
    }
}