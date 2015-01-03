<script language="javascript" src="<?=HOME?>/assets/js/update_user_profile.js"></script>
<?php
/** @var UserModel $user */
$user = $this->user;
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Настройки</strong></div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        <a href="<?=HOME?>/user/change_avatar" class="thumbnail" style="margin-bottom: 4px;" title="Сменить аватар">
                            <img  width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($user->avatar) ? "small_".$user->avatar : 'avatar.png')?>" class="img-thumbnail">
                        </a>
                        <a href="<?=HOME?>/user/profile/<?=$user->login?>" class="list-group-item">Мой Профиль</a>
                        <a href="<?=HOME?>/user/settings/" class="list-group-item active">Настройки</a>
                        <a href="<?=HOME?>/mail/" class="list-group-item">Личные сообщения</a>
                        <a href="<?=HOME?>/user/change_password" class="list-group-item">Сменить пароль</a>
                    </div>
                </div>
                <div class="col-md-10">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Настройки отображения</a></li>
                        <li><a href="#profile" data-toggle="tab">Настройки профиля</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <form class="form-horizontal" style="padding-top: 10px;" role="form" id="view_settings" method="post" action="<?=HOME?>/user/settings_view_process">
                                <div id="output_view"></div>
                                <div class="form-group" id="pass_old">
                                    <label for="inputPassword1" class="col-lg-3 control-label">Сообщений на страницу</label>
                                    <div class="col-lg-2">
                                        <input type="text" name="settings_count_of_page" class="form-control" id="inputPasswordOld" placeholder="" autocomplete="false" value="<?=$user->settings_count_of_page?>" min="5" max="40">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="settings_who_online" value="1"  <?=$user->settings_who_online ? 'checked="true"' : ''?>> "Кто просматривает тему"
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="settings_quick_answer" value="1" <?=$user->settings_quick_answer ? 'checked="true"' : ''?>> Быстрый ответ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-4">
                                        <button type="submit" id="button_view" class="btn btn-default" data-loading-text="Loading...">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="profile">
                            <form class="form-horizontal" style="padding-top: 10px;" role="form" id="profile_settings" method="post" action="<?=HOME?>/user/settings_profile_process">
                                <div id="output_profile"></div>
                                <div class="form-group" id="user_name_group">
                                    <label for="user_name" class="col-lg-3 control-label">Имя</label>
                                    <div class="col-lg-2">
                                        <input type="text" name="settings_user_name" class="form-control" id="settings_user_name" placeholder="" autocomplete="false" value="<?=$user->name?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-4">
                                        <button type="submit" id="button_profile" class="btn btn-default">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="output"></div>
                </div>
            </div>
        </div>
    </div>
</div>