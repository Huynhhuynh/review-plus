import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewlike from '../components/like-review'
import Reviewdislike from '../components/dislike-review'
import Btnlike from '../components/btn-like'
import Btndislike from '../components/btn-dislike'
import Btnreply from '../components/btn-reply'
import Contentreply from '../components/content-reply'

export default function dataReview(props) {

  const thisprop = props

  const [showReply, setShowReply] = useState(false)

  const callbackFunction = (childData) => {
    setShowReply(childData)
  }

  return (
    <>
      <div className="wrapper-comment-content-post">
        <div className="wrapper-user">
          <div className="avatar-user">
            <img src={thisprop.url_avatar}/>
          </div>
          <div className="action-like-count">
            {
              PHP_DATA.user_logged_in == 'yes' &&
              <Btnlike id_review = {thisprop.id_review}/>
            }
          </div>

          <div className="action-like-count">
            {
              PHP_DATA.user_logged_in == 'yes' &&
              <Btndislike id_review = {thisprop.id_review}/>
            }
          </div>
        </div>
        <div className="wrapper-right">
          <div className="review-0">
            <div className="top-user-profile">
              <div className="user-profile">
                { thisprop.name_author }

              </div>
              <div className="date-comment">
                { thisprop.date_comment }
              </div>
            </div>
            <div className="content-review">
              {thisprop.comment_rv}
            </div>
          </div>
          <div className="wrapper-action-review">
            <div>
              Like <Reviewlike id_review = {thisprop.id_review}/>
            </div>
            <div>
              Dislike  <Reviewdislike id_review = {thisprop.id_review}/>
            </div>
            <div className="action-like-count">
              {
                PHP_DATA.user_logged_in == 'yes' &&
                <Btnreply  parentCallback={callbackFunction} id_review = {thisprop.id_review} parent = {thisprop.parent}/>
              }
            </div>
          </div>
          {
            (showReply==true) &&
            <div className="content-reply">
              <Contentreply  id_review = {thisprop.id_review} parent = {thisprop.parent}/>
            </div>
          }
        </div>
      </div>
    </>
  )
}
