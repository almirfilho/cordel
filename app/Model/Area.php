<?php

class Area extends AppModel {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 

	public $name = "Area";

	public $hasMany = array( 'AreaChild' => array( 'className' => 'Area', 'foreignKey' => 'parent_id' ) );
	
	/*----------------------------------------
	 * Validation
	 ----------------------------------------*/
	
	public $validate = array(
	
		"controller"	=> array(
		
			"rule"		=> array( "between", "3", "25" ),
			"message"	=> "Controller deve conter entre 3 e 25 caracteres."
		),
		
		"action"	=> array(
		
			"rule"		=> array( "between", "3", "25" ),
			"message"	=> "Action deve conter entre 3 e 25 caracteres."
		),
		
		"controller_label"	=> array(
		
			"rule"		=> array( "between", "3", "50" ),
			"message"	=> "Rotulo do Controller deve conter entre 3 e 50 caracteres."
		),
		
		"action_label"	=> array(
		
			"rule"		=> array( "between", "3", "50" ),
			"message"	=> "Rotulo da Action deve conter entre 3 e 50 caracteres."	
		)
	);
	
	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/
	
	public function lists(){
		
		$areas 		= $this->find( "all", array( 'fields' => array( 'id', 'controller_label', 'action_label' ), 'contain' => array() ) );
		$areasLista = array();
	
		foreach( $areas as $area ){
			
			$index = $area[ 'Area' ][ 'controller_label' ];
			$areasLista[ $index ][ $area[ "Area" ][ "id" ] ] = $area[ "Area" ][ "action_label" ];
		}
		
		return $areasLista;
	}
	
}