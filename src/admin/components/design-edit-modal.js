import { useState, useEffect } from 'react'
/**
 * Design edit
 */

function RatingFieldItem( { ratingFieldData } ) {
  const [ fieldData, setFieldData ] = useState( ratingFieldData )

  return (
    <div className="rating-field-item-control">
      <div className="field-title">{ fieldData.name }</div>
      <div>{ JSON.stringify( ratingFieldData ) }</div>
    </div>
  )
}

export default function DesignEditModal( { designEditData } ) {

  if( designEditData == null ) return <></>

  return (
    <div className="design-edit-modal">
      <div className="design-edit-modal__inner">
        {/* { JSON.stringify( designEditData ) } */}
        <div className="design-edit-modal__heading">Edit Design</div>
        <div className="design-edit-modal__body">
          <form className="rp-form">
            <div className="group-field">
              <label>Label</label>
              <div className="field">
                <input value={ designEditData.label } />
              </div>
            </div>
            <div className="group-field">
              <label>Description</label>
              <div className="field">
                <textarea value={ designEditData.description }></textarea>
              </div>
            </div>
            <div className="group-field">
              <label>Select Post Type</label>
              <div className="field">
                <select multiple>
                  {
                    PHP_DATA.post_types.map( ( postType ) => {
                      let selected = designEditData.support_post_type.includes( postType.name ) ? { selected: 'selected' } : {}
                      return <option value={ postType.name } { ...selected }>{ postType.label }</option>
                    } )
                  }
                </select>
              </div>
            </div>
            <div className="group-field">
              <label>Rating Fields</label>
              <div className="field repeater-field">
                {
                  designEditData.rating_fields &&
                  designEditData.rating_fields.map( ( ratingFieldData ) => {
                    return <RatingFieldItem ratingFieldData={ ratingFieldData } />
                  } )
                }
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  ) 
}