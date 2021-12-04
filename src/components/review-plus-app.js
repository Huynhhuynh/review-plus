import { useReviewPlus } from '../context/states'
import ReviewForm from '../components/review-form'
import { useEffect, useState } from 'react'
/**
 * Review plus app
 */

export default function ReviewPlusApp( props ) {

  const { postId ,userID , reviewDesign , reviewContent } = useReviewPlus();
  const [showeditForm, setShowEditForm] = useState(false);
  const [useridReview, setuseridReview]  = useState([]);
  const idFormShow = [];



  useEffect( async () => {
    reviewContent.map( ( e,i ) => {
      if(Number(e.user_id_review)==userID && e.comment.length>0){
        idFormShow.push(Number(e.id_form_review));
      }
    })
    setShowEditForm([...idFormShow]);
  })

  return (
    <>
      {

        reviewDesign &&
        reviewDesign.length > 0 &&
        reviewDesign.map( (design,i) => {
          if( design?.enable != true ) {
            return;
          }else{
            return (
              <div id={design.id}>
              {
                !showeditForm.includes(Number(design.id)) && 
                <ReviewForm  designData={ design } postId={ postId } />
            
              }
              </div>
            )  
            

          }

        } )
      }
    </>
  )
}
