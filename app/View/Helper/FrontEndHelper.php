<?php

App::uses('AppHelper', 'View/Helper');

class FrontEndHelper extends AppHelper {

	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $helpers = array( 'Html', 'Session', 'Time' );

	private $months = array( 1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro' );

	private $iconClasses = array(
		'index' => 'icon-th-list',
		'add' => 'icon-plus',
	);

	/*----------------------------------------
	 * Constructor
	 ----------------------------------------*/

	function __construct(View $View, $settings = array()){
		parent::__construct($View,$settings);
	}

	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/

	public function message(){
	
		$flash = $this->Session->check( "Message.flash" ) ? $this->Session->read( "Message.flash" ) : $this->Session->read( "Message.auth" );

		if( $flash ){

			$class = empty( $flash[ 'params' ][ 'class' ] ) ? null : ' alert-'.$flash[ 'params' ][ 'class' ];
			$button = empty( $flash[ 'params' ][ 'button' ] ) ? null : $this->Html->link( $flash[ 'params' ][ 'button' ][ 'label' ], $flash[ 'params' ][ 'button' ][ 'url' ], array( 'class' => 'btn btn-mini' ) );
			$message = $this->Session->check( "Message.flash" ) ? $this->Session->flash() : $this->Session->flash('auth');

			return '<div class="alert'.$class.'"><a class="close" data-dismiss="alert">×</a>'.$message.$button.'</div>';
		}

		return null;
	}

	public function niceDate( &$date, $verbose = false ){

		if( $verbose )
			return '<i class="icon-calendar"></i> '. $this->Time->format( "d", $date ) .' de '. $this->months[$this->Time->format( "n", $date )] .' de '. $this->Time->format( "Y", $date ) .' <i class="icon-time"></i> '. $this->Time->format( "H:i:s", $date );

		return '<i class="icon-calendar"></i> '. $this->Time->format( "d/m/Y", $date ) .' <i class="icon-time"></i> '. $this->Time->format( "H:i:s", $date );
	}

	public function getHeader( &$controller, $action, $subtitle = null ){
		
		if( !$this->Session->check( "Auth.User.Profile" ) )
			return $this->output( "" );
		
		$permissions = &$this->Session->read( "Auth.User.Profile" );

		$tagOpen = '<div class="page-header"><h1>';
		$tagClose = '</h1></div>';

		if( $subtitle )
			return $tagOpen . $this->output( $subtitle ) . $tagClose;

		elseif( !empty( $permissions[ $controller ] ) )
			return $tagOpen . $this->output( $permissions[ $controller ][ 'controller_label' ] ) . $tagClose;

		else
			return null;
	}

	public function getMenu(){
		
		$string = '';
		$areas = &$this->Session->read( "Auth.User.Menu" );
		$permissions = &$this->Session->read( "Auth.User.Profile" );
		
		foreach ($areas as $area) {

			// se tiver permissao para controller/action
			if( !empty( $permissions[ $area['controller'] ][ 'action' ][ $area['action'] ] ) ){

				// nao eh submenu
				if( empty( $area[ 'AreaChild' ] ) )
					$string .= '<li class="'.$this->optionSelected( $area[ 'controller' ] ).'">'.$this->Html->link( $area[ 'controller_label' ], "/{$area['controller']}/{$area['action']}", array( 'escape' => false ) )."</li>\n";

				else { // submenu

					$string .= '<li class="dropdown">'.
						'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $area[ 'controller_label' ] .' <b class="caret"></b></a>'.
					    '<ul class="dropdown-menu">'.
					    '<li class="'.$this->optionSelected( $area[ 'controller' ] ).'">'.$this->Html->link( $area[ 'controller_label' ], "/{$area['controller']}/{$area['action']}", array( 'escape' => false ) )."</li>\n";
					
					foreach( $area[ 'AreaChild' ] as $areaChild )
						if( !empty( $permissions[ $areaChild['controller'] ][ 'action' ][ $areaChild['action'] ] ) )
							$string .= '<li class="divider"></li><li class="'.$this->optionSelected( $areaChild[ 'controller' ] ).'">'.$this->Html->link( $areaChild[ 'controller_label' ], "/{$areaChild['controller']}/{$areaChild['action']}", array( 'escape' => false ) )."</li>\n";

					$string .= '</ul></li>';
				}
			}
		}
				
		return $this->output( $string );
	}
	
	public function getSubMenu( &$submenu, &$controllerName, $actionName ){
		
		if( !empty( $submenu ) ){
		
			$string			= "";
			$permissions	= &$this->Session->read( "Auth.User.Profile" );
			
			if( !empty( $permissions[ $controllerName ] ) ){
		
				foreach( $submenu as $action ){
					
					if( array_key_exists( $action, $permissions[ $controllerName ][ 'action' ] ) ){

						$action == $actionName ? $active = ' class="active"' : $active = null;
						empty( $this->iconClasses[ $action ] ) ? $icon = null : $icon = "<i class=\"{$this->iconClasses[$action]}\"></i> ";
						$string .= "<li{$active}>". $this->Html->link( $icon . $permissions[ $controllerName ][ 'actions_labels' ][ $action ], "/{$controllerName}/{$action}", array( 'class' => "icon {$action}", 'escape' => false ) ) ."</li>\n";
					}
				}
			}
		
			return '<ul id="submenu" class="nav nav-tabs">'. $string .'</ul>';
			
		} else
			return null;
	}

	private function optionSelected( &$option ){
		
		if( $this->Session->check( "Menu.{$option}" ) )
			return ' '.$this->Session->read( "Menu.{$option}" );
			
		return null;
	}

}