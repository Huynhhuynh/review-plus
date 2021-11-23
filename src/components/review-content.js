import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewlike from '../components/like-review'
import Reviewdislike from '../components/dislike-review'
import Btnlike from '../components/btn-like'
import Btndislike from '../components/btn-dislike'
import Btnreply from '../components/btn-reply'
import Contentreply from '../components/content-reply'

export default function dataReview(props) {
  const { submitEditReview } = useReviewPlus()
  const thisprop = props
  const [showReply, setShowReply] = useState(false)
  const { userID } = useReviewPlus()
  const callbackFunction = (childData) => {
    setShowReply(childData)
  }

  const handleClickeditcomment =  async (e) => {
    e.preventDefault();
    submitEditReview(thisprop.id_review,thisprop.comment_rv,thisprop.id_form_rating)
  }

  return (
    <>
      <div className="reviews-item">
        <div className="wrapper-user">
          <div className="avatar-user">
            <img src={thisprop.url_avatar}/>
          </div>
        </div>
        <div className="wrapper-content">
          <div className="name-crt-date">
            <div className="name">
              { thisprop.name_author }
            </div>
            <div className="crt-date">
              { thisprop.date_comment }
            </div>
          </div>
          <div className="reviews-content">
            {thisprop.comment_rv}
          </div>
          <div className="reviews-action">
            <div>
              <div className="action-like-count act-item">
                {
                  PHP_DATA.user_logged_in == 'yes' &&
                  <Btnlike id_review = {thisprop.id_review}/>
                }
              </div>
              <span>Like</span> <Reviewlike id_review = {thisprop.id_review}/>
            </div>
            <div>
              <div className="action-like-count act-item">
                {
                  PHP_DATA.user_logged_in == 'yes' &&
                  <Btndislike id_review = {thisprop.id_review}/>
                }
              </div>
              <span>Dislike</span> <Reviewdislike id_review = {thisprop.id_review}/>
            </div>
            <div className="action-like-count act-item">
              {
                PHP_DATA.user_logged_in == 'yes' &&
                <Btnreply  parentCallback={callbackFunction} id_review = {thisprop.id_review} parent = {thisprop.parent}/>
              }
            </div>
              {
                PHP_DATA.user_logged_in == 'yes' && Number(thisprop.userID) == Number(userID) &&
                <div className="edit-review"  onClick={handleClickeditcomment}>
                    <img src="/wp-content/uploads/2021/11/pencil.png"/>
                    Edit
                </div>
              }
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
