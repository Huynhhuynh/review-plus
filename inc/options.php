<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
/**
 * Options 
 */

function rp_register_general_options() {

  $group_fields = [
    'general' => [
      'label' => __( 'General Settings', 'review-plus' ),
      'fields' => [
        
      ] ],
    'styling' => [
      'label' => __( 'Styling Settings', 'review-plus' ),
      'fields' => [

      ]
    ]
  ];

  $group_fields = apply_filters( 'review-plus/global_options', $group_fields );

  $options = Container::make( 'theme_options', __( 'Settings', 'review-plus' ) )
    ->set_page_file( 'review-plus-options' )
    ->set_page_parent( 'edit.php?post_type=review-entries' );

  foreach( $group_fields as $key => $item ) {
    $options->add_tab( $item[ 'label' ], $item[ 'fields' ] );
  }

  apply_filters( 'review-plus/options', $options );
}

add_action( 'carbon_fields_register_fields', 'rp_register_general_options' );