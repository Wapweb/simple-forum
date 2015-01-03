<?php
$module = "messages";
$messages = $this->messages;
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/panel">Панель управления</a></li>
        <li class="active">Сообщения</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php require_once(ROOT."/application/views/widgets/panel_menu.php");?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(count($messages) == 0):?>
                Сообщений нет
            <?php else: ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 5%">Id</th>
                        <th style="width: 10%">Автор</th>
                        <th style="width: 30%">Текст сообщения</th>
                        <th style="width: 15%">Дата</th>
                        <th style="width: 20%">Топик</th>
                        <th style="width: 20%">Действия</th>
                    </tr>
                    </thead>
                    <?php
                    /** @var MessageModel $message*/
                    foreach($messages as $message):
                        $topic = $message->getTopic();
                        ?>
                        <tr>
                            <td>
                                <?=$message->getId()?>
                            </td>
                            <td>
                                <a href="<?=HOME."/user/profile".$message->user_login?>"><?=$message->user_login?></a>
                            </td>
                            <td>
                                <?=nl2br(mb_substr($message->text,0,100))?>
                            </td>
                            <td>
                                <?=Utility::formatDate($message->create_date)?>
                            </td>
                            <td>
                                <a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a>
                            </td>
                            <td>
                                <a href="<?=HOME."/message/delete/".$topic->getId()?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                                <a href="<?=HOME."/message/edit/".$topic->getId()?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span> Редактировать</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?= $this->navigation ? $this->navigation : "" ?>
            <?php endif; ?>
        </div>
    </div>
</div>