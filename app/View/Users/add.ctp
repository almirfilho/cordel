<?= $this->Form->create( 'User', array( 'class' => 'form-horizontal' ) ); ?>

<div class="row">
	<div class="span6">
	<?php
		print $this->BForm->input( 'User.name', array( 'label' => 'Nome', 'placeholder' => 'Nome do usuário' ) );
		print $this->BForm->input( 'User.email', array( 'label' => 'Email', 'placeholder' => 'exemplo@dominio.com', 'type' => 'email' ) );
		print $this->BForm->input( 'User.profile_id', array( 'label' => 'Perfil', 'empty' => '-- Selecione --' ) );
	?>
	</div>

	<div class="span3">
		<div class="alert alert-block alert-info">
			<a class="close" data-dismiss="alert">×</a>
			Por padr&atilde;o, a senha para o novo usu&aacute;rio ser&aacute; "<strong>123456</strong>". Cada Usu&aacute;rio deve modificar sua pr&oacute;pria senha ao realizar seu primeiro acesso ao sistema.
		</div>
	</div>
</div>

<?= $this->element( "submit", array( 'cancel' => '/users' ) ) ?>