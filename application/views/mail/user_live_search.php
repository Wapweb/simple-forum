<?php
/** @var UserModel[] $findUsers */
$findUsers = $this->findUsers;
?>
<?php if(count($findUsers) > 0): ?>
<div class="dropdown clearfix">
    <button class="btn dropdown-toggle sr-only" type="button" data-toggle="dropdown">
        Dropdown
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" id="recipient_search" style="display: list-item" role="menu">
        <?php /** @var UserModel $user */?>
        <?php foreach($findUsers as $user): ?>
            <li><a tabindex="-1" href="#/"  class="users" id="<?=$user->login?>"><?=$user->login?></a></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>