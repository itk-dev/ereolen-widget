require('../scss/app.scss');
import Vue from 'vue';
 
import widget from './components/widget'
 
/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: {widget}
});