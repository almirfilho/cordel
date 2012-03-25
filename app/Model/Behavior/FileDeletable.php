<?php

class FileDeletableBehavior extends ModelBehavior {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/
	
	private $fields;
	
	private $filenames;
	
	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/
	
	public function setup( &$model, $settings = array() ){
		
		$this->fields = $settings[ 'fields' ];
	}
	
	private function deleteFile( $path, $filename ){

		$path = ROOT . DS . ".." . DS . $path;
        $path = str_replace( "/", DS, $path );
        $path = str_replace( "\\\\", DS, $path );
       
        return unlink( $path . DS . $filename );
    }
	
	/*----------------------------------------
	 * Callbacks
	 ----------------------------------------*/
	
	public function beforeDelete( &$model, $cascade = true ){
		
		$filenames = array();
		
		foreach( $this->fields as $field => $paths )
			$this->filenames[ $field ] = $model->field( $field );
		
		return true;
	}
	
	public function afterDelete( &$model ){
		
		foreach( $this->fields as $field => $paths )
			if( $this->filenames[ $field ] )
				foreach( $paths as $path )
					$this->deleteFile( $model->{$path}, $this->filenames[ $field ] );
	}
	
}