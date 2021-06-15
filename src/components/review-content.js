import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import React from "react"
import ReactDOM from 'react-dom'

export default function dataReview(props) {
  const [ showLike, setShowlike ] = useState( false )
  const { submitLike } = useReviewPlus()
  const { submitDisLike } = useReviewPlus();


  const dataReview = useReviewPlus()

  const thisprop = props

  // forEach(dataReview.reviewContent[1], i) => {
  //   if(dataReview.reviewContent[1][i]==thisprop.id_review){
  //     setShowlike(true);
  //   }
  // });



  const handleClickLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitLike( id_review )
  }

  const handleClickDisLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitDisLike( id_review )
  }

  return (
    <>

      <div>
        <div className="user-profile">
          {thisprop.name_author}
        </div>
        <div className="content-review">
          {thisprop.comment_rv}
        </div>
        <div className="wrapper-action-review">
          <button onClick={handleClickLike}>
            Like
          </button>
          <button onClick={handleClickDisLike}>
            Dislike
          </button>
        </div>
      </div>

    </>
  )
}
