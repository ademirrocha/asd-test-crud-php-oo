<?php 
    include  ($GLOBALS['PATH'] . '/views/layout/head.php');
    //include  ($GLOBALS['PATH'] . '/views/layout/menu.php');
?>
    <div class="container">
        <div class="col-md-12">
            <fieldset>
                <legend>USUÁRIOS CADASTRADOS</legend>
            </fieldset>
            <?php if(isset($_GET['success'])): ?>
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
                            class="btn btn-warning btn-circle">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href='acao=deletar_usuario&id=<?=  $value->getId(); ?>'
                            class="btn btn-danger btn-circle"
                            onclick='return confirm(\"Deseja realmente deletar?\")'>
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
				</tr>
                <?php endforeach; ?>
				<caption>Usuários</caption>
			</tbody>
		</table>
	</div>
    </div>
<?php include  ($GLOBALS['PATH'] . '/views/layout/footer.php'); ?>