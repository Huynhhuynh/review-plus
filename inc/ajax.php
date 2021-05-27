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

  wp_send_json( [
    'success' => true,
    'data' => $result,
  ] );
}

add_action( 'wp_ajax_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );

function rp_ajax_post_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $reviewData = $postData[ 'reviewData' ];
  wp_send_json( $reviewData );
}

add_action( 'wp_ajax_rp_ajax_post_review', 'rp_ajax_post_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_review', 'rp_ajax_post_review' );