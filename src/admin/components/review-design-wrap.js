import { ReviewDesignProvider, useReviewDesign } from '../context/state'
import { ReviewDesignLoop } from './review-design-loop'
import DesignEditModal from './design-edit-modal'

/**
 * Review type wrap
 * 
 */

function ReviewDesignApp() {
  const { reviewDesignData, designEdit } = useReviewDesign()

  return (
    <>
      <ReviewDesignLoop reviewDesign={ reviewDesignData } />
      <DesignEditModal />
    </>
  )
}

export default function ReviewDesignWrap () {

  return (
    <ReviewDesignProvider> 
      <ReviewDesignApp />
    </ReviewDesignProvider>
  )
}