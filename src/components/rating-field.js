import { useState, useEffect, useRef } from 'react'
import Rating from 'react-simple-star-rating'
/**
 * Rating fields 
 */


export default function RatingField(  { ratingOptions, label, register, setValue, errors, itemIndex, onChange } ) {
  const [ rate, setRate ] = useState( parseInt( ratingOptions.default_point ) )
  const refRate = useRef()

  const handleRating = ( rate ) => {
    setRate( rate )
    setValue( `${ label }.rate`, rate )

    onChange ? onChange( rate, ratingOptions ) : ''
  }

  const isError = () => {
    return ( errors && errors?.ratings &&  errors?.ratings[ itemIndex ]?.rate ) ? true : false
  }

  return (
    <div className={ [ 'rating-field-item', isError() ? '__is-invalid' : '' ].join( ' ' ) }>
      <div className="rating-field-item__name">{ ratingOptions.name }</div>
      <div className="rating-field-item__icons">
        {
          ratingOptions.max_point > 0 &&
          <Rating
            onClick={ handleRating }
            ratingValue={ rate }
            stars={ parseInt( ratingOptions.max_point ) }
            size={ 20 }
            transition
            fillColor="orange"
            emptyColor="gray"
            label={ false }
            className="review-plus-rating-field"
          />
        }
      </div>
      <input 
        className="hidden-rate-value"
        ref={ refRate }
        type='number'
        defaultValue={ rate } 
        { ...register( `${ label }.rate`, { required: true, min: 1 } ) }
        readOnly
      />
      <input type="hidden" defaultValue={ ratingOptions.name }  { ...register( `${ label }.name`, { required: true } ) } />
      <input type="hidden" defaultValue={ ratingOptions.slug }  { ...register( `${ label }.slug`, { required: true } ) } />
    </div>
  )
}