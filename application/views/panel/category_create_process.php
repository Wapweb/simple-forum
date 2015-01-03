<?php if($this->data['error']):?>
    <div class="alert alert-danger"><?=$this->data['error']?></div>
<?php else: ?>
    <div class="alert alert-success"><?=$this->data['msg']?></div>
    <script>setTimeout('location.replace("<?=HOME?>")', 1000);</script>
<?php endif; ?>