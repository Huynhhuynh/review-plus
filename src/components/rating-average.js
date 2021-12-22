import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Rating from 'react-simple-star-rating'

export default function ratingAverage(props) {
  const data_rating = useReviewPlus();
  const  isFloat = function(n){
    return Number(n) === n && n % 1 !== 0;
  }

  const [ dataStar, setdataStar ] = useState([])

  useEffect( async () => {
    if(data_rating.rating[3] && data_rating.rating[3].length>0){
      let data_image_all = [];
      data_rating.rating[3].map( ( r,index ) => {
        let data_image = [];
        if(isFloat(data_rating.rating[4][index])){
          let data_in = Math.floor(data_rating.rating[4][index]);
          for (let i=1; i<= data_in;i++){
            data_image.push('/wp-content/uploads/2021/11/star.png');
          }
          data_image.push('/wp-content/uploads/2021/12/tai-xuong.png');
        }else{
          let data_in = Math.floor(data_rating.rating[4][index]);
          for (let i=1; i<= data_in;i++){
            data_image.push('/wp-content/uploads/2021/11/star.png');
          } 
        }
        data_image_all.push(data_image);
      })
      // console.log('ok',data_image_all);
      setdataStar(...[data_image_all]);
    } 

  })


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
                      <>
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
                        
                      </>  
                    }
                    <div className="score">
                      {
                        PHP_DATA.user_logged_in == 'yes' &&
                        <div className="ss-score-total">
                          <span>Personalized scoring</span>
                          <p>{
                              data_rating.rating[4][index]
                            }
                          </p>
                          <div className="raw-score">
                            <span>Raw Score</span>
                            <div className="icon-start">
                              {
                                dataStar[index] && 
                                dataStar[index].length>0 &&
                                dataStar[index].map( (image,i) => {
                                  return (
                                    <>
                                      <img src={image} />
                                    </>
                                  )
                                })
                              }
                            </div>
                          </div>
                        </div>
                      }
                      {
                        PHP_DATA.user_logged_in != 'yes' &&
                        <div className="ss-score-total">
                          <span>Travel Session Score</span>
                          <a href="#">Login or Register To See Personal Travel Score</a>
                          <div className="raw-score">
                          </div>
                        </div>
                      }
                    </div>
                  </>
          })
        }
        </div>
      </div>
    }

    </>
  )
}
