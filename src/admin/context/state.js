import React, { createContext, useContext, useReducer, useState, useEffect } from 'react'
import { getAllReviewDesign, newDesign } from '../lib/api'
import * as Helpers from '../lib/helpers'
const findIndex = require( 'lodash/findIndex' )
const _ = require( 'lodash' )

/**
 * States Context
 */

const ReviewDesignContext = createContext()

function ReviewDesignProvider( { children } ) {
  const [ reviewDesignData, setReviewDesignData ] = useState( [] )
  const [ designEdit, setDesignEdit ] = useState( null )

  /**
   * 
   * @param {*} id 
   * @param {*} designData 
   * @returns 
   */
  const updateReviewDesignItem = ( id, designData ) => {
    let newRD = [ ...reviewDesignData ]
    let editIndex = findIndex( newRD, item => {
      return item.id == id
    } )

    if( editIndex == -1 ) return

    newRD[ editIndex ] = designData
    setReviewDesignData( newRD )
  }

  const newReviewDesignItem = async ( designData ) => {
    const result = await newDesign( designData )
    console.log( result )
  }

  const moveRatingFieldEdit = ( ratingFieldItemID, move ) => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    // get current index
    let cIndex = _.findIndex( ratingFields, o => { 
      return o.id == ratingFieldItemID 
    } )

    let itemMove = ratingFields.splice( cIndex, 1 )[0]; // save item
    let newIndex = ( move == 'up' ) ? cIndex -= 1 : cIndex += 1 // new index

    ratingFields.splice( newIndex, 0, itemMove ) // move item to new index

    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }

  const removeRatingFieldItem = ( ratingFieldItemID ) => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    _.remove( ratingFields, item => {
      return item.id == ratingFieldItemID
    } )  

    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }

  const addNewDesign = () => {
    let newDesign = Helpers.designItemTemplate()
    setDesignEdit( newDesign )
  }

  useEffect( async () => {
    const data = await getAllReviewDesign()
    setReviewDesignData( [...data] )
  }, [] )

  const addRatingFieldItem = () => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    let newField = Helpers.reviewItemTemplate()

    ratingFields.push( newField )
    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }

  const value = { 
    reviewDesignData, 
    setReviewDesignData,
    designEdit, 
    setDesignEdit, 
    updateReviewDesignItem,
    moveRatingFieldEdit,
    removeRatingFieldItem,
    addRatingFieldItem,
    addNewDesign,
    newReviewDesignItem
  }

  return (
    <ReviewDesignContext.Provider value={ value }>
      { children }
    </ReviewDesignContext.Provider>
  )
}

function useReviewDesign() {
  return useContext( ReviewDesignContext )
}

export { ReviewDesignProvider, useReviewDesign }