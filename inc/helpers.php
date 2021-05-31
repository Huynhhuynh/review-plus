<?php 
/**
 * Helpers 
 */

function rp_get_all_post_type() {
  $result = get_post_types( 
  [ 'public' => true ], 
  'objects', 
  'and' );

  $filter_data = array_map( function( $p ) {
    return [
      'label' => $p->label,
      'name' => $p->name,
    ];
  }, $result );

  return array_values( $filter_data );
}

/**
 * @return Array
 */
function rp_build_options_public_post_types() {
  $options = [];
  $result = rp_get_all_post_type();

  foreach( $result as $index => $item ) {
    $options[ $item[ 'name' ] ] = $item[ 'label' ];
  }

  return $options;
}

/**
 * Get review design 
 * 
 * @param Any $id
 */
function rp_get_review_design( $id = 'all' ) {
  if( $id == 'all' ) {
    $result = get_posts( [
      'numberposts' => -1,
      'post_type' => 'review-design',
      'post_status' => 'publish'
    ] );

    if( !$result || count( $result ) == 0 ) return [];

    return array_map( function( $item ) {
      $support_post_type = carbon_get_post_meta( $item->ID, 'support_post_type' );
      $rating_fields = carbon_get_post_meta( $item->ID, 'rating_fields' );
      
      return [
        'id' => $item->ID,
        'label' => $item->post_title,
        'description' => $item->post_content,
        'support_post_type' => $support_post_type ? $support_post_type : [],
        'post_tax' => [], // rp_group_tax_per_post_types( ( $support_post_type ? $support_post_type : [] ) ),
        'theme' => carbon_get_post_meta( $item->ID, 'theme' ),
        'theme_color' => carbon_get_post_meta( $item->ID, 'theme_color' ),
        'enable' => carbon_get_post_meta( $item->ID, 'enable' ),
        'rating_fields' => $rating_fields ? $rating_fields : [],
      ];
    }, $result );
  } else {
    $result = get_post( (int) $id );
    if( !$result ) return false;

    $support_post_type = carbon_get_post_meta( $result->ID, 'support_post_type' );
    $rating_fields = carbon_get_post_meta( $result->ID, 'rating_fields' );
    return [
      'id' => $result->ID,
      'label' => $result->post_title,
      'description' => $result->post_content,
      'support_post_type' => $support_post_type ? $support_post_type : [],
      'post_tax' => [], // rp_group_tax_per_post_types( ( $support_post_type ? $support_post_type : [] ) ),
      'theme' => carbon_get_post_meta( $result->ID, 'theme' ),
      'theme_color' => carbon_get_post_meta( $result->ID, 'theme_color' ),
      'enable' => carbon_get_post_meta( $result->ID, 'enable' ),
      'rating_fields' => $rating_fields ? $rating_fields : [],
    ];
  }
}

/**
 * Update review design meta fields 
 * 
 * @param Int $post_id
 * @param Array $designData
 */
function rp_update_review_design_meta_fields( $post_id = 0, $designData = [] ) {

  // WP Fields
  $postUpdateFields = [ 
    'label' => 'post_title', 
    'description' => 'post_content' 
  ];
  $updateArgs = [];
  foreach( array_keys( $postUpdateFields ) as $field ) {
    if( ! isset( $designData[ $field ] ) ) continue;
    $updateArgs[ $postUpdateFields[ $field ] ] = $designData[ $field ];
  }

  if( count( $updateArgs ) > 0 ) {
    $updateArgs[ 'ID' ] = $post_id;
    wp_update_post( $updateArgs );
  }

  /**
   * Update meta fields
   */
  $postUpdateMetaFields = [ 
    'support_post_type', 
    'theme', 
    'theme_color', 
    'enable', 
    'rating_fields' 
  ];
  
  foreach( $postUpdateMetaFields as $field ) {
    if( ! isset( $designData[ $field ] ) ) continue;
    carbon_set_post_meta( $post_id, $field, $designData[ $field ] );
  }

  return $post_id;
}

/**
 * New Review Design 
 * 
 * @param Array $designData
 */
function rp_new_review_design( $designData = [] ) {
  $ID = wp_insert_post( [
    'post_type'     => 'review-design',
    'post_title'    => wp_strip_all_tags( $designData['label'] ),
    'post_content'  => $designData['description'],
    'post_status'   => 'publish'
  ] );

  rp_update_review_design_meta_fields( $ID, $designData );
  return $ID;
}

/**
 * Delete review design 
 * 
 * @param Int $ID
 */
function rp_delete_review_design( $ID ) {
  return wp_delete_post( $ID, true );
}

/**
 * Comment form 
 * 
 * @since Int $post_id
 */
function rp_comment_form( $post_id = 0 ) {
  ?>
  <div class="review-plus-container" data-post-id="<?php echo $post_id ?>">
    <!-- Content by React -->
  </div> <!-- .review-plus-container -->
  <?php 
}

function rp_query_review_design( $post_type = '' ) {
  $args = [
    'numberposts' => -1,
    'post_type' => 'review-design',
    'meta_query' => [
      [
        'key' => 'support_post_type',
        'value' => $post_type,
        'compare' => 'IN',
      ]
    ],
  ];

  $designs = get_posts( $args );
  if( count( $designs ) <= 0 ) return;

  return array_map( function( $item ) {
    return rp_get_review_design( $item->ID );
  }, $designs );
}

function rp_get_review_design_by_post_type( $post_type = '' ) {
  return rp_query_review_design( $post_type );
}

/**
 * Update review entry 
 * 
 * @param Int $post_id 
 * @param Array $review_data 
 * 
 * @return Int 
 */
function rp_update_review( $post_id = 0, $review_data = [] ) {
  $wp_post_fields = [];

  if( isset( $review_data[ 'name' ] ) ) {
    $wp_post_fields[ 'post_title' ] = esc_html( $review_data[ 'name' ] );
  }

  if( count( $wp_post_fields ) > 0 ) {
    $wp_post_fields[ 'ID' ] = $post_id;
    wp_update_post( $wp_post_fields );
  }

  {
    /**
     * Update meta fields 
     */
    $meta_fields = [ 
      'ratings' => 'rating_json_field',
      'postId' => 'review_post_id',
      'parent' => 'parent',
      'comment' => 'comment_content',
      'user_id' => 'user_id',
      'name' => 'name',
      'email' => 'email',
      'url' => 'url',
    ];

    foreach( array_keys( $meta_fields ) as $key ) {
      if( ! isset( $review_data[ $key ] ) ) continue;

      if( $meta_fields[ $key ] == 'rating_json_field' ) {
        $review_data[ $key ] = serialize( $review_data[ $key ] );
      }

      carbon_set_post_meta( 
        $post_id, 
        $meta_fields[ $key ], 
        ( isset( $review_data[ $key ] ) ? $review_data[ $key ] : '' )
      );
    }
  }

  return $post_id;
}

function rp_new_review( $review_data = [] ) {

  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    
    $review_data[ 'user_id' ] = $current_user->ID;
    $review_data[ 'name' ] = esc_html( $current_user->display_name );
    $review_data[ 'email' ] = $current_user->user_email;
    $review_data[ 'url' ] = $current_user->user_url;
  }
  
  $id = wp_insert_post( [
    'post_type' => 'review-entries',
    'post_title' => ( isset( $review_data[ 'name' ] ) ? $review_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );

  rp_update_review( $id, $review_data );
  return $id;
}

/**
 * Post review 
 * 
 * @param Array $review_data
 */
function rp_post_review( $review_data = [] ) {
  return rp_new_review( $review_data );
}

/**
 * 
 */
function rp_group_tax_per_post_types( $post_types = [] ) {
  if( ! $post_types || count( $post_types ) <= 0 ) return [];
  $result = [];

  foreach( $post_types as $index => $post_type ) {
    $taxs = get_object_taxonomies( $post_type, 'objects' );
    if( ! $taxs || count( $taxs ) <= 0 ) continue;
    
    $result[ $post_type ] = [];
    foreach( $taxs as $_index => $tax ) {
      array_push( $result[ $post_type ], [
        'tax_label' => $tax->label,
        'tax_name' => $tax->name,
        'terms' => get_terms( $tax->name ),
      ] );
    } 
  }

  return $result;
}

add_action( 'init', function() {
  // var_dump( rp_get_review_design( 32 ) );
  if( isset( $_GET[ 'dev' ] ) ) {
    echo '<pre>';
    print_r( rp_group_tax_per_post_types( [ 'post' ] ) );
    echo '</pre>';
    // rp_get_review_design_by_post_type( 'post' );
    // carbon_set_post_meta( 86, 'rating_json_field', serialize( [ 'a' => a, 'b' => 'b' ] ) );
  }
} );
