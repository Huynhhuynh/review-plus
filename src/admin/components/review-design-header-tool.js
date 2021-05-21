import { useReviewDesign } from '../context/state'
/**
 * Review design header tool
 * 
 */

const ButtonNewDesign = ( props ) => {
  const { addNewDesign } = useReviewDesign()
  return <button className="rp-button-tool rp-button-tool__new-design" onClick={ addNewDesign }>New Design</button>
}

export default function ReviewDesignHeaderTool() {

  return (
    <div className="review-design-header-tool">
      <ButtonNewDesign />
    </div>
  )
}