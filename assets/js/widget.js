import Vue from 'vue'

import Widget from './components/widget'
import Material from './components/material'

/**
* Import vue-awesome fontawesome-icons (https://github.com/Justineo/vue-awesome)[https://github.com/Justineo/vue-awesome]
*/
import Icon from 'vue-awesome/components/Icon'
/**
* Import the individual font-awesome icons
*/
import 'vue-awesome/icons/angle-left'
import 'vue-awesome/icons/angle-right'

/**
* Import vue-slick https://github.com/staskjs/vue-slick
*/

import Slick from 'vue-slick';

/**
* Add the components globally
*/
Vue.component('v-icon', Icon)
Vue.component('widget', Widget)
Vue.component('material', Material)
Vue.component('vue-slick', Slick)

const container = document.getElementById('widget')

if (null !== container) {
    const config = (() => {
        try {
            return JSON.parse(container.getAttribute('data-configuration'))
        } catch (e) {
            return {}
        }
    })()

    /**
     * Create Vue Application instance with the id `app`
     */
    new Vue({
        el: container,
        config,
        components: {Widget, Material, Slick}
    })
}
