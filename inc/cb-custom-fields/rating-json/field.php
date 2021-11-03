<?php
namespace Carbon_Field_RatingJson;
use Carbon_Fields\Field\Field;

class RatingJson_Field extends Field {

	public function set_value_from_input( $input ) {
		$value = $input[ $this->get_name() ];
		$this->set_value( serialize( $value ) );
		return $this;
		// echo '<pre>'; print_r( [$input, $this->get_name(), $value] ); echo '</pre>';
		// die;
	}

	/**
	 *
	 */
	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
		$value_set = $this->get_value();

		$field_data = array_merge( $field_data, [
			'value' =>  maybe_unserialize( maybe_unserialize( $field_data[ 'value' ] ) )
		] );

		return $field_data;
	}
}
