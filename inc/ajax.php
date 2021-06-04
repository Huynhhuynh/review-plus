<?php 
/**
 * Ajax 
 */

function rp_ajax_get_all_review_design() {
  wp_send_json( rp_get_review_design( 'all' ) );
}

add_action( 'wp_ajax_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );

function rp_ajax_get_all_post_type() {
  return wp_send_json( rp_get_all_post_type() );
}

add_action( 'wp_ajax_rp_ajax_get_all_post_type', 'rp_ajax_get_all_post_type' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_post_type', 'rp_ajax_get_all_post_type' );

function rp_ajax_new_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  
  $designID = rp_new_review_design( $postData[ 'designData' ] );
  wp_send_json( [
    'success' => true,
    'ID' => $designID,
  ] );
}

add_action( 'wp_ajax_rp_ajax_new_design', 'rp_ajax_new_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_new_design', 'rp_ajax_new_design' );

function rp_ajax_delete_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  rp_delete_review_design( $postData[ 'designID' ] );
  wp_send_json( [
    'success' => true,
  ] );
}

add_action( 'wp_ajax_rp_ajax_delete_design', 'rp_ajax_delete_design' );
add_action( 'wp_ajax_priv_rp_ajax_delete_design', 'rp_ajax_delete_design' );

function rp_ajax_update_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $designData = $postData[ 'designData' ];

  rp_update_review_design_meta_fields( $designData[ 'id' ], $designData );
  wp_send_json( [
    'success' => true
  ] );
}

add_action( 'wp_ajax_rp_ajax_update_design', 'rp_ajax_update_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_update_design', 'rp_ajax_update_design' );

function rp_ajax_get_review_design_by_post_id() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $postID = $postData[ 'postID' ];
  $result = rp_get_review_design_by_post_type( get_post_type( $postID ) );
  $design = [];

  // Support Cats 
  if( $result ) {
    foreach( $result as $index => $item ) {
      if( $item[ 'support_category' ] && (count( $item[ 'support_category' ] ) > 0) ) {
        $_support_category = false;
        
        foreach( $item[ 'support_category' ] as $c_index => $c ) {
          if( rp_check_post_in_term( (int) $postID, $c[ 'tax' ], (int) $c[ 'term_id' ] ) ) {
            $_support_category = true;
            break;
          }
        }

        if( true == $_support_category )
          array_push( $design, $item );
      } else {
        array_push( $design, $item );
      }
    }
  }

  // Except Cats
  if( count( $design ) > 0 ) {
    foreach( $design as $_index => $_item ) {
      if( $_item[ 'except_category' ] && (count( $_item[ 'except_category' ] ) > 0) ) {
        foreach( $_item[ 'except_category' ] as $ex_c_index => $ex_c ) {
          if( rp_check_post_in_term( (int) $postID, $ex_c[ 'tax' ], (int) $ex_c[ 'term_id' ] ) ) {
            unset( $design[ $ex_c_index ] );
            // $design = array_slice( $design, $ex_c_index, 1 );
            break;
          }
        }
      }
    }
  }

  wp_send_json( [
    'success' => true,
    'data' => array_values( $design ),
  ] );
}

add_action( 'wp_ajax_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );

function rp_ajax_post_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $reviewData = $postData[ 'reviewData' ];
  $review_id = rp_post_review( $reviewData );

  wp_send_json( [
    'success' => true,
    'review_id' => $review_id
  ] );
}

add_action( 'wp_ajax_rp_ajax_post_review', 'rp_ajax_post_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_review', 'rp_ajax_post_review' );

function rp_ajax_get_all_group_tax_per_post_types() {
  $all_post_types = rp_build_options_public_post_types();
  $result = rp_group_tax_per_post_types( array_keys( $all_post_types ) );
  
  wp_send_json( $result );
}

add_action( 'wp_ajax_rp_ajax_get_all_group_tax_per_post_types', 'rp_ajax_get_all_group_tax_per_post_types' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_group_tax_per_post_types', 'rp_ajax_get_all_group_tax_per_post_types' );