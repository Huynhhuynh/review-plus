<?php 
/**
 * Helpers 
 */

function rp_get_all_post_type() {
  $result = get_post_types( 
  [ 'public' => true ], 
  'objects', 
  'and' );

  $filter_data = array_map( function( $p ) {
    return [
      'label' => $p->label,
      'name' => $p->name,
    ];
  }, $result );

  return array_values( $filter_data );
}