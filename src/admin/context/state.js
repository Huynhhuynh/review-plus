import React, { createContext, useContext, useReducer, useState, useEffect } from 'react'
import { getAllReviewDesign } from '../lib/api'
/**
 * States Context
 */

const ReviewDesignContext = createContext()

function ReviewDesignProvider( { children } ) {
  const [ reviewDesignData, setReviewDesignData ] = useState( [] )
  const [ designEdit, setDesignEdit ] = useState( null )

  useEffect( async () => {
    const data = await getAllReviewDesign()
    setReviewDesignData( [...data] )
  }, [] )

  const value = { 
    reviewDesignData, 
    setReviewDesignData,
    designEdit, 
    setDesignEdit, 
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