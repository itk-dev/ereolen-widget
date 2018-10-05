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
import 'vue-awesome/icons/code'
import 'vue-awesome/icons/copy'
import 'vue-awesome/icons/bars'
import 'vue-awesome/icons/search'
import 'vue-awesome/icons/plus'
import 'vue-awesome/icons/minus'
import 'vue-awesome/icons/angle-left'
import 'vue-awesome/icons/angle-right'

/**
* Require the scss for the app.
*/


/**
* Add the Icon component globally
*/
Vue.component('v-icon', Icon)
Vue.component('widget', Widget)
Vue.component('material', Material)

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
        components: {Widget, Material}
    })
}
