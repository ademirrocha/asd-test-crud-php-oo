<?php include  ($GLOBALS['PATH'] . '/views/layout/head.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Login</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST" action="/login">
                        <fieldset>
                            <div class="form-group">
                                <?php 
                                if(isset($_GET['errors'])):
                                    $errors = json_decode($_GET['errors']);
                                    if(isset($errors->email)):?>
                                        <span class="text-danger">
                                            <?=$errors->email?>
                                        </span>
                                    <?php 
                                        endif;
                                    endif;
                                ?>
                                <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                            </div>
                            <div class="form-group">
                                <?php 
                                    if(isset($_GET['errors'])):
                                        $errors = json_decode($_GET['errors']);
                                        if(isset($errors->password)):?>
                                            <span class="text-danger">
                                                <?=$errors->password?>
                                            </span>
                                        <?php 
                                        endif;
                                    endif;
                                ?>
                                <input class="form-control" placeholder="Senha" name="senha" type="password">
                            </div>
                            <input type="submit" class="btn btn-lg btn-success btn-block" name="logar" value="logar">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include  ($GLOBALS['PATH'] . '/views/layout/footer.php'); ?>
