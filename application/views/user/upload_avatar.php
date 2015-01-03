<?php if($this->res['error']):?>
    <div id="error_msg" class="alert alert-danger" style="margin-top: 5px;"><?=$this->res['error']?></div>
<?php else: ?>
    <div id="error_msg" class="alert alert-success" style="margin-top: 5px;"><?=$this->res['msg']?></div>
    <script>
        $(document).ready (function() {
            $("#resize").hide();
            $("#cur_avatar").fadeIn("slow");
            $("#cur_avatar").html('<img width="192" height="192" src="<?=HOME?>/assets/images/avatars/small_<?=$this->res['user_avatar']?>" class="img-thumbnail">');
        });
    </script>
<?php endif; ?>