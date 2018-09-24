require('../scss/app.scss')
import Vue from 'vue'
 
import widget from './components/widget'
import topnav from './components/topnav'
import Icon from 'vue-awesome/components/Icon'
import 'vue-awesome/icons/code'
import 'vue-awesome/icons/copy'
import 'vue-awesome/icons/bars'

// globally (in your main .js file)
Vue.component('v-icon', Icon)


/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: {widget, topnav}
});