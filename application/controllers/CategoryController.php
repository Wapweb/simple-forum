<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Шаповал
 * Date: 03.09.13
 * Time: 21:15
 * To change this template use File | Settings | File Templates.
 */

class CategoryController implements IController {
    function indexAction() {
        header("location:".HOME);
        exit;
    }

    function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->GetSimpleParams();
        $catalog_id = Utility::getUrlId($params[0]);
        $View = new View();

        /*$Category = new Category();
        $data_cat = $Category->get($catalog_id);

        $Forum = new Forum();
        $res = $Forum->getAll($catalog_id);

        $View->res = $res;
        $View->catalog_data = $data_cat;*/

        /** @var CatalogModel $Catalog */
        $Catalog = CatalogModel::findById($catalog_id);
        $Forums = $Catalog->getForums();

        $View->catalog = $Catalog;
        $View->forums = $Forums;

        $result = $View->render('category_show.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function createAction() {
        $fc = FrontController::getInstance();
        $View = new View();

        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }
        $View->title = "Создание раздела";

        if(isset($_POST["category_name"])) {
            $category_name = trim($_POST["category_name"]);

            if(empty($category_name)) {
                header("location:".HOME."/category/create/?failed");
                exit;
            }

            $Catalog = new CatalogModel();
            $Catalog->name = $category_name;
            $Catalog->save();

            header("location:".HOME."/category/show/".Utility::makeUrl($Catalog->name,$Catalog->getId()));
            exit;
        }


        $result = $View->render('panel/category_create.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function editAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $category_id = isset($params[0]) ? (int)$params[0] : 0;

        $View->title = "Редактирование раздела";
        /** @var CatalogModel $Forum */
        $Catalog = CatalogModel::findById($category_id);
        $View->category = $Catalog;

        if(isset($_POST['category_name'])) {
            $category_name = trim($_POST["category_name"]);
            if(empty($category_name)) {
                header("location:".HOME."/category/edit/".$category_id."/?failed");
                exit;
            }

            $Catalog->name = $category_name;
            $Catalog->save();
            header("location: ".HOME."/category/edit/".$category_id."/?success");
            exit;
        }


        $result = $View->render('panel/category_edit.php','head.php','foot.php');
        $fc->setBody($result);
    }


    function deleteAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level < 10) {
            throw new Exception("Доступ запрещен");
        }

        $params = $fc->GetSimpleParams();
        $category_id = isset($params[0]) ? (int)$params[0] : 0;

        $View->title = "Удаление раздела";
        /** @var CatalogModel $Forum */
        $Catalog = CatalogModel::findById($category_id);
        $View->category = $Catalog;

        if(isset($_GET["confirm"])) {
            $Catalog->delete();
            header("location:".HOME);
            exit;
        }

        $result = $View->render('panel/category_delete.php','head.php','foot.php');
        $fc->setBody($result);
    }
}