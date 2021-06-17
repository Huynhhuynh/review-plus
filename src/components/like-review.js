import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function likeReview(props) {
  const thisprop = props
  const likeReview = useReviewPlus();
  const like_number = 0;


  return (
    <>
        {
          likeReview.reviewLike &&
          likeReview.reviewLike.length > 0 &&

          likeReview.reviewLike.map( data => {
            if(data.id_review==thisprop.id_review ) {
              return <div className="number-action">{data.like}</div>
            }
          } )
        }
    </>
  )
}
