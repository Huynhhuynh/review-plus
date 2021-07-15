import React from "react"
import ReactDOM from 'react-dom'
import { ReviewPlusProvider } from './context/states'
import ReviewPlusApp from './components/review-plus-app'
import GetReview from './components/review'
import RatingAverage from './components/rating-average'

/**
 * Review type script
 */

const ReviewPlusWrap = ( { postId } ) => {
  return (
    <ReviewPlusProvider postId={ postId }>
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
      ReactDOM.render( <ReviewPlusWrap postId={ postID } />, elem );
    } )
  }
}
