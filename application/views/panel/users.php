<?php
$module = "users";
$users = $this->users;
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/panel">Панель управления</a></li>
        <li class="active">Пользователи</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(count($users) == 0):?>
                Пользователей нет
            <?php else: ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 5%">Id</th>
                        <th style="width: 35%">Логин</th>
                        <th style="width: 1">Ip</th>
                        <th style="width: 20%">Дата регистрации</th>
                        <th style="width: 30%">Действия</th>
                    </tr>
                    </thead>
                    <?php
                    /** @var UserModel $user*/
                    foreach($users as $user):
                        ?>
                        <tr>
                            <td>
                                <?=$user->getId()?>
                            </td>
                            <td>
                                <a href="<?=HOME."/user/profile".$user->login?>"><?=$user->login?></a>
                            </td>
                            <td>
                                <?=long2ip($user->register_ip)?>
                            </td>
                            <td>
                                <?=Utility::formatDate($user->register_date)?>
                            </td>
                            <td>
                                <a href="<?=HOME."/user/delete/".$user->getId()?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                                <a href="<?=HOME."/user/block/".$user->getId()?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> Заблокировать</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?= $this->navigation ? $this->navigation : "" ?>
            <?php endif; ?>
        </div>
    </div>
</div>