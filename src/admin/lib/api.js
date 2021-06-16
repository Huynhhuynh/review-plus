/**
 * API
 */

/**
 *
 * @param {*} action
 * @param {*} variables
 * @param {*} method
 * @returns
 */
export async function Request( action = null, variables = {}, method = 'POST' ) {
  const response = await fetch( `${ PHP_DATA.ajax_url }?action=${ action }`, {
    method,
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cache-Control': 'no-cache',
    },
    body: JSON.stringify( variables )
  } )

  return response.json();
}

/**
 *
 * @returns
 */
export async function getAllReviewDesign() {
  return await Request( 'rp_ajax_get_all_review_design', {

  } )
}

export async function newDesign( designData ) {
  return await Request( 'rp_ajax_new_design', {
    designData: designData
  } )
}

export async function deleteDesign( ID ) {
  return await Request( 'rp_ajax_delete_design', {
    designID: ID
  } )
}

export async function updateDesign( designData ) {
  return await Request( 'rp_ajax_update_design', {
    designData: designData
  } )
}

export async function getReviewDesignByPostID( postID ) {
  return await Request( 'rp_ajax_get_review_design_by_post_id', {
    postID
  } )
}

export async function postReview( reviewData ) {
  return await Request( 'rp_ajax_post_review', {
    reviewData
  } )
}

export async function postLikeReview( reviewID,postId ) {
  return await Request( 'rp_ajax_post_like_review', {
    reviewID,postId
  } )
}

export async function postLikedReview( reviewID,idPost ) {
  return await Request( 'rp_ajax_post_liked_review', {
    reviewID,idPost
  } )
}

export async function postDisLikedReview( reviewID,idPost ) {
  return await Request( 'rp_ajax_post_disliked_review', {
    reviewID,idPost
  } )
}

export async function postDisLikeReview( reviewID,idPost ) {
  return await Request( 'rp_ajax_post_dis_like_review', {
    reviewID,idPost
  } )
}


export async function getReview( idPost ) {
  return await Request( 'rp_ajax_get_review', {
    idPost
  } )
}

export async function getLikeReview( idPost ) {
  return await Request( 'rp_ajax_get_like_review', {
    idPost
  } )
}

export async function getDisLikeReview( idPost ) {
  return await Request( 'rp_ajax_get_dis_like_review', {
    idPost
  } )
}


export async function getAllGroupPostTax() {
  return await Request( 'rp_ajax_get_all_group_tax_per_post_types', {} )
}
