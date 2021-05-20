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

/**
 * @return Array
 */
function rp_build_options_public_post_types() {
  $options = [];
  $result = rp_get_all_post_type();

  foreach( $result as $index => $item ) {
    $options[ $item[ 'name' ] ] = $item[ 'label' ];
  }

  return $options;
}

/**
 * Get review design 
 * 
 * @param Any $id
 */
function rp_get_review_design( $id = 'all' ) {
  if( $id == 'all' ) {
    $result = get_posts( [
      'numberposts' => -1,
      'post_type' => 'review-design',
      'post_status' => 'publish'
    ] );

    if( !$result || count( $result ) == 0 ) return [];

    return array_map( function( $item ) {
      return [
        'id' => $item->ID,
        'label' => $item->post_title,
        'description' => $item->post_content,
        'support_post_type' => carbon_get_post_meta( $item->ID, 'support_post_type' ),
        'theme' => carbon_get_post_meta( $item->ID, 'theme' ),
        'theme_color' => carbon_get_post_meta( $item->ID, 'theme_color' ),
        'enable' => carbon_get_post_meta( $item->ID, 'enable' ),
        'rating_fields' => carbon_get_post_meta( $item->ID, 'rating_fields' ),
      ];
    }, $result );
  } else {
    $result = get_post( (int) $id );
    if( !$result ) return false;

    return [
      'id' => $result->ID,
      'label' => $result->post_title,
      'description' => $result->post_content,
      'support_post_type' => carbon_get_post_meta( $result->ID, 'support_post_type' ),
      'theme' => carbon_get_post_meta( $result->ID, 'theme' ),
      'theme_color' => carbon_get_post_meta( $result->ID, 'theme_color' ),
      'enable' => carbon_get_post_meta( $result->ID, 'enable' ),
      'rating_fields' => carbon_get_post_meta( $result->ID, 'rating_fields' ),
    ];
  }
}

add_action( 'init', function() {
  // var_dump( rp_get_review_design( 32 ) );
} );