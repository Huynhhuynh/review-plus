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