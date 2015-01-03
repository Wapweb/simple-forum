<?php
/** @var ForumModel $forum */
$forum = $this->forum;

/** @var TopicModel $topic */
$topic = $this->topic;

?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class=""><a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a></li>
        <li class="active">Удаление</li>
    </ol>
    <div class="alert alert-danger">
        Вы действительно желаете удалить тему <strong><?=$topic->name?></strong> ?
        <a href="<?=HOME."/topic/delete/".$topic->getId()."/?confirm"?>" class="btn btn-danger">Да</a>
        <a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>" class="btn btn-danger">Отмена</a>
    </div>
</div>