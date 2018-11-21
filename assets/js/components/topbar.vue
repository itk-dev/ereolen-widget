<template>
    <div class="row topbar">
        <div class="col-3 col-sm-2 topbar-left">
            <p class="text-white mt-2 mb-2">Widgets</p>
        </div>
        <div class="col-9 col-sm-10 topbar-right">
            <nav class="navbar navbar-expand-sm mt-0 mb-0 ">
                <button class="navbar-toggler text-white float-right mt-0" type="button" data-toggle="collapse" data-target="#topbarRight" aria-controls="topbarRight" aria-expanded="false" aria-label="Toggle navigation">
                    <v-icon name="bars" />
                </button>
                <div class="collapse navbar-collapse" id="topbarRight">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{ active: 'build' === page }" v-on:click="navigate('build')" href="#">{{ $t('Build widget') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{ active: 'users_manual' === page }" v-on:click="navigate('users_manual')" href="#">{{ $t('How to') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{ active: 'about_widgets' === page }" v-on:click="navigate('about_widgets')" href="#">{{ $t('About widgets') }}</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'TopBar',
        data() {
            return {
                page: 'build'
            }
        },
        methods: {
            // @see https://flaviocopes.com/vue-components-communication/#using-an-event-bus-to-communicate-between-any-component
            navigate(target) {
                this.page = target
                this.$root.$emit('navigate', target)
            }
        }
    }
</script>
