import { useReviewPlus } from '../context/states'
import { useEffect, useState, useRef } from 'react'
import Reviewlike from '../components/like-review'
import Reviewdislike from '../components/dislike-review'
import Btnlike from '../components/btn-like'
import Btndislike from '../components/btn-dislike'
import Btnreply from '../components/btn-reply'
import Contentreply from '../components/content-reply'
import {Link} from 'react-scroll'

export default function Reviewdata(props) {
  const { submitEditReview ,submitEditReviewHideForm , userID} = useReviewPlus()
  const thisprop = props
  const [showReply, setShowReply] = useState(false)
  const [idScroll,setidScroll] = useState('');
  const callbackFunction = (childData) => {
    setShowReply(childData)
  }
  useEffect( async () => {
    setidScroll(thisprop.id_form_rating)
  } )
  
  const handleClickeditcomment =  async (e) => {
    e.preventDefault();
    submitEditReview(thisprop.id_review,thisprop.comment_rv,thisprop.id_form_rating)
  }
  const handleClickedithideForm =  async (e) => {
    e.preventDefault();
    submitEditReviewHideForm(thisprop.id_review)
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
                <Link className="edit-review"  
                      onClick={handleClickeditcomment} 
                      to={idScroll} 
                      smooth={true}
                      duration = {1000} 
                      offset={-50}
                >
                    <img src="/wp-content/uploads/2021/11/pencil.png"/>
                    Edit
                </Link>
              }
              {

                PHP_DATA.user_logged_in == 'yes' && thisprop.userID =='hide-custom'  &&
                <div className="edit-review hide"  onClick={handleClickedithideForm}>
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
