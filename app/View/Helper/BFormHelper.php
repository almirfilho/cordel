<?php

App::uses('AppHelper', 'View/Helper');
define('FORMS_REQUIRED_STRING', '<span class="req">*</span> ');

class BFormHelper extends FormHelper {

	/*----------------------------------------
	 * Constructor
	 ----------------------------------------*/

	function __construct(View $View, $settings = array()){
		parent::__construct($View, $settings);
	}

	/*----------------------------------------
	 * Methods
	 ----------------------------------------*/

	public function input( $fieldName, $options = array() ){

		$str = '<div class="control-group';
		
		$model = explode('.', $fieldName);
		$field = $model[1];
		$model = $model[0];
		$before = $after = null;

		if( !isset( $options[ 'required' ] ) )
			$options[ 'label' ] = FORMS_REQUIRED_STRING . $options[ 'label' ];

		if( !empty( $this->validationErrors[$model][$field] ) )
			$str .= ' error';

		elseif( !empty( $options[ 'help' ] ) )
			$after = "<span class=\"help-inline help\">{$options['help']}</span>";

		if( !empty( $options[ 'prepend' ] ) ){

			$before = "<div class=\"input-prepend\"><span class=\"add-on\">{$options['prepend']}</span>";
			$after .= '</div>';
		}

		$str .= '">';
		$str .= parent::label( $fieldName, $options[ 'label' ], array( 'class' => 'control-label' ) );
		unset( $options[ 'label' ] );
		$str .= parent::input( $fieldName, array_merge( array( 'label' => false, 'div' => 'controls', 'escape' => false, 'before' => $before, 'after' => $after, 'error' => array( 'attributes' => array( 'wrap' => 'span', 'class' => 'help-inline' ) ) ), $options ) );
		$str .= '</div>';
		return $str;
	}

}