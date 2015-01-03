<?php
$module = "index";
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li class="active">Панель управления</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
</div>