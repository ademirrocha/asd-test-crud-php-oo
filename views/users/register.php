<?php 
    include  ($GLOBALS['PATH'] . '/views/layout/head.php');
    include  ($GLOBALS['PATH'] . '/views/layout/menu.php');
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form action="/users/create" method="POST" class="">
                <fieldset>
                    <legend>Atualizar dados do usuário.</legend>
                    <div class=" col-md-6 col-md-offset-3">
                        <?php 
                        if(isset($_GET['errors'])):
                            $errors = json_decode($_GET['errors']);
                            foreach($errors as $key => $error):?>
                                <div class="alert alert-danger">
                                    <?php 
                                    if(is_array($error)):
                                        echo strtoupper($key) . ': ';
                                        foreach($error as $err):
                                             echo $err . '<br>';
                                        endforeach;
                                    else:
                                        echo strtoupper($key) . ': '. ($error);
                                    endif;
                                    ?>
                                        
                                
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
                            <div class="panel-heading bg-primary">
                                <h3 class="panel-title">Usuário</h3>
                            </div>
                            <div class="panel-body">
                                <label class="control-label" >Nome: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" value="<?=$_GET['name'] ?? 'test'?>" placeholder="Digite o nome" required/>
                                </div>

                                <label class="control-label" >email: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" value="<?=$_GET['email'] ?? 'test'?>" placeholder="Digite o email" required/>
                                </div>

                                <label class="control-label" >Senha: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" name="password" value="test" class="form-control" placeholder="Digite uma senha" />
                                </div>

                                <label class="control-label" >Confirmar Senha: </label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" value="test" name="password_confirmation" class="form-control" placeholder="Confirme a senha" />
                                </div>

                                <div class="form-group input-group  col-sm-12">
                                    <input type="submit" name="acao" value="Salvar" class="btn btn-primary  col-md-6 col-md-offset-3">
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