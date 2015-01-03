<?php
/** @var TopicModel[] $topics */
$topics = $this->topics;
/** @var ForumModel[] $forums */
$forums = $this->forums;

$countNames = array("сообщение","сообщения","сообщений");
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <p>
                <a href="<?=HOME?>" class="btn btn-default">Последние темы</a>
                <a href="<?=HOME?>/topic/active" class="btn btn-default active">Активные темы</a>
            </p>
            <?php if(count($topics) == 0):?>
                Топиков нет
            <?php else: ?>
            <table class="table">
                <tbody>
                <?php /** @var $topic TopicModel */?>
                <?php foreach($topics as $topic):?>
                    <tr>
                        <?php
                        $forum = $topic->getForum();
                        $forum_link = HOME."/forum/show/".Utility::UrlTranslit($forum->name)."-".$forum->getId();
                        ?>
                        <?php $link = HOME."/topic/show/".Utility::UrlTranslit($topic->name)."-".$topic->getId()?>
                        <?php $lastMessage = $topic->getLastMessage();?>
                        <?php $countMessage = MessageModel::getRowCount("WHERE ".TopicModel::getPrimaryKey()." = '".$topic->getId()."'")?>
                        <td>
                            <a href="<?=$link?>"><strong><?=$topic->name?></strong></a> <span style="float: right" class="h6"><a href="<?=$forum_link?>"><?=$forum->name?></a></span>
                            <div class="additional-block text-muted h6">
                                <span class="additional-info"><?=$countMessage?> <?=Utility::getNameWithEnd($countMessage,$countNames)?></span>
                                <span class="additional-info"> <a href="<?=HOME."/message/find/".$lastMessage->getId()?>">Последнее сообщение</a> от <a href="<?=HOME."/user/profile/".$lastMessage->user_login?>"><?=$lastMessage->user_login?></a></span>
                                <span class="additional-info"> <?=Utility::formatDate($lastMessage->create_date)?></span>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(isset($this->navigation)):?>
                <?=$this->navigation?>
            <?php endif; ?>
            <?php endif; ?>
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
        <div class="col-md-4">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><strong>Форумы</strong></div>

                <div class="list-group">
                    <?php /** @var $forum ForumModel */?>
                    <?php foreach($forums as $forum):?>
                        <?php $cnt = TopicModel::getRowCount("WHERE ".ForumModel::getPrimaryKey()." = '".$forum->getId()."'")?>
                        <?php $link = HOME."/forum/show/".Utility::UrlTranslit($forum->name)."-".$forum->getId();?>
                        <a class="list-group-item" href="<?=$link?>"><?=$forum->name?><span class="badge"><?=$cnt?></span></a>

                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div><!-- /.container -->
