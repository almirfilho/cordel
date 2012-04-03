<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<title><?= $title_for_layout ?></title>

<?php
	print $this->Html->css( array( 'bootstrap.min', 'bootstrap-responsive.min', 'cordel', 'custom' ) );
	print $this->Html->script( array( 'jquery.min', "bootstrap/bootstrap.min", 'bootstrap/bootstrap-alert', 'bootstrap/bootstrap-dropdown', 'cordel' ) ) ;
	print $scripts_for_layout;
?>

</head>
<body class="container">

<div id="header" class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<?= $this->Html->link( $title_for_layout, '/', array( 'escape' => false, 'class' => 'brand' ) ) ?>
			<ul id="menu" class="nav">
				<?= $this->FrontEnd->getMenu() ?>
			</ul>
			<ul id="menu-admin" class="nav pull-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $this->Session->read( "Auth.User.name" ) ?> <b class="caret"></b></a>
				    <ul class="dropdown-menu">
				    	<li><?= $this->Html->link( '<i class="icon-off"></i> Sair', '/users/logout', array( 'escape' => false ) ) ?></li>
						<li><?= $this->Html->link( '<i class="icon-user"></i> Meus dados', '/users/manageAccount', array( 'escape' => false ) ) ?></li>
						<li class="divider"></li>
						<li class="nav-header">Último Login</li>
						<?php
							$date = $this->Session->read( "Auth.User.last_login" );
							if( $date ){
						?>
					    	<li class="logtime"><i class="icon-calendar"></i> <?= $this->Time->format( "d/m/Y", $date ) ?></li>
					    	<li class="logtime"><i class="icon-time"></i> <?= $this->Time->format( "H:i:s", $date ) ?></li>

					    <?php } else { ?>

					    	<li class="logtime">Nunca</li>

					    <?php } ?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>

<div id="middle">

	<?php
		print $this->FrontEnd->getHeader( $this->name, $this->action, $subtitle );
		print $this->FrontEnd->message();
    	print $this->FrontEnd->getSubMenu( $submenu, $this->name, $this->action );
    ?>

	<div id="content"><?= $content_for_layout ?></div>
	<div class="clear"></div>
</div>

<div id="footer" class="navbar navbar-fixed-bottom">
	<div class="navbar-inner">
		<div class="container">
			<p><?= $title_for_layout." - ".date( "d/m/Y" ) ?></p>
		</div>
	</div>
</div>

<?= $this->element('sql_dump') ?>
</body>
</html>