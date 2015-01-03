<?php
$module = "forums";
$forums = $this->forums;
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/panel">Панель управления</a></li>
        <li class="active">Форумы</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(count($forums) == 0):?>
                Форумов нет
            <?php else: ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 10%">Id</th>
                        <th style="width: 40%">Название</th>
                        <th style="width: 20%">Раздел</th>
                        <th style="width: 30%">Действия</th>
                    </tr>
                    </thead>
                    <?php
                    /** @var ForumModel $forum*/
                    foreach($forums as $forum):
                        $category = $forum->getCatalog();
                    ?>
                        <tr>
                            <td>
                                <?=$forum->getId()?>
                            </td>
                            <td>
                                <a href="<?=HOME."/forum/show/".Utility::makeUrl($forum->name,$forum->getId())?>"><?=$forum->name?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/category/show/".Utility::makeUrl($category->name,$category->getId())?>"><?=$category->name?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/forum/delete/".$forum->getId()?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                                <a href="<?=HOME."/forum/edit/".$forum->getId()?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> Редактировать</a>
                                <a href="<?=HOME."/topic/create/".$forum->getId()?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span> Новая тема</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?= $this->navigation ? $this->navigation : "" ?>
            <?php endif; ?>
        </div>
    </div>
</div>