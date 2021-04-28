import React, { Component } from "react"
import ReactDOM from "react-dom"

import ReviewDesignWrap from './components/review-design-wrap'

/**
 * Review type script
 */

export default class ReviewDesign {

  constructor() {
    const root = document.getElementById( 'rp-review-design-root' )
    if( ! root ) return 

    ReactDOM.render( <ReviewDesignWrap />, root );
  } 
}