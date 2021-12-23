import React from "react"
//import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewdata from '../components/review-content';


export default function ReviewContentApp( props ) {
  // useEffect( async () => {
    
  const [ dataStar, setdataStar ] = useState([])
  const [pointAverage, setAverage] = useState();
  // })
  const dataReview = useReviewPlus()
  const  isFloat = function(n){
    return Number(n) === n && n % 1 !== 0;
  }
  
  useEffect( async () => {
    if(dataReview.rating[4] && dataReview.rating[4].length>0){
      let total_point_pio = 0;
      let data_image = [];
      dataReview.rating[4].map( ( r,index ) => {
        total_point_pio += r;
      })
      let total_average = (total_point_pio/dataReview.rating[4].length);
      if(isFloat(total_average)){
        setAverage(total_average.toFixed(2))
      }else{
        setAverage(total_average)
      }
      
      // console.log('ssss',total_average,isFloat(total_average))
      if(isFloat(total_average)){
        let data_in = Math.floor(total_average);
        for (let i=1; i<= data_in;i++){
          data_image.push('/wp-content/uploads/2021/11/star.png');
        }
        data_image.push('/wp-content/uploads/2021/12/tai-xuong.png');
      }else{
        if(total_average!=0){
          let data_in = Math.floor(total_average);
          for (let i=1; i<= data_in;i++){
            data_image.push('/wp-content/uploads/2021/11/star.png');
          } 
        }
      }
      setdataStar(...[data_image])
    } 
  })
  return (
    <>
    {
        PHP_DATA.user_logged_in == 'yes' &&
        <div className="ss-score-total">
          <span>Personalized scoring</span>
          {
            dataReview.rating[4] && 
            dataReview.rating[4].length==0 &&
            <div>
              <p>0</p>
              <div className="raw-score">
               <span>Raw Score</span> 
                {/* <div className="icon-start">
                  {
                    dataStar && 
                    dataStar.length>0 &&
                    dataStar.map( (image,i) => {
                      return (
                        <>
                          <img src={image} />
                        </>
                      )
                    })
                  }
                </div> */}
              </div>
            </div>
            
          }
          {
            dataReview.rating[4] &&
            dataReview.rating[4].length>0 &&
            <>
              <p>{pointAverage}</p>
              <div className="raw-score">
                <span>Raw Score</span> 
                <div className="icon-start">
                  {
                    dataStar && 
                    dataStar.length>0 &&
                    dataStar.map( (image,i) => {
                      return (
                        <>
                          <img src={image} />
                        </>
                      )
                    })
                  }
                </div>
              </div>
            </>
          }
          
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

    {
      dataReview.reviewContent &&
      dataReview.reviewContent.length > 0 &&
      <div className="wrapper-review-lst">

            <h3>Community Reviews</h3>

      {
        dataReview.reviewContent &&
        dataReview.reviewContent.length > 0 &&
        dataReview.reviewContent.map( data => {
          if(data.parent == 0 && data.comment.length>0) {
            return <Reviewdata parent={data.parent} id_form_rating= {data.id_form_review} name_author={ data.name } comment_rv={ data.comment }  id_review = { data.id_reviews } url_avatar= {data.url_avatar}  date_comment={data.date_coment} userID = {data.user_id_review} />
          }
        } )
      }
      </div>
    }
    </>
  )

}
