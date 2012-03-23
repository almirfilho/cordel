<?= $this->Form->create( "Profile", array( "action" => "add/{$redirect}", "class" => "form" ) ) ?>

<table class="visualizar auto">
	<tr>
		<td class="label"><?= $this->Form->label( "Profile.name", 'Nome: <span class="req">*</span>' ) ?></td>
		<td class="input"><?= $this->Form->input( "Profile.name", array( 'label' => false, 'escape' => false, 'class' => 'text' ) ) ?></td>
	</tr>
	<tr>
		<td><?= $this->Form->label( "Area", "&Aacute;reas de Acesso:" ) ?></td>
		<td class="areas"><?= $this->Form->input( "Area",  array( "label" => false, 'escape' => false, "multiple" => "checkbox" ) ) ?></td>
	</tr>
	<tr>
		<td colspan="2"><?= $this->element( "submit", array( 'cancel' => '/profiles' ) ) ?></td>
	</tr>
</table>

<?= $this->Form->end() ?>