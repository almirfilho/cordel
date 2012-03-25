<?php

class AppController extends Controller {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $title		=	"Cordel";
	
	public $components	= 	array(
		'Auth' => array( 
			'authorize'  => 'controller',
			'loginError' => '<strong>Usuário</strong> ou <strong>senha</strong> inv&aacute;lida.',
			'authError'  => 'Por favor, efetue <strong>login</strong> para ter acesso a esta área.',
			'loginRedirect' => '/',
			'fields' => array( 'username' => 'email', 'password' => 'password' ) ), 
		'Session', 
		'Menu' 
	);
    
    public $helpers		= 	array( 'Form', 'Html', 'Paginator', 'Time', 'Text', 'FrontEnd', 'BForm' );
    
    public $paginate	=	array( 'limit' => 20, 'order' => 'created DESC', 'contain' => false );
    
    public $uses		=	array( 'Profile' );

	public $submenu		=	array();
	
	public $subtitle	=	null;
	
	/*----------------------------------------
	 * Callbacks
	 ----------------------------------------*/
	
	public function beforeRender(){

		$this->set( "title_for_layout", $this->title );
		$this->set( "submenu", $this->submenu );
		$this->set( "subtitle", $this->subtitle );
	}
	
	public function beforeFilter(){
		
	    if( $this->Auth->user() ){

			if( !$this->Session->check( "Auth.User.Profile" ) ){

				$this->Session->write( "Auth.User.Profile", $this->Profile->getAreas( $this->Auth->user( "profile_id" ) ) );
				$this->Profile->User->lastLogin( $this->Auth->user( "id" ) );
				$this->Menu->mount();
			}

			if( !$this->Auth->user( 'pass_switched' ) && $this->action != 'manageAccount' )
				$this->redirect( array( 'controller' => 'users', 'action' => 'manageAccount' ) );
		}
	}
	
	/*----------------------------------------
	 * Controller Methods
	 ----------------------------------------*/
	
	public function search( $action = 'index' ){
		
		if( !empty( $this->data ) ){
			
			$data = array_pop( $this->data );
			
			if( !empty( $data[ 'word' ] ) && !empty( $data[ 'field' ] ) ){
				
				$word = trim( str_replace( ";", "", Sanitize::escape( $data[ 'word' ] ) ) );
				$field = trim( str_replace( ";", "", Sanitize::escape( $data[ 'field' ] ) ) );
				$query = "w={$word};f={$field}";
				$this->redirect( array( 'controller' => $this->name, 'action' => $action, $query ) );
			}
		}

		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
	}
	
	protected function filter( $modelName, $filterFields ){
		
		if( !empty( $this->params[ 'pass' ][0] ) ){
			
			$query = $this->params[ 'pass' ][0];
			
			if( ereg( "^w=(.+);f=(.+)$", $query ) ){
				
				$array = explode( ";", $query );
				$word = substr( $array[0], 2 );
				$field = substr( $array[1], 2 );

				$this->paginate[ 'conditions' ] = array( "{$modelName}.{$field} LIKE" => "%{$word}%" );
			}
		}
		
		$this->set( "filter_fields", $filterFields );
	}
	
	public function isAuthorized(){
		
		return true;
	}
	
	protected function checkAccess( $controller = null, $action = null ){
		
		if( $controller == null || $action == null ){
			
			$this->Session->setFlash( "Ocorreu um erro de permiss&otilde;es. (erro: falta de parametros)", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );			
		}
		
		if( !$this->Session->check( "Auth.User" ) ){
			
			$this->Session->setFlash( "Por favor, efetue login para ter acesso a esta &aacute;rea.", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );	
		}
		
		if( !$this->Session->check( "Auth.User.Profile.{$controller}" ) ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o tem acesso a esta &Aacute;rea ({$this->label}).", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );
		}
		
		if( !$this->Session->check( "Auth.User.Profile.{$controller}.action.{$action}" ) ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o tem acesso a esta opera&ccedil;&atilde;o ({$this->label}: {$action}).", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );
		}
	}

	protected function checkResult( &$data, $model, &$url = null ){

		if( !$data ){

			if( !$url ) $url = array( 'controller' => $this->name, 'action' => 'index' );
			$this->setMessage( 'noResult', $model );
			$this->redirect( $url );
		}
	}

	protected function setMessage( $template, $model = null ){

		$str = $class = null;

		switch( $template ){

			case "saveSuccess":
				$str = $this->{$model}->label." salv".$this->{$model}->gender." com sucesso.";
				$class = 'success';
				break;

			case "saveError":
				$str = "Ocorreu um erro ao tentar salvar ".$this->{$model}->label.". Por favor tente novamente.";
				$class = 'error';
				break;

			case "deleteSuccess":
				$str = $this->{$model}->label." excluíd".$this->{$model}->gender." com sucesso.";
				$class = 'success';
				break;

			case "deleteError":
				$str = "Ocorreu um erro ao tentar excluir ".$this->{$model}->label.". Por favor tente novamente.";
				$class = 'error';
				break;

			case "validateError":
				$str = "Preencha todos os campos abaixo <strong>corretamente</strong> e tente novamente.";
				$class = 'error';
				break;

			case 'noResult':
				$str = up( $this->{$model}->gender )." ".$this->{$model}->label." que você está tentando acessar não existe.";
				$class = 'error';
				break;
		}
		
		$this->Session->setFlash( $str, "default", array( 'class' => $class ) );
	}
	
}

?>