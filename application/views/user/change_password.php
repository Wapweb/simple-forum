<?php
/** @var UserModel $user */
$user = $this->user;
?>
<script language="javascript" src="<?=HOME?>/assets/js/change_password.js"></script>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Смена пароля</strong></div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                            <a href="<?=HOME?>/user/change_avatar" class="thumbnail" style="margin-bottom: 4px;" title="Сменить аватар">
                                <img  width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($user->avatar) ? "small_".$user->avatar : 'avatar.png')?>" class="img-thumbnail">
                            </a>
                            <a href="<?=HOME?>/user/profile/<?=$user->login?>" class="list-group-item">Мой Профиль</a>
                            <a href="<?=HOME?>/user/settings/" class="list-group-item">Настройки</a>
                        <a href="<?=HOME?>/mail/" class="list-group-item">Личные сообщения</a>
                            <a href="<?=HOME?>/user/change_password" class="list-group-item active">Сменить пароль</a>
                    </div>
                </div>
                <div class="col-md-10">
                    <form class="form-horizontal" role="form" id="change_password" method="post" action="<?=HOME?>/user/change_password_process">
                        <div class="form-group" id="pass_old">
                            <label for="inputPassword1" class="col-lg-3 control-label">Старый пароль</label>
                            <div class="col-lg-4">
                                <input type="password" name="password_old" class="form-control" id="inputPasswordOld" placeholder="Старый пароль" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group" id="pass">
                            <label for="inputPassword1" class="col-lg-3 control-label">Новый пароль</label>
                            <div class="col-lg-4">
                                <input type="password" name="password" class="form-control" id="inputPassword1" placeholder="Новый пароль" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group" id="pass_repeat">
                            <label for="inputPassword1" class="col-lg-3 control-label">Новый пароль(повторите)</label>
                            <div class="col-lg-4">
                                <input type="password" name="password_repeat" class="form-control" id="inputPassword2" placeholder="Новый пароль(повторите)" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-4">
                                <button type="submit" id="button" class="btn btn-default">Сменить пароль</button>
                            </div>
                        </div>
                    </form>
                    <div id="output"></div>
                </div>
            </div>
        </div>
    </div>
</div>