<?php 
    include  ($GLOBALS['PATH'] . '/views/layout/head.php');
    include  ($GLOBALS['PATH'] . '/views/layout/menu.php');
?>
    <div class="container">
        <div class="col-md-12">
            <fieldset>
                <legend>USUÁRIOS CADASTRADOS</legend>
            </fieldset>
            <?php 
                if(isset($_GET['errors'])):
                    $errors = json_decode($_GET['errors']);
                    foreach($errors as $key => $error):?>
                        <div class="alert alert-danger">
                        <?php echo ($error);?>
                        </div>
                <?php 
                    endforeach;
                endif;
                if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_GET['success']; ?>
                </div>
            <?php endif;?>
        </div>
        <div class="col-sm-12">
		<table>
			
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">NOME</th>
					<th scope="col">EMAIL</th>
					<th scope="col">DATA CADASTRO</th>
                    <th scope="col">AÇÕES:</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($users as $key => $value): ?>
				<tr>
					<td scope="row" data-label="ID"><?= $value->getId(); ?></td>
					<td data-label="NOME"><?= $value->getName(); ?></td>
					<td data-label="EMAIL"><?= $value->getEmail(); ?></td>
					<td data-label="DATA CADASTRO"><?= $value->getCreatedAt(); ?></td>
                    <td>
                        <a href='/users/find?id=<?= $value->getId(); ?>'
                            class="btn btn-success btn-elipse">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href='/users/delete'
                            class="btn btn-danger btn-elipse"
                            onclick="event.preventDefault(); document.getElementById('form-delete-user-<?=$value->getId();?>').submit();">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <form id="form-delete-user-<?=$value->getId();?>" action="/users/delete" method="POST" style="display: none;">
							<input type="hidden" name="id" value="<?=$value->getId();?>">
						</form>
                    </td>
				</tr>
                <?php endforeach; ?>
				<caption>Usuários</caption>
			</tbody>
		</table>
	</div>
    </div>
<?php include  ($GLOBALS['PATH'] . '/views/layout/footer.php'); ?>