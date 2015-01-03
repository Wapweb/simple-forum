<?php
/** @var ForumModel $forum */
$forum = $this->forum;
?>

<div class="container">
    <ol class="breadcrumb">
        <li class=""><a href="<?=HOME."/forum/show/".Utility::makeUrl($forum->name,$forum->getId())?>"><?=$forum->name?></a></li>
        <li class="active">Редактирование</li>
    </ol>
    <div class="panel panel-primary">
        <div class="panel-heading"><div class="text-center"><strong>Редактирование форума</strong></div></div>
        <div class="panel-body">
            <?php if(isset($_GET["failed"])): ?>
                <div class="alert alert-danger">Ошибка редактирование форума! Заполните все поля!</div>
            <?php endif; ?>
            <?php if(isset($_GET["success"])): ?>
                <div class="alert alert-success">Форум успешно изменен
                    <a href="<?=HOME."/forum/show/".Utility::UrlTranslit($forum->name)."-".$forum->getId()?>">Продолжить</a>
                </div>
            <?php endif; ?>
            <form class="form-horizontal" role="form" id="forum_update" method="post" action="<?=HOME?>/forum/edit/<?=$forum->getId()?>">
                <div class="form-group" id="cat">
                    <label for="inputCategory" class="col-lg-2 control-label">Название форума</label>
                    <div class="col-lg-4">
                        <input type="text" name="forum_name" value="<?=$forum->name?>" class="form-control" id="inputCategory" placeholder="Название форума" autocomplete="false">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-4">
                        <button type="submit" id="button" class="btn btn-default">Изменить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>