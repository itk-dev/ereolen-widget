module.exports = {
    root: true,
    extends: [
        // add more generic rulesets here, such as:
        'standard',
        'eslint:recommended',
        'plugin:vue/recommended'
    ],
    rules: {
        'brace-style': ['error', '1tbs'],
        'space-before-blocks': ['error', 'always'],
        'object-curly-spacing': ['error', 'never'],
        'array-bracket-spacing': ['error', 'never'],
        'func-call-spacing': ['error', 'never'],
        'comma-style': ['error', 'last'],
        'comma-spacing': 'error',
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
