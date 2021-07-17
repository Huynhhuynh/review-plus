/**
 * Loader fields
 */

import { registerFieldType } from '@carbon-fields/core'
import RatingJsonField from './rating-json'

registerFieldType( 'ratingjson', RatingJsonField );
