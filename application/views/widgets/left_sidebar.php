<div class="col-md-2">
    <div class="list-group">
        <a href="<?=HOME?>/user/change_avatar" class="thumbnail" style="margin-bottom: 4px;" title="Сменить аватар">
            <img  width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($user->avatar) ? "small_".$user->avatar : 'avatar.png')?>" class="img-thumbnail">
        </a>
        <a href="<?=HOME?>/user/profile/<?=$user->login?>" class="list-group-item <?=$module == "profile" ? "active" : ""?>">Мой Профиль</a>
        <a href="<?=HOME?>/user/settings/" class="list-group-item <?=$module == "settings" ? "active" : ""?>">Настройки</a>
        <a href="<?=HOME?>/mail/" class="list-group-item <?=$module == "mail" ? "active" : ""?>">Личные сообщения</a>
        <a href="<?=HOME?>/user/change_password" class="list-group-item <?=$module == "change_password" ? "active" : ""?>">Сменить пароль</a>
    </div>
</div>