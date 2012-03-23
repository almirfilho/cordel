<?php

App::import( "Sanitize" );

class ProfilesController extends AppController {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 

	public $name	= "Profiles";
	
	public $setMenu	= "Profiles";
	
	public $submenu	= array( 'index', 'add' );
	
	/*----------------------------------------
	 * Actions
	 ----------------------------------------*/
	
	public function index(){

		$this->checkAccess( $this->name, __FUNCTION__ );
		$this->paginate[ 'contain' ] = array();
		$this->set( "profiles", $this->paginate( "Profile" ) );		
	}
	
	public function view( $id = null ){
		
		if( !(int)$id )
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			
		$this->checkAccess( $this->name, "index" );
		
		$this->Profile->contain( array( 
			'Area' => array( 
				'fields' => array( 'controller_label', 'action_label' ),
				'order' => 'Area.controller_label ASC'
		) ) );
		
		$this->set( "profile", $this->Profile->findById( $id ) );
	}
	
	public function add( $redirect = null ){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( !empty( $this->data ) ){
			
			$this->Profile->create( Sanitize::clean( $this->data ) );
			
			if( $this->Profile->validates() ){
				
				if( $this->Profile->save( null, false ) ){
					
					$this->Session->setFlash( "Perfil salvo com sucesso.", "default", array( 'class' => 'success' ) );
					if( !$redirect ) $this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
					elseif( $redirect == "userAdd" ) $this->redirect( array( 'controller' => 'users', 'action' => 'add' ) );
					
				} else
					$this->Session->setFlash( "Erro ao tentar salvar o Perfil. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
				
			} else				
				$this->Session->setFlash( "Preencha corretamente os campos abaixo e tente novamente.", "default", array( 'class' => 'error' ) );
		}
		
		$this->set( "areas", $this->Profile->Area->lists() );
		$this->set( "redirect", $redirect );
	}
	
	public function edit( $redirect = null, $id = null ){

		if( !$redirect || !(int)$id )
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			
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
					
					$this->Session->setFlash( "Perfil editado com sucesso.", "default", array( 'class' => 'success' ) );
					if( $redirect == "index" ) $this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
					elseif( $redirect == "view" ) $this->redirect( array( 'controller' => $this->name, 'action' => 'view', $id ) );
					
				} else					
					$this->Session->setFlash( "Erro ao tentar editar Perfil. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
				
			} else				
				$this->Session->setFlash( "Preencha corretamente os campos abaixo e tente novamente.", "default", array( 'class' => 'error' ) );
		}
		
		$this->set( "areas", $this->Profile->Area->lists() );
		$this->set( "redirect", $redirect );
	}
	
	public function delete( $id = null ){
		
		if( !(int)$id )
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			
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
			$this->Session->setFlash( "Perfil deletado com sucesso.", "default", array( 'class' => 'success' ) );
		else
			$this->Session->setFlash( "Erro ao deletar Perfil. Tente novamente.", "default", array( 'class' => 'error' ) );
			
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );		
	}
		
	public function multipleActions( $action = null ){
		
		if( $this->data && $action ){
			
			switch( $action ){
				
				case "delete":
					
					$this->checkAccess( $this->name, $action );
					
					if( $this->Profile->deleteMultiple( $this->checkList( $this->data[ 'Profile' ][ 'checks' ] ) ) )
						$this->Session->setFlash( "Perf&iacute;s exclu&iacute;dos com sucesso.", "default", array( 'class' => 'success' ) );
					else
						$this->Session->setFlash( "Ocorreu um erro ao tentar excluir um ou mais Perf&iacute;s. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
					
					break;
			}
		}
		
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
	}
	
	/*----------------------------------------
	 * Controller Methods
	 ----------------------------------------*/
	
	/**
	 * Aqui verificamos se foram selecionados profiles de admin ou o profile do proprio usuario.
	 */
	protected function checkList( $checks ){
		
		$list 			= parent::checkList( $checks );
		$len 			= sizeof( $list );
		$userProfile	= $this->Auth->user( "profile_id" );
		
		for( $i = 0; $i < $len; $i++ )
			if( $list[ $i ] == 1 || $list[ $i ] == $userProfile )
				unset( $list[ $i ] );
		
		return $list;
	}

}

?>