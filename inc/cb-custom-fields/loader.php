<?php 
use Carbon_Fields\Carbon_Fields;
use Carbon_Field_RatingJson\RatingJson_Field;

define( 'Carbon_Field_RatingJson\\DIR', __DIR__ );

Carbon_Fields::extend( RatingJson_Field::class, function( $container ) {
	return new RatingJson_Field(
		$container['arguments']['type'],
		$container['arguments']['name'],
		$container['arguments']['label']
	);
} );