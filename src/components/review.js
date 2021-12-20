import React from "react"
//import ReactDOM from 'react-dom'
import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import Reviewdata from '../components/review-content';


export default function ReviewContentApp( props ) {
  // useEffect( async () => {
    
  const [ dataStar, setdataStar ] = useState([])
  // })
  const dataReview = useReviewPlus()
  const  isFloat = function(n){
    return Number(n) === n && n % 1 !== 0;
  }
  let data_image = [];
  if(isFloat(dataReview.pointTravel)){
    let data_in = Math.floor(dataReview.pointTravel);
    
    for (let i=1; i<= data_in;i++){
      data_image.push('/wp-content/uploads/2021/11/star.png');
    }
    data_image.push('/wp-content/uploads/2021/12/tai-xuong.png');
  }else{
    let data_in = Math.floor(dataReview.pointTravel);
    for (let i=1; i<= data_in;i++){
      data_image.push('/wp-content/uploads/2021/11/star.png');
    }
  }
  useEffect( async () => {
    setdataStar(data_image);
  })
  
  const recursiveMenu = function (data, parent_id=0, sub=true) {

  }
  return (
    <>
    {
        PHP_DATA.user_logged_in == 'yes' &&
        <div className="ss-score-total">
          <span>Personalized scoring</span>
          <p>{
            dataReview.pointTravel
            }
          </p>
          <div className="raw-score">
            <span>Raw Score</span>
            <div className="icon-start">
              {
                data_image && 
                data_image.length>0 &&
                data_image.map( (image,i) => {
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
          <span>Raw Score</span>
          <div className="icon-start">
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
            <img src="/wp-content/uploads/2021/11/star.png" />
          </div>
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
