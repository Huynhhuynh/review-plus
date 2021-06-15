import { useReviewDesign } from '../context/state'
/**
 * Review type item
 *
 */

const ratingIcons = {
  star: <svg viewBox="0 -10 511.98685 511" xmlns="http://www.w3.org/2000/svg"><path d="m510.652344 185.902344c-3.351563-10.367188-12.546875-17.730469-23.425782-18.710938l-147.773437-13.417968-58.433594-136.769532c-4.308593-10.023437-14.121093-16.511718-25.023437-16.511718s-20.714844 6.488281-25.023438 16.535156l-58.433594 136.746094-147.796874 13.417968c-10.859376 1.003906-20.03125 8.34375-23.402344 18.710938-3.371094 10.367187-.257813 21.738281 7.957031 28.90625l111.699219 97.960937-32.9375 145.089844c-2.410156 10.667969 1.730468 21.695313 10.582031 28.09375 4.757813 3.4375 10.324219 5.1875 15.9375 5.1875 4.839844 0 9.640625-1.304687 13.949219-3.882813l127.46875-76.183593 127.421875 76.183593c9.324219 5.609376 21.078125 5.097657 29.910156-1.304687 8.855469-6.417969 12.992187-17.449219 10.582031-28.09375l-32.9375-145.089844 111.699219-97.941406c8.214844-7.1875 11.351563-18.539063 7.980469-28.925781zm0 0"/></svg>,
}

export function RatingFieldFake( { name, slug, max_point, default_point, rating_icon } ) {
  let ratingNumber = [ ...Array( parseInt( max_point ) ).keys() ]

  return (
    <div className="rating-item">
      <label>{ name }</label>
      <div className="rating-select">
        {
          ratingNumber.map( ( num ) => {
            return <span key={ `${ num }:${ slug }` } className="rating-icon">
              { ratingIcons[ rating_icon ] }
            </span>
          } )
        }
      </div>
    </div>
  )
}

export function ReviewDesignItem( { designData } ) {
  const { setDesignEdit, deleteReviewDesign } = useReviewDesign()

  const onSetDesignEdit = () => {
    setDesignEdit( designData )
  }

  const onDeleteDesign = ( e ) => {
    e.preventDefault()
    let r = confirm( 'Delete this item?' )

    if( r == true )
      deleteReviewDesign( designData.id )
  }

  return (
    <div className="review-design-item">
      <div className={ [ 'review-design-item__inner', designData.enable ? '__is-enable' : '__is-disable' ].join( ' ' ) }>
        <div className="review-design-item__heading" style={ { backgroundColor: designData.theme_color } }>
          <h4 className="review-design-item__title">{ designData.label } (#{ designData.id })</h4>
          <div className="review-design-item__desc" dangerouslySetInnerHTML={{__html: designData.description}}></div>
        </div>
        <div className="review-design-item__meta">
          <ul className="review-design-item__meta-list">
            <li className="review-design-item__meta-item">
              <label>Post type</label>
              <div className="rp-support-post-type">{ designData.support_post_type.map( ( p ) => <span className="rp-tag">{ p }</span> ) }</div>
            </li>
            <li className="review-design-item__meta-item">
              <label>Theme</label>
              <div>{ designData.theme }</div>
            </li>
            <li className="review-design-item__meta-item">
              <label>Color</label>
              <div className="rd-color-ui">
                <span className="rd-color-ui__tag" style={ { backgroundColor: designData.theme_color } }></span>
                { designData.theme_color }
              </div>
            </li>
            <li className="review-design-item__meta-item">
              <label>Enable</label>
              <div>{ designData.enable ? 'yes' : 'no' }</div>
            </li>
            <li className="review-design-item__meta-item">
              <label>Rating Fields</label>
              <div>{ designData.rating_fields.length }</div>
            </li>
          </ul>
          <div className="review-design-item__button-actions">
            <a href="#" className="delete-design" onClick={ onDeleteDesign }>Delete design</a>
            <button className="edit-design" onClick={ onSetDesignEdit }>Edit</button>
          </div>
        </div>
      </div>
    </div>
  )
}

export function ReviewDesignLoop( { reviewDesign } ) {

  return (
    <div class="review-design-loop-container">
      {
        reviewDesign &&
        reviewDesign.map( ( designItem ) => {
          return <ReviewDesignItem designData={ designItem } key={ designItem.id } />
        } )
      }
    </div>
  )
}
