<?php 
/**
 * Hooks 
 */
add_filter( 'comments_template', function( $template ) {
  return REVIEW_PLUS_DIR . '/templates/comment.php';
} );

// add_action( 'comment_form_after', 'rp_comment_form' );

add_action( 'save_post', function( $post_ID, $post, $update ) {
  
}, 20, 3 );