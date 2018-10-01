module.exports = {
    root: true,
    extends: [
        // add more generic rulesets here, such as:
        'standard',
        'eslint:recommended',
        'plugin:vue/recommended'
    ],
    rules: {
        // @see https://eslint.org/docs/rules/
        'array-bracket-spacing': ['error', 'never'],
        'brace-style': ['error', '1tbs'],
        'comma-dangle': ['error', 'never'],
        'comma-spacing': 'error',
        'comma-style': ['error', 'last'],
        'func-call-spacing': ['error', 'never'],
        'indent': ['error', 4, { MemberExpression: 0 }],
        'key-spacing': ['error'],
        'object-curly-spacing': ['error', 'never'],
        'quotes': ['error', 'single'],
        'semi': ['error', 'never'],
        'space-before-blocks': ['error', 'always'],
        // @see https://github.com/vuejs/eslint-plugin-vue#priority-a-essential-error-prevention
        'vue/attributes-order': false,
        'vue/html-indent': ['error', 4, {}],
        'vue/max-attributes-per-line': false,
        'vue/no-v-html': false,
        'vue/script-indent': ['error', 4, { baseIndent: 1 }],
        'vue/v-bind-style': 'longform',
        'vue/v-on-style': 'longform'
    },
    overrides: [
        // https://github.com/vuejs/eslint-plugin-vue/blob/master/docs/rules/script-indent.md#important-note
        {
            files: ['*.vue'],
            rules: {
                indent: 'off'
            }
        }
    ],
    env: {
        node: true
    }
}
