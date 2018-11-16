<template>
    <div class="spinner" v-bind:style="cssProps">
        <div class="bounce1" />
        <div class="bounce2" />
        <div class="bounce3" />
    </div>
</template>

<script>
    export default {
        name: 'ItkSpinner',
        computed: {
            cssProps() {
                return {
                    '--spinner-size': '2.5rem'
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .spinner {
        width: var(--spinner-size);
        text-align: center;
        > div {
            width: calc(var(--spinner-size) / 4);
            height: calc(var(--spinner-size) / 4);
            /* stylelint-disable */
            background-color: var(--danger);
            /* stylelint-enable */
            border-radius: 100%;
            display: inline-block;
            animation: bouncedelay 1.4s infinite ease-in-out both;
        }
        .bounce1 {
            animation-delay: -.32s;
        }
        .bounce2 {
            animation-delay: -.16s;
        }
    }
    @keyframes bouncedelay {
        0%, 80%, 100% {
            transform: scale(0);
        } 40% {
            transform: scale(1.0);
        }
    }
</style>
