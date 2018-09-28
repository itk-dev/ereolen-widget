module.exports = {
    root: true,
    extends: [
        // add more generic rulesets here, such as:
        'standard',
        'eslint:recommended',
        'plugin:vue/recommended'
    ],
    rules: {
        'quotes': ['error', 'single'],
        'vue/html-indent': ['error', 4, {}],
        'vue/v-bind-style': 'longform',
        'vue/max-attributes-per-line': false,
        'vue/attributes-order': false
    },
    env: {
        node: true
    }
}
