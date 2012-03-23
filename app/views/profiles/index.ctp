<?php $this->Html->script( array( 'check' ), false ) ?>

<?php
	print $this->Form->create( null, array( 'action' => 'multipleActions/delete', 'onsubmit' => 'return confirm("Tem certeza de que deseja excluir os Perfís selecionados? Todos os usuários atrelados a eles também serão excluídos!");', 'class' => 'actions' ) );
	print $this->Form->submit( "Excluir selecionados", array( 'class' => 'submit', 'div' => false ) );
?>

<table class="list medium">
	
<tr class="head">
	<th></th>
	<th class="check"><?= $this->Form->checkbox( "Check.all", array( 'class' => 'all' ) ) ?></th>
	<th><?= $this->Paginator->sort( "Nome", "name" ) ?></th>
	<th class="actions">A&ccedil;&otilde;es</th>
</tr>

<?php if( empty( $profiles ) ){ ?>
	
<tr><td></td><td colspan="3" class="bold">N&atilde;o h&aacute; Perf&iacute;s cadastrados ainda.</td></tr>

<?php } else { foreach( $profiles as $i => $profile ): $i % 2 ? $class = null : $class = ' class="altrow"'; ?>

<tr<?= $class ?>>
	<td class="pic Profiles"></td>
	<td class="check"><?= $this->Form->checkbox( "Profile.checks.{$profile['Profile']['id']}", array( 'class' => 'checks' ) ) ?></td>
	<td><?= $profile[ 'Profile' ][ 'name' ] ?></td>
	<td>
	<?php
		print $this->Html->link( "Visualizar", "/profiles/view/{$profile['Profile']['id']}", array( 'class' => 'icon view' ) );
		print $this->Html->link( "Editar", "/profiles/edit/index/{$profile['Profile']['id']}", array( 'class' => 'icon edit' ) );
		print $this->Html->link( "Excluir", "/profiles/delete/{$profile['Profile']['id']}", array( 'class' => 'icon delete' ), "Tem certeza de que deseja excluir este Perfil? Todos os usuários atrelados a ele também serão excluídos!" );
	?>
	</td>
</tr>
	
<?php endforeach; print $this->element( "paginationButtons", array( 'mode' => 'table', 'size' => 4 ) ); } ?>

</table>

<?= $this->Form->end() ?>