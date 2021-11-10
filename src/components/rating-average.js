import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Rating from 'react-simple-star-rating'

export default function ratingAverage(props) {
  const data_rating = useReviewPlus();
  return (
    <>
    {
      data_rating.rating[3] &&
      data_rating.rating[3].length>0 &&
      <div className="wrapper-rating-average">
          <h3>Rating Average</h3>
        <div>
        {
          data_rating.rating.length > 0 &&
          data_rating.rating[3].map( ( r,index ) => {
            return  <>
                    <div className="name-form-rating">
                      <span>{r}</span>
                    </div>{
                        <div className="av-rating-bl">

                          <div className="av-rating-item">
                              <div className="rating-content">
                                {
                                  data_rating.rating[1][index].map((item_name,index_name)=>{
                                    return <>
                                            <div>
                                              {
                                                  item_name
                                              }
                                            </div>
                                          </>
                                  })

                                }
                              </div>
                              <div className="rating-data">
                                  <p className="rating-val">
                                    {
                                      data_rating.rating[0][index].map((item_point,index_point)=>{
                                        return <>
                                                <div className="wrapper-point-star">
                                                {
                                                  Math.round(item_point)
                                                }.0
                                                <span>/
                                                  {
                                                    data_rating.rating[2][index]
                                                  }
                                                </span>
                                                <Rating
                                                  ratingValue={ Math.round(item_point) }
                                                  stars={ parseInt( data_rating.rating[2][index] ) }
                                                  size={ 20 }
                                                  transition
                                                  fillColor="orange"
                                                  emptyColor="gray"
                                                  label={ false }
                                                  className="review-plus-rating-field"
                                                />
                                                </div>
                                               </>
                                      })
                                    }

                                  </p>

                              </div>
                          </div>
                        </div>
                    }


                    </>
          })
        }
        </div>
      </div>
    }

    </>
  )
}
