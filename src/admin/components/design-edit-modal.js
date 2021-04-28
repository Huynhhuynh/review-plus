/**
 * Design edit
 */

export default function DesignEditModal( { designEditData } ) {

  if( designEditData == null ) return <></>

  return (
    <div className="design-edit-modal">
      <div className="design-edit-modal__inner">
        { JSON.stringify( designEditData ) }
      </div>
    </div>
  ) 
}