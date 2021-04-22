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
    body: JSON.stringify( variables )
  } )

  return response.json();
}

/**
 * 
 * @returns 
 */
export async function getAllReviewType() {
  return await Request( 'rp_ajax_get_all_review_type', {} )
}