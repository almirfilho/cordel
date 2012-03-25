<?php 

class MenuComponent extends Object {

	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $name 		= "Menu";
	
	public $components	= array( 'Session' );
	
	public $controller;
	
	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/
	
	public function initialize( &$controller ){
		
		$this->controller = &$controller;
	}
	
	public function beforeRender( &$controller ){
		
		//	setando a opcao selecionada do menu
		if( !empty( $controller->setMenu ) ){
			
			$this->Session->write( "Menu", null );
			$this->Session->write( "Menu.{$controller->setMenu}", 'active' );

		} else
			$this->Session->write( "Menu", null );
	}

	public function mount(){

		// $areas = $this->Session->read( "Auth.User.Profile" );
		$areas = $this->controller->Profile->find( 'first', array(
			'conditions' => array( 'Profile.id' => $this->Session->read( 'Auth.User.profile_id' ) ),
			'fields' => 'id',
			'contain' => array(
				'Area' => array(
					'conditions' => array( 'Area.appear' => '1', 'Area.parent_id' => null ),
					'fields' => array( 'controller', 'controller_label', 'action' ),
					'AreaChild' => array(
						'conditions' => array( 'AreaChild.appear' => '1' ),
						'fields' => array( 'controller', 'controller_label', 'action' )
		) ) ) ) );

		$this->Session->write( 'Auth.User.Menu', $areas[ 'Area' ] );

		// debug($areas);
	}
	
}

?>