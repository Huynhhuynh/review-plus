<?php 
use Carbon_Fields\Container;
use Carbon_Fields\Field;
/**
 * Review entries custom post type
 */

function rp_register_review_entries_cpt() {
  
  $labels = [
    'name'                  => _x( 'Review Entries', 'Post type general name', 'review-plus' ),
    'singular_name'         => _x( 'Review Entries', 'Post type singular name', 'review-plus' ),
    'menu_name'             => _x( 'Review Entries', 'Admin Menu text', 'review-plus' ),
    'name_admin_bar'        => _x( 'Review Entries', 'Add New on Toolbar', 'review-plus' ),
    'add_new'               => __( 'Add New', 'review-plus' ),
    'add_new_item'          => __( 'Add New Review Entries', 'review-plus' ),
    'new_item'              => __( 'New Review Entries', 'review-plus' ),
    'edit_item'             => __( 'Edit Review Entries', 'review-plus' ),
    'view_item'             => __( 'View Review Entries', 'review-plus' ),
    'all_items'             => __( 'All Review Entries', 'review-plus' ),
    'search_items'          => __( 'Search Review Entries', 'review-plus' ),
    'parent_item_colon'     => __( 'Parent Review Entries:', 'review-plus' ),
    'not_found'             => __( 'No Review Entries found.', 'review-plus' ),
    'not_found_in_trash'    => __( 'No Review Entries found in Trash.', 'review-plus' ),
    'featured_image'        => _x( 'Review Entries Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'archives'              => _x( 'Review Entries archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'review-plus' ),
    'insert_into_item'      => _x( 'Insert into Review Entries', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'review-plus' ),
    'uploaded_to_this_item' => _x( 'Uploaded to this Review Entries', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'review-plus' ),
    'filter_items_list'     => _x( 'Filter Review Entries list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'review-plus' ),
    'items_list_navigation' => _x( 'Review Entries list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'review-plus' ),
    'items_list'            => _x( 'Review Entries list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'review-plus' ),
  ];

  $args = [
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'query_var'          => true,
    'rewrite'            => [ 'slug' => 'review-entries' ],
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_icon'          => 'dashicons-star-filled',
    'supports'           => [ 'title' ],
  ];

  register_post_type( 'review-entries', $args );
}

add_action( 'init', 'rp_register_review_entries_cpt' );

function rp_review_entry_register_meta_fields() {

  $fields = apply_filters( 'review-plus/review-entry-meta-fields', [
    Field::make( 'ratingjson', 'test_rating_json_field', __( 'Rating Json Fields', 'review-plus' ) )
      ->set_default_value( 'default value...!' ),
    Field::make( 'text', 'review_post_id', __( 'Review Post ID', 'review-plus' ) ),
    Field::make( 'rich_text', 'comment_content', __( 'Content', 'review-plus' ) ),
    Field::make( 'separator', '__separator', __( 'Author', 'review-plus' ) ),
    Field::make( 'text', 'name', __( 'Name', 'review-plus' ) ),
    Field::make( 'text', 'email', __( 'Email', 'review-plus' ) ),
    Field::make( 'text', 'url', __( 'URL', 'review-plus' ) ),
  ] );

  Container::make( 'post_meta', __( 'Review Entry', 'review-plus' ) )
    ->where( 'post_type', '=', 'review-entries' )
    ->add_fields( $fields );
}

add_action( 'carbon_fields_register_fields', 'rp_review_entry_register_meta_fields' );