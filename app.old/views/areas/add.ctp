<?= $this->Form->create( "Area", array( "action" => "add", "class" => "form" ) ) ?>

<table class="visualizar auto">
	<tr>
		<td class="label"><?= $this->Form->label( "Area.controller", "Controller:" ) ?></td>
		<td class="input"><?= $this->Form->input( "Area.controller", array( 'label' => false, 'class' => 'text' ) ) ?></td>
	</tr>
	<tr>
		<td><?= $this->Form->label( "Area.controller_label", "Controller Label:" ) ?></td>
		<td><?= $this->Form->input( "Area.controller_label",  array( 'label' => false, 'class' => 'text' ) ) ?></td>
	</tr>
	<tr>
		<td><?= $this->Form->label( "Area.action", "Action:" ) ?></td>
		<td><?= $this->Form->input( "Area.action",  array( 'label' => false, 'class' => 'text' ) ) ?></td>
	</tr>
	<tr>
		<td><?= $this->Form->label( "Area.action_label", "Action Label:" ) ?></td>
		<td><?= $this->Form->input( "Area.action_label",  array( 'label' => false, 'class' => 'text' ) ) ?></td>
	</tr>
	<tr>
		<td colspan="2">
		<?php
		 	print $this->Form->submit( "SALVAR", array( 'class' => 'submit' ) );
			print $this->Form->submit( "CANCELAR", array( 'class' => 'submit cancel' ) )
		?>
		</td>
	</tr>
</table>

<?= $this->Form->end() ?>