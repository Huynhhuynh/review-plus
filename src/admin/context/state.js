import React, { createContext, useContext, useReducer, useState, useEffect } from 'react'
import { getAllReviewType } from '../lib/api'
/**
 * States Context
 */

const ReviewTypeContext = createContext()

function ReviewTypeProvider( { children } ) {
  const [ reviewTypeData, setReviewTypeData ] = useState( [] )

  useEffect( async () => {
    const data = await getAllReviewType()
    setReviewTypeData( [...data] )
  }, [] )

  const value = { reviewTypeData, setReviewTypeData }
  return (
    <ReviewTypeContext.Provider value={ value }>
      { children }
    </ReviewTypeContext.Provider>
  )
}

function useReviewType() {
  return useContext( ReviewTypeContext )
}

export { ReviewTypeProvider, useReviewType }