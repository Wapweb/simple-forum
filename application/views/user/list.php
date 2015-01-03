<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Пользователи форума</strong></div> </div>
        <div class="panel-body">
                <div class="btn-group">
                    <div class="btn btn-default disabled">Фильтр</div>
                    <a href="<?=HOME?>/users/list/" class="btn btn-default <?=!$this->filter ? 'active' : ''?>">Все</a>
                    <a href="<?=HOME?>/users/list/filter/user" class="btn btn-default <?=$this->filter == 'user' ? 'active' : ''?>">Пользователи</a>
                    <a href="<?=HOME?>/users/list/filter/moderator" class="btn btn-default <?=$this->filter == 'moderator' ? 'active' : ''?>">Модераторы</a>
                    <a href="<?=HOME?>/users/list/filter/admin" class="btn btn-default <?=$this->filter == 'admin' ? 'active' : ''?>">Администраторы</a>
                </div>
            <?php if($this->count > 0): ?>

                <table class="table">
                    <tr>
                        <th style="width: 10%">№</th><th style="width: 70%">Логин</th><th style="width: 20%">Регистрация</th>
                    </tr>
                    <?php /** @var UserModel $user */ ?>
                    <?php foreach($this->users as $user) : ?>
                        <tr>
                            <td><?=$user->getId()?></td>
                            <td><a href="<?=HOME?>/user/profile/<?=$user->login?>"><?=$user->login?></a> <span class="label label-primary"><?=$user->getUserType()?></span></td>
                            <td><?=date("d.m.y H:i",$user->register_date)?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <?php if($this->navigation):?>
                    <?=$this->navigation?>
                <?php endif;?>
            <?php else:?>
                <div class="alert alert-warning" style="margin-top: 5px;">Пользователей нет</div>
            <?php endif; ?>
        </div>
    </div>
</div>