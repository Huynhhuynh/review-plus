import { useReviewPlus } from '../context/states'
import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'




export default function contentReply(props) {
  const { submitReply } = useReviewPlus()
  const thisprop = props
  const { postId } = useReviewPlus()
  const { reviewDesign } = useReviewPlus()
  const dataReplyAll = []
  const { register, setValue, handleSubmit, trigger, formState: { errors } } = useForm()
  const [ formSubmited, setFormSubmited ] = useState( false )
  const [ datahtml, setDatahtml ] = useState(  )
  const [ submitFormData, setSubmitFormData ] = useState({
    postId:postId,
    parent:thisprop.id_review,
    designId:reviewDesign[0].id
  })
  const onSubmitReply = async ( data ) => {
    let newSubmitFormData = { ...submitFormData, ...data }
    const result = await submitReply( newSubmitFormData )
  }
  // onSubmit={handleSubmit(onSubmitRely)}
  return (
    <>
      <div className="wrapper-reply-custom">
        <div className="form-reply">
          <form className="reply-plus-form form" onSubmit={ handleSubmit( onSubmitReply ) } >
            <div className="__field">
              <textarea
                { ...register( 'comment', { required: true } ) }
                className={ [ 'rp-comment', ( errors.comment ? '__is-invalid' : '' ) ].join( ' ' ) }
                defaultValue={ submitFormData.comment }
              >
              </textarea>{ errors.replycomment && <span className="__invalid-message">Please enter your comment!</span> }
            </div>
            <button type="submit">
              Reply
            </button>
          </form>
        </div>
        <div className="show-reply-comment">
          ssadas
        </div>
      </div>

    </>
  )




}
