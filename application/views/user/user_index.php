<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong><?=$this->user->getId() == $this->userProfile->getId() ? 'Мой профиль' : 'Профиль пользователя'?></strong></div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        <?php
                        if($this->user->getId() == $this->userProfile->getId()):
                        ?>
                        <a href="<?=HOME?>/user/change_avatar" class="thumbnail" style="margin-bottom: 4px;" title="Сменить аватар">
                            <img  width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($this->userProfile->avatar) ? "small_".$this->userProfile->avatar : 'avatar.png')?>    " class="img-thumbnail">
                        </a>
                        <?php else: ?>
                            <img  width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($this->userProfile->avatar) ? "small_".$this->userProfile->avatar : 'avatar.png')?>" class="img-thumbnail">
                        <?php endif;?>
                        <?php
                            if($this->user->getId() == $this->userProfile->getId()):
                        ?>
                        <a href="<?=HOME?>/user/profile/<?=$this->user->login?>" class="list-group-item active">Мой Профиль</a>
                        <a href="<?=HOME?>/user/settings/" class="list-group-item">Настройки</a>
                        <a href="<?=HOME?>/mail/" class="list-group-item">Личные сообщения</a>
                        <a href="<?=HOME?>/user/change_password" class="list-group-item">Сменить пароль</a>
                        <?php else: ?>
                                <a href="<?=HOME?>/user/profile/<?=$this->userProfile->login?>" class="list-group-item active">Профиль пользователя</a>
                                <div class="list-group-item">Личное сообщение (в разработке)</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <h3 style="display: inline"><?=$this->userProfile->login?> </h3> <?=Utility::userIsOnline($this->userProfile->getId()) ? '<span class="label label-success" style="display: inline">Online</span>' : '<span class="label label-danger" style="display: inline">Offline</span>'?>
                    <a href="" class="btn-primary"></a>
                    <h5>Регистрация: <?=date("d.m.y H:i",$this->userProfile->register_date)?></h5>
                    <div class="panel panel-default">
                        <div class="panel-heading">Статистика</div>

                            <table class="table">
                                <tr>
                                <td style="width: 20%;">Статус</td>
                                <td><a href="<?=HOME."/users/list/filter/".Utility::getUserFilter($this->userProfile->level)?>"><strong><?=Utility::getUserStatus($this->userProfile->level)?></strong></a></td>
                                </tr>
                                <tr>
                                <td>Сообщений на форуме</td>
                                <td>0</td>
                                </tr>
                                <tr>
                                <td>Созданных тем</td>
                                <td>0</td>
                                </tr>
                            </table>


                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">Контакты</div>
                            <table class="table">
                                <tr>
                                <td style="width: 20%;" >Email</td>
                                <td><a href="mailto:#"><?=$this->userProfile->email?></a></td>
                                </tr>
                            </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>