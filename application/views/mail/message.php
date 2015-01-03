<?php
/** @var UserModel $user */
$user = $this->user;
/** @var EmailModel $email */
$email = $this->email;

$iam = "recipient";
/** @var UserModel $userSender */
$userSender = null;
/** @var UserModel $userRecipient */
$userRecipient = null;
if($user->getId() == $email->sender_id) {
    $iam = "sender";
    $userSender = $user;
    $userRecipient = UserModel::findById($email->recipient_id);
} else {
    $userSender = UserModel::findById($email->sender_id);
    $userRecipient = $user;
}

if($iam == "recipient" || $userRecipient->getId() == $userSender->getId()) {
    $email->read = 1;
    $email->save();
}

$module = "mail";

?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><a href="<?=HOME?>/mail"><strong>Почта</strong></a> &mdash; <?=$iam == "recipient" ? "входящее" : "отправленное"?> письмо &laquo;<?=$email->title?>&raquo;</div></div>
        <div class="panel-body">
            <div class="row">
                        <div class="col-md-12">
                            <h4><?=$email->title?></h4>
                            <table class="table table-bordered">
                                <tr>
                                <td style="width: 5%">От</td>
                                <td><a href="<?=HOME."/user/profile/".$userSender->login?>"><?=$userSender->login?></a></td>
                                    </tr>
                               <tr>
                                <td>Кому</td>
                                <td><a href="<?=HOME."/user/profile/".$userRecipient->login?>"><?=$userRecipient->login?></a></td>
                                </tr>
                                <tr>
                                <td>Дата</td>
                                <td><?=date("H:i d.m.y",$email->create_date)?></td>
                                </tr>
                            </table>
                            <?=nl2br($email->body)?>
                            <hr>
                            <?php if($iam == "recipient"):?>
                                <a class="btn btn-primary" href="<?=HOME?>/mail/compose/<?=$email->getId()?>">Ответить</a>
                            <?php endif; ?>
                            <a class="btn btn-default" href="<?=HOME?>/mail/">Почта</a>
                        </div>
            </div>
        </div>
    </div>
</div>