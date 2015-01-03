<?php
$module = "categories";
$categories = $this->categories;
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/panel">Панель управления</a></li>
        <li class="active">Разделы</li>
    </ol>

    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <div class="btn-group">
            <a href="<?=HOME?>/category/create" class="btn btn-success">Добавить раздел</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(count($categories) == 0):?>
                Разделов нет
            <?php else: ?>

            <table class="table">
                <thead>
                    <tr><th style="width: 10%">Id</th><th style="width: 60%">Название</th><th style="width: 30%">Действия</th></tr>
                </thead>
            <?php
                /** @var CatalogModel $category  */
                foreach($categories as $category):
            ?>
              <tr>
                  <td>
                      <?=$category->getId()?>
                  </td>
                  <td>
                      <a href="<?=HOME."/category/show/".Utility::makeUrl($category->name,$category->getId())?>"><?=$category->name?></a>
                  </td>
                  <td>
                      <a href="<?=HOME."/category/delete/".$category->getId()?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                      <a href="<?=HOME."/category/edit/".$category->getId()?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> Редактировать</a>
                      <a href="<?=HOME."/forum/create/".$category->getId()?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span> Создать форум</a>
                  </td>
              </tr>
            <?php endforeach; ?>
            </table>
            <?= $this->navigation ? $this->navigation : "" ?>
            <?php endif; ?>
        </div>
    </div>

</div>