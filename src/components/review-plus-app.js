import { useReviewPlus } from '../context/states'
import ReviewForm from '../components/review-form'
/**
 * Review plus app
 */

export default function ReviewPlusApp( props ) {

  const { postId, reviewDesign } = useReviewPlus()

  return (
    <>
      {
        reviewDesign &&
        reviewDesign.length > 0 && 
        reviewDesign.map( design => {
          // console.log( design )
          if( design?.enable != true ) return;
          return <ReviewForm designData={ design } postId={ postId } />
        } )
      }
    </>
  )
}