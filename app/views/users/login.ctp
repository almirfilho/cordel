<?= $this->Form->create( "User", array( "action" => "login", 'class' => 'well form-horizontal' ) ) ?>
	
	<div class="control-group">
		<?= $this->Form->label( 'User.email:', 'Usuário', array( 'class' => 'control-label' ) ) ?>
		<?= $this->Form->input( "User.email", array( "label" => false, 'div' => 'controls' ) ) ?>
	</div>
	<div class="control-group">
		<?= $this->Form->label( 'User.password:', 'Senha', array( 'class' => 'control-label' ) ) ?>
		<?= $this->Form->input( "User.password", array( "label" => false, 'div' => 'controls' ) ) ?>
	</div>
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls"><?= $this->Form->button( 'Entrar', array( 'type' => 'submit', 'class' => 'btn' ) ) ?></div>
	</div>
	
<?= $this->Form->end() ?>