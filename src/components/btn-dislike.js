import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function btndislikeReview(props) {
  const thisprop = props
  const { submitDisLike } = useReviewPlus()
  const { submitDisLiked } = useReviewPlus()
  const { postId }  = useReviewPlus();
  const data_user_current_dislike = useReviewPlus()
  const [ showbt, setShowbt ] = useState( false )
  const array_id_reviews = data_user_current_dislike.reviewContent[2]
  const result_array = array_id_reviews.map(function (x) {
    return parseInt(x, 10)
  });

  useEffect(() => {
    if(result_array.includes(thisprop.id_review)){
      setShowbt(true)
    }

  });

  const handleClickDisLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitDisLike( id_review,postId )
  }

  const handleClickDisLiked =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitDisLiked( id_review,postId )
  }


  return (
    <>

      {
        (showbt==true)&&
        <button  className="disliked" onClick={handleClickDisLiked}>
          DisLike
        </button>
      }

      {
        (showbt==false)&&
        <button className="dislike" onClick={handleClickDisLike}>
          DisLike
        </button>
      }
    </>
  )
}
