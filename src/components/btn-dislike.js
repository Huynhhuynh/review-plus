import React from "react"
import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function btndislikeReview(props) {
  const thisprop = props
  const { submitDisLike } = useReviewPlus()
  const handleClickDisLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitDisLike( id_review )
  }


  return (
    <>
      <button onClick={handleClickDisLike}>
        DisLike
      </button>
    </>
  )
}
