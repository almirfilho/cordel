<?php

App::import( "Sanitize" );

class UsersController extends AppController {

	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $name	= "Users";
	
	public $setMenu = "Users";

	public $label	= 'Usuários';
	
	public $submenu	= array( 'index', 'add' );
	
	/*----------------------------------------
	 * Actions
	 ----------------------------------------*/
	
	public function index(){

		$this->checkAccess( $this->name, __FUNCTION__ );
		$this->paginate[ 'fields' ] = array( 'id', 'name', 'email' );
		$this->paginate[ 'contain' ] = array( 'Profile.name' );
		$this->paginate[ 'order' ] = "User.created DESC";
		$this->set( "users", $this->paginate( "User" ) );
	}
	
	public function view( $id = null ){
			
		$this->checkAccess( $this->name, "index" );
		$this->User->contain( array( 'Profile' => array( 'fields' => array( 'name' ) ) ) );
		$user = $this->User->findById( $id );

		$this->checkResult( $user, 'User' );
		$this->set( "user", $user );
	}
	
	public function add(){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( !empty( $this->data ) ){
			
			$this->User->create( Sanitize::clean( $this->data ) );
			
			if( $this->User->validates() ){
				
				if( $this->User->save( null, false ) ){
					
					$this->setMessage( 'saveSuccess', 'User' );
					$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $this->User->id ) );
					
				} else				
					$this->setMessage( 'saveError', 'User' );
				
			} else				
				$this->setMessage( 'validateError' );
		}

		$this->profilesList();
	}
	
	public function edit( $id = null ){
		
		$this->checkAccess( $this->name, __FUNCTION__ );

		if( $id == 1 ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode editar o Usu&aacute;rio Administrador.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
			
		if( empty( $this->data ) ){
			
			$this->User->contain();
			$this->data = $this->User->findById( $id );

		} else {
			
			$this->User->create( Sanitize::clean( $this->data ) );
			
			if( $this->User->validates() ){
						
				if( $this->User->save( null, false ) ){
					
					$this->setMessage( 'saveSuccess', 'User' );
					$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $id ) );
					
				} else
					$this->setMessage( 'saveError', 'User' );
				
			} else
				$this->setMessage( 'validateError' );
		}
		
		$this->profilesList();
	}
	
	public function delete( $id = null ){
			
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		$this->User->contain();
		$user = $this->User->findById( $id );
		
		if( $user[ 'User' ][ 'id' ] == $this->Auth->user( "id" ) ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode excluir seu pr&oacute;prio usu&aacute;rio.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
		
		if( $user[ 'User' ][ 'profile_id' ] == 1 && $user[ 'User' ][ 'id' ] == 1 ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode excluir o Usu&aacute;rio Administrador.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
		}
		
		if( $this->User->delete( $id ) )
			$this->setMessage( 'deleteSuccess', 'User' );
		else
			$this->setMessage( 'saveError', 'User' );
			
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
	}
		
	public function login( $data = null ){

		$this->layout = "login";
    }
      
    public function logout(){

    	$this->User->lastLogin( $this->Auth->user( "id" ) );        
		$this->redirect( $this->Auth->logout() );
	}
	
	public function manageAccount(){
		
		if ( empty( $this->data ) ){
			
			//	pegando o user logado no BD
			$this->User->contain();
			$this->data = $this->User->findById( $this->Auth->user( "id" ) );
			$this->data[ "User" ][ "currentPassword" ] = $this->data[ "User" ][ "password" ];
			$this->data[ "User" ][ "password" ] = "";
			
		} else {
			
			if( $this->data[ 'User' ][ 'email' ] == '--' )
				$this->data[ 'User' ][ 'email' ] = '';
			
			$this->User->create( Sanitize::clean( $this->data ) );
			
			if ( $this->User->validates() ){
				
				if( $this->data[ "User" ][ "newPassword" ] != "" )
					$this->data[ "User" ][ "password" ] = Security::hash( $this->data[ "User" ][ "newPassword" ], "md5", true );
					
				if( empty( $this->data[ 'User' ][ 'email' ] ) )
					$this->data[ 'User' ][ 'email' ] = '--';
				
				if( $this->User->save( $this->data, false, array( "name", "email", "password", "modified" ) ) ){
					
					$this->Session->setFlash( "Seus dados foram atualizados com sucesso.", "default", array( 'class' => 'success' ) );
					$this->Session->write( "Auth.User.name", $this->data[ "User" ][ "name" ] );
					
				} else {
				
					$this->Session->setFlash( "Ocorreu um erro ao tentar atualizar seus dados. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
				}
				
				$this->redirect( "/" );
				
			} else {

				$this->Session->setFlash( "Preencha todos os campos abaixo corretamente e tente novamente.", "default", array( 'class' => 'error' ) );
			}
		}
		
		$this->submenu = array();
		$this->subtitle = "Meus Dados";
	}
	
	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/

	private function profilesList(){

		$this->set( "profiles", $this->User->Profile->find( "list", array( 'order' => 'name ASC' ) ) );
	}

	/*----------------------------------------
	 * Callbacks
	 ----------------------------------------*/
	
	public function beforeFilter(){
		
		Security::setHash( "md5" );
	}
	
}

?>