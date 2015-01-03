<?php

/** @var ForumModel $forum */
$forum = $this->forum;

/** @var CatalogModel $catalog */
$catalog = $this->catalog;

/** @var TopicModel[] $topics */
$topics = $this->topics;

/** @var UserModel $user */
$user = $this->user;
?>

<div class="container" style="">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($catalog->name)."-".$catalog->getId()?>"><?=$catalog->name?></a></li>
        <li class="active"><?=$forum->name?></li>
    </ol>
    <a href="<?=HOME?>/topic/create/<?=$forum->getId()?>" class="btn btn-default" style="margin-bottom: 5px;">Создать тему</a>

    <?php if($this->navigation != ""):?>
        <div style="margin-top: 15px;"><?=$this->navigation?></div>
    <?php endif; ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><a style="color: #ffffff;" href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></div>
    <?php if(count($topics) == 0):?>
        <div class="text-info btn-lg">Топиков нет</div>
    <?php else:?>

            <table class="table table-striped">

                <tbody>
                <?php /** @var TopicModel $topic */?>
                <?php foreach($topics as $topic):?>
                    <tr>
                        <td style="width: 3%"><span class="glyphicon glyphicon-list-alt h3"></span></td>
                        <td style="width: 50%">
                            <a href="<?=HOME?>/topic/show/<?=Utility::UrlTranslit($topic->name)."-".$topic->getId()?>"><strong><?=$topic->name?></strong></a><br>
                            <h6> Автор: Wapweb</h6>
                        <td style="width: 30%">
                            <a href="">wapweb</a><br>
                            <a href="">Сегодня в 20-40</a>
                        </td>
                        <td style="width: 15%">0 просмотров<br>
                            0 ответов</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
        </div>

    <?php if($this->navigation != ""):?>
        <?=$this->navigation?>
    <?php endif; ?>

    <?php if($user != null) : ?>
        <?php if($user->level == 10) : ?>
            <div class="alert alert-info" style="margin-bottom: 10px;">
                <h5>Управление разделом</h5>
                <div class="btn-group">
                    <a href="<?=HOME?>/forum/edit/<?=$forum->getId()?>" class="btn btn-primary">Редактировать форум</a><a href="<?=HOME?>/forum/delete/<?=$forum->getId()?>" class="btn btn-primary">Удалить форум</a>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>


</div><!-- /.container -->
