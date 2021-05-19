import { useState, useEffect } from 'react'
import { Multiselect } from 'multiselect-react-dropdown'
import Switch from 'react-switch'
import { useReviewDesign } from '../context/state'

/**
 * Design edit
 */

function RatingFieldItem( { ratingFieldData, onUpdate } ) {
  const [ fieldData, setFieldData ] = useState( ratingFieldData )

  const onUpdateField = ( value, fieldName ) => {
    let newData = { ...fieldData }
    newData[ fieldName ] = value

    setFieldData( newData )
    onUpdate ? onUpdate( newData ) : ''
  }

  return (
    <div className="rating-field-item-control">
      <fieldset>
        <legend>{ fieldData.name }</legend>
        <div className="rating-field-item-control__body">
          <div className="group-field">
            <label>Name</label>
            <div className="field">
              <input 
                className="rp-field" 
                type="text" 
                value={ fieldData.name } 
                onChange={ e => {
                  onUpdateField( e.target.value, 'name' )
                } } />
            </div>
          </div>
          <div className="group-field">
            <label>Slug</label>
            <div className="field">
              <input 
                className="rp-field" 
                type="text" 
                value={ fieldData.slug } 
                onChange={ e => {
                  onUpdateField( e.target.value, 'slug' )
                } } />
            </div>
          </div>
          <div className="group-field">
            <label>Icon</label>
            <div className="field">
              <input 
                className="rp-field" 
                type="text" 
                value={ fieldData.rating_icon } 
                onChange={ e => {
                  onUpdateField( e.target.value, 'default_point' )
                } } />
            </div>
          </div>
          <div className="group-field">
            <label>Max Point</label>
            <div className="field">
              <input 
                className="rp-field" 
                type="number" 
                value={ fieldData.max_point } 
                onChange={ e => {
                  onUpdateField( e.target.value, 'max_point' )
                } } />
            </div>
          </div>
          <div className="group-field">
            <label>Default</label>
            <div className="field">
              <input 
                className="rp-field" 
                type="number" 
                value={ fieldData.default_point } 
                onChange={ e => {
                  onUpdateField( e.target.value, 'default_point' )
                } } />
            </div>
          </div>
        </div>
      </fieldset>
    </div>
  )
}

export default function DesignEditModal() {
  const { designEdit, setDesignEdit, updateReviewDesignItem } = useReviewDesign()
  if( designEdit == null ) return <></>
  

  let selected = PHP_DATA.post_types.filter( ( postType ) => {
    return designEdit.support_post_type.includes( postType.name )
  } )

  const onUpdateField = ( value, fieldName ) => {
    let newData = { ...designEdit }
    newData[ fieldName ] = value
    setDesignEdit( newData )
  }

  const onUpdatePosttype = ( list, item ) => {
    let nameList = list.map( pt => pt.name )
    onUpdateField( nameList, 'support_post_type' )
  }

  const onCloseModal = () => {
    setDesignEdit( null )
  }

  const onSave = () => {
    updateReviewDesignItem( designEdit.id, designEdit )
    onCloseModal()
  }

  let postTypeOptions = {
    options: PHP_DATA.post_types,
    selectedValues: selected,
    onSelect: onUpdatePosttype,
    onRemove: onUpdatePosttype,
    displayValue: 'label',
    style: {
      searchBox: { 'border-radius': '1px' },
      chips: { 'border-radius': '30px', 'background': '#3f51b5' }
    }
  }

  return (
    <div className="design-edit-modal">
      <div className="design-edit-modal__inner">
        <div className="design-edit-modal__heading">Edit Design</div>
        <div className="design-edit-modal__body">
          <form className="rp-form">
            <div className="group-field __inline">
              <div className="field" style={ { width: '80px' } }>
                <Switch 
                  onColor={ '#3f51b5' }
                  checkedIcon={ false }
                  uncheckedIcon={ false }
                  onChange={ checked => { onUpdateField( checked, 'enable' ) } } 
                  checked={ designEdit.enable } 
                />
              </div>
              <label>Enable Review</label>
            </div>
            <div className="group-field">
              <label>Label</label>
              <div className="field">
                <input 
                  className="rp-field" 
                  type="text" 
                  value={ designEdit.label } 
                  onChange={ e => {
                    onUpdateField( e.target.value, 'label' )
                  } } />
              </div>
            </div>
            <div className="group-field">
              <label>Description</label>
              <div className="field">
                <textarea 
                  className="rp-field" 
                  value={ designEdit.description } 
                  onChange={ e => {
                    onUpdateField( e.target.value, 'description' )
                  } } ></textarea>
              </div>
            </div>
            <div className="group-field">
              <label>Select Post Type</label>
              <div className="field">
                <Multiselect {...postTypeOptions} />
              </div>
            </div>
            <div className="group-field">
              <label>Rating Fields</label>
              <div className="field repeater-field">
                {
                  designEdit.rating_fields &&
                  designEdit.rating_fields.map( ( ratingFieldData, index ) => {
                    return <RatingFieldItem 
                      ratingFieldData={ ratingFieldData } 
                      onUpdate={ updateFieldData => {
                        let newRatingFields = [ ...designEdit.rating_fields ]
                        newRatingFields[ index ] = updateFieldData
                        onUpdateField( newRatingFields, 'rating_fields' )
                      } } />
                  } )
                }
              </div>
            </div>
          </form>
          <div className="modal-actions">
            <button className="modal-button modal-button__close" onClick={ onCloseModal }>Cancel</button>
            <button className="modal-button modal-button__save" onClick={ onSave }>Update</button>
          </div>
        </div>
      </div>
    </div>
  ) 
}