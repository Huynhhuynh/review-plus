import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import { useForm } from "react-hook-form"
import RatingField from './rating-field'
import { Multiselect } from 'multiselect-react-dropdown'
/**
 * Review form
 */

const NotLoggedFields = ( { submitFormData, register, errors } ) => {

  const { reviewDesign } = useReviewPlus();
  return (
    <>
      {
        reviewDesign &&
        reviewDesign.length > 0 &&
        reviewDesign.map( design => {
          if( design?.login_required == true ){
            return <div className="message-not-login">
                Please <a href="">Login</a> to leave a full review.<a href="#">Register</a>
            </div>
          }else{
            return <>
              <div className="one-line">
                  <div className="rp-field rp-field__name">
                    <label>
                      <span className="__label">Name *</span>
                      <div className="__field">
                        <input
                          { ...register( 'name', { required: true } ) }
                          type="text"
                          className={ [ 'rp-name', ( errors.name ? '__is-invalid' : '' ) ].join( ' ' ) }
                          defaultValue={ submitFormData.name }
                          />
                        { errors.name && <span className="__invalid-message">Please enter your name!</span> }
                      </div>
                    </label>
                  </div>
                  <div className="rp-field rp-field__email">
                    <label>
                      <span className="__label">Email *</span>
                      <div className="__field">
                        <input
                          { ...register( 'email', { required: true, pattern: /\S+@\S+\.\S+/ } ) }
                          type="text"
                          className={ [ 'rp-email', ( errors.email ? '__is-invalid' : '' ) ].join( ' ' ) }
                          defaultValue={ submitFormData.email }
                          />
                        { errors.email && <span className="__invalid-message">Please enter your E-mail!</span> }
                      </div>
                    </label>
                  </div>
              </div>
              <div className="rp-field rp-field__url">
                <label>
                  <span className="__label">Url</span>
                  <div className="__field">
                    <input
                      { ...register( 'url' ) }
                      type="text"
                      className="rp-url"
                      defaultValue={ submitFormData.url }
                    />
                  </div>
                </label>
              </div>
            </>
          }
        } )
      }

    </>
  )
}

export default function ReviewForm( { designData, postId } ) {
  const { submitReview } = useReviewPlus()
  const { register, setValue, handleSubmit, trigger, formState: { errors } } = useForm()
  const [ loading, setLoading ] = useState( false )
  const [ formSubmited, setFormSubmited ] = useState( false )
  const [ showReviewAlready, setshowReviewAlready ] = useState( false )
  const [ submitFormData, setSubmitFormData ] = useState( {
    postId,
    designId: designData.id,
    parent: 0,
    name: '',
    email: '',
    comment: '',
    url: '',
    pros:[],
    cons:[],
    categories:[],
    ratings: designData.rating_fields.map( item => {
      let { name, slug, default_point } = item
      return { name, slug, rate: parseInt( default_point ) }
    } )
  } )

  var prosOptionsData = designData.pros_fields.map( item => {
    let { id, name } = item
    return { id, name }
  } )
  const onUpdatePros = (selectedList, selectedItem) => {
      let prosList = selectedList.map( pros => pros.name )
      let _submitFormData = { ...submitFormData }
      let prosList_r =[]
      for (var i in prosList) {
        prosList_r.push({
          'name':prosList[i]
        })
      }
      _submitFormData.pros = prosList_r;
      setSubmitFormData(_submitFormData);
  }
  const prosOptions = {
    options: prosOptionsData,
    selectedValues: [],
    displayValue: 'name',
    onSelect: onUpdatePros,
    onRemove: onUpdatePros,
    placeholder: 'Pick Your Top 3 Pros',
    showCheckbox: true,
    selectionLimit: 3,
  }

  var consOptionsData = designData.cons_fields.map( item => {
    let { id, name } = item
    return { id, name }
  } )
  const onUpdateCons = (selectedList, selectedItem) => {
    let consList = selectedList.map( cons => cons.name )
    let conslist_r =[]
    for (var i in consList) {
      conslist_r.push({
        'name':consList[i]
      })
    }
    let _submitFormData = { ...submitFormData }
    _submitFormData.cons = conslist_r;
    setSubmitFormData(_submitFormData);
  }
  const consOptions = {
    options: consOptionsData,
    selectedValues: [],
    displayValue: 'name',
    onSelect: onUpdateCons,
    onRemove: onUpdateCons,
    placeholder: 'Pick Your Top 3 Cons',
    showCheckbox: true,
    selectionLimit: 3,
  }

  var categoriesOptionsData = designData.categories_fields.map( item => {
    let { id, score, name } = item
    return { id, score, name }
  } )
  const onUpdateCategories = (selectedList, selectedItem) => {
    let categoriesList = selectedList.map( categories => categories.name )
    let categoriesList_r =[]
    for (var i in categoriesList) {
      categoriesList_r.push({
        'name':categoriesList_r[i]
      })
    }
    let _submitFormData = { ...submitFormData }
    _submitFormData.categories = categoriesList_r;
    setSubmitFormData(_submitFormData);
  }
  const categoriesOptions = {
    options: categoriesOptionsData,
    selectedValues: [],
    displayValue: 'name',
    onSelect: onUpdateCategories,
    onRemove: onUpdateCategories,
    placeholder: 'Pick Your Categories',
    showCheckbox: true,
    // selectionLimit: 3,
  }

  const updateRatingField = ( slug, rate ) => {
    let _submitFormData = { ...submitFormData }
    let _ratings = [ ..._submitFormData.ratings ]
    let index = _ratings.findIndex( r => ( r.slug == slug ) )

    if( index == -1 ) return
    _ratings[ index ].rate = rate
    _submitFormData.ratings = _ratings

    setSubmitFormData( _submitFormData )
  }

  const updateField = ( name, value ) => {
    let _submitFormData = { ...submitFormData }
    _submitFormData[ name ] = value

    setSubmitFormData( _submitFormData )
  }

  const onSubmitReview = async ( data ) => {
    setLoading( true )
    let newSubmitFormData = { ...submitFormData, ...data }
    const result = await submitReview( newSubmitFormData )
    setLoading( false )
    if(result.success){
        setFormSubmited( true )
    }else{
        setFormSubmited( false )
        if(result.message){
            setshowReviewAlready( true );
        }else{
            setshowReviewAlready( false );
        }
    }
  }

  return (
    <>
      <div className="review-form-container">
        <h3 className="rp-title">{ designData.label }</h3>
        <p className="rp-desc">{ designData.description }</p>
        {
          ( formSubmited == true ) &&
          <div className="__rp-thank-you">
            <p>👌 Thanks for your review.</p>
          </div>
        }
        {
          (formSubmited == false) &&
          <form
            className="review-plus-form"
            onSubmit={ handleSubmit( onSubmitReview ) }>
            {
              designData.rating_fields.length > 0 &&
              <>
                <span className="__label">Your Rating *</span>
                <div className="rp-review-list">
                {
                  designData.rating_fields.map( ( r, index ) => {
                    return <RatingField
                      ratingOptions={ r }
                      label={ `ratings.${ index }` }
                      itemIndex={ index }
                      register={ register }
                      setValue={ setValue }
                      errors={ errors }
                      onChange={ ( rate, _field ) => {
                        updateRatingField( _field.slug, rate )
                        trigger( `ratings.${ index }.rate` )
                      } } />
                  } )
                }
                </div>
              </>
            }
            <div className="one-line">
            {
              designData.pros_fields.length > 0 &&
              <>
                <div>
                  <span className="__label">Pros</span>
                  <div className="rp-pros-list">
                      <Multiselect {...prosOptions} />
                  </div>
                </div>
              </>
            }
            {
              designData.cons_fields.length > 0 &&
              <>
                <div>
                  <span className="__label">Cons</span>
                  <div className="rp-cons-list">
                      <Multiselect {...consOptions} />
                  </div>
                </div>
              </>
            }
            </div>
            {
              designData.categories_fields.length > 0 &&
              <>
                <div>
                  <span className="__label">Categories</span>
                  <div className="rp-categories-list">
                      <Multiselect {...catgoriesOptions} />
                  </div>
                </div>
              </>
            }
            {
              PHP_DATA.user_logged_in == 'yes' &&
              <div className="rp-field rp-field__comment">
                <label>
                  <span className="__label">Comment *</span>
                  <div className="__field">
                    <textarea
                      { ...register( 'comment', { required: true } ) }
                      className={ [ 'rp-comment', ( errors.comment ? '__is-invalid' : '' ) ].join( ' ' ) }
                      defaultValue={ submitFormData.comment }
                      ></textarea>{ errors.comment && <span className="__invalid-message">Please enter your comment!</span> }
                  </div>
                </label>
              </div>
            }
            {
              PHP_DATA.user_logged_in != 'yes' &&
              <NotLoggedFields submitFormData={ submitFormData } register={ register } errors={ errors } />
            }
            {
                (showReviewAlready==true) &&
                <div className="text-already-submit">
                    You have already submitted this review
                </div>
            }
            <button
              type="submit"
              className={ [ 'review-button-submit', ( loading ? '__is-loading' : '' ) ].join( ' ' ) }>
              { loading ? 'Please waiting...' : 'Submit Review' }
            </button>
          </form>
        }

      </div>
      </>
  )
}
