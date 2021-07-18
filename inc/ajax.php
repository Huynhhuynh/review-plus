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
  // echo '<pre>';
  // print_r($reviewData);
  // echo '</pre>';
  // die();
  spam_reviews_form($reviewData);
}

add_action( 'wp_ajax_rp_ajax_get_review', 'rp_ajax_get_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_review', 'rp_ajax_get_review' );

function rp_ajax_get_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $postID = $postData[ 'idPost' ];
  $data_reviews =  get_review_content_by_id_post($postID);
  $data_rating = get_review_rating_by_id_post($postID);
  ob_start();
  wp_send_json( [
    'success' => true,
    'data' => array_values( $data_reviews ),
    'rating' =>$data_rating
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
  rp_new_point_review_session ( $postData['reviewID'],$postData['postId'] );
  rp_new_like_point_review($postData['reviewID'],$postData['postId']);
  $like_reviews =  get_like_review($postData['postId']);
  $like_point_user_login = get_like_dislike_user_current($postData['postId'],'likeentrie');
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review,
    'likeupdate' => $like_reviews,
    'likeuserlogin'=>$like_point_user_login
  ] );


}



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


add_action( 'wp_ajax_rp_ajax_post_dis_like_review', 'rp_ajax_post_dis_like_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_dis_like_review', 'rp_ajax_post_dis_like_review' );

function rp_ajax_post_dis_like_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $id_review = $postData['reviewID'];
  $id_post = $postData['idPost'];
  rp_minus_point_review_session ( $id_post,$id_review );
  rp_minus_point_review_travel( $id_post,$id_review );
  rp_new_dis_like_point_review( $id_post,$id_review );
  $dislike = get_dis_like_review( $id_post );
  $dislike_point_user_login = get_like_dislike_user_current($id_post,'dislikeentrie');
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review,
    'dislikeupdate'=>$dislike,
    'dislikeuserlogin'=>$dislike_point_user_login
  ] );


}



add_action( 'wp_ajax_rp_ajax_post_liked_review', 'rp_ajax_post_liked_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_liked_review', 'rp_ajax_post_liked_review' );

function rp_ajax_post_liked_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $id_review = $postData['reviewID'];
  update_status_like($postData['reviewID'],$postData['idPost']);
  update_status_sesion_type($postData['reviewID'],$postData['idPost']);
  $like_reviews =  get_like_review($postData['idPost']);
  $like_point_user_login = get_like_dislike_user_current($postData['idPost'],'likeentrie');
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review,
    'likeupdate' => $like_reviews,
    'likeuserlogin'=>$like_point_user_login
  ] );

}

add_action( 'wp_ajax_rp_ajax_post_disliked_review', 'rp_ajax_post_disliked_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_disliked_review', 'rp_ajax_post_disliked_review' );

function rp_ajax_post_disliked_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  update_status_dislike($postData['reviewID'],$postData['idPost']);
  update_status_sesion_dislike_type($postData['reviewID'],$postData['idPost']);
  update_status_travel_dislike_type($postData['reviewID'],$postData['idPost']);
  $dislike = get_dis_like_review($postData['idPost']);
  $dislike_point_user_login = get_like_dislike_user_current($postData['idPost'],'dislikeentrie');
  wp_send_json( [
    'success' => true,
    'data' =>  $id_review,
    'dislikeupdate'=>$dislike,
    'dislikeuserlogin'=>$dislike_point_user_login
  ] );
}


add_action( 'wp_ajax_rp_ajax_get_point_review', 'rp_ajax_get_point_review' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_point_review', 'rp_ajax_get_point_review' );


function rp_ajax_get_point_review() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $postID = $postData[ 'idPost' ];
  $point_like_user_login = get_like_dislike_user_current($postID,'likeentrie');
  $point_dislike_user_login = get_like_dislike_user_current($postID,'dislikeentrie');
  wp_send_json( [
    'success' => true,
    'like'=>array_values($point_like_user_login),
    'dislike'=>array_values( $point_dislike_user_login )
  ] );
}


function rp_ajax_post_data_reply() {
  $json = file_get_contents('php://input');
  $postData = json_decode( $json, true );
  $dataReply = $postData['dataReply'];
  reply_comment_review($dataReply);
  wp_send_json( [
    'success' => true
  ] );
}

add_action( 'wp_ajax_rp_ajax_post_data_reply', 'rp_ajax_post_data_reply' );
add_action( 'wp_ajax_nopriv_rp_ajax_post_data_reply', 'rp_ajax_post_data_reply' );

function rp_ajax_get_score_user() {
  $data_score = get_score_user();
  wp_send_json( [
    'success' => true,
    'score'=>$data_score
  ] );
}

add_action( 'wp_ajax_rp_ajax_get_score_user', 'rp_ajax_get_score_user' );
add_action( 'wp_ajax_nopriv_rp_ajax_get_score_user', 'rp_ajax_get_score_user' );
