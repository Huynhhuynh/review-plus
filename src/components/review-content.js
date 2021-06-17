import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewlike from '../components/like-review'
import Reviewdislike from '../components/dislike-review'
import Btnlike from '../components/btn-like'
import Btndislike from '../components/btn-dislike'

export default function dataReview(props) {

  const thisprop = props

  return (
    <>

      <div className="wrapper-comment-content-post">
        <div className="avatar-user">
          <img src={thisprop.url_avatar}/>
        </div>
        <div>
          <div className="user-profile">
            { thisprop.name_author }

          </div>
          <div className="date-comment">
            { thisprop.date_comment }
          </div>
          <div className="content-review">
            {thisprop.comment_rv}
          </div>
        </div>

        <div className="wrapper-action-review">
          <div className="action-like-count">
            {
              PHP_DATA.user_logged_in == 'yes' &&
              <Btnlike id_review = {thisprop.id_review}/>
            }

            <Reviewlike id_review = {thisprop.id_review}/>
          </div>
          <div className="action-like-count">
            {
              PHP_DATA.user_logged_in == 'yes' &&
              <Btndislike id_review = {thisprop.id_review}/>
            }

            <Reviewdislike id_review = {thisprop.id_review}/>
          </div>
        </div>
      </div>

    </>
  )
}
