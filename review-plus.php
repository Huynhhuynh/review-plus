<?php 
/*
Plugin Name: Review Plus
Plugin URI: https://becoding.dev/review-plus/
Description: Create reviews! Choose from Stars, etc.
Author: Beplus
Text Domain: review-plus
Domain Path: /languages
Version: 1.0.0
Author URI: https://becoding.dev/
*/

require_once( __DIR__ . '/vendor/autoload.php' );

{
  /**
   * Define
   */
  define( 'REVIEW_PLUS_VER', '1.0.0' );
  define( 'REVIEW_PLUS_URI', plugin_dir_url( __FILE__ ) );
  define( 'REVIEW_PLUS_DIR', plugin_dir_path( __FILE__ ) );
}

{
  /**
   * Include
   */
  require_once( REVIEW_PLUS_DIR . '/inc/static.php' );
  require_once( REVIEW_PLUS_DIR . '/inc/helpers.php' );
  require_once( REVIEW_PLUS_DIR . '/inc/hooks.php' );
  require_once( REVIEW_PLUS_DIR . '/inc/ajax.php' );
  require_once( REVIEW_PLUS_DIR . '/inc/options.php' );

  /**
   * Admin
   */
  require_once( REVIEW_PLUS_DIR . '/inc/admin/review-type-panel.php' );
}


function rp_crb_boot() {
  require_once( 'vendor/autoload.php' );
  \Carbon_Fields\Carbon_Fields::boot();
}

add_action( 'after_setup_theme', 'rp_crb_boot' );