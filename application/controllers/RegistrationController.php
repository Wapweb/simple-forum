<?php
class RegistrationController implements IController {
    function indexAction() {
        $fc = FrontController::getInstance();
		
        $View = new View();
		
		if($View->user->level > 0) {
			header("location: ".HOME);
			exit;
		}
		
        $View->title = "Регистрация";
        $result = $View->render('register.php','head.php','foot.php');
        $fc->setBody($result);
    }

    function processAction() {
        $fc = FrontController::getInstance();

        $View = new View();
        $Reg = new Registration($_POST['password'],$_POST['password_repeat'],$_POST['email'],$_POST['login']);
        $View->data = $Reg->save();
        if(!$View->data['error']) {
            header('Refresh:2;url='.HOME);
        }
        $result = $View->render("register_process.php");
        $fc->setBody($result);
    }
}