<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
/**
 * Review design custom post type
 *
 */

function rp_register_review_design_cpt() {
  $labels = [
    'name'                  => _x( 'Review Design', 'Post type general name', 'review-plus' ),
    'singular_name'         => _x( 'Review Design', 'Post type singular name', 'review-plus' ),
    'menu_name'             => _x( 'Review Design', 'Admin Menu text', 'review-plus' ),
    'name_admin_bar'        => _x( 'Review Design', 'Add New on Toolbar', 'review-plus' ),
    'add_new'               => __( 'Add New', 'review-plus' ),
    'add_new_item'          => __( 'Add New Review Design', 'review-plus' ),
    'new_item'              => __( 'New Review Design', 'review-plus' ),
    'edit_item'             => __( 'Edit Review Design', 'review-plus' ),
    'view_item'             => __( 'View Review Design', 'review-plus' ),
    'all_items'             => __( 'All Review Design', 'review-plus' ),
    'search_items'          => __( 'Search Review Design', 'review-plus' ),
    'parent_item_colon'     => __( 'Parent Review Design:', 'review-plus' ),
    'not_found'             => __( 'No Review Design found.', 'review-plus' ),
    'not_found_in_trash'    => __( 'No Review Design found in Trash.', 'review-plus' ),
    'featured_image'        => _x( 'Review Design Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'review-plus' ),
    'archives'              => _x( 'Review Design archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'review-plus' ),
    'insert_into_item'      => _x( 'Insert into Review Design', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'review-plus' ),
    'uploaded_to_this_item' => _x( 'Uploaded to this Review Design', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'review-plus' ),
    'filter_items_list'     => _x( 'Filter Review Design list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'review-plus' ),
    'items_list_navigation' => _x( 'Review Design list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'review-plus' ),
    'items_list'            => _x( 'Review Design list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'review-plus' ),
  ];

  $args = [
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'query_var'          => true,
    'rewrite'            => [ 'slug' => 'review-design' ],
    'capability_type'    => 'post',
    'has_archive'        => false,
    'show_in_menu'       => 'edit.php?post_type=review-entries',
    'supports'           => [ 'title', 'editor', 'author' ],
  ];

  register_post_type( 'review-design', $args );
}

add_action( 'init', 'rp_register_review_design_cpt' );

/**
 * Review design register meta fields
 *
 * @return void
 */
function rp_review_design_register_meta_fields() {

  $fields = apply_filters( 'review-plus/review-design-meta-fields', [
    Field::make( 'checkbox', 'enable', __( 'Enable' ) )
      ->set_default_value( false ),
    Field::make( 'checkbox', 'login_required', __( 'Login' ) )
      ->set_default_value( true ),
    Field::make( 'multiselect', 'support_post_type', __( 'Support Post Types', 'review-plus' ) )
      ->add_options( 'rp_build_options_public_post_types' ),
    Field::make( 'complex', 'support_category', __( 'Support Category', 'review-plus' ) )
      ->add_fields( [
        Field::make( 'text', 'group', __( 'Group', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'tax', __( 'Tax Name', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'term_label', __( 'Term Label', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'term_id', __( 'Term ID', 'review-plus' ) )
          ->set_width( 25 ),
      ] ),
    Field::make( 'complex', 'except_category', __( 'Except Category', 'review-plus' ) )
      ->add_fields( [
        Field::make( 'text', 'group', __( 'Group', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'tax', __( 'Tax Name', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'term_label', __( 'Term Label', 'review-plus' ) )
          ->set_width( 25 ),
        Field::make( 'text', 'term_id', __( 'Term ID', 'review-plus' ) )
          ->set_width( 25 ),
      ] ),
    Field::make( 'select', 'theme', __( 'Theme', 'review-plus' ) )
      ->set_options( array(
        'default' => __( 'Default', 'review-plus' ),
      ) ),
    Field::make( 'color', 'theme_color', __( 'Theme Color', 'review-plus' ) )
      ->set_palette( [ '#3f51b5', '#42b983', '#f8c555', '#cc99cd', '#f08d49' ] )
      ->set_default_value( '#3f51b5' ),
    Field::make( 'complex', 'rating_fields', __( 'Rating Fields', 'review-plus' ) )
      ->add_fields( [
        Field::make( 'text', 'id', __( 'ID', 'review-plus' ) )
          // ->set_attribute( 'readOnly', true )
          ->set_width( 30 ),
        Field::make( 'text', 'name', __( 'Name', 'review-plus' ) )
          ->set_width( 30 ),
        Field::make( 'text', 'slug', __( 'Slug', 'review-plus' ) )
          ->set_width( 30 ),
        Field::make( 'text', 'max_point', __( 'Max Point', 'review-plus' ) )
          ->set_attribute( 'type', 'number' )
          ->set_default_value( 5 )
          ->set_width( 30 ),
        Field::make( 'text', 'default_point', __( 'Default Point', 'review-plus' ) )
          ->set_attribute( 'type', 'number' )
          ->set_default_value( 0 )
          ->set_width( 30 ),
        Field::make( 'select', 'rating_icon', __( 'Rating Icon', 'review-plus' ) )
          ->add_options( [
            'start' => __( 'Start', 'review-plus' )
          ] )
          ->set_default_value( 'start' )
          ->set_width( 30 ),
      ] )
    ->set_header_template( '
      <% if (name) { %>
        <%- name %>
      <% } %>' ),
  ] );

  Container::make( 'post_meta', __( 'Review Design', 'review-plus' ) )
    ->where( 'post_type', '=', 'review-design' )
    ->add_fields( $fields );
}

add_action( 'carbon_fields_register_fields', 'rp_review_design_register_meta_fields' );
