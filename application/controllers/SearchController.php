<?php
/**
 * Created by PhpStorm.
 * User: Шаповал
 * Date: 09.06.14
 * Time: 15:41
 */

class SearchController implements IController{
    private $_view;
    private $_fc;

    public function __construct() {
        $this->_view = new View();
        $this->_fc = FrontController::getInstance();
    }

    #region Actions

    public function indexAction(){
        $this->_view->title = "Поиск по форуму";
        $result = $this->_view->render("search/index.php","head.php","foot.php");
        $this->_fc->setBody($result);
    }

    #endregion
} 