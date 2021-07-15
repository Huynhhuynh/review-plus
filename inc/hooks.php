<?php
/**
 * Hooks
 */
add_filter( 'comments_template', function( $template ) {
  return REVIEW_PLUS_DIR . '/templates/comment.php';
} );

// add_action( 'comment_form_after', 'rp_comment_form' );

// add_action( 'carbon_fields_post_meta_container_saved', function( $post_id ) {
add_filter( 'update_post_metadata', function( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
  if ( $meta_key != '_rating_json_field' ) return $check;

  $rating_fields = maybe_unserialize( maybe_unserialize( $meta_value ) );
  if( ! $rating_fields || count( $rating_fields ) <= 0 ) return $check;

  foreach( $rating_fields as $index => $r ) {
    $slug = '__@_' . $r[ 'slug' ];
    update_post_meta( $object_id, $slug, (int) $r[ 'rate' ] );
  }

  return $check;
}, 20, 5 );
