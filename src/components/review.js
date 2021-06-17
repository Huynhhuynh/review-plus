import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewdata from '../components/review-content'


export default function ReviewContentApp( props ) {
  const dataReview = useReviewPlus()
  return (
    < >
      {
        dataReview.reviewContent[0] &&
        dataReview.reviewContent[0].length > 0 &&

        dataReview.reviewContent[0].map( data => {
          return <Reviewdata name_author={ data.name } comment_rv={ data.comment }  id_review = { data.id_reviews } url_avatar= {data.url_avatar}  date_comment={data.date_coment} />
        } )

      }

    </>
  )

}
