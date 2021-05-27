import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import { useForm } from "react-hook-form"
import RatingField from './rating-field'

/**
 * Review form 
 */

const NotLoggedFields = ( { submitFormData, register, errors } ) => {

  return (
    <>
      <div className="rp-field rp-field__name">
        <label>
          <span className="__label">Name *</span>
          <div className="__field">
            <input 
              { ...register( 'name', { required: true } ) }
              type="text" 
              className={ [ 'rp-name', ( errors.name ? '__is-invalid' : '' ) ].join( ' ' ) } 
              defaultValue={ submitFormData.name } 
              />
            { errors.name && <span className="__invalid-message">Please enter your name!</span> }
          </div>
        </label>
      </div>
      <div className="rp-field rp-field__email">
        <label>
          <span className="__label">Email *</span>
          <div className="__field">
            <input 
              { ...register( 'email', { required: true, pattern: /\S+@\S+\.\S+/ } ) }
              type="text" 
              className={ [ 'rp-email', ( errors.email ? '__is-invalid' : '' ) ].join( ' ' ) } 
              defaultValue={ submitFormData.email } 
              />
            { errors.email && <span className="__invalid-message">Please enter your E-mail!</span> }
          </div>
        </label>
      </div>
      <div className="rp-field rp-field__url">
        <label>
          <span className="__label">Url</span>
          <div className="__field">
            <input 
              { ...register( 'url' ) }
              type="text" 
              className="rp-url" 
              defaultValue={ submitFormData.url } 
            />
          </div>
        </label>
      </div>
    </>
  )
}

export default function ReviewForm( { designData, postId } ) {
  const { submitReview } = useReviewPlus()
  const { register, setValue, handleSubmit, trigger, formState: { errors } } = useForm()
  const [ submitFormData, setSubmitFormData ] = useState( {
    postId,
    parent: 0,
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

  const onSubmitReview = ( data ) => {
    let newSubmitFormData = { ...submitFormData, ...data }
    submitReview( newSubmitFormData )
  }

  console.log( PHP_DATA.user_logged_in )

  return (
    <>
      <div className="review-form-container">
        <h2 className="rp-title">{ designData.label }</h2>
        <p className="rp-desc">{ designData.description }</p>
        <form 
          className="review-plus-form" 
          onSubmit={ handleSubmit( onSubmitReview ) }>
          {
            designData.rating_fields.length > 0 && 
            <>
              <h4 className="heading-review-list">Your Rating</h4>
              <div className="rp-review-list">
              {
                designData.rating_fields.map( ( r, index ) => {
                  return <RatingField 
                    ratingOptions={ r } 
                    label={ `ratings.${ index }` }
                    itemIndex={ index }
                    register={ register } 
                    setValue={ setValue }
                    errors={ errors }
                    onChange={ ( rate, _field ) => {
                      updateRatingField( _field.slug, rate )
                      trigger( `ratings.${ index }.rate` )
                    } } />
                } )
              }
              </div>
            </>
          }
          <div className="rp-field rp-field__comment">
            <label>
              <span className="__label">Comment *</span>
              <div className="__field">
                <textarea 
                  { ...register( 'comment', { required: true } ) }
                  className={ [ 'rp-comment', ( errors.comment ? '__is-invalid' : '' ) ].join( ' ' ) } 
                  defaultValue={ submitFormData.comment }
                  ></textarea>{ errors.comment && <span className="__invalid-message">Please enter your comment!</span> }
              </div>
            </label>
          </div>
          {
            PHP_DATA.user_logged_in != 'yes' &&
            <NotLoggedFields submitFormData={ submitFormData } register={ register } errors={ errors } />
          }
          <button type="submit" className="review-button-submit">Submit Review</button>
        </form>
      </div>
    </>
  )
}