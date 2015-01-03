<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?=HOME?>/assets/ico/favicon.png">

    <title><?=$this->title?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?=HOME?>/assets/css/bootstrap.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="<?=HOME?>/assets/css/starter-template.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?=HOME?>/assets/js/html5shiv.js"></script>
    <script src="<?=HOME?>/assets/js/respond.min.js"></script>
    <![endif]-->

    <script src="<?=HOME?>/assets/js/jquery.js"></script>
    <script src="<?=HOME?>/assets/js/bootstrap.min.js"></script>
    <script src="<?=HOME?>/assets/js/jquery.form.js"></script>
    <script src="<?=HOME?>/assets/js/holder.js"></script>
    <script src="<?=HOME?>/assets/js/start.js"></script>
</head>

<body>
<div id="wrap">
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=HOME?>">ABITua</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?=HOME?>/users/list">Пользователи</a></li>
            </ul>
            <?php if($this->user->level > 0):?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="<?=HOME?>/user/profile/<?=$this->user->login?>" class="dropdown-toggle" data-toggle="dropdown"><?=$this->user->login?>
                            <?php
                                $newEmails = EmailModel::hasUserNewEmail($this->user);
                            ?>
                            <?=$newEmails ? " <strong>(!)</strong>" : ""?>
                            <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php if($this->user->level == 10): ?>
                            <li><a href="<?=HOME?>/panel/"><strong>Админ-панель</strong></a></li>
                            <?php endif; ?>
                            <li><a href="<?=HOME?>/user/profile/<?=$this->user->login?>">Профиль</a></li>
                            <li><a href="<?=HOME?>/mail"><?=$newEmails ? "<strong>Мои сообщения (+)</strong>" : "Мои сообщения"?></a></li>
                            <li><a href="<?=HOME?>/user/settings">Настройки</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Безопасность</li>
                            <li><a href="<?=HOME?>/user/change_password">Сменить пароль</a></li>
                        </ul>
                    </li>
                    <li><a href="<?=HOME?>/logout">Выход</a></li>
                </ul>

            <?php else: ?>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?=HOME?>/sign">Вход</a></li>
                    <li><a href="<?=HOME?>/registration">Регистрация</a></li>
                </ul>

            <?php endif; ?>
        </div><!--/.nav-collapse -->
    </div>
</div>