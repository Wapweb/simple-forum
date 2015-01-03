<?php
/** @var CatalogModel $category */
$category = $this->category;
?>

<div class="container">
    <ol class="breadcrumb">
        <li class="">Раздел <a href="<?=HOME."/category/show/".Utility::makeUrl($category->name,$category->getId())?>"><?=$category->name?></a></li>
        <li class="active">Редактирование</li>
    </ol>
    <div class="panel panel-primary">
        <div class="panel-heading"><div class="text-center"><strong>Редактирование раздела</strong></div></div>
        <div class="panel-body">
            <?php if(isset($_GET["failed"])): ?>
                <div class="alert alert-danger">Ошибка редактирование раздела! Заполните все поля!</div>
            <?php endif; ?>
            <?php if(isset($_GET["success"])): ?>
                <div class="alert alert-success">Раздел успешно изменен
                    <a href="<?=HOME."/category/show/".Utility::UrlTranslit($category->name)."-".$category->getId()?>">Продолжить</a>
                </div>
            <?php endif; ?>
            <form class="form-horizontal" role="form" id="forum_update" method="post" action="<?=HOME?>/category/edit/<?=$category->getId()?>">
                <div class="form-group" id="cat">
                    <label for="inputCategory" class="col-lg-2 control-label">Название раздела</label>
                    <div class="col-lg-4">
                        <input type="text" name="category_name" value="<?=$category->name?>" class="form-control" id="inputCategory" placeholder="Название раздела" autocomplete="false">
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