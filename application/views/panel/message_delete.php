<?php
/** @var ForumModel $forum */
$forum = $this->forum;

/** @var TopicModel $topic */
$topic = $this->topic;

/** @var MessageModel $message */
$message = $this->message;

?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class=""><a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a></li>
        <li class="active">
            Удаление
            <a href="<?=HOME."/message/find/".$message->getId()?>">сообщения #<?=$message->getId()?></a>
            пользователя <a href="<?=HOME."/user/profile/".$message->user_login?>"><?=$message->user_login?></a>
        </li>
    </ol>
    <div class="alert alert-danger">
        Вы действительно желаете удалить <a href="<?=HOME."/message/find/".$message->getId()?>"> Сообщение #<?=$message->getId()?></a> ?
        <a href="<?=HOME."/message/delete/".$message->getId()."/?confirm"?>" class="btn btn-danger">Да</a>
        <a href="<?=HOME."/message/find/".$message->getId()?>" class="btn btn-danger">Отмена</a>
    </div>
</div>