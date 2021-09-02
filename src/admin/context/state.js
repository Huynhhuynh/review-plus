import React, { createContext, useContext, useState, useEffect } from 'react'
import { getAllReviewDesign, newDesign, deleteDesign, updateDesign, getAllGroupPostTax  } from '../lib/api'
import * as Helpers from '../lib/helpers'
import { findIndex, remove } from 'lodash'

/**
 * States Context
 */

const ReviewDesignContext = createContext()

function ReviewDesignProvider( { children } ) {
  const [ reviewDesignData, setReviewDesignData ] = useState( [] )
  const [ groupPostTax, setGroupPostTax ] = useState( {} )
  const [ designEdit, setDesignEdit ] = useState( null )

  /**
   *
   * @param {*} id
   * @param {*} designData
   * @returns
   */
  const updateReviewDesignItem = async ( id, designData ) => {
    let newRD = [ ...reviewDesignData ]
    let editIndex = findIndex( newRD, item => {
      return item.id == id
    } )

    if( editIndex == -1 ) return

    console.log('ok',designData);

    const result = await updateDesign( designData )
    if( result.success != true ) return

    newRD[ editIndex ] = designData
    setReviewDesignData( newRD )
  }

  const newReviewDesignItem = async ( designData ) => {
    const result = await newDesign( designData )
    if( result.success != true ) return

    let newRD = [ ...reviewDesignData ]
    let newData = { ...designData }
    newData.id = parseInt( result.ID )

    newRD.unshift( newData )
    setReviewDesignData( newRD )
  }

  const deleteReviewDesign = async ( designID ) => {
    const result = await deleteDesign( designID )
    if( result.success != true ) return

    let newRD = [ ...reviewDesignData ]
    remove( newRD, d => {
      return d.id == designID
    } )

    setReviewDesignData( newRD )
  }

  const moveRatingFieldEdit = ( ratingFieldItemID, move ) => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    // get current index
    let cIndex = findIndex( ratingFields, o => {
      return o.id == ratingFieldItemID
    } )

    let itemMove = ratingFields.splice( cIndex, 1 )[0]; // save item
    let newIndex = ( move == 'up' ) ? cIndex -= 1 : cIndex += 1 // new index

    ratingFields.splice( newIndex, 0, itemMove ) // move item to new index

    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }
  const moveProsFieldEdit = ( prosFieldItemID, move ) => {
    let newdesignEdit = {...designEdit}
    let prosFields = [ ...newdesignEdit.pros_fields ]

    // get current index
    let cIndex = findIndex( prosFields, o => {
      return o.id == prosFieldItemID
    } )

    let itemMove = prosFields.splice( cIndex, 1 )[0]; // save item
    let newIndex = ( move == 'up' ) ? cIndex -= 1 : cIndex += 1 // new index

    prosFields.splice( newIndex, 0, itemMove ) // move item to new index

    newdesignEdit.pros_fields = prosFields
    setDesignEdit( newdesignEdit )
  }
  const moveConsFieldEdit = ( consFieldItemID, move ) => {
    let newdesignEdit = {...designEdit}
    let consFields = [ ...newdesignEdit.cons_fields ]

    // get current index
    let cIndex = findIndex( consFields, o => {
      return o.id == consFieldItemID
    } )

    let itemMove = consFields.splice( cIndex, 1 )[0]; // save item
    let newIndex = ( move == 'up' ) ? cIndex -= 1 : cIndex += 1 // new index

    consFields.splice( newIndex, 0, itemMove ) // move item to new index

    newdesignEdit.cons_fields = consFields
    setDesignEdit( newdesignEdit )
  }
  const moveCategoriesFieldEdit = ( categoriesFieldItemID, move ) => {
    let newdesignEdit = {...designEdit}
    let categoriesFields = [ ...newdesignEdit.categories_fields ]

    // get current index
    let cIndex = findIndex( categoriesFields, o => {
      return o.id == categoriesFieldItemID
    } )

    let itemMove = categoriesFields.splice( cIndex, 1 )[0]; // save item
    let newIndex = ( move == 'up' ) ? cIndex -= 1 : cIndex += 1 // new index

    categoriesFields.splice( newIndex, 0, itemMove ) // move item to new index

    newdesignEdit.categories_fields = categoriesFields
    setDesignEdit( newdesignEdit )
  }

  const removeRatingFieldItem = ( ratingFieldItemID ) => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    remove( ratingFields, item => {
      return item.id == ratingFieldItemID
    } )

    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }
  const removeProsFieldItem = ( prosFieldItemID ) => {
    let newdesignEdit = {...designEdit}
    let prosFields = [ ...newdesignEdit.pros_fields ]

    remove( prosFields, item => {
      return item.id == prosFieldItemID
    } )

    newdesignEdit.pros_fields = prosFields
    setDesignEdit( newdesignEdit )
  }
  const removeConsFieldItem = ( consFieldItemID ) => {
    let newdesignEdit = {...designEdit}
    let consFields = [ ...newdesignEdit.cons_fields ]

    remove( consFields, item => {
      return item.id == consFieldItemID
    } )

    newdesignEdit.cons_fields = consFields
    setDesignEdit( newdesignEdit )
  }
  const removeCategoriesFieldItem = ( categoriesFieldItemID ) => {
    let newdesignEdit = {...designEdit}
    let categoriesFields = [ ...newdesignEdit.categories_fields ]

    remove( categoriesFields, item => {
      return item.id == categoriesFieldItemID
    } )
    newdesignEdit.categories_fields = categoriesFields
    setDesignEdit( newdesignEdit )
  }

  const addNewDesign = () => {
    let newDesign = Helpers.designItemTemplate()
    setDesignEdit( newDesign )
  }

  const addRatingFieldItem = () => {
    let newdesignEdit = {...designEdit}
    let ratingFields = [ ...newdesignEdit.rating_fields ]

    let newField = Helpers.reviewItemTemplate()

    ratingFields.push( newField )
    newdesignEdit.rating_fields = ratingFields
    setDesignEdit( newdesignEdit )
  }
  const addProsFieldItem = () => {
    let newdesignEdit = {...designEdit}
    let prosFields = [ ...newdesignEdit.pros_fields ]

    let newField = Helpers.prosConsItemTemplate()
    prosFields.push( newField )
    newdesignEdit.pros_fields = prosFields
    setDesignEdit( newdesignEdit )
  }
  const addConsFieldItem = () => {
    let newdesignEdit = {...designEdit}
    let consFields = [ ...newdesignEdit.cons_fields ]

    let newField = Helpers.prosConsItemTemplate()
    consFields.push( newField )
    newdesignEdit.cons_fields = consFields
    setDesignEdit( newdesignEdit )
  }
  const addCategoriesFieldItem = () => {
    let newdesignEdit = {...designEdit}
    let categoriesFields = [ ...newdesignEdit.categories_fields ]

    let newField = Helpers.categoriesItemTemplate()
    categoriesFields.push( newField )
    newdesignEdit.categories_fields = categoriesFields
    setDesignEdit( newdesignEdit )
  }

  useEffect( () => {

    async function _getAllDesign() {
      const _design = await getAllReviewDesign()
      setReviewDesignData( [..._design] )
    }

    async function _getAllGroupPostTax() {
      const _groupTax = await getAllGroupPostTax()
      setGroupPostTax( {..._groupTax } )
    }

    _getAllDesign()
    _getAllGroupPostTax()
  }, [] )

  const value = {
    reviewDesignData,
    setReviewDesignData,
    designEdit,
    setDesignEdit,
    updateReviewDesignItem,
    moveRatingFieldEdit,
    removeRatingFieldItem,
    addRatingFieldItem,
    moveProsFieldEdit,
    removeProsFieldItem,
    addProsFieldItem,
    moveConsFieldEdit,
    removeConsFieldItem,
    addConsFieldItem,
    moveCategoriesFieldEdit,
    removeCategoriesFieldItem,
    addCategoriesFieldItem,
    addNewDesign,
    newReviewDesignItem,
    deleteReviewDesign,
    groupPostTax
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
