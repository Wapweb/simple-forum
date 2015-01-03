<?php
class UserController implements IController {
    function indexAction() {
        /*$fc = FrontController::getInstance();

        $View = new View();

        $result = $View->render('user/user_index.php','head.php','foot.php');
        $fc->setBody($result);*/
        header("Location:".HOME);
        exit;

    }

    function profileAction() {
        $fc = FrontController::getInstance();

        $View = new View();

        if($View->user->level == 0) {
            $View->error = 'Только <a href="'.HOME.'/registration" class="alert-link">зарегистрированные</a> пользователи могут просматривать профили других участников форума';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
            $fc->setBody($result);
        } else {



            $params = $fc->GetSimpleParams();
            $user_login = $params[0];

            if(!$user_login) {
                header("Location:".HOME);
                exit;
            }

            $user = UserModel::getUserByLogin($user_login);

            $View->title = "Профиль - ".$user->login;

            if($user === false) {
                $result = $View->render('user/user_not_found.php','head.php','foot.php');
                $fc->setBody($result);
            } else {
                $View->userProfile = $user;
                $result = $View->render('user/user_index.php','head.php','foot.php');
                $fc->setBody($result);
            }

        }

    }

    function change_avatarAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $result = $View->render('user/change_avatar.php','head.php','foot.php');
        }

        $fc->setBody($result);
    }

    function settingsAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $View->title = 'Настройки';
            $result = $View->render('user/settings.php','head.php','foot.php');
        }

        $fc->setBody($result);
    }

    function settings_view_processAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $settings_count_of_page = abs(intval(isset($_POST['settings_count_of_page']) ? $_POST['settings_count_of_page'] : 0));
            $settings_who_online = abs(intval(isset($_POST['settings_who_online']) ? $_POST['settings_who_online'] : 0));
            $settings_quick_answer = abs(intval(isset($_POST['settings_quick_answer']) ? $_POST['settings_quick_answer'] : 0));

            $settings_count_of_page = $settings_count_of_page > 4 && $settings_count_of_page < 41 ? $settings_count_of_page : 10;

            $View->user->settings_count_of_page = $settings_count_of_page;
            $View->user->settings_who_online = $settings_who_online;
            $View->user->settings_quick_answer = $settings_quick_answer;
            $View->user->save();

            $params = $fc->GetSimpleParams();
            $json = isset($params[0]) ? : '';
            if($json == 'json')
                $result = $View->render('user/settings_view_process_json.php');
            else
                $result = $View->render('user/settings_view_process.php');
        }

        $fc->setBody($result);
    }

    //
    function settings_profile_processAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $View->user->name = mb_substr($_POST['settings_user_name'],0,100,'UTF-8');
            $View->user->save();

            $params = $fc->GetSimpleParams();
            $json = isset($params[0]) ? : '';
            if($json == 'json')
                $result = $View->render('user/settings_profile_process_json.php');
            else
                $result = $View->render('user/settings_profile_process.php');
        }

        $fc->setBody($result);
    }

    function upload_avatarAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $Upload = new UploadAvatar();
            $View->res = $Upload->save($View->user);
            $result = $View->render('user/upload_avatar.php');
        }

        $fc->setBody($result);
    }

    function change_passwordAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $result = $View->render('user/change_password.php','head.php','foot.php');
        }

        $fc->setBody($result);
    }

    function change_password_processAction() {
        $fc = FrontController::getInstance();
        $View = new View();
        if($View->user->level == 0) {
            $View->error = 'Доступ запрещен';
            $View->title = 'Доступ запрещен';
            $result = $View->render('user/user_error.php','head.php','foot.php');
        } else {
            $Change = $View->user->changePassword($_POST['password'],$_POST['password_repeat'],$_POST['password_old']);
            $View->res = $Change;
            $result = $View->render('user/change_password_process.php');
        }

        $fc->setBody($result);
    }

}