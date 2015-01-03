<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script>
    // prepare the form when the DOM is ready
    $(document).ready(function() {

        var wbbOpt = {
            buttons: "bold,italic,underline,|,img,link,smilebox,|,code,quote"
        }
        $('#textarea_msg').wysibb(wbbOpt);
    });
</script>
<div class="container">

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

    <ol class="breadcrumb">
        <li><a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><?=$forum->name?></a></li>
        <li class=""><a href="<?=HOME."/topic/show/".Utility::makeUrl($topic->name,$topic->getId())?>"><?=$topic->name?></a></li>
        <li class="active">
            <?php
                if($this->user->getId() == $message->user_id):
            ?>
                    Редактирование моего
                    <a href="<?=HOME."/message/find/".$message->getId()?>">сообщения #<?=$message->getId()?></a>
            <?php else: ?>
                    Редактирование
                    <a href="<?=HOME."/message/find/".$message->getId()?>">сообщения #<?=$message->getId()?></a>
                    пользователя <a href="<?=HOME."/user/profile/".$message->user_login?>"><?=$message->user_login?></a>
            <?php endif; ?>
        </li>
    </ol>

    <div class="panel panel-primary">
        <div class="panel-heading"><div class="text-center"><strong>Редактирование сообщения</strong></div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-18 col-sm-12 col-md-12" id="msg">
                    <div class="row">
                        <div class="col-xs-18 col-sm-12 col-md-12">
                            <?=isset($_GET['failed']) ? '<div class="alert alert-danger alert-dismissable" id="empty_msg">
                            Введите текст сообщения!
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>' : ''?>
                            <form action="<?=HOME."/message/edit/".$message->getId()?>" method="post">
                                <textarea id="textarea_msg" name="message_text" rows="8" maxlength="10000" placeholder="Сообщение темы"><?=$message->text?></textarea>
                                <input type="submit" name="submit" value="Изменить" class="btn btn-default">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>