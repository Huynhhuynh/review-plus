/**
 * States 
 */

import React, { createContext, useContext, useState, useEffect } from 'react'
import { getReviewDesignByPostID, postReview } from '../admin/lib/api'
const ReviewPlusContext = createContext()

function ReviewPlusProvider( { children, postId } ) {
  const [ reviewDesign, setReviewDesign ] = useState( [] )

  useEffect( async () => {
    const Result = await getReviewDesignByPostID( postId )
    if( Result.success != true ) return
    setReviewDesign( Result.data )
  }, [] )

  const submitReview = async ( reviewData ) => {
    const result = await postReview( reviewData )
    return result
  }

  const value = { 
    postId,
    reviewDesign,
    submitReview
  }

  return (
    <ReviewPlusContext.Provider value={ value }>
      { children }
    </ReviewPlusContext.Provider>
  )
}

function useReviewPlus() {
  return useContext( ReviewPlusContext )
}

export { ReviewPlusProvider, useReviewPlus }