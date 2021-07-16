import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'

export default function btnReply(props) {

  const handleClickreply =  async (e) => {
    props.parentCallback(true);
  }

  return (
    <>
      <div className="wrapper-reply"  onClick={handleClickreply}>
        Reply
      </div>
    </>
  )

}
