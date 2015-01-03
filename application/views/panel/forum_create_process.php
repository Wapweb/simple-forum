<?php if($this->data['error']):?>
    <div class="alert alert-danger"><?=$this->data['error']?></div>
<?php else: ?>
    <div class="alert alert-success"><?=$this->data['msg']?></div>
    <?php
        $url = HOME."/forum/show/".$this->data['forum_url'];
    ?>
    <script>setTimeout('location.replace("<?=$url?>")', 1000);</script>
<?php endif; ?>