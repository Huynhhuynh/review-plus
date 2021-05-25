/**
 * Review plus main script
 */
import './scss/main.scss'
import ReviewDesign from './rating-plus'

! ( ( w, $ ) => {
  'use strict'

  const init = () => {
    new ReviewDesign()
  }

  $( () => {
    init()
  } )

} )( window, window.jQuery )