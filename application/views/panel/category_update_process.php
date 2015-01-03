<?php if($this->data['error']):?>
    <div class="alert alert-danger"><?=$this->data['error']?></div>
<?php else: ?>
    <div class="alert alert-success"><?=$this->data['msg']?></div>
    <?php
        $url = HOME."/category/show/".$this->data['catalog_url'];
    ?>
    <script>setTimeout('location.replace("<?=$url?>")', 1000);</script>
<?php endif; ?>