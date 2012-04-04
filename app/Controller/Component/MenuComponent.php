<?php 

class MenuComponent extends Component {

	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $name 		= "Menu";
	
	public $components	= array( 'Session' );
	
	public $controller;

	/*----------------------------------------
	 * Constructor
	 ----------------------------------------*/
	
	function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
	}

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

		$areas = $this->controller->Profile->find( 'first', array(
			'conditions' => array( 'Profile.id' => $this->Session->read( 'Auth.User.profile_id' ) ),
			'fields' => 'id',
			'contain' => array(
				'Area' => array(
					'order' => 'Area.controller_label ASC',
					'conditions' => array( 'Area.appear' => '1', 'Area.parent_id' => null ),
					'fields' => array( 'controller', 'controller_label', 'action' ),
					'AreaChild' => array(
						'conditions' => array( 'AreaChild.appear' => '1' ),
						'fields' => array( 'controller', 'controller_label', 'action' )
		) ) ) ) );
		
		$this->Session->write( 'Auth.User.Menu', $areas[ 'Area' ] );
	}
	
}