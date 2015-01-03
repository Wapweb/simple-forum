<?php
/** @var UserModel $user */
$user = $this->user;
$module = "mail";
/** @var EmailModel[] $emails */
$emails = $this->emails;
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><a href="<?=HOME?>/mail"><strong>Почта</strong></a></div></div>
        <div class="panel-body">
            <div class="row">
                <?php require_once ROOT."/application/views/widgets/left_sidebar.php";?>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class=""><a href="<?=HOME?>/mail/compose"><span class="glyphicon glyphicon-envelope"></span> Написать</a></li>
                                <li class="active"><a href="<?=HOME?>/mail"><span class="glyphicon glyphicon-arrow-down"></span> Входящие</a></li>
                                <li><a href="<?=HOME?>/mail/sent"><span class="glyphicon glyphicon-arrow-up"></span> Отправленные</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <?php if(count($emails) > 0):?>
                                <table class="table">
                                    <tbody>
                                    <?php /** @var EmailModel $email */?>
                                    <?php foreach($emails as $email) : ?>
                                        <tr>
                                            <?php if($email->read == 0):?>
                                                <td style="width: 20%"><a href="<?=HOME?>/user/profile/<?=$email->user_login?>"><div><strong><?=$email->user_login?></strong></div></a> </td>
                                                <td style="width: 65%"><a href="<?=HOME?>/mail/message/<?=$email->getId()?>"><div><strong><?=$email->title?></strong></div></a></td>
                                            <?php else: ?>
                                                <td style="width: 20%"><a href="<?=HOME?>/user/profile/<?=$email->user_login?>"><div><?=$email->user_login?></div></a> </td>
                                                <td style="width: 65%"><a href="<?=HOME?>/mail/message/<?=$email->getId()?>"><div><?=$email->title?></div></a></td>
                                            <?php endif;?>
                                            <td style="width: 15%"><?=date("H:i d.m.y",$email->create_date)?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if(isset($this->navigation)):?>
                                    <?=$this->navigation?>
                                <?php endif;?>
                            <?php else: ?>
                                <div class="alert alert-info">Входящих сообщений пока нет</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>