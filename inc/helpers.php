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
      $support_post_type = carbon_get_post_meta( $item->ID, 'support_post_type' );
      $rating_fields = carbon_get_post_meta( $item->ID, 'rating_fields' );

      return [
        'id' => $item->ID,
        'label' => $item->post_title,
        'description' => $item->post_content,
        'support_post_type' => $support_post_type ? $support_post_type : [],
        'theme' => carbon_get_post_meta( $item->ID, 'theme' ),
        'theme_color' => carbon_get_post_meta( $item->ID, 'theme_color' ),
        'enable' => carbon_get_post_meta( $item->ID, 'enable' ),
        'rating_fields' => $rating_fields ? $rating_fields : [],
      ];
    }, $result );
  } else {
    $result = get_post( (int) $id );
    if( !$result ) return false;

    $support_post_type = carbon_get_post_meta( $result->ID, 'support_post_type' );
    $rating_fields = carbon_get_post_meta( $result->ID, 'rating_fields' );
    return [
      'id' => $result->ID,
      'label' => $result->post_title,
      'description' => $result->post_content,
      'support_post_type' => $support_post_type ? $support_post_type : [],
      'theme' => carbon_get_post_meta( $result->ID, 'theme' ),
      'theme_color' => carbon_get_post_meta( $result->ID, 'theme_color' ),
      'enable' => carbon_get_post_meta( $result->ID, 'enable' ),
      'rating_fields' => $rating_fields ? $rating_fields : [],
    ];
  }
}

/**
 * Update review design meta fields 
 * 
 * @param Int $post_id
 * @param Array $designData
 */
function rp_update_review_design_meta_fields( $post_id = 0, $designData = [] ) {

  // WP Fields
  $postUpdateFields = [ 
    'label' => 'post_title', 
    'description' => 'post_content' 
  ];
  $updateArgs = [];
  foreach( array_keys( $postUpdateFields ) as $field ) {
    if( ! isset( $designData[ $field ] ) ) continue;
    $updateArgs[ $postUpdateFields[ $field ] ] = $designData[ $field ];
  }

  if( count( $updateArgs ) > 0 ) {
    $updateArgs[ 'ID' ] = $post_id;
    wp_update_post( $updateArgs );
  }

  /**
   * Update meta fields
   */
  $postUpdateMetaFields = [ 
    'support_post_type', 
    'theme', 
    'theme_color', 
    'enable', 
    'rating_fields' 
  ];
  
  foreach( $postUpdateMetaFields as $field ) {
    if( ! isset( $designData[ $field ] ) ) continue;
    carbon_set_post_meta( $post_id, $field, $designData[ $field ] );
  }

  return $post_id;
}

/**
 * New Review Design 
 * 
 * @param Array $designData
 */
function rp_new_review_design( $designData = [] ) {
  $ID = wp_insert_post( [
    'post_type'     => 'review-design',
    'post_title'    => wp_strip_all_tags( $designData['label'] ),
    'post_content'  => $designData['description'],
    'post_status'   => 'publish'
  ] );

  rp_update_review_design_meta_fields( $ID, $designData );
  return $ID;
}

/**
 * Delete review design 
 * 
 * @param Int $ID
 */
function rp_delete_review_design( $ID ) {
  return wp_delete_post( $ID, true );
}

add_action( 'init', function() {
  // var_dump( rp_get_review_design( 32 ) );
} );