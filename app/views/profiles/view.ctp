<table class="visualizar medium">
	
	<tr>
		<td class="label">Nome:</td>
		<td><?= $profile[ 'Profile' ][ 'name' ] ?></td>
	</tr>
	<tr class="altrow">
		<td class="label">&Aacute;reas de Acesso:</td>
		<td>
		<?php if( !empty( $profile[ 'Area' ] ) ){ foreach( $profile[ 'Area' ] as $area ): ?>
			
			<p><?= $area[ 'controller_label' ] ?> &raquo; <span class="bold"><?= $area[ 'action_label' ] ?></span></p>
		
		<?php endforeach; } else { print '--'; } ?>
		</td>
	</tr>
	
</table>

<div class="buttons">
<?php
	print $this->Html->link( "Editar", "/profiles/edit/view/{$profile['Profile']['id']}", array( 'class' => 'button icon edit round' ) );
	print $this->Html->link( "Excluir", "/profiles/delete/{$profile['Profile']['id']}", array( 'class' => 'button icon delete2 round' ), "Tem certeza de que deseja excluir este Perfíl de Usuário? Todos os usuários pertencetes a ele também serão excluídos!" );
?>
</div>