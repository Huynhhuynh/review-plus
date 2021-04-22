import React, { Component } from "react"
import ReactDOM from "react-dom"

import ReviewTypeWrap from './components/review-type-wrap'

/**
 * Review type script
 */

export default class ReviewType {

  constructor() {
    const root = document.getElementById( 'rp-review-type-root' )
    if( ! root ) return 

    ReactDOM.render( <ReviewTypeWrap />, root );
  } 
}