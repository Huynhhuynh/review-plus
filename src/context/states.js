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

function ReviewPlusProvider( { children, postId, userID } ) {
  const [ reviewDesign, setReviewDesign ] = useState( [] )
  const [ reviewContent, setReviewContent ] = useState( [] )
  const [ pointLikeReview, setPointLikeReview ] = useState( [] )
  const [ pointDisLikeReview, setPointDisLikeReview ] = useState( [] )
  const [ reviewLike, setReviewLike ] = useState( [] )
  const [ reviewDisLike, setReviewDisLike ] = useState( [] )
  const [ scoreUser, setScoreUser ] = useState( [] )
  const [rating, setRating] = useState([])
  const [ pointTravel, setPointTravel ] = useState([])
  const [ dataCommentEdit, setdataCommentEdit ] = useState('')

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
      if(Result_point.point_session_travel!=0){
        setPointTravel(Result_point.point_session_travel)
      }else{
        setPointTravel(0)
      }

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
  const submitEditReview = async ( idReviewp,datacomment,idformReview ) => {

    const new_state = reviewContent;
    const new_reviewDesign = reviewDesign;
    var data_rating_custom =[];
    if(new_state){
      for(let i in new_state) {
        if(new_state[i].id_reviews==idReviewp) {
          new_state[i].user_id_review='hide-custom';
          data_rating_custom = new_state[i].rating_review
        }
      }
    }

    if(new_reviewDesign) {
      for(let i in new_reviewDesign) {
        if(new_reviewDesign[i].id==Number(idformReview)){
          for(let j in data_rating_custom ) {
            new_reviewDesign[i].rating_fields[j].default_point = data_rating_custom[j];
          }
        }
      }
    }

    setReviewContent(new_state);
    setdataCommentEdit(datacomment);


  }

  const submitEditReviewHideForm = async ( idReviewp ) => {

    const new_state = reviewContent;
    if(new_state){
      for(let i in new_state) {
        if(new_state[i].id_reviews==idReviewp) {
          new_state[i].user_id_review='1';
        }
      }
    }

    setReviewContent(new_state);


  }

  const submitReply = async ( replyData ) => {
    const result = await postReply( replyData )
    return result
  }
  const submitLike = async ( id_review ) => {
    const result = await postLikeReview ( id_review,postId )
    if(result.likeupdate){
      setReviewLike( result.likeupdate )
    }
    if(result.likeuserlogin) {
      setPointLikeReview( result.likeuserlogin )
    }

    return result
  }

  const submitLiked = async ( id_review,postId ) => {
    const result = await postLikedReview ( id_review,postId )
    if(result.likeupdate ){
      setReviewLike( result.likeupdate )
    }

    if(result.likeuserlogin) {
      setPointLikeReview( result.likeuserlogin )
    }

    return result
  }

  const submitDisLiked = async ( id_review,postId ) => {
    const result = await postDisLikedReview ( id_review,postId )

    if(result.dislikeupdate){
      setReviewDisLike(result.dislikeupdate)
    }

    if(result.dislikeuserlogin){
      setPointDisLikeReview(result.dislikeuserlogin)
    }

    return result
  }

  const submitDisLike = async ( id_review,postId ) => {
    const result = await postDisLikeReview ( id_review,postId )

    if(result.dislikeupdate){
      setReviewDisLike(result.dislikeupdate)
    }

    if(result.dislikeuserlogin){
      setPointDisLikeReview(result.dislikeuserlogin)
    }

    return result
  }


  const value = {
    dataCommentEdit,
    postId,
    userID,
    reviewDesign,
    submitReview,
    submitEditReview,
    submitEditReviewHideForm,
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
    rating,
    pointTravel
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
