<?php 
/**
 * Static 
 */

function rp_enqueue_scripts() {
  wp_enqueue_script( 'review-plus', REVIEW_PLUS_URI . '/dist/review-pus.frontend.bundle.js', 'jquery', REVIEW_PLUS_VER, true );

  wp_localize_script( 'review-plus', 'PHP_DATA', [
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'lang' => [],
  ] );
}

add_action( 'wp_enqueue_scripts', 'rp_enqueue_scripts' );

function rp_admin_enqueue_scripts() {
  wp_enqueue_script( 'review-plus-backend', REVIEW_PLUS_URI . '/dist/review-pus.backend.bundle.js', 'jquery', REVIEW_PLUS_VER, true );

  wp_localize_script( 'review-plus-backend', 'PHP_DATA', [
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'post_types' => rp_get_all_post_type(),
    'lang' => [],
  ] );
}

add_action( 'admin_enqueue_scripts', 'rp_admin_enqueue_scripts' );