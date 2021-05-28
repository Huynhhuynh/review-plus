/**
 * Carbon fields Rating json field  
 * 
 */

import { Component } from '@wordpress/element'

class RatingJsonField extends Component {

  constructor( props ) {
    super( props )
    this.state = {
      label: this.props.field.label,
      name: this.props.name,
      value: this.props.value
    }

    this.onChangeValue = this.onChangeValue.bind( this )
  }

  onChangeValue( e ) {
    let value = e.target.value
    this.setState( { value } )
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const self = this
    const { label, name, value } = this.state
    return (
      <div className="rating-json-field-container">
        <table className="rp-table">
          <thead>
            <tr>
              <th className="__label">Label</th>
              <th className="__slug">Slug</th>
              <th className="__rate">Rate</th>
            </tr>
          </thead>
          <tbody>
            {
              value &&
              ( value.length > 0 ) &&
              value.map( item => {
                return <tr>
                  <td className="__label">
                    <input type="text" value={ item.name } />
                  </td>
                  <td className="__slug">
                    <input type="text" value={ item.slug } />
                  </td>
                  <td className="__rate">
                    <input type="number" value={ item.rate } />
                  </td>
                </tr>
              } )
            }
          </tbody>
        </table>
      </div>
    );
  }
}

export default RatingJsonField;