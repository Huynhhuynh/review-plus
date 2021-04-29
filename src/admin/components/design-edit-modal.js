import { useState, useEffect } from 'react'
import { Multiselect } from 'multiselect-react-dropdown'
/**
 * Design edit
 */

function RatingFieldItem( { ratingFieldData } ) {
  const [ fieldData, setFieldData ] = useState( ratingFieldData )

  return (
    <div className="rating-field-item-control">
      <div className="rating-field-item-control__title">{ fieldData.name }</div>
      <div className="rating-field-item-control__body">
        {/* { JSON.stringify( ratingFieldData ) } */}
        <div className="group-field">
          <label>Name</label>
          <div className="field">
            <input className="rp-field" type="text" value={ fieldData.name } />
          </div>
        </div>
        <div className="group-field">
          <label>Slug</label>
          <div className="field">
            <input className="rp-field" type="text" value={ fieldData.slug } />
          </div>
        </div>
        <div className="group-field">
          <label>Max Point</label>
          <div className="field">
            <input className="rp-field" type="number" value={ fieldData.max_point } />
          </div>
        </div>
        <div className="group-field">
          <label>Point Default</label>
          <div className="field">
            <input className="rp-field" type="number" value={ fieldData.default_point } />
          </div>
        </div>
        <div className="group-field">
          <label>Icon</label>
          <div className="field">
            <input className="rp-field" type="text" value={ fieldData.rating_icon } />
          </div>
        </div>
      </div>
    </div>
  )
}

export default function DesignEditModal( { designEditData } ) {

  if( designEditData == null ) return <></>

  let selected = PHP_DATA.post_types.filter( ( postType ) => {
    return designEditData.support_post_type.includes( postType.name )
  } )

  let postTypeOptions = {
    options: PHP_DATA.post_types,
    selectedValues: selected,
    onSelect: ( list, item ) => {
      console.log( list, item )
    },
    onRemove: ( list, item ) => {
      console.log( list, item )
    },
    displayValue: 'label',
    style: {
      chips: {
        'border-radius': '3px'
      }
    }
  }

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
                <input className="rp-field" type="text" value={ designEditData.label } />
              </div>
            </div>
            <div className="group-field">
              <label>Description</label>
              <div className="field">
                <textarea className="rp-field" value={ designEditData.description }></textarea>
              </div>
            </div>
            <div className="group-field">
              <label>Select Post Type</label>
              <div className="field">
                <Multiselect {...postTypeOptions} />
                {/* <select multiple>
                  {
                    PHP_DATA.post_types.map( ( postType ) => {
                      let selected = designEditData.support_post_type.includes( postType.name ) ? { selected: 'selected' } : {}
                      return <option value={ postType.name } { ...selected }>{ postType.label }</option>
                    } )
                  }
                </select> */}
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