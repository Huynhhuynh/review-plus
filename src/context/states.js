/**
 * States
 */

import React, { createContext, useContext, useState, useEffect } from 'react'
import { getReviewDesignByPostID, postReview, getReview, postLikeReview, postDisLikeReview, getLikeReview, getDisLikeReview } from '../admin/lib/api'
const ReviewPlusContext = createContext()

function ReviewPlusProvider( { children, postId } ) {
  const [ reviewDesign, setReviewDesign ] = useState( [] )

  const [ reviewContent, setReviewContent ] = useState( [] )

  const [ reviewLike, setReviewLike ] = useState( [] )
  const [ reviewDisLike, setReviewDisLike ] = useState( [] )

  useEffect( async () => {
    const Result = await getReviewDesignByPostID( postId )

    if( Result.success == true ){
      setReviewDesign( Result.data )
    }else{
      return
    }

  }, [] )

  useEffect( async () => {
    const Result_review = await getReview(postId)
    if(Result_review.success==true) {
      setReviewContent(Result_review.data)
    }else{
      return
    }

  }, [] )

  useEffect( async () => {
    const Result_like_review = await getLikeReview(postId)
    if(Result_like_review.success==true) {
      setReviewLike(Result_like_review.data)
    }else{
      return
    }

  }, [] )

  useEffect( async () => {
    const Result_dis_like_review = await getDisLikeReview(postId)
    if(Result_dis_like_review.success==true) {
      setReviewDisLike(Result_dis_like_review.data)
    }else{
      return
    }

  }, [] )


  const submitReview = async ( reviewData ) => {
    const result = await postReview( reviewData )
    return result
  }
  const submitLike = async ( id_review ) => {
    const result = await postLikeReview ( id_review )
    return result
  }

  const submitDisLike = async ( id_review ) => {
    const result = await postDisLikeReview ( id_review )
    return result
  }
  const value = {
    postId,
    reviewDesign,
    submitReview,
    reviewContent,
    submitLike,
    submitDisLike,
    reviewLike,
    reviewDisLike
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
