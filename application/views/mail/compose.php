<script language="javascript" src="<?=HOME?>/assets/js/search_recipient.js"></script>
<?php
/** @var UserModel $user */
$user = $this->user;
$module = "mail";
?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><a href="<?=HOME?>/mail"><strong>Почта</strong></a> &mdash; новое письмо</div></div>
        <div class="panel-body">
            <div class="row">
                <?php require_once ROOT."/application/views/widgets/left_sidebar.php";?>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class="active"><a href="<?=HOME?>/mail/compose"><span class="glyphicon glyphicon-envelope"></span> Написать</a></li>
                                <li class=""><a href="<?=HOME?>/mail"><span class="glyphicon glyphicon-arrow-down"></span> Входящие</a></li>
                                <li><a href="<?=HOME?>/mail/sent"><span class="glyphicon glyphicon-arrow-up"></span> Отправленные</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <?php
                                if(isset($_GET["error"])) {
                                    echo "<div class='alert alert-danger'>Заполните все поля!</div>";
                                }
                                if(isset($_GET["not_found"])) {
                                    echo "<div class='alert alert-danger'>Пользователь с таким логином не найден!</div>";
                                }
                            ?>
                            <form role="form" id="topic_create" method="post" action="<?=HOME?>/mail/send/">
                                <div class="form-group">
                                    <label for="recipient">Кому</label>
                                    <input type="text" name="recipient" autocomplete="off" value="<?=isset($this->email) ? $this->userRecipient->login : ""?>" maxlength="200" class="form-control" id="recipient" placeholder="Логин">

                                        <div id="output">
                                        </div>

                                </div>
                                <div class="form-group">
                                    <label for="title">Тема</label>
                                    <input type="text"  value="<?=isset($this->email) ? "RE: ".$this->email->title : ""?>" name="title" maxlength="200" class="form-control" id="title" placeholder="">
                                </div>
                                <div class="form-group">
                                    <textarea id="textarea_msg" name="message_text" rows="10" maxlength="10000" class="form-control" placeholder="Сообщение"></textarea>
                                </div>
                                <button type="submit" id="button" class="btn btn-primary">Отправить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>