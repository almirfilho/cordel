<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	public $title		=	"Cordel";
	
	public $components	= 	array(

		'Security',
		'Auth' => array( 
			'authorize'  => 'Controller',
			'authError'  => 'Por favor, efetue <strong>login</strong> para ter acesso a esta √°rea.',
			'loginRedirect' => '/',
			'authenticate' => array(
	            'Form' => array(
					'fields' => array( 'username' => 'email', 'password' => 'password' ) ) ) ),
		'Session', 
		'Menu' 
	);
    
    public $helpers		= 	array( 'Session', 'Form', 'Html', 'Paginator', 'Time', 'Text', 'FrontEnd', 'BForm' );
    
    public $paginate	=	array( 'limit' => 20, 'order' => 'created DESC', 'contain' => false );
    
    public $uses		=	array( 'Profile' );

	public $submenu		=	array();
	
	public $subtitle	=	null;
	
//Actions acessadas sem restriÁıes 
	public $allowActions =  array( 'home', 'display');
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
		$action = '';//INICIALIZANDO A VARI¡VEL
	
		//VERIFICA SE O ACTION QUE EST¡ SENDO SOLICITADO NO MOMENTO EST¡ NA PROPRIEDADE $this->allowActions, SE ESTIVER, GUARDA NA VARI¡VEL $action
		if ($this->allowActions){
			foreach ($this->allowActions as $key  => $allowAction) {
			
				 if ( $this->action == $allowAction ){
				 	$action = $allowAction;
				 }
				
			}
			
		}
		
		/*COM A VARI¡VEL $ACTION PREENCHIDA COM O NOME DO ACTION DO MOMENTO, ENT√O CHECA PRIMEIRAMENTE SE O USU¡RIO TEM ACESSO
		AO CONTROLLER ATUAL E SE A VARI¡VEL  $action … IGUAL AO ACTION SOLICITADO NO MOMENTO.*/
	    if ( $this->Session->check( "Auth.User.Profile.{$this->name}" ) && $action == $this->action  ){
				
			return true;
			
		}
		
		//CHECANDO SE O USU¡RIO TEM ACESSO A DETERMINADA ¡REA E ACTION
		if ( !$this->checkAccess($this->name,$this->action ) ){

			return false;
			
		}
			
		//CONFORME A FUN«√O N√O CAIR EM NENHUM IF ANTERIORMENTE O CONTROLLER DO MOMENTO … LIBERADO PARA TER ACESSO
	    return true;
	}
	
	protected function checkAccess( $controller = null, $action = null ){
		
		if( $controller == null || $action == null ){
			
			$this->Session->setFlash( "Ocorreu um erro de permiss&otilde;es. (erro: falta de parametros)", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );	
			return false;		
		}
		
		if( !$this->Session->check( "Auth.User" ) ){
					
			$this->Session->setFlash( "faca login para acesso a esta area", array( 'class' => 'error' ) );
			
			$this->redirect( "/" );
			return false;
	
		}
		
		if( !$this->Session->check( "Auth.User.Profile.{$controller}" ) ){
					
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o tem acesso a esta &Aacute;rea ({$this->label}).", "default", array( 'class' => 'error' ) );
			
			$this->redirect( "/" );
			return false;
	
		}
		
		if( !$this->Session->check( "Auth.User.Profile.{$controller}.action.{$action}" ) ){
			
			$this->Session->setFlash( "Voc&ecirc; n&atilde;o tem acesso a esta opera&ccedil;&atilde;o ({$this->label}: {$action}).", "default", array( 'class' => 'error' ) );
			$this->redirect( "/" );
			
			return false;
		}
		
		/*RETORNA TRUE  QUANDO NENHUM DOS IF's ANTERIORES  FOREM SATISFAT”RIOS, OU SEJA, O SU¡RIO EST¡ LOGADO E TEM ACESSO AO SOLICITADO NO MOMENTO. ISSO PARA N√O CAIR NO SEGUNDO IF DO METODO 
		isAuthorized QUE BLOQUEARIA O CONTROLLER OU ACTION DO MOMENTO.*/
		return true;
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
				$str = '<strong>'.$this->{$model}->label."</strong> salv".$this->{$model}->gender." com <strong>sucesso</strong>.";
				$class = 'success';
				break;

			case "saveError":
				$str = "Ocorreu um <strong>erro</strong> ao tentar salvar <strong>".$this->{$model}->label."</strong>. Por favor tente novamente.";
				$class = 'error';
				break;

			case "deleteSuccess":
				$str = '<strong>'.$this->{$model}->label."</strong> exclu√≠d".$this->{$model}->gender." com <strong>sucesso</strong>.";
				$class = 'success';
				break;

			case "deleteError":
				$str = "Ocorreu um <strong>erro</strong> ao tentar excluir <strong>".$this->{$model}->label."</strong>. Por favor tente novamente.";
				$class = 'error';
				break;

			case "validateError":
				$str = "Preencha todos os campos abaixo <strong>corretamente</strong> e tente novamente.";
				$class = 'error';
				break;

			case 'noResult':
				$str = up( $this->{$model}->gender )." <strong>".$this->{$model}->label."</strong> que voc√™ est√° tentando acessar <strong>n√£o existe</strong>.";
				$class = 'error';
				break;
		}
		
		$this->Session->setFlash( $str, "default", array( 'class' => $class ) );
	}
	
}