/**
 * Review design header tool
 * 
 */

const ButtonNewDesign = ( props ) => {

  const onNewDesign = ( e ) => {
    e.preventDefault()
  }

  return <button className="rp-button-tool rp-button-tool__new-design" onClick={ onNewDesign }>New Design</button>
}

export default function ReviewDesignHeaderTool() {

  return (
    <div className="review-design-header-tool">
      <ButtonNewDesign />
    </div>
  )
}