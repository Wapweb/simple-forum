<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script language="javascript" src="<?=HOME?>/assets/js/textarea.js"></script>
<script language="javascript" src="<?=HOME?>/assets/js/online_topic.js"></script>
<script src="<?=HOME?>/assets/js/jquery.fileupload.js"></script>
<script src="<?=HOME?>/assets/js/fileupload.js"></script>
<script src="<?=HOME?>/assets/js/upload_files.js"></script>
<script src="<?=HOME?>/assets/js/add_message.js"></script>
<script src="<?=HOME?>/assets/js/delete_file.js"></script>
<style>
    div#spinner
    {
        display: none;
        width:142px;
        height: 16px;
        position: fixed;
        top: 50%;
        left: 50%;
        background:url(/assets/images/loader.gif) no-repeat center #fff;
        text-align:center;
        padding:10px;
        font:normal 16px Tahoma, Geneva, sans-serif;
        margin-left: -50px;
        margin-top: -50px;
        z-index:2;
        overflow: auto;
    }
    .progress { display:none;position:relative; width:100px; border: 1px solid #ddd; margin-bottom: 0; height:15px; }
    .bar { background-color: #428bca; width:0%; height:15px;}
    .percent { position:absolute; display:inline-block; top:0px; left:44%;font-size: 10px; }
</style>
<div id="spinner">
</div>

<?php

/** @var CatalogModel $catalog */
$catalog = $this->catalog;

/** @var ForumModel $forum */
$forum = $this->forum;

/** @var TopicModel $topic */
$topic = $this->topic;

/** @var MessageModel[] $messages */
$messages = $this->messages;

/** @var UserModel $user */
$user = $this->user;

?>

<div class="container">

    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class="active">Топик: <?=$topic->name?></li>
    </ol>
    <h3><?=$topic->name?></h3>
    <?php
    if($this->navigation != "")
        echo "<div class='text-right'>".$this->navigation."</div>";
    ?>
    <?php
    require_once ROOT."/application/libs/bbcode/Parser.php";

    $parser = new JBBCode\Parser();
    //$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

    $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

    $builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
    $parser->addCodeDefinition($builder->build());

    $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre>{param}</pre>');
    // $builder->setParseContent(false);
    $parser->addCodeDefinition($builder->build());
    ?>
    <div class="panel panel-default">
        <?php /** @var MessageModel $msg */?>
        <?php foreach($messages as $msg) :?>
            <?php
            $files = $msg->getFiles();
            $countFiles = count($files);
            /** @var FileModel $file */
            ?>
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <div class="media">
                            <a class="pull-left" href="<?=HOME."/user/profile/".$msg->user_login?>">
                                <img width="38" height="38" class="media-object" src="<?=HOME?>/assets/images/avatars/<?=$msg->user_avatar ? 'small_'.$msg->user_avatar : 'avatar.png'?>" alt="...">
                            </a>
                            <div class="media-body">
                                <div class="media-heading">
                                    <a name="msg_<?=$msg->getId()?>" href="/user/profile/<?=$msg->user_login?>"><strong><?=$msg->user_login?></strong></a> <?=Utility::userIsOnline($msg->user_id) ? '<sup><span class="label label-success">ON</span></sup>' : '<sup><span class="label label-danger">OFF</span></sup>'?>
                                    <span class="text-muted h6" style="margin-left: 5px"><?=Utility::formatDate($msg->create_date)?></span>
                                    <span style="float: right"><a href="<?=HOME."/message/find/".$msg->getId()?>" title="Ссылка на сообщение">#</a></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-top: 5px">
                    <div class="col-md-12">
                        <?php
                        $text = nl2br($msg->text);
                        $parser->parse($text);
                        $out = $parser->getAsHtml();
                        print $out;
                        ?>

                        <?php if($countFiles > 0): ?>
                            <hr>
                            <h6 class="text-muted">Прикрепленные файлы:</h6>
                            <ul class="list-unstyled">
                                <?php foreach($files as $file): ?>
                                    <li><a href="<?=HOME?>/assets/files/<?=$file->name?>" target="_blank"><span class="glyphicon glyphicon-file"></span> <?=$file->name?></a></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif; ?>

                        <?php if($msg->update_count):?>
                            <hr>
                            <h6><small>
                                    Последний раз сообщение отредактировал <a href="<?=HOME."/user/profile/".$msg->last_update_login?>"><?=$msg->last_update_login?></a>
                                    в <?=date("H:i d.m.y",$msg->update_date)?> (всего <?=$msg->update_count?> раз(а))

                                </small></h6>

                        <?php endif; ?>
                    </div>
                </div>
				<?php if($user->level > 0) :?>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-offset-1 col-md-11">
                        <div class="text-right">
                            <a href="<?=HOME?>/topic/answer_msg/<?=$msg->getId()?>" id="<?=$msg->getId()?>" class="msg_answer btn btn-default btn-xs">Ответить</a>
                            <a href="<?=HOME?>/topic/quote_msg/<?=$msg->getId()?>" class="msg_quote btn btn-default btn-xs" id="<?=$msg->getId()?>">Цитировать</a>
                        </div>
                    </div>
                </div>
				<?php endif; ?>
            </div>
        <?php endforeach ?>
    </div>
    <?php
    if($this->navigation != "")
        echo "<div class='text-right'>".$this->navigation."</div>";
    ?>

    <?php if($user->level > 0) :?>

        <?php if($user->settings_quick_answer):?>

            <div class="panel panel-default">
                <div class="panel panel-body">
                    <div class="row">
                        <div class="col-md-12" id="msg">
                            <div id="output">
                            </div>
                            <div class="alert alert-danger alert-dismissable" id="empty_msg" style="display: none;">
                                Введите текст сообщения!
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <form id="msgForm" action="<?=HOME."/topic/add_msg/".$topic->getId()."/?page=".abs(intval(isset($_GET['page']) ? $_GET['page'] : 1))?>" method="post">
                                <textarea id="textarea_msg" name="message_text" rows="8" cols="100" maxlength="10000" placeholder="Сообщение темы"></textarea>
                            </form>
                            <form action="<?=HOME?>/file/upload" id="fileForm" method="post" enctype="multipart/form-data">
                                <div class="text-right" style="margin-top: -5px;margin-bottom: 5px;">
                                    <input  form="fileForm" type="file" id="files" name="fileNames[]" title="Прикрепить файлы" multiple>
                                    <input form="msgForm" type="submit" name="submit" form="" value="Отправить" class="btn btn-primary" id="msg-btn">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="10240" />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress">
                                <div class="bar"></div >
                                <div class="percent">0%</div >
                            </div>
                            <div id="fileDownload">
                            </div>
                            <div id="fileOut">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <?php if($user->settings_who_online):?>

            <div class="panel panel-default" style="margin-top: 5px;">
                <div class="panel-heading">Кто просматривает тему (всего <?=$this->online_users['count']?>):</div>
                <div class="panel-body">
                    <?php foreach($this->online_users['data'] as $user):?>
                        <a href="<?=HOME."/user/profile/".$user['user_login']?>"><?=$user['user_login']?></a>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>
		
		<?php else: ?>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-info">Только <a href="http://abitua.com/registration" class="alert-link">зарегистрированные</a> пользователи могут писать сообщения</div>
				</div>
			</div>

    <?php endif; ?>

		<div class="text-center">
					<script type="text/javascript"><!--
google_ad_client = "ca-pub-6880298536449575";
/* Первый блок */
google_ad_slot = "5492101241";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
</div>