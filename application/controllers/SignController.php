<?php
class SignController implements IController {
    function indexAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        $View->title = "Вход";
        $result = $View->render('sign.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function processAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        $Login = new Login($_POST['email'],$_POST['password']);
        $View->data = $Login->register();
        $result = $View->render("sign_process.php");
        $fc->setBody($result);
    }
}