import { useState } from 'react'
import Rating from 'react-simple-star-rating'
/**
 * Rating fields 
 */


export default function RatingField(  { ratingOptions, onChange } ) {
  const [ rate, setRate ] = useState( ratingOptions.default_point )
  const handleRating = ( rate ) => {
    setRate( rate )
    onChange ? onChange( rate, ratingOptions ) : ''
  }

  return (
    <div className="rating-field-item">
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
            fillColor='orange'
            emptyColor='gray'
            label={ false }
            className='review-plus-rating-field'
          />
        }
      </div>
    </div>
  )
}