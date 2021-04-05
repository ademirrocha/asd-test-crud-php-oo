<?php 
    include  ($GLOBALS['PATH'] . '/views/layout/head.php');
    //include  ($GLOBALS['PATH'] . '/views/layout/menu.php');
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form action="/users/update" method="POST" class="">
                <fieldset>
                    <legend>Atualizar dados do usuário.</legend>
                    <div class=" col-md-6 col-md-offset-3">
                        <?php 
                        if(isset($_GET['errors'])):
                            $errors = json_decode($_GET['errors']);
                            foreach($errors as $key => $error):?>
                                <div class="alert alert-danger">
                                <?php echo strtoupper($key) . ': '. ($error);?>
                                </div>
                        <?php 
                            endforeach;
                        endif;
                        if(isset($_GET['success'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_GET['success']; ?>
                            </div>
                        <?php endif;?>
                    
                        <div class=" panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Usuário</h3>
                            </div>
                            <div class="panel-body">
                                <label class="control-label" >Nome: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" value="<?= $user->getName(); ?>" required/>
                                </div>

                                <label class="control-label" >email: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" name="email" class="form-control" value="<?= $user->getEmail(); ?>" required/>
                                </div>

                                <label class="control-label" >Senha: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="Digite uma senha" />
                                </div>

                                <label class="control-label" >Confirmar Senha: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme a senha" />
                                </div>

                                <label class="control-label" >Senha Atual: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" name="old-password" class="form-control" placeholder="Digite sua senha senha atual" required/>
                                </div>

                                <div class="form-group input-group">
                                    <input type="hidden" name="id" value="<?= $user->getId(); ?>">
                                    <input type="submit" name="acao" value="Salvar Alterações" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php include  ($GLOBALS['PATH'] . '/views/layout/footer.php'); ?>