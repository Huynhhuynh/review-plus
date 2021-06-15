import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function btnlikeReview(props) {
  const thisprop = props
  const { submitLike } = useReviewPlus()
  const data_user_current_like = useReviewPlus()
  const array_id_reviews = data_user_current_like.reviewContent[1]
  const [ showbtn, setshowbtn ] = useState( false )
  const result_array = array_id_reviews.map(function (x) {
    return parseInt(x, 10)
  });

  // console.log('ok',result_array);

  // if(result_array.includes(thisprop.id_review)){
  //   setshowbtn(true)
  // }

  const handleClickLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitLike( id_review )
  }


  return (
    <>
      <button onClick={handleClickLike}>
        Like
      </button>
    </>
  )
}
