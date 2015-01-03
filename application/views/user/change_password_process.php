<?php if($this->res['error']):?>
    <div class="alert alert-danger"><?=$this->res['error']?></div>
<?php else: ?>
    <div class="alert alert-success"><?=$this->res['msg']?></div>
    <script>
        $('#change_password').find('input:password').val('');
    </script>
<?php endif; ?>