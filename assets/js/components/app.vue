<template>
    <div class="row">
        <div class="col-sm-2 sidebar pt-3 pb-3">
            <nav class="nav flex-column">
                <a class="nav-link active" href="#">Materiale karrusel</a>
            </nav>
        </div>
        <div class="col-sm-10 main pt-3 pb-3">
            <div v-if="'build' !== page" class="widget-page" v-html="pages[page]" />

            <div v-else id="build" class="widget-builder">
                <h1>{{ $t('Build your carousel') }}</h1>
                <form>
                    <fieldset>
                        <h3>{{ $t('Widget content') }}</h3>
                        <div v-if="messages.length > 0">
                            <div v-for="message in messages" class="alert alert-fixed-bottom" v-bind:class="['alert-'+message.type]" v-bind:key="message.id">
                                {{ message.text }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="dismissMessage(message)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-auto">
                                    <p>{{ $t('Choose how to add content to the widget carousel') }}</p>
                                </div>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="content" v-bind:value="SearchTypes.MANUAL" checked v-model="search.type">
                                            {{ $t('Add manually') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="content" v-bind:value="SearchTypes.URL" v-model="search.type">
                                            {{ $t('Use search from eReolen') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10 col-lg-8">
                                <div class="form-group" v-if="search.type === SearchTypes.MANUAL">
                                    <label for="contentSearchManual">{{ $t('Search for materials you want to show in the carousel') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><v-icon name="search" /></div>
                                        </div>
                                        <input type="text" class="form-control" name="contentSearchManual" id="contentSearchManual" v-bind:placeholder="$t('Enter author, title, isbn or publisher')" v-model="search.query" v-on:keyup.enter="debouncedSearch">
                                        <div class="input-group-append">
                                            <itk-spinner v-show="searchState" class="itk-spinner fixed-on-input" />
                                        </div>
                                    </div>
                                    <small id="widgetContentSearchManualHelp" class="form-text text-muted">{{ $t('The search will return up to 10 results. If the material you are searching for does not appear then please try to add another keyword.') }}</small>
                                </div>
                                <div class="form-group" v-if="search.type === SearchTypes.URL">
                                    <label for="contentSearchSearch" v-html="$t('Do a search on {ereolen_searchLink} and paste the url here', {ereolen_searchLink: widgetContext.searchLink})" />
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{ $t('URL') }}</div>
                                        </div>
                                        <input type="text" class="form-control" name="contentSearchSearch" id="contentSearchSearch" aria-describedby="contentSearchSearchHelp" v-bind:placeholder="$t('Example: {ereolen_searchUrl}/dennis', {ereolen_searchUrl: widgetContext.searchUrl})" v-model="search.url" v-on:keyup.enter="debouncedSearch">
                                        <div class="input-group-append">
                                            <itk-spinner v-show="searchState" class="itk-spinner fixed-on-input" />
                                        </div>
                                    </div>
                                    <small id="contentSearchSearchHelp" class="form-text text-muted" v-html="$t('Perform a search on {ereolen_searchLink} and copy the url in the address bar. Then paste it here.', {ereolen_searchLink: widgetContext.searchLink})" />
                                </div>
                            </div>
                        </div>
                        <div class="row content-search" v-if="search.type === SearchTypes.MANUAL">
                            <div class="col-sm-12" v-show="searchState">
                                <p v-html="searchMessage" />
                            </div>
                            <div class="col-sm-12" v-if="searchResult && searchResult.data && searchResult.data.length > 0">
                                <label>{{ $t('Search result') }}: <strong>{{ $t('Click on a material to add it to the carousel') }}</strong></label><a href="#" class="btn btn-success btn-sm text-light ml-2" @click="addAllMaterials">{{ $t('Add all materials to carousel') }}</a>
                                <div class="row content-search-results">
                                    <material v-for="material in searchResult.data" v-bind:key="material.id" v-bind:data="material" v-bind:id="material.id" v-bind:title="material.title" v-bind:cover="material.cover" v-bind:url="material.url" icon="plus" v-bind:action.prevent="addMaterial" />
                                </div>
                            </div>
                            <div class="col-sm-12" v-if="widgetContentManual.length > 0">
                                <label>{{ $t('Materials in the carousel') }}: <strong>{{ $t('Click on a material to remove it from the carousel') }}</strong></label><a href="#" class="btn btn-danger btn-sm text-light ml-2" @click="removeAllMaterials">{{ $t('Remove all materials from carousel') }}</a>
                                <div class="row content-search-results added">
                                    <material v-for="material in widgetContentManual" v-bind:key="material.id" v-bind:data="material" v-bind:id="material.id" v-bind:title="material.title" v-bind:cover="material.cover" v-bind:url="material.url" icon="minus" v-bind:action.prevent="removeMaterial" />
                                </div>
                            </div>
                        </div>
                        <div class="row content-search" v-if="search.type === SearchTypes.URL">
                            <div class="col-sm-12" v-show="searchState">
                                <p v-html="searchMessage" />
                            </div>
                            <div class="col-sm-12" v-if="widgetContentUrl.length > 0">
                                <p>{{ $t('Search result loaded. Preview is updated.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10 col-lg-8">
                                <div class="form-group">
                                    <label for="widgetTitle">{{ $t('Widget title') }}</label>
                                    <input type="text" class="form-control" name="widgetTitle" id="widgetTitle" aria-describedby="widgetTitleHelp" v-bind:placeholder="$t('Fx New titles')" v-model="widgetTitle">
                                    <small id="widgetTitleHelp" class="form-text text-muted">{{ $t('The widget title is displayed as a heading in the widgeten') }}</small>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <h3>{{ $t('Widget display settings') }}</h3>
                        <div class="row">
                            <div class="col-sm-10 col-lg-8">
                                <div class="form-group">
                                    <label for="widget_theme">{{ $t('Widget color') }}</label>
                                    <select class="form-control" name="widget_theme" id="widget_theme" v-model="widgetConfiguration.theme">
                                        <option v-for="theme in widgetThemes" v-bind:value="theme" v-bind:key="theme.label">
                                            {{ $t(theme.label) }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="widget_size">{{ $t('Widget size') }}</label>
                                    <select class="form-control" name="widget_size" id="widget_size" v-model="widgetConfiguration.size">
                                        <option v-for="size in widgetSizes" v-bind:value="size" v-bind:key="size.label">
                                            {{ size.label }} {{ size.width }}x{{ size.height }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <h3>{{ $t('Widget preview') }}</h3>
                        <div class="widget-preview bg-white">
                            <itk-spinner v-if="saveState" class="itk-spinner fixed-on-preview" />
                            <widget v-bind:size="widgetConfiguration.size" v-bind:theme="widgetConfiguration.theme" v-bind:title="widgetTitle" v-bind:data="widgetContent" v-bind:context="widgetContext" />
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="row">
                            <div class="col-sm-10 col-lg-8">
                                <h3>{{ $t('Widget embed code') }}</h3>
                                <label>{{ $t('Insert this code on your website to display the widget.') }}</label>
                                <div class="code-preview">
                                    <div class="code-preview-header">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="code-preview-header-title">
                                                    <v-icon name="code" />{{ $t('HTML') }}
                                                </span>
                                            </div>
                                            <div class="col-auto ml-auto">
                                                <button type="button" class="code-preview-header-copy" v-on:click="doCopyEmbedCode">
                                                    <v-icon name="copy" />{{ $t('Copy') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="code-preview-content">
                                        <pre><code>
                                            {{ embedCode.code }}
                                        </code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'

    Vue.mixin({
        beforeCreate () {
            const options = this.$options
            if (options.config) {
                this.$config = options.config
            } else if (options.parent && options.parent.$config) {
                this.$config = options.parent.$config
            }
        }
    })

    const SearchTypes = {
        MANUAL: 'manual',
        URL: 'url'
    }
    const axios = require('axios')
    const debounce = require('debounce')
    const queryString = require('query-string')

    const CancelToken = axios.CancelToken
    let cancelSearch = null
    let cancelSave = null

    const widgetThemes = [
        {label: 'theme.light', class: 'theme-light'},
        {label: 'theme.dark', class: 'theme-dark'}
    ]

    const widgetSizes = [
        {label: 'Lille kvadrat', width: 200, height: 200},
        {label: 'Kvadrat', width: 250, height: 250},
        {label: 'Mellemstor rektangel', width: 300, height: 250},
        {label: 'Stor rektangel', width: 336, height: 280},
        {label: 'Skyskraber', width: 120, height: 600},
        {label: 'Bred skyskraber', width: 160, height: 600},
        {label: 'Halvside', width: 300, height: 600},
        {label: 'Banner', width: 468, height: 60},
        {label: 'Stort leaderboard', width: 970, height: 90},
        {label: 'Mobilbanner', width: 320, height: 50},
        {label: 'Mobilbanner 2', width: 320, height: 100}
    ]

    // Dismiss messages afthe amount of milliseconds.
    const messageDismissDelay = 2000

    export default {
        name: 'App',
        data() {
            return {
                pages: {},
                // The page we're currently showing
                page: 'build',
                SearchTypes: SearchTypes,
                widgetThemes: widgetThemes,
                widgetSizes: widgetSizes,
                widgetContext: this.$config.context,
                // The search query.
                search: {
                    type: SearchTypes.MANUAL,
                    // "Manual" search
                    query: null,
                    // Search by ereolen.dk url
                    url: null
                },
                saveState: null,
                searchState: null,
                // User message, e.g. "Searching for James Hetfield …"
                searchMessage: null,
                // Any errors reported while searching.
                searchError: null,
                // The search result after a succesful search.
                searchResult: null,
                copySucceeded: null,
                // User messages (alerts)
                messages: [],

                // The actual widget (stored in database)
                //  {
                //   id
                //   title
                //   configuration
                //   content
                //  }
                widget: null,
                widgetTitle: '',
                widgetConfiguration: {
                    theme: widgetThemes[0],
                    size: widgetSizes[0]
                },
                // The selected materials
                widgetContentManual: [],
                // Widget content from search by url.
                widgetContentUrl: []
            }
        },
        computed: {
            widgetContent() {
                return SearchTypes.MANUAL === this.search.type ? this.widgetContentManual : this.widgetContentUrl
            },
            embedUrl() {
                return (this.widget && this.widget.id) ? this.$config.widgetEmbedUrl.replace('{id}', this.widget.id) : null
            },
            embedCode() {
                const url = this.embedUrl
                return {
                    code: '<iframe src="'+url+'" width="' + this.widgetConfiguration.size.width + '" height="' + this.widgetConfiguration.size.height + '" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowtransparency="true"></iframe>'
                }
            }
        },
        mounted() {
            // @see https://flaviocopes.com/vue-components-communication/#using-an-event-bus-to-communicate-between-any-component
            this.$root.$on('navigate', (target) => {
                this.page = target
            })
        },
        created: function() {
            // Load widget data.
            try {
                const el = document.getElementById('app-widget-data')
                const data = JSON.parse(el.textContent)
                this.loadWidgetData(data)
            } catch (e) {
                // continue regardless of error
            }

            // Load pages
            try {
                const el = document.getElementById('app-pages')
                this.pages = JSON.parse(el.textContent)
            } catch (e) {
                // continue regardless of error
            }

            this.debouncedSearch = debounce(this.doSearch, 500)
            this.debouncedSave = debounce(this.doSave, 500)
            const search = queryString.parse(location.hash)
            if ('query' in search) {
                this.search.query = search['query']
            }

            // @see https://github.com/vuejs/vue/issues/844#issuecomment-390498696
            this.$watch((vm) => (vm.search.type, vm.search.query, vm.search.url, Date.now()), function() {
                this.debouncedSearch()
            })

            this.$watch((vm) => (vm.widgetTitle, vm.search.type, vm.search.url, vm.widgetConfiguration.theme, vm.widgetConfiguration.size, vm.widgetContent, Date.now()), function () {
                this.debouncedSave()
            })
        },
        methods: {
            addMessage: function(text, type = 'info') {
                if ('error' === type) {
                    type = 'danger'
                }
                const id = Date.now()+Math.random()
                const message = {text: text, type: type, id: id}
                this.messages.push(message)
                if (messageDismissDelay > 0) {
                    var vm = this
                    setTimeout(function() {
                        vm.dismissMessage(message)
                    }, messageDismissDelay)
                }
            },
            dismissMessage: function(message) {
                const index = this.messages.indexOf(message)
                if (index > -1) {
                    this.messages.splice(index, 1)
                }
            },
            doCopyEmbedCode: function () {
                const vm = this
                this.$copyText(this.embedCode.code).then(function (e) {
                    vm.addMessage('Embed code copied', 'success')
                }, function (e) {
                    vm.addMessage('Error copying embed code', 'error')
                })
            },
            isValid: function() {
                return this.widgetTitle
                    && ((SearchTypes.MANUAL === this.search.type && this.widgetContentManual && this.widgetContentManual.length > 0)
                    || (SearchTypes.URL === this.search.type && (this.search.url)))
            },
            // Load data from api.
            loadWidgetData: function(data) {
                const widget = data
                const configuration = data.configuration

                this.widget = widget
                if (configuration.search) {
                    this.search = configuration.search
                }
                this.widgetTitle = widget.title
                this.widgetContentManual = SearchTypes.MANUAL === this.search.type ? data.content : []
                this.widgetContentUrl = SearchTypes.URL === this.search.type ? data.content : []
                this.widgetConfiguration.theme = this.widgetThemes[0]
                if (configuration.theme) {
                    this.widgetThemes.forEach((theme) => {
                        if (theme.label === configuration.theme.label) {
                            this.widgetConfiguration.theme = theme
                        }
                    })
                }
                this.widgetSize = this.widgetSizes[0]
                if (configuration.size) {
                    this.widgetSizes.forEach((size) => {
                        if (size.label === configuration.size.label) {
                            this.widgetSize = size
                        }
                    })
                }
                this.addMessage('Widget loaded')
            },
            // Get data to send to api.
            getWidgetData: function() {
                return {
                    title: this.widgetTitle,
                    configuration: {
                        theme: this.widgetConfiguration.theme,
                        size: this.widgetConfiguration.size,
                        search: this.search,
                        context: this.widgetContext.name
                    },
                    content: this.widgetContent
                }
            },
            doSave: function() {
                if (!this.isValid()) {
                    return
                }
                if (null !== cancelSave) {
                    cancelSave()
                }

                const vm = this
                const data = this.getWidgetData()
                this.saveState = 'Saving'
                if (this.widget && this.widget.id) {
                    // Update
                    const saveUrl = '/api/widgets/'+this.widget.id
                    axios({
                        url: saveUrl,
                        method: 'PUT',
                        data: data,
                        cancelToken: new CancelToken(function executor(c) {
                            // An executor function receives a cancel function as a parameter
                            cancelSave = c
                        })
                    })
                        .then(function (response) {
                            vm.addMessage('Widget saved')
                            vm.widget = response.data
                        })
                        .catch(function (error) {
                            vm.addMessage('Error saving widget', 'error')
                        })
                        .then(function () {
                            vm.saveState = null
                        })
                } else {
                    // Create
                    const saveUrl = '/api/widgets'
                    axios({
                        url: saveUrl,
                        method: 'POST',
                        data: data,
                        cancelToken: new CancelToken(function executor(c) {
                            // An executor function receives a cancel function as a parameter
                            cancelSave = c
                        })
                    })
                        .then(function (response) {
                            vm.addMessage('Widget created')
                            vm.widget = response.data
                            history.replaceState({}, 'Widget created', '/widget/'+vm.widget.id+'/edit')
                        })
                        .catch(function (error) {
                            vm.addMessage('Error creating widget', 'error')
                        })
                        .then(function () {
                            vm.saveState = null
                        })
                }
            },
            addMaterial: function(material) {
                const list = this.searchResult.data
                const index = list.indexOf(material)
                if (index > -1) {
                    this.widgetContentManual = this.widgetContentManual.concat([list[index]])
                    list.splice(index, 1)
                }
            },
            removeMaterial: function(material) {
                const list = this.widgetContentManual
                const index = list.indexOf(material)
                if (index > -1) {
                    if (null === this.searchResult) {
                        this.searchResult = {data: []}
                    }
                    this.searchResult.data.push(list[index])
                    list.splice(index, 1)
                }
            },
            addAllMaterials: function() {
                if (null === this.searchResult) {
                    this.searchResult = {data: []}
                }
                this.widgetContentManual = this.widgetContentManual.concat(this.searchResult.data)
                this.searchResult.data = []
            },
            removeAllMaterials: function() {
                if (null === this.searchResult) {
                    this.searchResult = {data: []}
                }
                this.searchResult.data = this.searchResult.data.concat(this.widgetContentManual)
                this.widgetContentManual = []
            },
            doSearch: function() {
                const searchUrl = this.$config.searchUrl
                let searchMessage = null
                let params = null
                switch (this.search.type) {
                case SearchTypes.URL:
                    if (!this.search.url) {
                        return
                    }

                    searchMessage = this.$t('Searching …')

                    params = {url: this.search.url}
                    break

                case SearchTypes.MANUAL:
                    if (!this.search.query || this.search.query.length < 3) {
                        return
                    }
                    searchMessage = this.$t('Searching for {ereolen_searchQuery}', {ereolen_searchQuery: this.search.query})
                    params = {query: this.search.query}
                    break
                }

                var vm = this
                if (null !== params) {
                    vm.searchState = 'searching'
                    vm.searchError = null
                    if (null !== cancelSearch) {
                        cancelSearch()
                    }
                    if (null !== searchMessage) {
                        this.searchMessage = searchMessage
                    }
                    axios({
                        url: searchUrl,
                        params: params,
                        cancelToken: new CancelToken(function executor(c) {
                            // An executor function receives a cancel function as a parameter
                            cancelSearch = c
                        })
                    })
                        .then(result => {
                            if (SearchTypes.URL === vm.search.type) {
                                vm.widgetContentUrl = result.data.data || []
                            } else {
                                vm.searchResult = result.data
                            }
                            vm.searchState = null
                            vm.searchMessage = null
                            vm.searchError = null
                        })
                        .catch(error => {
                            if (axios.isCancel(error)) {
                                return
                            }
                            vm.searchError = error
                            vm.searchState = null
                        })
                }
            }
        }
    }
</script>
