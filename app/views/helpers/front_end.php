<?php

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
	 * Atributtes
	 ----------------------------------------*/

	public function niceDate( &$date, $verbose = false ){

		if( $verbose )
			return '<i class="icon-calendar"></i> '. $this->Time->format( "d", $date ) .' de '. $this->months[$this->Time->format( "n", $date )] .' de '. $this->Time->format( "Y", $date ) .' <i class="icon-time"></i> '. $this->Time->format( "H:i:s", $date );

		return '<i class="icon-calendar"></i> '. $this->Time->format( "d/m/Y", $date ) .' <i class="icon-time"></i> '. $this->Time->format( "H:i:s", $date );
	}

	public function getHeader( &$controller, &$action, $subtitle = null ){
		
		if( !$this->Session->check( "Auth.User.Profile" ) )
			return $this->output( "" );
		
		$permissions = &$this->Session->read( "Auth.User.Profile" );

		$tagOpen = '<div class="page-header"><h1>';
		$tagClose = '</h1></div>';

		if( !empty( $permissions[ $controller ] ) )
			return $tagOpen . $this->output( $permissions[ $controller ][ 'controller_label' ] ) . $tagClose;

		elseif( $subtitle )
			return $tagOpen . $this->output( $subtitle ) . $tagClose;

		else
			return null;
	}

	public function getMenu(){
		
		$string = '';
		$areas = &$this->Session->read( "Auth.User.Menu" );
		
		foreach ($areas as $area) {

			// nao eh submenu
			if( empty( $area[ 'AreaChild' ] ) )
				$string .= '<li class="'.$this->optionSelected( $area[ 'controller' ] ).'">'.$this->Html->link( $area[ 'controller_label' ], "/{$area['controller']}/{$area['action']}", array( 'escape' => false ) )."</li>\n";

			else { // submenu

				$string .= '<li class="dropdown">'.
					'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $area[ 'controller_label' ] .' <b class="caret"></b></a>'.
				    '<ul class="dropdown-menu">'.
				    '<li class="'.$this->optionSelected( $area[ 'controller' ] ).'">'.$this->Html->link( $area[ 'controller_label' ], "/{$area['controller']}/{$area['action']}", array( 'escape' => false ) )."</li>\n";
				
				foreach( $area[ 'AreaChild' ] as $areaChild )
					$string .= '<li class="divider"></li><li class="'.$this->optionSelected( $areaChild[ 'controller' ] ).'">'.$this->Html->link( $areaChild[ 'controller_label' ], "/{$areaChild['controller']}/{$areaChild['action']}", array( 'escape' => false ) )."</li>\n";

				$string .= '</ul></li>';
			}
		}
				
		return $this->output( $string );
	}
	
	public function getSubMenu( &$submenu, &$controllerName, &$actionName ){
		
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

?>