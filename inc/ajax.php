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