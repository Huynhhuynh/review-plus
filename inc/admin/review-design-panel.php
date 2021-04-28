<?php 
/**
 * Review design settings panel
 */

function rp_review_design_settings_panel() {
  add_submenu_page(
    'review-plus-options',
    __( 'Review Design', 'review-plus' ),
    __( 'Review Design', 'review-plus' ),
    'manage_options',
    'review-design-settings-panel',
    'rp_review_design_settings_panel_callback' );
}

add_action( 'admin_menu', 'rp_review_design_settings_panel', 99 );

function rp_review_design_settings_panel_callback() {
  ?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php _e( 'Review Design', 'review-plus' ) ?></h2>
    <div id="rp-review-design-root"></div>
  </div>
  <?php
}