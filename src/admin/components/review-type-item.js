import { useReviewType } from '../context/state'
/**
 * Review type item
 * 
 */

export default function ReviewTypeItem( { typeData } ) {
  const { reviewTypeData } = useReviewType()

  return (
    <div className="review-type-item">
      Review Type Item: 
      { JSON.stringify( reviewTypeData ) }
    </div>
  )
}