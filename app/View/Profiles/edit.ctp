<?php
	print $this->Form->create( "Profile", array( "class" => "form-horizontal" ) );
	print $this->Form->hidden( 'Profile.id' );
	print $this->BForm->input( 'Profile.name', array( 'label' => 'Nome', 'placeholder' => 'Nome do perfil' ) );
?>

<div class="control-group">
<?php
	print $this->Form->label( 'Area', 'Áreas de Acesso', array( 'class' => 'control-label' ) );
	print $this->Form->input( 'Area', array( "label" => false, 'div' => 'controls areas', 'escape' => false, 'multiple' => 'checkbox' ) );
?>
</div>

<?= $this->element( "submit", array( 'cancel' => "/profiles/view/{$this->passedArgs[0]}" ) ) ?>