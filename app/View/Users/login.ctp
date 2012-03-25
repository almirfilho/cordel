<script type="text/javascript">
$(document).ready( function(){
	$('form').submit( function(event){
		
		$('button.btn').attr( 'disabled', 'disabled' ).addClass( 'disabled' );
		$('div.spinner').show();
	});
});
</script>

<?= $this->Form->create( "User", array( "action" => "login", 'class' => 'well form-horizontal' ) ) ?>
	
	<div class="control-group">
	<?php
		print $this->Form->label( 'User.email', 'Usuário', array( 'class' => 'control-label' ) );
		print $this->Form->input( "User.email", array( "label" => false, 'div' => 'controls' ) );
	?>
	</div>
	<div class="control-group">
	<?php
		print $this->Form->label( 'User.password', 'Senha', array( 'class' => 'control-label' ) );
		print $this->Form->input( "User.password", array( "label" => false, 'div' => 'controls' ) );
	?>
	</div>
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<?= $this->Form->button( 'Entrar', array( 'type' => 'submit', 'class' => 'btn pull-left' ) ) ?>
			<div class="spinner"></div>
		</div>
	</div>
	
<?= $this->Form->end() ?>