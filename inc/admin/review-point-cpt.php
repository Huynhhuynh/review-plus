<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
/**
 * Review entries custom post type
 */

function rp_register_entrie_point_cpt() {

  $labels = [
    'name'                  => _x( 'Point Entries', 'Post type general name', 'review-plus' ),
    'singular_name'         => _x( 'Point Entries', 'Post type singular name', 'review-plus' ),
    'menu_name'             => _x( 'Point Entries', 'Admin Menu text', 'review-plus' ),
    'name_admin_bar'        => _x( 'Point Entries', 'Add New on Toolbar', 'review-plus' ),
    'add_new'               => __( 'Add New', 'review-plus' ),
    'add_new_item'          => __( 'Add New Point Entries', 'review-plus' ),
    'new_item'              => __( 'New Review Entries', 'review-plus' ),
    'edit_item'             => __( 'Edit Point Entries', 'review-plus' ),
    'view_item'             => __( 'View Point Entries', 'review-plus' ),
    'all_items'             => __( 'All Point Entries', 'review-plus' ),
    'search_items'          => __( 'Search Point Entries', 'review-plus' ),
    'parent_item_colon'     => __( 'Parent Point Entries:', 'review-plus' ),
    'not_found'             => __( 'No Point Entries found.', 'review-plus' ),
    'not_found_in_trash'    => __( 'No Point Entries found in Trash.', 'review-plus' ),
    'featured_image'        => _x( 'Review Point Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'archives'              => _x( 'Point Entries archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'review-plus' ),
    'insert_into_item'      => _x( 'Insert into Point Entries', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'review-plus' ),
    'uploaded_to_this_item' => _x( 'Uploaded to this Point Entries', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'review-plus' ),
    'filter_items_list'     => _x( 'Filter Point Entries list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'review-plus' ),
    'items_list_navigation' => _x( 'Point Entries list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'review-plus' ),
    'items_list'            => _x( 'Point Entries list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'review-plus' ),
  ];

  $args = [
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'query_var'          => true,
    'rewrite'            => [ 'slug' => 'point-entries' ],
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_icon'          => 'dashicons-star-filled',
    'supports'           => [ 'title' ],
  ];

  register_post_type( 'point-entries', $args );
}

add_action( 'init', 'rp_register_entrie_point_cpt' );

function rp_review_point_register_meta_fields() {

  $fields = apply_filters( 'review-plus/review-entry-meta-fields', [

    Field::make( 'select', 'point_type_entrie', __( 'Point Type', 'review-plus' ) )
    ->add_options( array(
        'travelpoint' => 'Travel',
        'sessionpoint' => 'Session',
        'likeentrie' => 'Like',
        'dislikeentrie'=>'Dislike'
    ) ),
    Field::make( 'text', 'author_action_entrie', __( 'Author ID', 'review-plus' ) ),
    Field::make( 'text', 'review_post_id', __( 'Review ID', 'review-plus' ) ),
    Field::make( 'text', 'post_id', __( 'Post ID', 'review-plus' ) ),
    Field::make( 'text', 'review_user_id', __( 'User Review ID', 'review-plus' ) ),
    Field::make( 'text', 'point_number_entrie', __( 'Point', 'review-plus' ) ),
  ] );

  Container::make( 'post_meta', __( 'Point Entry', 'review-plus' ) )
    ->where( 'post_type', '=', 'point-entries' )
    ->add_fields( $fields );
}

add_action( 'carbon_fields_register_fields', 'rp_review_point_register_meta_fields' );
