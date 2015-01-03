<?php
class LogoutController implements IController {
    function indexAction() {
        setcookie('usr_id','');
        setcookie('usr_pass','');
        session_destroy();
        header("location:".HOME);
    }
}