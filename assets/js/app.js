import Vue from 'vue'
import makeI18n from './i18n'

/**
* Import componenets
*/
import App from './components/app'
import Topbar from './components/topbar'
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

/**
* Require the scss for the app.
*/
require('../scss/app.scss')

/**
* Add the Icon component globally
*/
Vue.component('v-icon', Icon)
Vue.component('widget', Widget)
Vue.component('material', Material)

const container = document.getElementById('app')

if (null !== container) {
    const config = (() => {
        try {
            return JSON.parse(container.getAttribute('data-configuration'))
        } catch (e) {
            return {}
        }
    })()
    const i18n = makeI18n(config.locale || 'da')

    /**
     * Create Vue Application instance with the id `app`
     */
    new Vue({
        el: container,
        i18n,
        components: {App, Topbar, Widget, Material}
    })
}
