import { useState, useEffect } from 'react'
import { Multiselect } from 'multiselect-react-dropdown'
import Switch from 'react-switch'
import { useReviewDesign } from '../context/state'
import { BlockPicker } from 'react-color'

// const _ = require( 'lodash' )
import { findIndex } from 'lodash'

// import { CKEditor } from '@ckeditor/ckeditor5-react'
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

/**
 * Design edit
 */

const ControlBar = ( { ratingFieldId } ) => {
  const { moveRatingFieldEdit, removeRatingFieldItem } = useReviewDesign()

  const onMove = ( move ) => {
    moveRatingFieldEdit( ratingFieldId, move )
  }

  const onRemoveItem = () => {
    let r = confirm( 'Remove this item?' )
    if( r ) removeRatingFieldItem( ratingFieldId )
  }

  return (
    <div className="rating-field-item-control__control">
      <button type="button" className="__move-up" onClick={ e => { onMove( 'up' ) } }>↑ Move Up</button>
      <button type="button" className="__move-down" onClick={ e => { onMove( 'down' ) } }>↓ Move Down</button>
      <button type="button" className="__remove-item" onClick={ onRemoveItem }>Remove Item</button>
    </div>
  )
}


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
        <ControlBar 
          ratingFieldId={ fieldData.id }
        />
      </fieldset>
    </div>
  )
}

function ColorSelector( { color, onChange } ) {
  const [ open, setOpen ] = useState( false )

  const onOpen = ( e ) => {
    e.preventDefault()
    setOpen( true )
  }

  const onClose = ( e ) => {
    e.preventDefault()
    setOpen( false )
  }

  const pickColor = {
    backgroundColor: color
  }

  const cover = {
    position: 'fixed',
    top: '0px',
    right: '0px',
    bottom: '0px',
    left: '0px',
  }

  const _onChange = ( color ) => {
    if( onChange ) onChange( color )
  }

  return (
    <div className="rp-color-field">
      <span className="__pick-color" style={ pickColor } onClick={ onOpen }></span>
      {
        open == true &&
        <>
          <div style={ cover } onClick={ onClose } style={ cover }/>
          <div className="__popover-pick-color">
            <BlockPicker color={ color } onChange={ _onChange } />
          </div>
        </>
      }
    </div>
  )
}

export default function DesignEditModal() {
  const { reviewDesignData, designEdit, setDesignEdit, updateReviewDesignItem, addRatingFieldItem, newReviewDesignItem, groupPostTax } = useReviewDesign()
  if( designEdit == null ) return <></>
  
  const [ _groupPostTax, _setGroupPostTax ] = useState( [] )

  const buildGroupOptions = ( postTypes = [] ) => {
    let _groupPostTax = {...groupPostTax}
    let options = []
    
    Object.keys( _groupPostTax ).forEach( ( postTypeName ) => { 
      let taxs = _groupPostTax[ postTypeName ]
      if( ! taxs || taxs.length <= 0 ) return
      
      taxs.forEach( ( tax ) => {
        let taxLabel = tax.tax_label
        let taxName = tax.tax_name
        let terms = tax.terms

        // Conver object to array
        if( terms && typeof terms == 'object' ) {
          terms = Object.values( terms )
        }

        if( terms && terms.length > 0 ) {
          terms.forEach( t => {
            console.log( t )
            options.push( {
              group: `${ taxLabel } (${ postTypeName })`,
              tax: taxName,
              term_label: t.name,
              term_id: t.term_id,
            } )
          } )
        }
      } )
    } )
    
    return options
  }

  const isEdit = ( () => {
    let find = findIndex( reviewDesignData, d => {
      return d.id == designEdit.id
    } )
    
    return find === -1 ? false : true
  } )()

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
    if ( isEdit ) {
      // update
      updateReviewDesignItem( designEdit.id, designEdit )
    } else {
      // add new
      newReviewDesignItem( designEdit )
    }
    
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

  const onUpdateCategory = ( list, item ) => {
    onUpdateField( list, 'support_category' )
  }

  let categoryOptions = {
    options: [ ...buildGroupOptions() ],
    selectedValues: designEdit.support_category,
    groupBy: 'group',
    displayValue: 'term_label',
    onSelect: onUpdateCategory,
    onRemove: onUpdateCategory,
    style: {
      searchBox: { 'border-radius': '1px' },
      chips: { 'border-radius': '30px', 'background': '#3f51b5' }
    }
  }

  return (
    <div className="design-edit-modal">
      <div className="design-edit-modal__inner">
        <div className="design-edit-modal__heading">{ isEdit ? 'Edit' : 'New' } Design</div>
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
                  } } >  
                </textarea>
                {/* <CKEditor
                  editor={ ClassicEditor } 
                  config={ {
                    toolbar: [ 'bold', 'italic' ],
                  } }
                  data={ designEdit.description }
                  onChange={ ( event, editor ) => {
                    const data = editor.getData();
                    onUpdateField( data, 'description' )
                  } }
                /> */}
              </div>
            </div>
            <div className="group-field __inline">
              <div className="field" style={ { width: '54px' } }>
                <ColorSelector color={ designEdit.theme_color } onChange={ color => {
                  onUpdateField( color.hex, 'theme_color' )
                } } />
              </div>
              <label>Theme Color</label>
            </div>
            <div className="group-field">
              <label>Select Post Type</label>
              <div className="field">
                <Multiselect {...postTypeOptions} />
              </div>
            </div>
            <div className="group-field">
              <label>Select Category (Limit category to display review form)</label>
              <div className="field">
                <Multiselect {...categoryOptions} />
              </div>
            </div>
            <div className="group-field">
              <label>Rating Fields</label>
              <div className="field repeater-field">
                {
                  designEdit.rating_fields &&
                  (designEdit.rating_fields.length > 0) &&
                  designEdit.rating_fields.map( ( ratingFieldData, index ) => {
                    return <RatingFieldItem 
                      key={ ratingFieldData.id }
                      ratingFieldData={ ratingFieldData } 
                      onUpdate={ updateFieldData => {
                        let newRatingFields = [ ...designEdit.rating_fields ]
                        newRatingFields[ index ] = updateFieldData
                        onUpdateField( newRatingFields, 'rating_fields' )
                      } } />
                  } )
                }
              </div>
              <button type="button" className="button-add-more-rating-field" onClick={ e => {
                e.preventDefault()
                addRatingFieldItem()
              } }>Add Item</button>
            </div>
          </form>
          <div className="modal-actions">
            <button className="modal-button modal-button__close" onClick={ onCloseModal }>Cancel</button>
            <button className="modal-button modal-button__save" onClick={ onSave }>{ isEdit ? 'Update' : 'Add Design' }</button>
          </div>
        </div>
      </div>
    </div>
  ) 
}