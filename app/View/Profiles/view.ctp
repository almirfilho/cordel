<div class="form-horizontal view">
	<div class="row-fluid title">
		<h2 class="span10"><i class="icon-tag"></i> <?= $profile[ 'Profile' ][ 'name' ] ?></h2>
		<div class="span2">
		<?php
			print $this->Html->link( '<i class="icon-trash icon-white"></i> Excluir', "/profiles/delete/{$profile['Profile']['id']}", array( 'class' => 'btn btn-mini btn-danger delete', 'escape' => false ) );
			print $this->Html->link( '<i class="icon-edit"></i> Editar', "/profiles/edit/{$profile['Profile']['id']}", array( 'class' => 'btn btn-mini', 'escape' => false ) );
		?>
		</div>
	</div>

	<table class="table table-striped">
	<tbody>
		<tr>
			<td class="dlabel">Áreas de acesso</td>
			<td class="data">
			<?php if( !empty( $profile[ 'Area' ] ) ){ foreach( $profile[ 'Area' ] as $area ): ?>
				
				<p><?= $area[ 'controller_label' ] ?> &raquo; <span class="bold"><?= $area[ 'action_label' ] ?></span></p>
			
			<?php endforeach; } else { print 'Nenhuma.'; } ?>
			</td>
		</tr>
		
	</tbody>
	</table>
</div>

<?= $this->element( 'deleteModal', array( 'model' => 'Perfil de Usuário' ) ) ?>