require('../scss/app.scss')
import Vue from 'vue'
 
import widget from './components/widget'
import topnav from './components/topnav'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faFileCode, faCopy } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faFileCode, faCopy)

Vue.component('font-awesome-icon', FontAwesomeIcon)
 
/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: {widget, topnav}
});