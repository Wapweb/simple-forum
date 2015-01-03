<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script language="javascript" src="<?=HOME?>/assets/js/textarea.js"></script>
<script language="javascript" src="<?=HOME?>/assets/js/online_topic.js"></script>
<style>
    div#spinner
    {
        display: none;
        width:142px;
        height: 16px;
        position: fixed;
        top: 50%;
        left: 50%;
        background:url(/assets/images/loader.gif) no-repeat center #fff;
        text-align:center;
        padding:10px;
        font:normal 16px Tahoma, Geneva, sans-serif;
        margin-left: -50px;
        margin-top: -50px;
        z-index:2;
        overflow: auto;
    }
</style>
<div id="spinner">
</div>

<?php

/** @var CatalogModel $catalog */
$catalog = $this->catalog;

/** @var ForumModel $forum */
$forum = $this->forum;

/** @var TopicModel $topic */
$topic = $this->topic;

/** @var MessageModel[] $messages */
$messages = $this->messages;

/** @var UserModel $user */
$user = $this->user;

?>

<div class="container">

    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($catalog->name)."-".$catalog->getId()?>"><?=$catalog->name?></a></li>
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class="active">Топик: <?=$topic->name?>
            <a href="<?=HOME?>/topic/edit/<?=$topic->getId()?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit" title="Редактировать топик"></span></a>
            <a href="<?=HOME?>/topic/delete/<?=$topic->getId()?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove" title="Удалить топик"></span></a></li>
    </ol>
    <?php
    if($this->navigation != "")
        echo $this->navigation;
    ?>
    <?php
    require_once ROOT."/application/libs/bbcode/Parser.php";

    $parser = new JBBCode\Parser();
    //$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

    $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

    $builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
    $parser->addCodeDefinition($builder->build());

    $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre>{param}</pre>');
    // $builder->setParseContent(false);
    $parser->addCodeDefinition($builder->build());
    ?>
    <div class="panel panel-gray">
        <?php /** @var MessageModel $msg */?>
        <?php foreach($messages as $msg) :?>
            <?php
                $user = UserModel::findById($msg->user_id);
            ?>
            <div class="panel-heading" style="border-top: 1px solid #c0c0c0;">
                <h4  class="panel-title"><?=$msg->user_login?> <a href="" name="msg_<?=$msg->getId()?>" style="float:right">#<?=$msg->getId()?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-3 col-sm-2 col-md-1">

                        <a href="<?=HOME."/user/profile/".$msg->user_login?>" class="">
                            <img width="96" height="96" class="img-thumbnail" alt="..." src="<?=HOME?>/assets/images/avatars/<?=$user->avatar ? 'small_'.$user->avatar : 'avatar.png'?>">
                        </a><br>
                        <small>Регистрация:<br> <?=date("d.m.y",$user->register_date)?><br></small>
                        <?=Utility::userIsOnline($msg->user_id) ? '<span class="label label-success">online</span>' : '<span class="label label-danger">offline</span>'?>
                    </div>
                    <div class="col-xs-15 col-sm-10 col-md-11">
                        <div class="h6 text-muted edit" style="padding-bottom: 9px;">Отправлено  <?=date("d.m.y в H:i",$msg->create_date)?>
                            <a href="<?=HOME?>/message/delete/<?=$msg->getId()?>" style="float: right; margin-left: 3px;" class="text-primary text-edit"><span class="glyphicon glyphicon-remove" title="Удалить"></span></a> <a href="<?=HOME?>/message/edit/<?=$msg->getId()?>" style="float: right" class="text-primary text-edit"><span class="glyphicon glyphicon-edit" title="Редактировать"></span></a>
                        </div>

                        <div class="row">
                            <div class="col-xs-18 col-sm-12 col-md-12">
                                <?php
                                $text = nl2br($msg->text);
                                $parser->parse($text);
                                $out = $parser->getAsHtml();
                                print $out;
                                ?>
                                <?php if($msg->update_count):?>
                                <hr>
                                    <h6><small>
                                        Последний раз сообщение отредактировал <a href="<?=HOME."/user/profile/".$msg->last_update_login?>"><?=$msg->last_update_login?></a>
                                        в <?=date("H:i d.m.y",$msg->update_date)?> (всего <?=$msg->update_count?> раз(а))

                                    </small></h6>

                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-xs-3 col-sm-2 col-md-1">
                        <div class="text-left"><a href="" class="btn btn-default btn-xs">Жалоба</a></div>
                    </div>
                    <div class="col-xs-15 col-sm-10 col-md-11">
                        <p class="text-right"><a href="" class="label label-success">&uarr;</a> <a href="" class="label label-danger">&darr;</a> <span class="label label-success">42</span></p>
                        <div class="text-right">
                            <a href="<?=HOME?>/topic/answer_msg/<?=$msg->getId()?>" id="<?=$msg->getId()?>" class="msg_answer btn btn-default btn-xs">Ответить</a>
                            <a href="<?=HOME?>/topic/quote_msg/<?=$msg->getId()?>" class="msg_quote btn btn-default btn-xs" id="<?=$msg->getId()?>">Цитировать</a>
                        </div>
                    </div>
                </div>


            </div>
        <?php endforeach ?>
    </div>
    <?php
    if($this->navigation != "")
        echo $this->navigation;
    ?>

    <?php if($user != null) :?>

        <?php if($user->settings_quick_answer):?>

            <div class="panel panel-default">
                <div class="panel-heading">Быстрый ответ</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-4 col-sm-2 col-md-1">
                            <h4 style="margin-top: -5px;"><?=$user->login?></h4>
                            <a href="<?=HOME."/user/profile/".$user->login?>" class="">
                                <img width="128" height="128" class="img-thumbnail" alt="..." src="<?=HOME?>/assets/images/avatars/<?=$user->avatar ? 'small_'.$user->avatar : 'avatar.png'?>">
                            </a><br>
                            <small>Регистрация:<br> <?=date("d.m.y",$user->register_date)?><br></small>
                            <span class="label label-success">online</span>
                        </div>
                        <div class="col-xs-14 col-sm-10 col-md-11" id="msg">
                            <div class="row">
                                <div class="col-xs-18 col-sm-12 col-md-12">
                                    <?=isset($_GET['failed']) ? '<div class="alert alert-danger alert-dismissable" id="empty_msg">
                                    Введите текст сообщения!
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    </div>' : ''?>
                                    <form action="<?=HOME."/topic/add_msg/".$topic->getId()."/?page=".abs(intval(isset($_GET['page']) ? $_GET['page'] : 0))?>" method="post">
                                        <textarea id="textarea_msg" name="message_text" rows="8" maxlength="10000" placeholder="Сообщение темы"></textarea>
                                        <input type="submit" name="submit" value="Отправить" class="btn btn-default">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        <?php endif; ?>

        <?php if($user->settings_who_online):?>

            <div class="panel panel-default">
                <div class="panel-heading">Кто просматривает тему (всего <?=$this->online_users['count']?>):</div>
                <div class="panel-body">
                    <?php foreach($this->online_users['data'] as $user):?>
                        <a href="<?=HOME."/user/profile/".$user['user_login']?>"><?=$user['user_login']?></a>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>