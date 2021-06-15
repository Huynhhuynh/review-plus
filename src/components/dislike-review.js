import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function dislikeReview(props) {
  const thisprop = props
  const dislikeReview = useReviewPlus();
  
  return (
    <>
        {
          dislikeReview.reviewDisLike &&
          dislikeReview.reviewDisLike.length > 0 &&

          dislikeReview.reviewDisLike.map( data => {
            if(data.id_review==thisprop.id_review ) {
              return <div>{data.dislike}</div>
            }
          } )
        }
    </>
  )
}
