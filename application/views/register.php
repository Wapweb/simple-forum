<script src="<?=HOME?>/assets/js/register.js"></script>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><div class="text-center"><strong>Регистрация на форуме</strong></div> </div>
        <div class="panel-body">
            <div id="output"></div>
            <form class="form-horizontal" role="form" id="reg" method="post" action="<?=HOME?>/registration/process">
                <div class="form-group" id="login">
                    <label for="inputLogin" class="col-lg-2 control-label">Логин</label>
                    <div class="col-lg-5">
                        <input type="text" name="login" class="form-control" id="inputLogin" placeholder="Логин (цифры и/или буквы латинского алфавита)" >
                        <p class="help-block">Буквы латинского алфавита и/или цифры. Длина от 3 до 50 символов</p>
                    </div>
                </div>
                <div class="form-group" id="email">
                    <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-5">
                        <input type="email" name="email" class="form-control" id="inputEmail1" placeholder="Email" >
                        <p class="help-block">Например: abit-poisk@mail.ru</p>
                    </div>
                </div>
                <div class="form-group" id="pass">
                    <label for="inputPassword1" class="col-lg-2 control-label">Пароль</label>
                    <div class="col-lg-5">
                        <input type="password" name="password" class="form-control" id="inputPassword1" placeholder="Пароль" >
                        <p class="help-block">Пароль должен содержать от 4 до 16 символов</p>
                    </div>
                </div>
                <div class="form-group" id="pass_repeat">
                    <label for="inputPassword1" class="col-lg-2 control-label">Пароль(повторите)</label>
                    <div class="col-lg-5">
                        <input type="password" name="password_repeat" class="form-control" id="inputPassword2" placeholder="Пароль(повторите)" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-4">
                        <button type="submit" id="button" class="btn btn-default">Регистрация</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div><!-- /.container -->
