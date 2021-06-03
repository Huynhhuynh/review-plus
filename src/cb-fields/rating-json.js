import { v4 as uuidv4 } from 'uuid';

/**
 * Carbon fields Rating json field  
 * 
 */

import { Component } from '@wordpress/element'

class RatingJsonField extends Component {

  constructor( props ) {
    super( props )
    // console.log( this.props )
    this.state = {
      label: this.props.field.label,
      name: this.props.name,
      value: this.props.value ? this.props.value.map( item => {
        item.id = uuidv4()
        return item
      } ) : [],
      moreItem: {
        name: '',
        slug: '',
        rate: 0
      }
    }

    this.onChangeRating = this.onChangeRating.bind( this )
    this.onChangeMoreItem = this.onChangeMoreItem.bind( this )
    this.onPushNewRateItem = this.onPushNewRateItem.bind( this )
    this.onDeleteRateItem = this.onDeleteRateItem.bind( this )
  }

  onChangeRating( value, name, id ) {
    let _value = [ ...this.state.value ]
    let index = _value.findIndex( e => ( e.id == id ) )

    if( index == -1 ) return

    _value[ index ][ name ] = value
    this.setState( { value: _value } )
  }

  onChangeMoreItem( value, name ) {
    let _value = { ...this.state.moreItem }
    _value[ name ] = value
    this.setState( {  moreItem: _value } )
  }

  onPushNewRateItem() {
    let _value = [ ...this.state.value ]
    let newRate = { ...this.state.moreItem }
    newRate.id = uuidv4()

    _value.push( newRate )
    this.setState( { value: _value } )
    this.setState( { moreItem: {
      name: '',
      slug: '',
      rate: 0
    } } )
  }

  onDeleteRateItem( id ) {
    let r = confirm( 'Delete this item?' )
    if( r != true ) return

    let _value = [ ...this.state.value ]
    let index = _value.findIndex( e => ( e.id == id ) )

    _value.splice( index, 1 )
    this.setState( { value: _value } )
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const self = this
    const { label, name, value, moreItem } = this.state
    
    return (
      <div className="rating-json-field-container">
        <table className="rp-table">
          <thead>
            <tr>
              <th className="__label">Label</th>
              <th className="__slug">Slug</th>
              <th className="__rate">⭐ Rate</th>
              <th className="__actions"></th>
            </tr>
          </thead>
          <tbody> 
            {
              value &&
              ( value.length > 0 ) &&
              value.map( ( item, index ) => { 
                return <tr key={ item.id }>
                  <td className="__label">
                    <input 
                      type="text" 
                      name={ `${ name }[${ index }][name]` }
                      value={ item.name } 
                      onChange={ e => {
                        self.onChangeRating( e.target.value, 'name', item.id )
                      } } />
                  </td>
                  <td className="__slug">
                    <input 
                      type="text" 
                      name={ `${ name }[${ index }][slug]` }
                      value={ item.slug } 
                      onChange={ e => {
                        self.onChangeRating( e.target.value, 'slug', item.id )
                      } }/>
                  </td>
                  <td className="__rate">
                    <input 
                      type="number" 
                      name={ `${ name }[${ index }][rate]` }
                      value={ item.rate } 
                      onChange={ e => {
                        self.onChangeRating( e.target.value, 'rate', item.id )
                      } }/>
                  </td>
                  <td className="__actions">
                    <button type="button" onClick={ e => {
                      e.preventDefault()
                      self.onDeleteRateItem( item.id )
                    } }>Delete</button>
                  </td>
                </tr>
              } )
            }
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4">
                <strong>Add More Rate Item</strong>
              </td>
            </tr>
            <tr>
              <td>
                <input 
                  type="text" 
                  placeholder="Type label here..." 
                  value={ moreItem.name } 
                  onChange={ e => self.onChangeMoreItem( e.target.value, 'name' ) } />
              </td>
              <td>
                <input 
                  type="text" 
                  placeholder="Type slug here..." 
                  value={ moreItem.slug }
                  onChange={ e => self.onChangeMoreItem( e.target.value, 'slug' ) } />
              </td>
              <td>
                <input 
                  type="number" 
                  value={ moreItem.rate } 
                  onChange={ e => self.onChangeMoreItem( e.target.value, 'rate' ) } />
              </td>
              <td className="__actions">
                <button 
                  type="button" 
                  onClick={ e => {
                    e.preventDefault()
                    self.onPushNewRateItem()
                  } }>Add Item</button>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    );
  }
}

export default RatingJsonField;