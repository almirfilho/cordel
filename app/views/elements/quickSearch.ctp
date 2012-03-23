<?php if( isset( $filter_fields ) ): ?>

<div class="search medium">
<?php
	$options = array( 'class' => 'form' );

	if( isset( $controller ) )
		$options[ 'url' ] = array( 'controller' => $controller, 'action' => 'search', $action );
	else
		$options[ 'action' ] = "search/{$action}";
		
	print $this->Form->create( $model, $options );
	print $this->Form->input( $model.".word", array( 'label' => 'Palavra-chave:', 'div' => false, 'class' => 'text' ) );
	print $this->Form->input( $model.".field", array( 'label' => false, 'div' => false, 'class' => 'select', 'options' => $filter_fields, 'escape' => false ) );
	print $this->Form->end( array( 'label' => 'Filtrar', 'class' => 'submit', 'div' => false ) );
?>
</div>

<?php endif; ?>