import { v4 as uuidv4 } from 'uuid';

export function reviewItemTemplate() {
  return {
    id: uuidv4(),
    name: '',
    slug: '',
    rating_icon: 'start',
    max_point: 5,
    default_point: 0,
  }
}

export function prosConsItemTemplate() {
  return {
    id: uuidv4(),
    name: '',
  }
}

export function designItemTemplate() {
  return {
    'id': uuidv4(),
    'label': 'New Design',
    'description': 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...',
    'support_post_type': [],
    'theme': 'default',
    'theme_color': '#3f51b5',
    'enable': false,
    'login_required':true,
    'rating_fields': [],
    'pros_fields': [],
    'cons_fields': [],
  }
}
