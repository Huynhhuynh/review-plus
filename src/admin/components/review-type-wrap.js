import { ReviewTypeProvider } from '../context/state'
import ReviewTypeItem from './review-type-item'

/**
 * Review type wrap
 * 
 */

export default function ReviewTypeWrap () {
  return (
    <ReviewTypeProvider>
      <ReviewTypeItem />
    </ReviewTypeProvider>
  )
}