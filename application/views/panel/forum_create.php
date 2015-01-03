<?php

/** @var CatalogModel $category */
$category = $this->category;
?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($category->name)."-".$category->getId()?>"><?=$category->name?></a></li>
        <li class="active">Новый форум</li>
    </ol>
    <div class="panel panel-primary">
        <div class="panel-heading"><div class="text-center"><strong>Создание форума</strong></div></div>
        <div class="panel-body">
            <?php if(isset($_GET["failed"])): ?>
                <div class="alert alert-danger">Ошибка создание форума! Заполните все поля!</div>
            <?php endif; ?>
            <form role="form" id="topic_create" method="post" action="<?=HOME?>/forum/create/<?=$category->getId()?>">
                <div class="form-group" id="tn">
                    <label for="id_name">Название форума</label>
                    <div class="row">
                        <div class="col-lg-6" id="tpn">
                            <input type="text" name="forum_name" maxlength="200" class="form-control" id="id_name" placeholder="Название форума">
                        </div>
                    </div>
                </div>
                <button type="submit" id="button" class="btn btn-default">Создать</button>
            </form>
        </div>
    </div>
</div>