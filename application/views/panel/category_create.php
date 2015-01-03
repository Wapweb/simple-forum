<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?=HOME?>">Главная</a></li>
        <li class="active">Новый раздел</li>
    </ol>
    <div class="panel panel-primary">
        <div class="panel-heading"><div class="text-center"><strong>Создание раздела</strong></div></div>
        <div class="panel-body">
            <?php if(isset($_GET["failed"])): ?>
                <div class="alert alert-danger">Ошибка создание раздела! Заполните все поля!</div>
            <?php endif; ?>
            <form role="form" id="topic_create" method="post" action="<?=HOME?>/category/create">
                <div class="form-group" id="tn">
                    <label for="id_name">Название раздела</label>
                    <div class="row">
                        <div class="col-lg-6" id="tpn">
                            <input type="text" name="category_name" maxlength="200" class="form-control" id="id_name" placeholder="Название форума">
                        </div>
                    </div>
                </div>
                <button type="submit" id="button" class="btn btn-default">Создать</button>
            </form>
        </div>
    </div>
</div>