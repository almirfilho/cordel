<script type="text/javascript">
$(document).ready( function(){
	$('#UserNewPassword').val('');
});
</script>

<?php
	print $this->Form->create( 'User', array( 'class' => 'form-horizontal' ) );
	print $this->Form->hidden( 'User.id' );
	print $this->BForm->input( 'User.name', array( 'label' => 'Nome', 'placeholder' => 'Nome do usuário' ) );
	print $this->BForm->input( 'User.email', array( 'label' => 'Email', 'autocomplete' => 'off', 'placeholder' => 'exemplo@dominio.com', 'type' => 'email' ) );
	print $this->BForm->input( 'User.profile_id', array( 'label' => 'Perfil', 'empty' => '-- Selecione --' ) );
?>

<fieldset>
	<legend>Mudança de senha <small>Preencha apenas se desejar modificar a senha atual</small></legend>
	<?php
		print $this->BForm->input( 'User.newPassword', array( 'label' => 'Nova senha', 'type' => 'password', 'autocomplete' => 'off' ) );
		print $this->BForm->input( 'User.passwordConfirm', array( 'label' => 'Confirme a senha', 'type' => 'password', 'autocomplete' => 'off' ) );
	?>
</fieldset>

<?= $this->element( "submit", array( 'cancel' => "/users/view/{$this->passedArgs[0]}" ) ) ?>