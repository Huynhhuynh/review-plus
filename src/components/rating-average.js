import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Rating from 'react-simple-star-rating'

export default function ratingAverage(props) {
  const data_rating = useReviewPlus();

  if(data_rating.reviewDesign && data_rating.reviewDesign.length>0){
    console.log('ok',data_rating.reviewDesign[0].rating_fields[0].max_point);
  }
  // if(data_rating.rating && data_rating.rating.length>0){
  //   const field_count_rating = data_rating.rating[0].length
  //   console.log('ok',field_count_rating);
  //   for (var i in data_rating.rating) {
  //
  //   }
  //
  // }


  return (
    <>
      <div className="wrapper-rating-average">
        <h3> Rating Average</h3>
        {

          data_rating.rating.length > 0 &&
          data_rating.rating[0].map( ( r,index ) => {
            return  <>
                    <p>{data_rating.rating[1][index]}</p>
                    <Rating
                      ratingValue={ Math.round(r) }
                      stars={ parseInt( 5 ) }
                      size={ 20 }
                      transition
                      fillColor="orange"
                      emptyColor="gray"
                      label={ false }
                      className="review-plus-rating-field"
                    />
                  </>

          })

        }

      </div>
    </>
  )
}
