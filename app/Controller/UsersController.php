<?php

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
			
		$this->checkAccess( $this->name, __FUNCTION__ );
		$this->User->contain( array( 'Profile' => array( 'fields' => array( 'name' ) ) ) );
		$user = $this->User->findById( $id );

		$this->checkResult( $user, 'User' );
		$this->set( "user", $user );
	}
	
	public function add(){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( $this->request->is('post') ){
			
			$this->User->create( $this->request->data );
			
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
			$this->redirect( array( 'controller' => $this->name, 'action' => 'view', 1 ) );
		}
			
		if( !$this->request->is('post') ){
			
			$this->User->contain();
			$this->data = $this->User->findById( $id );

		} else {
			
			$this->User->create( $this->request->data );
			
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
			$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $id ) );
		}
		
		if( $user[ 'User' ][ 'profile_id' ] == 1 && $user[ 'User' ][ 'id' ] == 1 ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o pode excluir o Usu&aacute;rio Administrador.", "default", array( 'class' => 'error' ) );
			$this->redirect( array( 'controller' => $this->name, 'action' => 'view', $id ) );
		}
		
		if( $this->User->delete( $id ) )
			$this->setMessage( 'deleteSuccess', 'User' );
		else
			$this->setMessage( 'saveError', 'User' );
			
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
	}
		
	public function login(){

		if( $this->request->is('post') ) {
			
        	if( $this->Auth->login() )
            	$this->redirect($this->Auth->redirect());
			else
				$this->Session->setFlash( '<strong>Usuário</strong> ou <strong>senha</strong> inv&aacute;lida.', 'default', array( 'class' => 'error' ), 'auth' );
		}

		$this->layout = "login";
    }
      
    public function logout(){

		$this->redirect( $this->Auth->logout() );
	}
	
	public function manageAccount(){
	
		if( !$this->request->is('post') ){
			
			//	recuperando o usuario logado do BD
			$this->data = $this->User->find( 'first', array(
				'conditions' => array( 'id' => $this->Auth->user( 'id' ) ),
				'fields' => array( 'name', 'email' ),
				'contain' => false
			) );
			
		} else {
			
			$this->User->create( $this->request->data );
			$this->User->id = $this->Auth->user( 'id' );
			
			if( $this->User->validates( array( 'pass_switched' => $this->Auth->user( 'pass_switched' ) ) ) ){

				if( $this->User->save( null, false ) ){
					
					$this->Session->setFlash( "Seus dados foram atualizados com sucesso.", "default", array( 'class' => 'success' ) );
					$this->Session->write( "Auth.User.name", $this->data[ "User" ][ "name" ] );
					$this->Session->write( "Auth.User.pass_switched", $this->User->_passSwitched );
					$this->redirect( "/" );
					
				} else
					$this->Session->setFlash( "Ocorreu um erro ao tentar atualizar seus dados. Por favor tente novamente.", "default", array( 'class' => 'error' ) );

			} else
				$this->setMessage( 'validateError' );
		}

		if( !$this->Auth->user( 'pass_switched' ) && !$this->Session->check( 'Message.flash' ) )
			$this->Session->setFlash( '<h4>Bem vindo(a)!</h4>Este é seu primeiro acesso a este Sistema. Antes de continuar é necessário modificar sua senha de acesso.<br />Confira também seus dados abaixo. Feito isto, não informe sua senha para outras pessoas.' );
		
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
		
		parent::beforeFilter();
		Security::setHash( "md5" );
	}
	
}