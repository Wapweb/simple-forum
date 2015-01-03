<?php

?>
<div class="container" style="  ">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li class="active"><?=$this->catalog->name?></li>
    </ol>


    <div class="panel panel-primary">
        <div class="panel-heading"><a style="color: #ffffff;" href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($this->catalog->name)."-".$this->catalog->getId()?>"><?=$this->catalog->name?></a></div>
    <?php if(count($this->forums) == 0):?>
    <div class="text-info btn-lg">Форумов пока нет</div>
    <?php else:?>

        <table class="table table-striped table-bordered">
    
                <tbody>
                    <?php /** @var ForumModel $forum */ ?>
                    <?php foreach($this->forums as $forum):?>
                        <tr>
                            <td style="width: 30%">
                                <span class="glyphicon glyphicon-align-left btn-lg"></span>
                                <a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><strong><?=$forum->name?></strong></a>
                            </td>
                            <td style="width: 45%">
                                <a href="">Название темы</a><br>
                                Автор: <a href="">wapweb</a><br>
                                <a href="">30 августа 2013 - 21:16</a>
                            </td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if($this->user != null) : ?>
        <?php if($this->user->level == 10) : ?>
            <div class="alert alert-info" style="margin-bottom: 10px;">
                <h5>Управление разделом</h5>
                <div class="btn-group">
                    <a href="<?=HOME?>/forum/create/<?=$this->catalog->getId()?>" class="btn btn-primary">Добавить форум</a><a href="<?=HOME?>/category/edit/<?=$this->catalog->getId()?>" class="btn btn-primary">Редактировать раздел</a><a href="<?=HOME?>/category/delete/<?=$this->catalog->getId()?>" class="btn btn-primary">Удалить раздел</a>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>


</div><!-- /.container -->
