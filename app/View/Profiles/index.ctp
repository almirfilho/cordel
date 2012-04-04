<?php if( empty( $profiles ) ){ ?>

	<p class="alert alert-info nothing"><i class="icon-info-sign"></i> Não há nenhum Perfil de Usuário ainda. <?= $this->Html->link( 'Criar Novo', '/profiles/add', array( 'class' => 'btn btn-mini' ) ) ?></p>

<?php } else { ?>

	<table class="table table-striped">
	<thead>
	<tr>
		<th class="pic"></th>
		<th><?= $this->Paginator->sort( "name", "Nome" ) ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach( $profiles as $profile ): ?>

	<tr>
		<td><i class="icon-tag"></i></td>
		<td><?= $this->Html->link( $profile[ 'Profile' ][ 'name' ], "/profiles/view/{$profile['Profile']['id']}", array( 'escape' => false ) ) ?></td>
	</tr>
		
	<?php endforeach; ?>
	</tbody>
	</table>

<?php print $this->element( "pagination" ); } ?>