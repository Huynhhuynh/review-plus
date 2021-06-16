<?php
/**
 * Ajax
 */

function rp_ajax_get_all_review_design() {
  wp_send_json( rp_get_review_design( 'all' ) );
}

add_action( 'wp_ajax_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_review_design', 'rp_ajax_get_all_review_design' );

function rp_ajax_get_all_post_type() {
  return wp_send_json( rp_get_all_post_type() );
}

add_action( 'wp_ajax_rp_ajax_get_all_post_type', 'rp_ajax_get_all_post_type' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_post_type', 'rp_ajax_get_all_post_type' );

function rp_ajax_new_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $designID = rp_new_review_design( $postData[ 'designData' ] );
  wp_send_json( [
    'success' => true,
    'ID' => $designID,
  ] );
}

add_action( 'wp_ajax_rp_ajax_new_design', 'rp_ajax_new_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_new_design', 'rp_ajax_new_design' );

function rp_ajax_delete_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  rp_delete_review_design( $postData[ 'designID' ] );
  wp_send_json( [
    'success' => true,
  ] );
}

add_action( 'wp_ajax_rp_ajax_delete_design', 'rp_ajax_delete_design' );
add_action( 'wp_ajax_priv_rp_ajax_delete_design', 'rp_ajax_delete_design' );

function rp_ajax_update_design() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $designData = $postData[ 'designData' ];
  // echo '<pre>';
  // print_r($designData);
  // echo '</pre>';
  rp_update_review_design_meta_fields( $designData[ 'id' ], $designData );

  wp_send_json( [
    'success' => true
  ] );
}

add_action( 'wp_ajax_rp_ajax_update_design', 'rp_ajax_update_design' );
add_action( 'wp_ajax_nopriv_rp_ajax_update_design', 'rp_ajax_update_design' );

function rp_ajax_get_review_design_by_post_id() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $postID = $postData[ 'postID' ];
  $result = rp_get_review_design_by_post_type( get_post_type( $postID ) );
  $design = [];
  // Support Cats
  if( $result ) {
    foreach( $result as $index => $item ) {
      if( $item[ 'support_category' ] && (count( $item[ 'support_category' ] ) > 0) ) {
        $_support_category = false;

        foreach( $item[ 'support_category' ] as $c_index => $c ) {
          if( rp_check_post_in_term( (int) $postID, $c[ 'tax' ], (int) $c[ 'term_id' ] ) ) {
            $_support_category = true;
            break;
          }
        }

        if( true == $_support_category )
          array_push( $design, $item );
      } else {
        array_push( $design, $item );
      }
    }
  }

  // Except Cats
  if( count( $design ) > 0 ) {
    foreach( $design as $_index => $_item ) {
      if( $_item[ 'except_category' ] && (count( $_item[ 'except_category' ] ) > 0) ) {
        foreach( $_item[ 'except_category' ] as $ex_c_index => $ex_c ) {
          if( rp_check_post_in_term( (int) $postID, $ex_c[ 'tax' ], (int) $ex_c[ 'term_id' ] ) ) {
            unset( $design[ $ex_c_index ] );
            // $design = array_slice( $design, $ex_c_index, 1 );
            break;
          }
        }
      }
    }
  }

  wp_send_json( [
    'success' => true,
    'data' => array_values( $design ),
  ] );
}

add_action( 'wp_ajax_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_review_design_by_post_id', 'rp_ajax_get_review_design_by_post_id' );

function rp_ajax_post_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $reviewData = $postData[ 'reviewData' ];

  spam_reviews_form($reviewData);


}


add_action( 'wp_ajax_rp_ajax_get_review', 'rp_ajax_get_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_review', 'rp_ajax_get_review' );


function rp_ajax_get_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );

  $postID = $postData[ 'idPost' ];
  // echo $postID;
  // die();
  $data_reviews =  get_review_content_by_id_post($postID);
  wp_send_json( [
    'success' => true,
    'data' => array_values( $data_reviews ),
  ] );
}


add_action( 'wp_ajax_rp_ajax_post_review', 'rp_ajax_post_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_review', 'rp_ajax_post_review' );

function rp_ajax_get_all_group_tax_per_post_types() {
  $all_post_types = rp_build_options_public_post_types();
  $result = rp_group_tax_per_post_types( array_keys( $all_post_types ) );

  wp_send_json( $result );
}

add_action( 'wp_ajax_rp_ajax_get_all_group_tax_per_post_types', 'rp_ajax_get_all_group_tax_per_post_types' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_all_group_tax_per_post_types', 'rp_ajax_get_all_group_tax_per_post_types' );


add_action( 'wp_ajax_rp_ajax_post_like_review', 'rp_ajax_post_like_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_like_review', 'rp_ajax_post_like_review' );

function rp_ajax_post_like_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $id_review = $postData['reviewID'];
  rp_new_point_review_session ( $point_data = [],$id_review );
  rp_new_like_point_review($point_data = [],$id_review);
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review
  ] );


}


add_action( 'wp_ajax_rp_ajax_post_dis_like_review', 'rp_ajax_post_dis_like_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_dis_like_review', 'rp_ajax_post_dis_like_review' );



add_action( 'wp_ajax_rp_ajax_get_like_review', 'rp_ajax_get_like_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_like_review', 'rp_ajax_get_like_review' );


function rp_ajax_get_like_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $postID = $postData[ 'idPost' ];
  $like_reviews =  get_like_review($postID);
  wp_send_json( [
    'success' => true,
    'data' => array_values($like_reviews),
  ] );
}


add_action( 'wp_ajax_rp_ajax_get_dis_like_review', 'rp_ajax_get_dis_like_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_dis_like_review', 'rp_ajax_get_dis_like_review' );


function rp_ajax_get_dis_like_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $postID = $postData[ 'idPost' ];
  $dislike_reviews =  get_dis_like_review($postID);
  wp_send_json( [
    'success' => true,
    'data' => array_values($dislike_reviews),
  ] );
}



function rp_ajax_post_dis_like_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $id_review = $postData['reviewID'];
  rp_minus_point_review_session ( $point_data = [],$id_review );
  rp_minus_point_review_travel( $point_data = [],$id_review );
  rp_new_dis_like_point_review($point_data = [],$id_review);
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review
  ] );


}
