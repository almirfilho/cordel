<div class="form-actions">
	<p class="required"><span class="req">*</span> campos de preenchimento obrigat&oacute;rio</p>
	<?php
		$options = array(
			'class' => 'btn cancel pull-left',
			'title' => 'clique para cancelar',
			'div' => false
		);
		
		if( !empty( $cancel ) )
			$options[ 'alt' ] = $this->Html->url( $cancel );
		
		if( !empty( $cancelRedirect ) )
			$options[ 'alt' ] = $this->Html->url( $cancel[ $cancelRedirect ] );

	 	print $this->Form->button( '<i class="icon-check icon-white"></i> Salvar', array( 'class' => 'btn btn-primary pull-left submit', 'div' => false, 'escape' => false ) );
	 	print $this->Form->button( 'Cancelar', $options );
	?>
	<div class="spinner"></div>
</div>

<?= $this->Form->end() ?>