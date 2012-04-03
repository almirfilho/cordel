<?php $this->Html->script( 'bootstrap/bootstrap-modal', false ) ?>

<!-- modal -->
<div class="modal hide fade" id="delete">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Exclusão de <?= $model ?></h3>
	</div>
	<div class="modal-body">
		<p>Tem certeza de que deseja excluir este <?= $model ?>? <?php if( !empty( $msg ) ) print $msg; ?></p>
	</div>
	<div class="modal-footer">
		<a id="deleteCancel" href="#" class="btn">Cancelar</a>
		<a id="deleteConfirm" href="#" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir</a>
	</div>
</div>

<script type="text/javascript">

var deleteUrl;

$(document).ready( function(){

	$('.btn.delete').click( function(e){
		e.preventDefault();
		deleteUrl = $(this).attr('href');
		$('#delete').modal('show').on( 'shown', function(){
			$('#deleteConfirm').focus();
		});
	});

	$('#deleteCancel').click( function(e){
		e.preventDefault();
		$('#delete').modal('hide');
	});

	$('#deleteConfirm').click( function(e){
		e.preventDefault();
		window.location = deleteUrl;
	});
});
</script>