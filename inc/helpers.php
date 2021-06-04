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
      return rp_get_review_design_by_id( $item->ID );
    }, $result );
  } else {
    return rp_get_review_design_by_id( (int) $id );
  }
}

/**
 * 
 */
function rp_get_review_design_by_id( $post_id ) {
  $result = get_post( (int) $post_id ); 
  if( !$result ) return false;

  $support_post_type = carbon_get_post_meta( $result->ID, 'support_post_type' );
  $rating_fields = carbon_get_post_meta( $result->ID, 'rating_fields' );

  return [
    'id' => $result->ID,
    'label' => $result->post_title,
    'description' => $result->post_content,
    'support_post_type' => $support_post_type ? $support_post_type : [],
    'support_category' => carbon_get_post_meta( $result->ID, 'support_category' ), 
    'except_category' => carbon_get_post_meta( $result->ID, 'except_category' ), 
    'theme' => carbon_get_post_meta( $result->ID, 'theme' ),
    'theme_color' => carbon_get_post_meta( $result->ID, 'theme_color' ),
    'enable' => carbon_get_post_meta( $result->ID, 'enable' ),
    'rating_fields' => $rating_fields ? $rating_fields : [],
  ];
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
    'support_category',
    'except_category',
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
function rp_comment_form() {
  $post_id = get_the_ID();
  if( empty( $post_id ) ) return;
  ?>
  <div class="review-plus-container" data-post-id="<?php echo $post_id ?>">
    <!-- Content by React JS -->
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
      'designId' => 'design_id',
      'parent' => 'parent',
      'comment' => 'comment_content',
      'user_id' => 'user_id',
      'name' => 'name',
      'email' => 'email',
      'url' => 'url',
      'user_ip' => 'user_ip',
    ];

    $review_data[ 'user_ip' ] = rp_get_client_ip();

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
        'terms' => get_terms( $tax->name, [
          'hide_empty' => false
        ] ),
      ] );
    } 
  }

  return $result;
}

/**
 * 
 */
function rp_count_response( $review_post_id = 0 ) {
  $result = get_posts( [
    'post_type' => 'review-entries',
    'post_status' => 'publish',
    'meta_query' => [
      [
        'key' => 'review_post_id',
        'value' => $review_post_id,
        'compare' => '=',
      ]
    ],
  ] );

  return count( $result );
} 

function rp_get_client_ip() {
  $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
  else if(getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if(getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
  else if(getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if(getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
  else if(getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
  else
    $ipaddress = 'UNKNOWN';

  return $ipaddress;
}

/**
 * Check post in term 
 * 
 * @param Int $post_id 
 * @param String $tax 
 * @param Int $term_id 
 * 
 * @return Boolean
 */
function rp_check_post_in_term( $post_id, $tax, $term_id ) {
  $terms = wp_get_post_terms( (int) $post_id, $tax );
  $filter = array_filter( $terms, function( $term ) use ( $term_id ) {
    return ($term->term_id == (int) $term_id);
  } );
  
  return (count( $filter ) > 0) ? true : false;
}

add_action( 'init', function() {
  // var_dump( rp_get_review_design( 32 ) );
  if( isset( $_GET[ 'dev' ] ) ) {
    echo '<pre>';
    echo get_post_meta( 101, '__@_field-1', true );
    // echo rp_check_post_in_term( 93, 'category', 5 );
    // print_r( carbon_get_post_meta( 32, 'support_category' ) );
    // print_r( rp_get_review_design( 32 ) );
    // print_r( rp_group_tax_per_post_types( [ 'post' ] ) );
    echo '</pre>';
    // rp_get_review_design_by_post_type( 'post' );
    // carbon_set_post_meta( 86, 'rating_json_field', serialize( [ 'a' => a, 'b' => 'b' ] ) );
  }
} );
