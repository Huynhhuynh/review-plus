import React, { createContext, useContext, useReducer, useState, useEffect } from 'react'
import { getAllReviewDesign } from '../lib/api'
const findIndex = require( 'lodash/findIndex' )
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

  useEffect( async () => {
    const data = await getAllReviewDesign()
    setReviewDesignData( [...data] )
  }, [] )

  const value = { 
    reviewDesignData, 
    setReviewDesignData,
    designEdit, 
    setDesignEdit, 
    updateReviewDesignItem
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