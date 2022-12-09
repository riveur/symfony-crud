/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'bulma/css/bulma.min.css'

import { initToggleButtons } from './js/ButtonToggle.js'
import { initDeleteButton as initBulmaNotificationDeleteButton } from './js/BulmaNotification.js'

initToggleButtons()
initBulmaNotificationDeleteButton()

// start the Stimulus application
// import './bootstrap';
