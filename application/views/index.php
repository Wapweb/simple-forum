<div class="container">
    <?php if($this->user != null) : ?>
        <?php if($this->user->level ==10):?>
            <div class="alert alert-info" style="margin-bottom: 10px;">
                <h5>Доброго времени суток, администратор!</h5>
                <div class="btn-group">
                    <a href="<?=HOME?>/category/create" class="btn btn-primary">Добавить новый раздел</a><a href="<?=HOME?>/panel/settings" class="btn btn-primary">Настройки системы</a><a href="<?=HOME?>/users/list" class="btn btn-primary">Управление пользователями</a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif;?>

        <?php /** @var CatalogModel $catalog */?>
        <?php foreach($this->catalogs as $catalog):?>

            <?php
                /** @var ForumModel $forum */
                $forums = $catalog->getForums();
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading"><a style="color: #ffffff" href="<?=HOME?>/category/show/<?=Utility::UrlTranslit($catalog->name)."-".$catalog->getId()?>"><?=$catalog->name?></a></div>
                <!--<div class="panel-body">!-->
                    <table class="table table-striped">
                        <tbody>
                        <?php foreach($forums as $forum):?>
                            <tr>

                                <td style="width: 30%"><span class="glyphicon glyphicon-align-left btn-lg"></span> <a href="<?=HOME?>/forum/show/<?=Utility::UrlTranslit($forum->name)."-".$forum->getId()?>"><strong><?=$forum->name?></strong></a>
                                <td style="width: 45%">
                                    <a href="">Название темы</a><br>
                                    Автор: <a href="">wapweb</a><br>
                                    <a href="">30 августа 2013 - 21:16</a>
                                </td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <!--</div>!-->
            </div>
        <?php endforeach; ?>
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

</div><!-- /.container -->
