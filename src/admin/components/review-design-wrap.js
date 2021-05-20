import { ReviewDesignProvider, useReviewDesign } from '../context/state'
import { ReviewDesignLoop } from './review-design-loop'
import DesignEditModal from './design-edit-modal'
import ReviewDesignHeaderTool from './review-design-header-tool'

/**
 * Review type wrap
 * 
 */

function ReviewDesignApp() {
  const { reviewDesignData, designEdit } = useReviewDesign()

  return (
    <>
      <ReviewDesignHeaderTool />
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