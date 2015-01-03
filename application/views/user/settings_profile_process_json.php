<?php
header('Content-type: application/json');
$out = '';
$out = '<div class="alert alert-success alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Настройки профиля обновлены!
        </div>
';
print json_encode(array('out'=>$out));
?>