<?php

/** @var ForumModel $forum */
$forum = $this->forum;

/** @var CatalogModel $catalog */
$catalog = $this->catalog;

/** @var TopicModel[] $topics */
$topics = $this->topics;

/** @var UserModel $user */
$user = $this->user;

/** @var ForumModel[] $forums */
$forums = $this->forums;

$countNames = array("сообщение","сообщения","сообщений");
?>

<div class="container" style="">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li class="active"><?=$forum->name?>
            <?php if($user->level > 9): ?>
                <a href="<?=HOME?>/forum/edit/<?=$forum->getId()?>" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" title="Редактировать форум"></span></a>
                <a href="<?=HOME?>/forum/delete/<?=$forum->getId()?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" title="Удалить форум"></span></a>
            <?php endif;?>
        </li>
    </ol>

    <div class="row">
        <div class="col-md-8">
		
			<?php if($user->level > 0): ?>
            <p>
                <a href="<?=HOME?>/topic/create/<?=$forum->getId()?>" class="btn btn-default" style="margin-bottom: 5px;">Создать тему</a>
            </p>
			<?php endif;?>
			
            <?php if(count($topics) == 0):?>
                Топиков нет
            <?php else: ?>
            <table class="table">
                <tbody>
                <?php /** @var $topic TopicModel */?>
                <?php foreach($topics as $topic):?>
                    <tr>
                        <?php
                        $forum = $topic->getForum();
                        $forum_link = HOME."/forum/show/".Utility::UrlTranslit($forum->name)."-".$forum->getId();
                        ?>
                        <?php $link = HOME."/topic/show/".Utility::UrlTranslit($topic->name)."-".$topic->getId()?>
                        <?php $lastMessage = $topic->getLastMessage();?>
                        <?php $countMessage = MessageModel::getRowCount("WHERE ".TopicModel::getPrimaryKey()." = '".$topic->getId()."'")?>
                        <td>
                            <a href="<?=$link?>"><strong><?=$topic->name?></strong></a> <span style="float: right" class="h6"><a href="<?=$forum_link?>"><?=$forum->name?></a></span>
                            <div class="additional-block text-muted h6">
                                <span class="additional-info"><?=$countMessage?> <?=Utility::getNameWithEnd($countMessage,$countNames)?></span>
                                <span class="additional-info"> <a href="<?=HOME."/message/find/".$lastMessage->getId()?>">Последнее сообщение</a> от <a href="<?=HOME."/user/profile/".$lastMessage->user_login?>"><?=$lastMessage->user_login?></a></span>
                                <span class="additional-info"> <?=Utility::formatDate($lastMessage->create_date)?></span>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(isset($this->navigation)):?>
                <?=$this->navigation?>
            <?php endif; ?>
            <?php endif;?>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><strong>Форумы</strong></div>

                <div class="list-group">
                    <?php /** @var $forum ForumModel */?>
                    <?php foreach($forums as $forumLoc):?>
                        <?php $cnt = TopicModel::getRowCount("WHERE ".ForumModel::getPrimaryKey()." = '".$forumLoc->getId()."'")?>
                        <?php $link = HOME."/forum/show/".Utility::UrlTranslit($forumLoc->name)."-".$forumLoc->getId();?>
                        <a class="list-group-item <?=$forumLoc->getId() == $forum->getId() ? "active" : ""?>" href="<?=$link?>"><?=$forumLoc->name?><span class="badge"><?=$cnt?></span></a>

                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>


</div><!-- /.container -->
