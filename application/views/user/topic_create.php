<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script language="javascript" src="<?=HOME?>/assets/js/topic_add.js"></script>

<?php
/** @var ForumModel $forum */
$forum = $this->forum;
?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class="active">Новая тема</li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Создание темы</strong></div></div>
        <div class="panel-body">
            <form role="form" id="topic_create" method="post" action="<?=HOME?>/topic/create/<?=$forum->getId()?>">
                <div class="form-group" id="tn">
                    <label for="id_name">Название Темы</label>
                    <div class="row">
                        <div class="col-lg-6" id="tpn">
                            <input type="text" name="topic_name" maxlength="200" class="form-control" id="id_name" placeholder="Название темы">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="mt">
                    <label for="id_topic_message">Сообщение темы</label>
                    <div class="row">
                        <div class="col-lg-8" id="msgt">
                            <textarea id="wbbeditor" name="message_text" rows="10" maxlength="10000" class="form-control" id="id_topic_message" placeholder="Сообщение темы"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" id="button" class="btn btn-default">Создать</button>
            </form>
            <div id="output" style="padding-top: 5px;"></div>
        </div>
    </div>
</div>