import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewdata from '../components/review-content'


export default function ReviewContentApp( props ) {
  const dataReview = useReviewPlus()
  console.log('ok',dataReview.reviewContent);
  return (
    < >
      {
        dataReview.reviewContent &&
        dataReview.reviewContent.length > 0 &&

        dataReview.reviewContent.map( data => {
          if(data.parent == 0) {
            return <Reviewdata parent={data.parent} name_author={ data.name } comment_rv={ data.comment }  id_review = { data.id_reviews } url_avatar= {data.url_avatar}  date_comment={data.date_coment} />
          }

        } )

      }

    </>
  )

}
