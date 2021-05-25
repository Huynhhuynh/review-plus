/**
 * Review form 
 */

export default function ReviewForm( { designData } ) {

  return (
    <>
      <div className="review-form-container">
        <h2 className="rp-title">{ designData.label }</h2>
        <p className="rp-desc">{ designData.description }</p>
        <form className="review-plus-form">
          <div className="rp-field rp-field__comment">
            <label>
              <span className="__label">Comment</span>
              <textarea className="rp-comment"></textarea>
            </label>
          </div>
          <div className="rp-field rp-field__name">
            <label>
              <span className="__label">Name *</span>
              <input type="text" className="rp-name" />
            </label>
          </div>
          <div className="rp-field rp-field__email">
            <label>
              <span className="__label">Email *</span>
              <input type="email" className="rp-email" />
            </label>
          </div>
          <div className="rp-field rp-field__url">
            <label>
              <span className="__label">Url</span>
              <input type="text" className="rp-url" />
            </label>
          </div>
          <button type="button" className="review-button-submit">Submit Review</button>
        </form>
      </div>
    </>
  )
}