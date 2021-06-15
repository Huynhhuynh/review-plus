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
  const [ showLike, setShowlike ] = useState( false )


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
          <div>
            <Reviewlike id_review = {thisprop.id_review}/>
            <Btnlike id_review = {thisprop.id_review}/>
          </div>
          <div>
            <Reviewdislike id_review = {thisprop.id_review}/>
            <Btndislike id_review = {thisprop.id_review}/>
          </div>
        </div>
      </div>

    </>
  )
}
