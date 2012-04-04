<?php

class Profile extends AppModel {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 

	public $name  = 'Profile';

	public $label = 'Perfil de Usuário';

	/*----------------------------------------
	 * Associations
	 ----------------------------------------*/ 
	
	public $hasAndBelongsToMany = array( 'Area' );
	
	public $hasMany 			= array( 'User' );
	
	/*----------------------------------------
	 * Validation
	 ----------------------------------------*/
	
	public $validate = array(
	
		'name' => array(
			
			'rule'		=> 'notEmpty',
			'message'	=> 'Preencha Nome'
		)
	);
	
	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/
	
	public static function isAdmin( $profile_id ){

		return $profile_id == Configure::read( 'AdminProfileId' );
	}

	public function getAreas( $profile_id ){

		$profile = $this->find( 'first', array( 
			'conditions' => array( 'Profile.id' => $profile_id ), 
			'fields' => 'Profile.modified',
			'contain' => array( 
				'Area' => array(
					'order' => 'controller_label ASC'
		) ) ) );
		
		$areas = array();
		
		foreach( $profile[ 'Area' ] as $area ){
			
			if( !isset( $areas[ $area[ 'controller' ] ] ) )
				$areas[ $area[ 'controller' ] ] = array( 'controller_label' => $area[ 'controller_label' ], 'action' => array(), 'actions_labels' => array() );
				
			$areas[ $area[ 'controller' ] ][ 'action' ][ $area[ 'action' ] ] = $area[ 'appear' ];
			$areas[ $area[ 'controller' ] ][ 'actions_labels' ][ $area[ 'action' ] ] = $area[ 'action_label' ];
		}
		
		return $areas;
	}
	
}