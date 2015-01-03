<script language="javascript" src="<?=HOME?>/assets/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="<?=HOME?>/assets/css/wbbtheme.css" type="text/css" />
<script language="javascript"  src="<?=HOME?>/assets/js/wysibb_answer.js"></script>
<script src="<?=HOME?>/assets/js/jquery.fileupload.js"></script>
<script src="<?=HOME?>/assets/js/fileupload.js"></script>
<script src="<?=HOME?>/assets/js/upload_files.js"></script>
<script src="<?=HOME?>/assets/js/add_message.js"></script>
<script src="<?=HOME?>/assets/js/delete_file.js"></script>
<style>
    .progress { display:none;position:relative; width:100px; border: 1px solid #ddd; margin-bottom: 0; height:15px; }
    .bar { background-color: #428bca; width:0%; height:15px;}
    .percent { position:absolute; display:inline-block; top:0px; left:44%;font-size: 10px; }
</style>

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

/** @var MessageModel $message */
$message = $this->message;

/** @var TopicModel $topic */
$topic = $message->getTopic();
?>

<div class="container">

    <ol class="breadcrumb">
        <li>Топик <a href="<?=HOME?>/topic/show/<?=Utility::UrlTranslit($topic->name)."-".$topic->getId()?>"><?=$topic->name?></a></li>
    </ol>

        <div class="panel panel-default">
            <div class="panel-heading"><div class="text-center"><strong>Ответ пользователю <?=$message->user_login?></strong></div></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" id="msg">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="output">
                                </div>
                                <?=isset($_GET['failed']) ? '<div class="alert alert-danger alert-dismissable" id="empty_msg">
                            Введите текст сообщения!
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>' : ''?>
                                <form id="msgForm" action="<?=HOME."/topic/add_msg/".$topic->getId()."/".$message->getId()?>" method="post">
                                    <textarea id="textarea_msg" name="message_text" rows="8" cols="100" maxlength="10000" placeholder="Сообщение темы"><?=$message->user_login?>, </textarea>
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

</div>