$(document).ready( function(){
	
	$('.alert').alert();
	$('.dropdown-toggle').dropdown();

	$('#menu li.dropdown').each( function(){
		if( $(this).find('li.active').length > 0 )
			$(this).addClass( 'active' );
	});
	// $(".collapse").collapse();
	
	$('button.cancel, input.cancel').click( function( event ){
		
		event.preventDefault();
		
		if( $(this).attr('alt') != "" )
			window.location = $(this).attr('alt');
	});
	
	$('form').submit( function(event){
		
		$(this).find('input:submit, button').attr( 'disabled', 'disabled' ).addClass( 'disabled' );
		$(this).find('div.spinner').show();
	});
});