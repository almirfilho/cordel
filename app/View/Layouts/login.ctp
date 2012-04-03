<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<title><?= $title_for_layout ?> :: Login</title>

<?php
	print $this->Html->css( array( 'bootstrap.min', 'bootstrap-responsive.min', 'cordel.login' ) );
	print $this->Html->script( array( 'jquery.min', "bootstrap/bootstrap.min", 'bootstrap/bootstrap-alert' ) ) ;
?>

<script type="text/javascript">
$(document).ready( function(){
	$('.alert').alert();
	$('#UserUsername').focus();
});
</script>

</head>

<body>

<div id="login">
	<h1><?= $title_for_layout ?></h1>
	<h6>LOGIN</h6>
	<?php
		print $this->FrontEnd->message();
		print $content_for_layout;
	?>
</div>

</body>
</html>