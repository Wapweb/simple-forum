<?php
/** @var UserModel $user */
$user = $this->user;
?>
<style>

    .progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; height:25px; margin-top:5px;  }
    .bar { background-color: #428bca; width:0%; height:25px;}
    .percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
<script src="<?=HOME?>/assets/js/jquery.fileupload.js"></script>
<script src="<?=HOME?>/assets/js/fileupload.js"></script>
<script>
    $(document).ready (function() {

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');
        $(".progress").hide();

        $("input#file_avatar").on("change", function() {
            $('#upload').trigger('submit');
        });

        $('#upload').ajaxForm({
            beforeSend: function() {
                //array('jpg','jpeg','gif','png');
                var file_name = $("#file_avatar").val();
                var extension = file_name.replace(/^.*\./, '');
                if(!file_name) {
                   //$("#error_msg").text("Вы не выбрали файл");
                    //$("#error_msg").show();
                } else {
                    $(".progress").show();
                    $(".progress-bar").empty();
                    status.empty();
                    var percentVal = '0%';
                    bar.width(percentVal);
                    percent.html(percentVal);

                    $('#button').attr('disabled', 'disabled');
                    $("#button").append(" <img src='/assets/images/update.gif' alt='update'>");

                    //$("#cur_avatar").fadeOut("slow");
                }
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);

                var file_name = $("#file_avatar").val();
                var extension = file_name.replace(/^.*\./, '');
                if(extension == 'jpg' || extension == 'jpeg' || extension =='png' || extension == 'gif') {
                    if(percentComplete > 90) {
                        $('#resize').show();
                        //alert('test');
                    }
                }
                percent.html(percentVal);
            },
            success: function() {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                status.html(xhr.responseText);
                $(".file-input-name").remove();
                $('#file_avatar').val('');
                $('#button').attr('disabled', false);
                $("#button").text("Загрузить");
                $(".progress").hide();
            }
        });
    });
</script>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Смена аватара</strong></div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        <a href="<?=HOME?>/user/change_avatar" class="thumbnail" style="margin-bottom: 4px;" title="Сменить аватар">
                            <div id="cur_avatar"><img width="192" height="192" src="<?=HOME?>/assets/images/avatars/<?=(!empty($user->avatar) ? "small_".$user->avatar : 'avatar.png')?>" class="img-thumbnail"></div>
                        </a>
                        <a href="<?=HOME?>/user/profile/<?=$user->login?>" class="list-group-item">Мой Профиль</a>
                        <a href="<?=HOME?>/user/settings/" class="list-group-item">Настройки</a>
                        <a href="<?=HOME?>/mail/" class="list-group-item">Личные сообщения</a>
                        <a href="<?=HOME?>/user/change_password" class="list-group-item">Сменить пароль</a>
                    </div>
                </div>

                <div class="col-md-10">
                    <form role="form" action="<?=HOME?>/user/upload_avatar" method="post" enctype="multipart/form-data" id="upload">
                        <div class="form-group">
                            <label for="exampleInputFile">Аватар:</label>
                            <input type="file" id="file_avatar" name="avatar" title="Загрузить новый аватар">
                        </div>
                        <button type="submit" style="display: none;" class="btn btn-default" id="button">Загрузить</button>
                    </form>


                    <div class="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    <div id="status">

                    </div>
                    <div id="resize" style="display: none;">Выполняется ресайз аватара...</div>

                </div>
            </div>
        </div>
    </div>
</div>