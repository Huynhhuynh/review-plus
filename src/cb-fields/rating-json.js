/**
 * Carbon fields Rating json field  
 * 
 */

import { Component } from '@wordpress/element'

class RatingJsonField extends Component {

  constructor( props ) {
    super( props )
    this.state = {
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
    const { name, value } = this.state

    return (
      <div>
        <textarea
					type="text"
					name={ name }
					value={ value }
          onChange={ self.onChangeValue }
				></textarea>
      </div>
    );
  }
}

export default RatingJsonField;