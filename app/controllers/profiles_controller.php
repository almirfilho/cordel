<?php

App::import( "Sanitize" );

class ProfilesController extends AppController {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 

	public $name	= "Profiles";
	
	public $setMenu	= "Profiles";

	public $label = 'Perfil de Usuário';
	
	public $submenu	= array( 'index', 'add' );
	
	/*----------------------------------------
	 * Actions
	 ----------------------------------------*/
	
	public function index(){

		$this->checkAccess( $this->name, __FUNCTION__ );
		$this->set( "profiles", $this->paginate( "Profile" ) );
	}
	
	public function view( $id = null ){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		$profile = $this->Profile->find( 'first', array( 
			'conditions' => array( 'Profile.id' => $id ),
			'contain' => array(
				'Area' => array( 
					'fields' => array( 'controller_label', 'action_label' ),
					'order' => 'Area.controller_label ASC, Area.action_label ASC'
		) ) ) );
		
		$this->checkResult( $profile, 'Profile' );
		$this->set( "profile", $profile );
	}
	
	public function add(){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( !empty( $this->data ) ){
			
			$this->Profile->create( Sanitize::clean( $this->data ) );
			
			if( $this->Profile->validates() ){
				
				if( $this->Profile->save( null, false ) ){
					
					$this->setMessage( 'saveSuccess', 'Profile' );
					$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $this->Profile->id ) );
					
				} else
					$this->setMessage( 'saveError', 'Profile' );
				
			} else
				$this->setMessage( 'validateError' );
		}
		
		$this->set( "areas", $this->Profile->Area->lists() );
	}
	
	public function edit( $id = null ){

		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( $id == 1 ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode editar o Perfil Administrador.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
		
		if( empty( $this->data ) ){
			
			$this->Profile->contain( array( 'Area' ) );
			$this->data = $this->Profile->findById( $id );
			
		} else {
			
			$this->Profile->create( Sanitize::clean( $this->data ) );
			
			if( $this->Profile->validates() ){
				
				if( $this->Profile->save( null, false ) ){
					
					$this->setMessage( 'saveSuccess', 'Profile' );
					$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $id ) );
					
				} else					
					$this->setMessage( 'saveError', 'Profile' );
				
			} else				
				$this->setMessage( 'validateError' );
		}
		
		$this->set( "areas", $this->Profile->Area->lists() );
	}
	
	public function delete( $id = null ){
			
		$this->checkAccess( $this->name, __FUNCTION__ );
	
		if( $id == 1 ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode excluir o Perfil Administrador.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
		
		if( $id == $this->Auth->user( "profile_id" ) ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode excluir seu próprio Perfil.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
		
		if( $this->Profile->delete( $id ) )
			$this->setMessage( 'deleteSuccess', 'Profile' );
		else
			$this->setMessage( 'deteleError', 'Profile' );
			
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );		
	}

}

?>