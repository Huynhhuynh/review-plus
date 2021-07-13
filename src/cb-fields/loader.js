/**
 * Loader fields
 */

import { registerFieldType } from '@carbon-fields/core'
import RatingJsonField from './rating-json'
// import ProsJsonField from './pros-json'
// import ConsJsonField from './cons-json'

registerFieldType( 'ratingjson', RatingJsonField );
// registerFieldType( 'prosjson', ProsJsonField );
// registerFieldType( 'consjson', ConsJsonField );
