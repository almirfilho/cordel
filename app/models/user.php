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

		'password' => array(

			'currentPassword' => array(
				'rule'	=>	'currentPassword',
				'message'	=>	'Senha incorreta'
			),

			'notEmpty' => array(
				'rule'	=>	'passNotEmpty',
				'message'	=>	'Preencha com sua senha atual'
			)
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
			),

			'newPassNotSame' => array(
				
				'rule' => 'newPassNotSame',
				'message' => 'Sua nova senha não pode ser igual a senha antiga'
			),

			'newPassNotEmpty' => array(
				
				'rule' => 'newPassNotEmpty',
				'message' => 'Preencha sua nova senha'
			)
		),

		'passwordConfirm'	=>	array(
				
			'rule'	=>	'passwordConfirm',
			'message'	=>	'Senha de Confirmação não confere'
		)
	);

	public function passwordConfirm( $check ){
		
		return array_pop( $check ) == $this->data[ $this->name ][ 'newPassword' ];
	}
	
	public function currentPassword( $check ){
		
		$currentPassword = $this->field( 'password' );
		return array_pop( $check ) == $currentPassword;
	}

	public function passNotEmpty( $check ){
		
		return array_pop( $check ) != Security::hash( '', 'md5', true );
	}

	public function newPassNotEmpty( $check ){

		if( isset( $this->_passSwitched ) ){

			if( !$this->_passSwitched ){

				$value = array_pop( $check );
				return !empty( $value );
			}
		}
		
		return true;
	}
	
	public function newPassNotSame( $check ){

		if( isset( $this->_passSwitched ) ){
			
			if( !$this->_passSwitched ){
				
				$currentPassword = $this->field( 'password' );
				return Security::hash( array_pop( $check ), 'md5', true ) != $currentPassword;
			}
		}
		
		return true;
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

	public function beforeValidate( $options = array() ){

		if( array_key_exists( 'pass_switched', $options ) ){

			$this->_passSwitched = $options[ 'pass_switched' ];
			$this->validate[ 'newPassword' ][ 'alphanumeric' ][ 'allowEmpty' ] = false;
			$this->validate[ 'newPassword' ][ 'between' ][ 'allowEmpty' ] = false;
		}

		return true;
	}
	
	public function beforeSave(){

		if( !$this->id )
			$this->data[ $this->name ][ 'password' ] = Security::hash( '123456', 'md5', true );

		elseif( !empty( $this->data[ $this->name ][ 'newPassword' ] ) ){

			$this->data[ $this->name ][ 'password' ] = Security::hash( $this->data[ $this->name ][ 'newPassword' ], 'md5', true );
			unset( $this->data[ $this->name ][ 'newPassword' ] );

			if( !empty( $this->data[ $this->name ][ 'passwordConfirm' ] ) )
				unset( $this->data[ $this->name ][ 'passwordConfirm' ] );

			if( isset( $this->_passSwitched ) )
				if( !$this->_passSwitched )
					$this->_passSwitched = $this->data[ $this->name ][ 'pass_switched' ] = '1';
		}

		return true;
	}
	
}

?>