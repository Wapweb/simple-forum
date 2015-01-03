<script src="<?=HOME?>/assets/js/login.js"></script>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Вход</strong></div></div>
        <div class="panel-body">
    <form class="form-horizontal" role="form" id="auth" method="post" action="<?=HOME?>/sign/process">
        <div class="form-group" id="email">
            <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
            <div class="col-lg-4">
                <input type="email" name="email" class="form-control" id="inputEmail1" placeholder="Email">
            </div>
        </div>
        <div class="form-group" id="pass">
            <label for="inputPassword1" class="col-lg-2 control-label">Пароль</label>
            <div class="col-lg-4">
                <input type="password" name="password" class="form-control" id="inputPassword1" placeholder="Пароль">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Запомнить меня
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-4">
                <button type="submit" class="btn btn-primary" id="button">Войти</button>
            </div>
        </div>
    </form>
            <div id="output"></div>
        </div>
    </div>

</div><!-- /.container -->
