<?php 
namespace Carbon_Field_RatingJson;
use Carbon_Fields\Field\Field;

class RatingJson_Field extends Field {

	/**
	 * Enqueue scripts and styles in admin.
	 * Called once per field type.
	 *
	 * @static
	 * @access public
	 *
	 * @return void
	 */
	public static function admin_enqueue_scripts() {
		
	}

	/**
	 * 
	 */
	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
		$value_set = $this->get_value();
		$field_data = array_merge( $field_data, [] );

		return $field_data;
	}
}