import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import { AiFillLike } from 'react-icons/ai'
import { AiOutlineLike } from 'react-icons/ai'
import Reviewlike from '../components/like-review'

export default function btnlikeReview(props) {
  const thisprop = props
  const { submitLike } = useReviewPlus()
  const { submitLiked } = useReviewPlus()
  const { postId }  = useReviewPlus();
  const data_user_current_like = useReviewPlus()
  const [ showbt, setShowbt ] = useState( false )
  const array_id_reviews = data_user_current_like.pointLikeReview
  const [ dataLike, setDatalike ] = useState( [] )
  const result_array = array_id_reviews.map(function (x) {
    return parseInt(x, 10)
  });

  useEffect(() => {
    
    if(result_array.includes(thisprop.id_review)){
      setShowbt(true)
    }else{
      setShowbt(false)
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
        (showbt==true) &&
        <div className="liked" onClick={handleClickLiked} dataLike = {dataLike}>
          <AiFillLike />
        </div>
      }

      {
        (showbt==false) &&
        <div className="like" onClick={handleClickLike}>
          <AiOutlineLike />
        </div>
      }

    </>
  )
}
