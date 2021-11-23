<?php
/**
 * Static
 */
// REVIEW_PLUS_VER
function rp_enqueue_scripts() {
  wp_enqueue_style( 'review-plus', REVIEW_PLUS_URI . '/dist/css/review-plus.frontend.css', false, '3.0.2' );
  wp_enqueue_script( 'review-plus', REVIEW_PLUS_URI . '/dist/review-plus.frontend.bundle.js', ['jquery'], '3.0.2', true );

  wp_localize_script( 'review-plus', 'PHP_DATA', [
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'user_logged_in' => is_user_logged_in() ? 'yes' : 'no',
    'lang' => [],
  ] );
}

add_action( 'wp_enqueue_scripts', 'rp_enqueue_scripts' );

wp_enqueue_style( 'review-plus-backend', REVIEW_PLUS_URI . '/dist/css/review-plus.backend.css', false, '3.0.2' );

function rp_admin_enqueue_scripts() {
  if( ! isset( $_GET[ 'page' ] ) || $_GET[ 'page' ] != 'review-design-settings-panel' )
    return;

  wp_enqueue_script( 'review-plus-backend', REVIEW_PLUS_URI . '/dist/review-plus.backend.bundle.js', ['jquery', 'carbon-fields-core'], '3.0.2', true );

  wp_localize_script( 'review-plus-backend', 'PHP_DATA', [
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'post_types' => rp_get_all_post_type(),
    'lang' => [],
  ] );
}

add_action( 'admin_enqueue_scripts', 'rp_admin_enqueue_scripts' );

function rp_cb_field_enqueue_scripts() {
  wp_enqueue_script( 'review-plus-cb-field', REVIEW_PLUS_URI . '/dist/review-plus.cbFields.bundle.js', ['jquery', 'carbon-fields-core'], '3.0.2', true );
}

add_action( 'admin_enqueue_scripts', 'rp_cb_field_enqueue_scripts' );
