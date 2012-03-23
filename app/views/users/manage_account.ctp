<?php
	print $this->Form->create( "User", array( "action" => "manageAccount", "class" => "form" ) );
	print $this->Form->hidden( "User.id" );
	print $this->Form->hidden( "User.currentPassword" );
?>

<table class="visualizar auto">
	<tr>
		<td class="label"><?= $this->Form->label( "User.name", 'Nome: <span class="req">*</span>' ) ?></td>
		<td class="input"><?= $this->Form->input( "User.name", array( 'label' => false, 'class' => 'text', 'escape' => false ) ) ?></td>
	</tr>
	<tr>
		<td class="label"><?= $this->Form->label( "User.email", "Email:" ) ?></td>
		<td class="input"><?= $this->Form->input( "User.email", array( 'label' => false, 'class' => 'text', 'escape' => false ) ) ?></td>
	</tr>
	<tr>
		<td class="label"><?= $this->Form->label( "User.currentPasswordConfirmation", 'Senha Atual: <span class="req">*</span>' ) ?></td>
		<td class="input"><?= $this->Form->input( "User.currentPasswordConfirmation", array( 'label' => false, 'class' => 'text', 'type' => 'password' ) ) ?></td>
	</tr>
	<tr>
		<td class="label"><?= $this->Form->label( "User.newPassword", "Nova Senha:" ) ?></td>
		<td class="input"><?= $this->Form->input( "User.newPassword", array( 'label' => false, 'class' => 'text', 'type' => 'password' ) ) ?></td>
		<td><label><span class="desc">(Apenas preencha se desejar mudar sua senha)</span></label></td>
	</tr>
	<tr>
		<td class="label"><?= $this->Form->label( "User.newPasswordConfirmation", "Confirme a Nova Senha:" ) ?></td>
		<td class="input"><?= $this->Form->input( "User.newPasswordConfirmation", array( 'label' => false, 'class' => 'text', 'type' => 'password' ) ) ?></td>
		<td><label><span class="desc">(Apenas preencha se desejar mudar sua senha)</span></label></td>
	</tr>
	<tr>
		<td colspan="2"><?= $this->element( "submit", array( 'cancel' => '/' ) ) ?></td>
	</tr>
</table>

<?= $this->Form->end() ?>