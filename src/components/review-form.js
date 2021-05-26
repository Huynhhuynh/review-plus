import { useState } from 'react'
import RatingField from './rating-field'

/**
 * Review form 
 */

export default function ReviewForm( { designData } ) {
  const [ submitFormData, setSubmitFormData ] = useState( {
    name: '',
    email: '',
    comment: '',
    url: '',
    ratings: designData.rating_fields.map( item => {
      let { name, slug, default_point } = item
      return { name, slug, rate: parseInt( default_point ) }
    } )
  } )

  const updateRatingField = ( slug, rate ) => {
    let _submitFormData = { ...submitFormData }
    let _ratings = [ ..._submitFormData.ratings ]
    let index = _ratings.findIndex( r => ( r.slug == slug ) )

    if( index == -1 ) return
    _ratings[ index ].rate = rate
    _submitFormData.ratings = _ratings
    
    setSubmitFormData( _submitFormData )
  }

  const updateField = ( name, value ) => {
    let _submitFormData = { ...submitFormData }
    _submitFormData[ name ] = value 

    setSubmitFormData( _submitFormData )
  }

  return (
    <>
      <div className="review-form-container">
        <h2 className="rp-title">{ designData.label }</h2>
        <p className="rp-desc">{ designData.description }</p>
        { JSON.stringify( submitFormData ) }
        <form className="review-plus-form">
          {
            designData.rating_fields.length > 0 && 
            <>
              <h4 className="heading-review-list">Your Rating</h4>
              <div className="rp-review-list">
              {
                designData.rating_fields.map( r => {
                  return <RatingField ratingOptions={ r } onChange={ ( rate, _field ) => {
                    updateRatingField( _field.slug, rate )
                  } } />
                } )
              }
              </div>
            </>
          }
          <div className="rp-field rp-field__comment">
            <label>
              <span className="__label">Comment</span>
              <textarea 
                className="rp-comment" 
                value={ submitFormData.comment }
                onChange={ e => {
                  updateField( 'comment', e.target.value )
                } }></textarea>
            </label>
          </div>
          <div className="rp-field rp-field__name">
            <label>
              <span className="__label">Name *</span>
              <input 
                type="text" 
                className="rp-name" 
                value={ submitFormData.name } 
                onChange={ e => {
                  updateField( 'name', e.target.value )
                } }/>
            </label>
          </div>
          <div className="rp-field rp-field__email">
            <label>
              <span className="__label">Email *</span>
              <input 
                type="email" 
                className="rp-email" 
                value={ submitFormData.email } 
                onChange={ e => {
                  updateField( 'email', e.target.value )
                } }/>
            </label>
          </div>
          <div className="rp-field rp-field__url">
            <label>
              <span className="__label">Url</span>
              <input 
                type="text" 
                className="rp-url" 
                value={ submitFormData.url } 
                onChange={ e => {
                  updateField( 'url', e.target.value )
                } }/>
            </label>
          </div>
          <button type="button" className="review-button-submit">Submit Review</button>
        </form>
      </div>
    </>
  )
}