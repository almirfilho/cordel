<div class="form-horizontal view">
	<div class="row-fluid title">
		<h2 class="span10"><i class="icon-user"></i> <?= $user[ 'User' ][ 'name' ] ?></h2>
		<div class="span2">
		<?php
			print $this->Html->link( '<i class="icon-trash icon-white"></i> Excluir', "/users/delete/{$user['User']['id']}", array( 'class' => 'btn btn-mini btn-danger delete', 'escape' => false ) );
			print $this->Html->link( '<i class="icon-edit"></i> Editar', "/users/edit/{$user['User']['id']}", array( 'class' => 'btn btn-mini', 'escape' => false ) );
		?>
		</div>
	</div>

	<table class="table table-striped">
	<tbody>
		<tr>
			<td class="dlabel">Email</td>
			<td class="data"><?= $user[ 'User' ][ 'email' ] ?></td>
		</tr>
		<tr>
			<td class="dlabel">Perfil</td>
			<td class="data"><?= $user[ 'Profile' ][ 'name' ] ?></td>
		</tr>
		<tr>
			<td class="dlabel">Último Login</td>
			<td class="data"><?php $user[ 'User' ][ 'last_login' ] ? print $this->FrontEnd->niceDate( $user[ 'User' ][ 'last_login' ], true ) : print 'Nunca efetuou login'; ?></td>
		</tr>
	</tbody>
	</table>
</div>

<?= $this->element( 'deleteModal', array( 'model' => 'Usuário' ) ) ?>