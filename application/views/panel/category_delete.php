<?php
/** @var CatalogModel $category */
$category = $this->category;
?>

<div class="container">
    <ol class="breadcrumb">
        <li>Раздел <a href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($category->name)."-".$category->getId()?>"><?=$category->name?></a></li>
        <li class="active">Удаление</li>
    </ol>
    <div class="alert alert-danger">
        Вы действительно желаете удалить раздел <strong><?=$category->name?></strong> ?
        <a href="<?=HOME."/category/delete/".$category->getId()."/?confirm"?>" class="btn btn-danger">Да</a>
        <a href="<?=HOME."/category/show/".Utility::makeUrl($category->name,$category->getId())?>" class="btn btn-danger">Отмена</a>
    </div>
</div>