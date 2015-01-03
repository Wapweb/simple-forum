<?php
/** @var ForumModel $forum */
$forum = $this->forum;

?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class="active">Удаление</li>
    </ol>
    <div class="alert alert-danger">
        Вы действительно желаете удалить форум <strong><?=$forum->name?></strong> ?
        <a href="<?=HOME."/forum/delete/".$forum->getId()."/?confirm"?>" class="btn btn-danger">Да</a>
        <a href="<?=HOME."/forum/show/".Utility::makeUrl($forum->name,$forum->getId())?>" class="btn btn-danger">Отмена</a>
    </div>
</div>