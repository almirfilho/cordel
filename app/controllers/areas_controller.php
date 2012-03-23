<?php

App::import( "Sanitize" );

class AreasController extends AppController {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 

	public $name = "Areas";
	
	public $submenu	= array( 
		
		'Areas' => array( 'add' )
	);
	
	/*----------------------------------------
	 * Actions
	 ----------------------------------------*/ 
	
	public function index(){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		unset( $this->paginate[ 'order' ] );
		$this->set( "areas", $this->paginate( "Area" ) );
	}
	
	public function add(){
		
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( !empty( $this->data ) ){
			
			$this->Area->create( Sanitize::clean( $this->data ) );
			
			if( $this->Area->validates() ){
				
				if( $this->Area->save( null, false ) )
					$this->Session->setFlash( "&Aacute;rea salva com sucesso.", "default", array( 'class' => 'success' ) );	
				else					
					$this->Session->setFlash( "Ocorreu um erro ao tentar salvar &Aacute;rea. Pro favor tente novamente.", "default", array( 'class' => 'error' ) );
				
				$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			}
		}
	}
	
	public function edit( $id = null ){
		
		if( !(int)$id )
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( empty( $this->data ) ){
			
			$this->Area->contain();
			$this->data = $this->Area->findById( $id );
			
		} else {
			
			$this->Area->create( Sanitize::clean( $this->data ) );
			
			if( $this->Area->validates() ){
				
				if( $this->Area->save( null, false ) )
					$this->Session->setFlash( "&Aacute;rea editada com sucesso.", "default", array( 'class' => 'success' ) );
				else
					$this->Session->setFlash( "Ocorreu um erro ao tentar editar &Aacute;rea. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
					
				$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			}
		}
	}
	
	public function delete( $id = null ){
		
		if( !(int)$id )
			$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
			
		$this->checkAccess( $this->name, __FUNCTION__ );
		
		if( $this->Area->delete( $id ) )
			$this->Session->setFlash( "&Aacute;rea exclu&iacute;da com sucesso.", "default", array( 'class' => 'success' ) );
		else
			$this->Session->setFlash( "Ocorreu um erro ao tentar excluir &Aacute;rea. Por favor tente novamente.", "default", array( 'class' => 'error' ) );
			
		$this->redirect( array( 'controller' => $this->name, 'action' => 'index' ) );
	}
	
	/*----------------------------------------
	 * Controller Methods
	 ----------------------------------------*/

	protected function checkAccess( $controller = null, $action = null ){
		
		if( !Configure::read() )
			parent::checkAccess( $controller, $action );
	}

}

?>