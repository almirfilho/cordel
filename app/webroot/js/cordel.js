$(document).ready( function(){
	
	$('.alert').alert();
	$('.dropdown-toggle').dropdown();

	$('#menu li.dropdown').each( function(){
		
		if( $(this).find('li.active').length > 0 )
			$(this).addClass( 'active' );
	});
	
	$('button.cancel, input.cancel').click( function( event ){
		
		event.preventDefault();
		
		if( $(this).attr('alt') != "" )
			window.location = $(this).attr('alt');
	});
	
	$('form').submit( function(event){
		
		$(this).find('input:submit, button').attr( 'disabled', 'disabled' ).addClass( 'disabled' );
		$(this).find('div.spinner').show();
	});

	$('button.btn.checkbox').click( function(){

		var hidden = $( $(this).attr('data-setinput') );

		if( hidden.val() == '' || hidden.val() == null || hidden.val() == undefined || hidden.val() == false )
			hidden.val('1');
		else
			hidden.val('0');
	});

	$('button.btn.radio').click( function(){

		var hidden = $( $(this).attr('data-setinput') );

		if( !hidden.attr('checked') )
			hidden.attr('checked', true);
	});
});

// easter egg
var kkeys = [];
var konami = "38,38,40,40,37,39,37,39,66,65";

$(document).keydown(function(e){
	
	kkeys.push( e.keyCode );

	if ( kkeys.toString().indexOf( konami ) >= 0 ){
	
		$(document).unbind( 'keydown', arguments.callee );
		alert('Uhhhhh BENINOOOOO NONONONONO!');
	}
});