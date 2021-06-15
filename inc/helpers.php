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

function rp_new_point_review_session ( $point_data = [],$id_review ) {

  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    $point_data[ 'user_id' ] = $current_user->ID;
    $point_data[ 'name' ] = esc_html( $current_user->display_name );
    $point_data[ 'email' ] = $current_user->user_email;
    $point_data[ 'url' ] = $current_user->user_url;
  }

  $user_id_reviews = carbon_get_post_meta($id_review,'user_id');
  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );


  carbon_set_post_meta( $id, 'point_type_entrie', 'sessionpoint');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $user_id_reviews );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', 1 );

  return $id;
}

function rp_minus_point_travel_dis_like ( $id_review ) {
  if( is_user_logged_in() ) {
        

  }
}

function rp_minus_point_review_session ( $point_data = [],$id_review ) {

  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    $point_data[ 'user_id' ] = $current_user->ID;
    $point_data[ 'name' ] = esc_html( $current_user->display_name );
    $point_data[ 'email' ] = $current_user->user_email;
    $point_data[ 'url' ] = $current_user->user_url;
  }

  $user_id_reviews = carbon_get_post_meta($id_review,'user_id');
  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );


  carbon_set_post_meta( $id, 'point_type_entrie', 'sessionpoint');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $user_id_reviews );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', -1 );

  return $id;
}

function rp_minus_point_review_travel ( $point_data = [],$id_review ) {

  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    $point_data[ 'user_id' ] = $current_user->ID;
    $point_data[ 'name' ] = esc_html( $current_user->display_name );
    $point_data[ 'email' ] = $current_user->user_email;
    $point_data[ 'url' ] = $current_user->user_url;
  }

  $user_id_reviews = carbon_get_post_meta($id_review,'user_id');
  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );


  carbon_set_post_meta( $id, 'point_type_entrie', 'travelpoint');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $user_id_reviews );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', -1 );

  return $id;
}


function rp_new_like_point_review ( $point_data = [],$id_review ) {
  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    $point_data[ 'user_id' ] = $current_user->ID;
    $point_data[ 'name' ] = esc_html( $current_user->display_name );
    $point_data[ 'email' ] = $current_user->user_email;
    $point_data[ 'url' ] = $current_user->user_url;
  }

  $user_id_reviews = carbon_get_post_meta($id_review,'user_id');
  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );


  carbon_set_post_meta( $id, 'point_type_entrie', 'likeentrie');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $user_id_reviews );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', 0 );

  return $id;
}

function rp_new_dis_like_point_review ( $point_data = [],$id_review ) {
  if( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $name = esc_html( $current_user->display_name );
    $point_data[ 'user_id' ] = $current_user->ID;
    $point_data[ 'name' ] = esc_html( $current_user->display_name );
    $point_data[ 'email' ] = $current_user->user_email;
    $point_data[ 'url' ] = $current_user->user_url;
  }

  $user_id_reviews = carbon_get_post_meta($id_review,'user_id');
  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );


  carbon_set_post_meta( $id, 'point_type_entrie', 'dislikeentrie');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $user_id_reviews );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', 0 );

  return $id;
}

function rp_new_point_review ( $point_data = [],$id_review ) {

  $id = wp_insert_post( [
    'post_type' => 'point-entries',
    'post_title' => ( isset( $point_data[ 'name' ] ) ? $point_data[ 'name' ] : '' ),
    'post_status' => 'publish'
  ] );

  $id_post = carbon_get_post_meta($id_review,'review_post_id');
  carbon_set_post_meta( $id, 'point_type_entrie','travelpoint');
  carbon_set_post_meta( $id,'author_action_entrie', $point_data[ 'user_id' ] );
  carbon_set_post_meta( $id,'review_post_id', $id_review );
  carbon_set_post_meta( $id,'review_user_id', $point_data['user_id_reviews'] );
  carbon_set_post_meta( $id,'post_id', $id_post );
  carbon_set_post_meta( $id,'point_number_entrie', 1 );

  return $id;

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

// function rp_point_review( $review_point = [],$id_review ) {
//   return rp_new_point_review( $review_point,$id_review );
// }

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

function rp_check_user_reviews_form ($id_user,$id_post,$id_form_reviews) {
    $args = array(
       'post_type' => 'review-entries',
       'post_status' => 'publish',
       'posts_per_page' => -1,
   );

   $passed = true;
   $entries = new WP_Query( $args );
    while ( $entries->have_posts() ) : $entries->the_post();
        $id_entrie = get_the_ID();
        $id_user_entri = carbon_get_post_meta( $id_entrie, 'user_id' );
        $review_post_id = carbon_get_post_meta( $id_entrie, 'review_post_id' );
        $review_design_id = carbon_get_post_meta( $id_entrie, 'design_id' );
        if( $id_user == $id_user_entri && $id_post == $review_post_id && $id_form_reviews) {
            $passed = false;
            break;
        }else {
            $passed = true;
        }
    endwhile;

    wp_reset_postdata();
    return $passed;

}

function rp_check_ip_reviews_form ($ip_user,$id_post,$id_form_reviews) {
    $args = array(
       'post_type' => 'review-entries',
       'post_status' => 'publish',
       'posts_per_page' => -1,
   );

   $passed = true;
   $entries = new WP_Query( $args );
    while ( $entries->have_posts() ) : $entries->the_post();
        $id_entrie = get_the_ID();
        $id_ip_entrie = carbon_get_post_meta( $id_entrie, 'user_ip' );
        $review_post_id = carbon_get_post_meta( $id_entrie, 'review_post_id' );
        $review_design_id = carbon_get_post_meta( $id_entrie, 'design_id' );
        if( $ip_user == $id_ip_entrie && $id_post == $review_post_id && $id_form_reviews) {
            $passed = false;
            break;
        }else {
            $passed = true;
        }
    endwhile;

    wp_reset_postdata();
    return $passed;

}

function spam_reviews_form ($Data) {
    $reviewData = $Data;
    $pointData = [];
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        $name = esc_html( $current_user->display_name );
        $id_user_current = get_current_user_id();
        $id_post = $reviewData['postId'];
        $id_design = $reviewData['designId'];
        $point_data[ 'user_id' ] = $id_user_current;
        $point_data[ 'name' ] = esc_html( $current_user->display_name );
        $passed_reviews = rp_check_user_reviews_form($id_user_current,$id_post,$id_design);
        if($passed_reviews){
            $review_id = rp_post_review( $reviewData );
            $user_id_reviews = carbon_get_post_meta($review_id,'user_id');
            $point_data['user_id_reviews'] = $user_id_reviews;
            rp_new_point_review($point_data,$review_id);
            wp_send_json( [
              'success' => true,
              'review_id' => $review_id
            ] );
        } else {
            wp_send_json( [
              'success' => false,
              'message' =>'You have already submitted this review',
              'review_id' => $review_id,
            ] );
        }
    } else {
        $id_ip_user_current = rp_get_client_ip();
        $id_post = $reviewData['postId'];
        $id_design = $reviewData['designId'];
        $passed_reviews = rp_check_ip_reviews_form($id_ip_user_current,$id_post,$id_design);
        if( $passed_reviews ) {
            $review_id = rp_post_review( $reviewData );

            wp_send_json( [
              'success' => true,
              'review_id' => $review_id
            ] );
        } else {
            wp_send_json( [
              'success' => false,
              'message' =>'You have already submitted this review',
            ] );
        }
    }
}


function get_review_content_by_id_post ( $id_post ) {
  $data_reviews = [];
  $data_points = [];
  $data_return = [];
  $id_user_current = get_current_user_id();
  $args = array(
    'post_type' => 'review-entries',
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'meta_query' => [
      [
        'key' => 'review_post_id',
        'value' => $id_post,
        'compare' => '=',
      ]
    ]
  );
  $reviews = new WP_Query( $args );
  while ( $reviews->have_posts() ) : $reviews->the_post();
    $id_post_reviews = get_the_ID();
    $author_name_reviews = carbon_get_post_meta($id_post_reviews,'name');
    $content_reviews = carbon_get_post_meta($id_post_reviews,'comment_content');
    array_push($data_reviews,[
      'id_reviews'=>$id_post_reviews,
      'name'=>$author_name_reviews,
      'comment'=>$content_reviews
    ]);

  endwhile;
  wp_reset_postdata();
  array_push($data_return,$data_reviews);
  $arg_points = array(
    'post_type' => 'point-entries',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => [
      'relation' => 'AND',
        [
          'key' => 'post_id',
          'value' => $id_post,
          'compare' => '=',
        ],
        [
          'key' => 'author_action_entrie',
          'value' => $id_user_current,
          'compare' => '=',
        ],
        [
          'key' => 'point_type_entrie',
          'value' => 'likeentrie',
          'compare' => 'LIKE',
        ],
    ]
  );


  $like = new WP_Query( $arg_points );
  while ( $like->have_posts() ) : $like->the_post();
    $id_point = get_the_ID();
    $id_revews = carbon_get_post_meta($id_point,'review_post_id');
    array_push($data_points,[
      'id_reviews '=>$id_revews,
    ]);

  endwhile;



  wp_reset_postdata();
  array_push($data_return,$data_points);
  return $data_return;

}



add_action( 'init', function() {
  // var_dump( rp_get_review_design( 32 ) );
  // var_dump(rp_check_user_reviews_form(1,8,11));
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
