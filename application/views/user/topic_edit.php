<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script>
    // prepare the form when the DOM is ready
    $(document).ready(function() {

        var wbbOpt = {
            buttons: "bold,italic,underline,|,img,link,smilebox,|,code,quote"
        }
        $('#wbbeditor').wysibb(wbbOpt);
    });
</script>

<?php
/** @var ForumModel $forum */
$forum = $this->forum;

/** @var TopicModel $topic */
$topic = $this->topic;

/** @var MessageModel $message */
$message = $this->message;

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

<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class=""><a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a></li>
        <li class="active">Редактирование</li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Редактирование темы</strong></div></div>
        <div class="panel-body">
            <?php if(isset($_GET["failed"])): ?>
                <div class="alert alert-danger">Ошибка редактирование темы! Заполните все поля!</div>
            <?php endif; ?>
            <?php if(isset($_GET["success"])): ?>
                <div class="alert alert-success">Тема успешно изменена!
                    <a href="<?=HOME."/topic/show/".Utility::UrlTranslit($topic->name)."-".$topic->getId()?>">Продолжить</a>
                </div>
            <?php endif; ?>
            <form role="form" id="topic_create" method="post" action="<?=HOME?>/topic/edit/<?=$topic->getId()?>">
                <div class="form-group" id="tn">
                    <label for="id_name">Название Темы</label>
                    <div class="row">
                        <div class="col-lg-6" id="tpn">
                            <input type="text" name="topic_name" maxlength="200" class="form-control" id="id_name" placeholder="Название темы" value="<?=$topic->name?>">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="mt">
                    <label for="id_topic_message">Сообщение темы</label>
                    <div class="row">
                        <div class="col-lg-8" id="msgt">
                            <textarea id="wbbeditor" name="message_text" rows="10" maxlength="10000" class="form-control" id="id_topic_message" placeholder="Сообщение темы"><?=$message->text?></textarea>
                        </div>
                    </div>
                    <button type="submit" id="button" class="btn btn-default">Изменить</button>
                </div>
            </form>
            <div id="output" style="padding-top: 5px;"></div>
        </div>
    </div>
</div>