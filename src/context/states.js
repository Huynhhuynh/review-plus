/**
 * States
 */

import React, { createContext, useContext, useState, useEffect } from 'react'
import {
  getReviewDesignByPostID,
  postReview,
  getReview,
  postLikeReview,
  postDisLikeReview,
  getLikeReview,
  getDisLikeReview,
  postLikedReview,
  postDisLikedReview,
  getPointReview,
  postReply,
  getScoreUser
} from '../admin/lib/api'
const ReviewPlusContext = createContext()

function ReviewPlusProvider( { children, postId } ) {
  const [ reviewDesign, setReviewDesign ] = useState( [] )
  const [ reviewContent, setReviewContent ] = useState( [] )
  const [ pointLikeReview, setPointLikeReview ] = useState( [] )
  const [ pointDisLikeReview, setPointDisLikeReview ] = useState( [] )
  const [ reviewLike, setReviewLike ] = useState( [] )
  const [ reviewDisLike, setReviewDisLike ] = useState( [] )
  const [ scoreUser, setScoreUser ] = useState( [] )
  const [rating, setRating] = useState([])

  useEffect( async () => {
    const Result = await getReviewDesignByPostID( postId )
    if( Result.success == true ){
      setReviewDesign( Result.data )
    }else{
      return
    }

  }, [] )

  // useEffect(async () => {
  //   const Score = await getScoreUser();
  //   if(Score.success){
  //     setScoreUser(Score.score)
  //   }
  // })

  useEffect( async () => {
    const Result_review = await getReview(postId)
    if(Result_review.success==true) {
      setReviewContent(Result_review.data)
      setRating(Result_review.rating)
    }else{
      return
    }

  }, [] )


  useEffect( async () => {
    const Result_point = await getPointReview(postId)
    if(Result_point.success==true) {
      setPointLikeReview(Result_point.like)
      setPointDisLikeReview(Result_point.dislike)
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

  const submitReply = async ( replyData ) => {
    const result = await postReply( replyData )
    return result
  }
  const submitLike = async ( id_review ) => {
    const result = await postLikeReview ( id_review,postId )
    setReviewLike( result.likeupdate )
    setPointLikeReview( result.likeuserlogin )
    return result
  }

  const submitLiked = async ( id_review,postId ) => {
    const result = await postLikedReview ( id_review,postId )
    setReviewLike( result.likeupdate )
    setPointLikeReview( result.likeuserlogin )
    return result
  }

  const submitDisLiked = async ( id_review,postId ) => {
    const result = await postDisLikedReview ( id_review,postId )
    setReviewDisLike(result.dislikeupdate)
    setPointDisLikeReview(result.dislikeuserlogin)
    return result
  }

  const submitDisLike = async ( id_review,postId ) => {
    const result = await postDisLikeReview ( id_review,postId )
    setReviewDisLike(result.dislikeupdate)
    setPointDisLikeReview(result.dislikeuserlogin)
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
    reviewDisLike,
    submitLiked,
    submitDisLiked,
    pointLikeReview,
    pointDisLikeReview,
    submitReply,
    rating
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
