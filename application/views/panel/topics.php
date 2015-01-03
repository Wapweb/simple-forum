<?php
$module = "topics";
$topics = $this->topics;
$forums = $this->forums;
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/panel">Панель управления</a></li>
        <li class="active">Топики</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-3 col-md-offset-9">
            <form action="<?=HOME?>/panel/topics" method="post" class="form-inline" role="form">
                <div class="form-group">
                    <select name="forum" class="form-control">
                        <option value="0" <?=$this->selected_forum_id == 0 ? "selected" : ""?>>Все форумы</option>
                        <?php foreach($forums as $forum):?>
                            <option value="<?=$forum->getId()?>" <?=$this->selected_forum_id == $forum->getId() ? "selected" : ""?>><?=$forum->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                    <button type="submit" class="btn btn-default">Ок</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(count($topics) == 0):?>
                Топиков нет
            <?php else: ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 10%">Id</th>
                        <th style="width: 30%">Название</th>
                        <th style="width: 10%">Автор</th>
                        <th style="width: 20%">Форум</th>
                        <th style="width: 30%">Действия</th>
                    </tr>
                    </thead>
                    <?php
                    /** @var TopicModel $topic*/
                    foreach($topics as $topic):
                        $forum = $topic->getForum();
                        ?>
                        <tr>
                            <td>
                                <?=$topic->getId()?>
                            </td>
                            <td>
                                <a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/user/profile".$topic->user_login?>"><?=$topic->user_login?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/forum/show/".Utility::makeUrl($forum->name,$forum->getId())?>"><?=$forum->name?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/topic/delete/".$topic->getId()?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                                <a href="<?=HOME."/topic/edit/".$topic->getId()?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> Редактировать</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?= $this->navigation ? $this->navigation : "" ?>
            <?php endif; ?>
        </div>
    </div>
</div>