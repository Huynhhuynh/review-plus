<?php 
/**
 * Review type settings panel
 */

function rp_review_type_settings_panel() {
  add_submenu_page(
    'review-plus-options',
    __( 'Review Type', 'review-plus' ),
    __( 'Review Type', 'review-plus' ),
    'manage_options',
    'review-type-settings-panel',
    'rp_review_type_settings_panel_callback' );
}

add_action( 'admin_menu', 'rp_review_type_settings_panel', 99 );

function rp_review_type_settings_panel_callback() {
  ?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php _e( 'Review Type Settings', 'review-plus' ) ?></h2>
    <div id="rp-review-type-root"></div>
  </div>
  <?php
}