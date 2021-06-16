import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'


export default function btnlikeReview(props) {
  const thisprop = props
  const { submitLike } = useReviewPlus()
  const { submitLiked } = useReviewPlus()
  const { postId }  = useReviewPlus();
  const data_user_current_like = useReviewPlus()
  const [ showbt, setShowbt ] = useState( false )
  const array_id_reviews = data_user_current_like.reviewContent[1]
  const result_array = array_id_reviews.map(function (x) {
    return parseInt(x, 10)
  });

  useEffect(() => {
    if(result_array.includes(thisprop.id_review)){
      setShowbt(true)
    }

  });


  const handleClickLike =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitLike( id_review,postId )
  }

  const handleClickLiked =  async (e) => {
    let id_review = thisprop.id_review
    const result = await submitLiked( id_review,postId )
  }

  return (
    <>
      {
        (showbt==true)&&
        <button className="liked" onClick={handleClickLiked}>
          Like
        </button>
      }

      {
        (showbt==false)&&
        <button className="like" onClick={handleClickLike}>
          Like
        </button>
      }

    </>
  )
}
