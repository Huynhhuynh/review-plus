<?php 
/**
 * Ajax 
 */

function rp_ajax_get_all_review_type() {
  wp_send_json( [
    [
      'label' => 'Type 1',
      'support_post_type' => [ 'post' ],
      'enable' => true,
      'rating_fields' => [
        [
          'name' => 'Field 1',
          'slug' => 'field-1',
          'max_point' => 5,
          'default_point' => 0,
          'rating_icon' => 'star'
        ]
      ]
    ],
    [
      'label' => 'Type 2',
      'support_post_type' => [ 'post' ],
      'enable' => true,
      'rating_fields' => [
        [
          'name' => 'Field 2',
          'slug' => 'field-2',
          'max_point' => 5,
          'default_point' => 0,
          'rating_icon' => 'star'
        ]
      ]
    ],
  ] );
}

add_action( 'wp_ajax_rp_ajax_get_all_review_type', 'rp_ajax_get_all_review_type' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_review_type', 'rp_ajax_get_all_review_type' );