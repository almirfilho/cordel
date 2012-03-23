<?php

class User extends AppModel {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 
	
	public $name 			=	'User';

	public $label			=	'Usuário';
	
	/*----------------------------------------
	 * Associations
	 ----------------------------------------*/ 
	
	public $belongsTo 		= 	array( 'Profile' );
	
	/*----------------------------------------
	 * Validation
	 ----------------------------------------*/
	
	public $validate 		= 	array(
		
		'name' 	=> array(
			
			'rule'		=> 'notEmpty',
			'message'	=> 'Preencha Nome'
		),
		
		'email' => array(
		
			'isUnique'	=> array(
				'rule'		=> 'isUnique',
				'message'	=> 'Este email já está cadastrado'
			),

			'email'	=> array(
				'rule'		=> 'email',
				'message'	=> 'Email inválido'
			),

			'notEmpty' 	=> array(
				'rule'		=> 'notEmpty',
				'message'	=> 'Preencha Email'
			)
		),

		'profile_id' => array(
			
			'rule' => 'notEmpty',
			'message' => 'Selecione um Perfil'
		),
		
		'newPassword' => array(
		
			'alphanumeric' => array(
	
				'rule' => 'alphanumeric',
				'message' => 'Senha deve conter apenas letras e/ou números (sem acentuação ou caracteres especiais)',
				'allowEmpty' => true
			),
			
			'between' => array(
	
				'rule' => array( 'between', 6, 20 ),
				'message' => 'Senha deve conter entre 6 e 20 caracteres',
				'allowEmpty' => true
			)
		),

		'passwordConfirm'	=>	array(
				
			'rule'	=>	'passwordConfirm',
			'message'	=>	'Senha de Confirmação não confere'
		),
		
		'currentPassword'	=>	array(
				
			'rule'	=>	'currentPassword',
			'message'	=>	'Senha incorreta'
		)
	);

	public function passwordConfirm( $check ){
		
		return array_pop( $check ) == $this->data[ $this->name ][ 'newPassword' ];
	}
	
	public function currentPassword( $check ){

		$currentPassword = $this->field( 'password' );
		return Security::hash( array_pop( $check ), 'md5', true ) == $currentPassword;
	}

	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/
	
	public function lastLogin( $user_id ){
		
		$this->id = $user_id;
		$this->saveField( 'last_login', date('Y-m-d H:i:s') );
	}

	/*----------------------------------------
	 * Callbacks
	 ----------------------------------------*/
	
	public function beforeSave(){

		if( !$this->id )
			$this->data[ $this->name ][ 'password' ] = Security::hash( '123456', 'md5', true );

		elseif( !empty( $this->data[ 'User' ][ 'newPassword' ] ) )
			$this->data[ $this->name ][ 'password' ] = Security::hash( $this->data[ $this->name ][ 'newPassword' ], 'md5', true );


		return true;
	}
	
}

?>