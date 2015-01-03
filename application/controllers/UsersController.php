<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 02.09.13
 * Time: 19:09
 * To change this template use File | Settings | File Templates.
 */

class UsersController implements IController{
    function indexAction() {
        /*$fc = FrontController::getInstance();

        $View = new View();

        $result = $View->render('user/user_index.php','head.php','foot.php');
        $fc->setBody($result);*/
        header("Location:".HOME);
        exit;

    }

    function listAction() {
        $fc = FrontController::getInstance();

        $view = new View();
        $params = $fc->GetSimpleParams();
        $paramsKeyValue = $fc->getParams();
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $filter = isset($paramsKeyValue["filter"]) ? $paramsKeyValue["filter"] : '';
        $end = HOME."/users/list";

        $view->users = UserModel::findAll($end,$page,$filter);
        $view->navigation = UserModel::$navigation;
        $view->filter = $filter;
        $view->count = count($view->users);

        $view->title = "Учасники форума";

        $result = $view->render('user/list.php','head.php','foot.php');
        $fc->setBody($result);

        /*$List = new SingleUser();
        $res = $List->findAll($params['filter'],isset($params['page']) ? $params['page'] : '');

        if($res['error']) {
            $View->error = $res['error'];
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
        } else {
            $View->data = $res['data'];
            $View->navig = $res['navig'];
            $View->filter = $params['filter'];
            $View->count = $res['count'];
            $result = $View->render('user/list.php','head.php','foot.php');
            $fc->setBody($result);
        }*/
    }
}