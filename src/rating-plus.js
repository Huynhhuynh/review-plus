import React from "react"
import ReactDOM from 'react-dom'
import { ReviewPlusProvider } from './context/states'
import ReviewPlusApp from './components/review-plus-app'
import GetReview from './components/review'
import RatingAverage from './components/rating-average'

/**
 * Review type script
 */

const ReviewPlusWrap = ( { postId, userID } ) => {
  return (
    <ReviewPlusProvider postId={ postId } userID = { userID }>
      <RatingAverage />
      <GetReview />
      <ReviewPlusApp />
    </ReviewPlusProvider>
  )
}

export default class ReviewDesign {

  constructor() {
    const elems = document.querySelectorAll( '.review-plus-container' )
    if( elems.length <= 0 ) return

    elems.forEach( ( elem ) => {
      const postID = parseInt( elem.dataset.postId )
      const userID = parseInt( elem.dataset.userLogin );
      ReactDOM.render( <ReviewPlusWrap postId={ postID } userID = { userID } />, elem );
    } )
  }
}
