<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	/*----------------------------------------
	 * Atributtes
	 ----------------------------------------*/ 
	
	public $actsAs 	= array( 'Containable' );

	public $label	= '$Modelo$';

	public $gender	= 'o';
	
	/*----------------------------------------
	 * Validation Methods
	 ----------------------------------------*/ 
	
	public function cpf( $check ){
		
		$cpf = html_entity_decode( array_pop( $check ) );
		if( !ereg( "([0-9]{3})[.]([0-9]{3})[.]([0-9]{3})[-]([0-9]{2})", $cpf ) ) return false;
		if( $cpf == "000.000.000-00" ) return false;
		
		$cpf = str_replace( array( ".", "-" ), "", $cpf );
		
		//	pega o digito verificador
		$dv_informado = (int)substr( $cpf, 9, 2 );
		
		//	calcula o valor do 10 digito de verificacao
		$soma = 0;
		for( $i = 0, $posicao = 10 ; $i < 9 ; $i++, $posicao-- )
			$soma += ( (int)$cpf{$i} ) * $posicao;
		
		$d10 = $soma % 11;
		if( $d10 < 2 ) $d10 = 0;
		else $d10 = 11 - $d10;
		
		//	calcula o valor do 11 digito de verificacao
		;
		$soma = 0;
		for( $i = 0, $posicao = 11 ; $i < 10 ; $i++, $posicao-- )
			$soma += ( (int)$cpf{$i} ) * $posicao;
		
		$d11 = $soma % 11;
		if( $d11 < 2 ) $d11 = 0;
		else $d11 = 11 - $d11;
		
		//	verifica se o dv calculado eh igual ao informado
		$dv = $d10 * 10 + $d11;
		
		return ( $dv == $dv_informado );
	}
	
	public function cnpj( $check ){
		
		$cnpj = html_entity_decode( array_pop( $check ) );
		if( !ereg( "([0-9]{2})[.]([0-9]{3})[.]([0-9]{3})[/]([0-9]{4})[-]([0-9]{2})", $cnpj ) ) return false;
		if( $cnpj == "00.000.000/0000-00" ) return false;
		
		$cnpj = str_replace( array( '.', '/', '-' ), "", $cnpj );
		
   		$j = 5;
		$k = 6;
		$soma1 = "";
		$soma2 = "";
		
		for($i = 0; $i < 13; $i++){
			
			$j = $j == 1 ? 9 : $j;
			$k = $k == 1 ? 9 : $k;
			$soma2 += ($cnpj{$i} * $k);
		
			if ($i < 12){
				$soma1 += ($cnpj{$i} * $j);
			}
			
			$k--;
			$j--;
		}
		
		$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
		$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
		
		return (($cnpj{12} == $digito1) and ($cnpj{13} == $digito2));
	}
	
	public function cep( $check ){
       
        $string = html_entity_decode( array_pop( $check ) );
        return ereg( "([0-9]{5})[-]([0-9]{3})", $string );
    }
   
    public function phone( $check ){
       
        $string = html_entity_decode( array_pop( $check ) );
        return ereg( "[(]([0-9]{2})[)]([0-9]{4})[-]([0-9]{4})", $string );
    }
    
	public function file( $check ){
       
		$file = array_pop( $check );
        if( $file[ 'error' ] != 0 ) return false;
        else return true;
    }
    
	public function float( $check ){
       
        $string = html_entity_decode( array_pop( $check ) );
        return preg_match( "/(^\d*\,?\d*[1-9]+\d*$)|(^[1-9]+\d*\,\d*$)/", $string );
    }
    
	public function digits( $check ){
		
		return ctype_digit( array_pop( $check ) );
	}

	public function alphaNumeric( $check ){
		
		return ctype_alnum( array_pop( $check ) );
	}

	/*------------------------------------------
     * Miscellaneous Functions
     *-----------------------------------------*/

	protected function formatDate( $field ){
		
		if( isset( $this->data[ $this->name ][ $field ] ) )
			$this->data[ $this->name ][ $field ] = substr( $this->data[ $this->name ][ $field ], 6 ) ."-". substr( $this->data[ $this->name ][ $field ], 3, 2 ) ."-". substr( $this->data[ $this->name ][ $field ], 0, 2 );
	}
	
	protected function fixNewLine( $field, $replace = "" ){
		
		if( isset( $this->data[ $this->name ][ $field ] ) )
			$this->data[ $this->name ][ $field ] = str_replace( "\\n", $replace, $this->data[ $this->name ][ $field ] );
	}
	
}