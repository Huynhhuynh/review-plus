<?php 
/**
 * Hooks 
 */

add_action( 'comment_form', 'rp_comment_form' );

add_action( 'save_post', function( $post_ID, $post, $update ) {
  
}, 20, 3 );