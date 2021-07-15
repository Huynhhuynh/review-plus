import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Rating from 'react-simple-star-rating'

export default function ratingAverage(props) {
  const data_rating = useReviewPlus();

  if(data_rating.reviewDesign && data_rating.reviewDesign.length>0){
    console.log('ok',data_rating.reviewDesign[0].rating_fields[0].max_point);
  }

  return (
    <>
      <div className="wrapper-rating-average">
        <h3>Rating Average</h3>
        <div className="av-rating-cnt">20 reviews</div>
        <div className="av-rating-bl">
        {
          data_rating.rating.length > 0 &&
          data_rating.rating[0].map( ( r,index ) => {
            return  <>
                    <div className="av-rating-item">
                        <div className="rating-content">{data_rating.rating[1][index]}</div>
                        <div className="rating-data">
                            <p className="rating-val">{Math.round(r)}.0 <span>/ 5</span></p>
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
                        </div>
                    </div>
                  </>
          })
        }
        </div>
      </div>
    </>
  )
}
