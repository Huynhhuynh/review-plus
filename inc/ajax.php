<?php 
/**
 * Ajax 
 */

function rp_ajax_get_all_review_design() {
  wp_send_json( [
    [
      'label' => 'Review Plus Demo for Post',
      'description' => 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...',
      'support_post_type' => [ 'post' ],
      'theme' => 'default',
      'theme_color' => 'black',
      'enable' => true,
      'rating_fields' => [
        [
          'id' => uniqid(),
          'name' => 'Field 1',
          'slug' => 'field-1',
          'max_point' => 5,
          'default_point' => 0,
          'rating_icon' => 'star'
        ],
        [
          'id' => uniqid(),
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

add_action( 'wp_ajax_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );