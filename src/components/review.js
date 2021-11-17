import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewdata from '../components/review-content'


export default function ReviewContentApp( props ) {
  const dataReview = useReviewPlus()
  const recursiveMenu = function (data, parent_id=0, sub=true) {

  }
  return (
    < >

    {
        PHP_DATA.user_logged_in == 'yes' &&
        <div className="ss-score-total">
          <span>Travel Session Score</span>
          <p>{
            dataReview.pointTravel
            }
          </p>
          <div className="raw-score">
            <span>Raw Score</span>
            <div className="icon-start">
              <img src="/wp-content/uploads/2021/11/star.png" />
              <img src="/wp-content/uploads/2021/11/star.png" />
              <img src="/wp-content/uploads/2021/11/star.png" />
              <img src="/wp-content/uploads/2021/11/star.png" />
              <img src="/wp-content/uploads/2021/11/star.png" />
            </div>
          </div>
        </div>
    }


    {
      PHP_DATA.user_logged_in != 'yes' &&
      <div className="ss-score-total">
        <span>Travel Session Score</span>
        <a href="#">Login or Register To See Personal Travel Score</a>
        <div className="raw-score">
          <span>Raw Score</span>
          <div className="icon-start">
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
          </div>
        </div>
      </div>
    }

    {
      dataReview.reviewContent &&
      dataReview.reviewContent.length > 0 &&
      <div className="wrapper-review-lst">

            <h3>Community Reviewsss</h3>

      {
        dataReview.reviewContent &&
        dataReview.reviewContent.length > 0 &&
        dataReview.reviewContent.map( data => {
          if(data.parent == 0 && data.comment.length>0) {
            return <Reviewdata parent={data.parent} name_author={ data.name } comment_rv={ data.comment }  id_review = { data.id_reviews } url_avatar= {data.url_avatar}  date_comment={data.date_coment} />
          }
        } )
      }
      </div>
    }
    </>
  )

}
