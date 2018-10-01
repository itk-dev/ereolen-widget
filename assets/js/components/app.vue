<template>
    <div class="row">
        <div class="col-sm-2 sidebar pt-3 pb-3">
            <nav class="nav flex-column">
                <a class="nav-link active" href="#">Materiale karrusel</a>
            </nav>
        </div>
        <div class="col-sm-10 main pt-3 pb-3">
            <h1>Byg din eReolen materiale karrusel</h1>
            <form>
                <fieldset>
                    <h3>Indhold</h3>
                    <div class="form-group">
                        <div class="row justify-content-start">
                            <div class="col-auto">
                                <div class="form-check">
                                    <!-- #TODO: Show itk-spinner while searching  -->
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="content" id="" value="manuel" checked v-model="contentSearch">
                                        Tilføj manuelt
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="content" id="" value="search" v-model="contentSearch">
                                        Brug søgning fra eReolen
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10 col-lg-8">
                            <div class="form-group" v-if="contentSearch === 'manuel'">
                                <label for="contentSearchManual">Søg efter bøger du vil vise i karrusellen</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><v-icon name="search" /></div>
                                    </div>
                                    <input type="text" class="form-control" name="contentSearchManual" id="contentSearchManual" placeholder="Skriv bogens forfatter, titel, isbn eller forlag" v-model="search.query" v-on:keyup.enter="debouncedSearch">
                                </div>
                            </div>
                            <div class="form-group" v-if="contentSearch === 'search'">
                                <label for="contentSearchSearch">Indsæt url fra eReolen</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URL</div>
                                    </div>
                                    <input type="text" class="form-control" name="contentSearchSearch" id="contentSearchSearch" aria-describedby="contentSearchSearchHelp" placeholder="F.eks. : https://ereolen.dk/search/ting/jussi%20adler" v-model="search.url" v-on:keyup.enter="debouncedSearch">
                                </div>
                                <small id="contentSearchSearchHelp" class="form-text text-muted">Lav først en søgning på <a href="//ereolen.dk">eReolen.dk</a> og kopier url'en i adresselinjen. Indsæt derefter url'en her.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row content-search" v-if="contentSearch === 'manuel' && searchResult && searchResult.data.length != 0">
                        <div class="col-sm-12">
                            <label>Søgeresultat: <strong>Tryk på bøgerne for at tilføje</strong></label><a href="#" class="btn btn-success btn-sm text-light ml-2" @click="addAllMaterials">Tilføj alle</a>
                            <div class="row content-search-results">
                                <material v-for="material in searchResult.data" v-bind:key="material.id" v-bind:data="material" v-bind:id="material.id" v-bind:title="material.title" v-bind:cover="material.cover" v-bind:url="material.url" icon="plus" v-bind:action="addMaterial" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label>Tilfjøede bøger: <strong>Tryk på bøgerne for at fjerne</strong></label><a href="#" class="btn btn-danger btn-sm text-light ml-2" @click="removeAllMaterials">Fjern alle</a>
                            <div class="row content-search-results added">
                                <material v-for="material in widgetContent" v-bind:key="material.id" v-bind:data="material" v-bind:id="material.id" v-bind:title="material.title" v-bind:cover="material.cover" v-bind:url="material.url" icon="minus" v-bind:action="removeMaterial" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-10 col-lg-8">
                            <div class="form-group">
                                <label for="widgetTitle">Widget titel (Overskrift)</label>
                                <input type="text" class="form-control" name="widgetTitle" id="widgetTitle" aria-describedby="widgetTitleHelp" placeholder="Skriv bogens forfatter, titel, isbn eller forlag" v-model="widgetTitle">
                                <small id="widgetTitleHelp" class="form-text text-muted">Denne vises som overskrift i widgeten</small>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <h3>Udseende</h3>
                    <div class="row">
                        <div class="col-sm-10 col-lg-8">
                            <div class="form-group">
                                <label for="widget_theme">Farve</label>
                                <select class="form-control" name="widget_theme" id="widget_theme" v-model="widgetTheme">
                                    <option v-for="(option,index) in widgetThemes" v-bind:value="index" v-bind:key="option.id">
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="widget_size">Størrelse</label>
                                <select class="form-control" name="widget_size" id="widget_size" v-model="widgetSize" @change="selectSize">
                                    <option v-for="(option,index) in widgetSizes" v-bind:value="index" v-bind:key="option.id">
                                        {{ option.label }} {{ option.width }}x{{ option.height }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <h3>Preview</h3>
                    <div class="widget-preview bg-white">
                        <!-- #TODO: Show itk-spinner while updating  -->
                        <widget v-bind:height="widgetSizes[widgetSize].height" v-bind:width="widgetSizes[widgetSize].width" v-bind:title="widgetTitle" v-bind:widget-content="widgetContent" v-if="widgetTitle.length != 0" />
                        <div class="widget-preview default" v-if="widgetTitle.length === 0">
                            Preview opdateres når du har valgt materialer.
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="col-sm-10 col-lg-8">
                            <h3>Indlejringskode</h3>
                            <label>Indsæt denne kode på dit website for at få vist din widget.</label>
                            <div class="code-preview">
                                <div class="code-preview-header">
                                    <div class="row">
                                        <div class="col-auto">
                                            <span class="code-preview-header-title">
                                                <v-icon name="code" />HTML
                                            </span>
                                        </div>
                                        <div class="col-auto ml-auto">
                                            <!-- #TODO: Add functionality to copy code on click -->
                                            <a href="#" class="code-preview-header-copy">
                                                <v-icon name="copy" />Kopier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="code-preview-content">
                                    <pre><code>
                                        <!-- #TODO: Use real url -->
                                        <!-- #TODO: Use real content  -->
                                        &lt;iframe src="https://widget.ereolen.dk/follow/1/?uri=ereolen:artist:6sFIWsNpZYqfjUpaCgueju&size={{ widgetSizes[widgetSize].width }}x{{ widgetSizes[widgetSize].height }}&theme={{ widgetThemes[widgetTheme].class }}" width="{{ widgetSizes[widgetSize].width }}" height="{{ widgetSizes[widgetSize].height }}" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowtransparency="true"&gt;&lt;/iframe&gt;
                                    </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</template>

<script>
    const axios = require('axios');
    const debounce = require('debounce')
    const queryString = require('query-string')

    const CancelToken = axios.CancelToken;
    let cancelSearch = null;

    export default {
        name: 'App',
        data() {
            return {
                // The selected materials
                widgetContent: [],
                widgetTitle: '',
                contentSearch: 'manuel',
                contentSearchManual: '',
                widgetThemes: [
                    {label: 'Lys', class: 'light'},
                    {label: 'Mørk', class: 'dark'}
                ],
                widgetTheme: 0,
                widgetSizes: [
                    {label: 'Rektangulær', width: 250, height: 250},
                    {label: 'Banner', width: 468, height: 60},
                    {label: 'Skyskraber', width: 120, height: 600},
                    {label: 'Bred skyskraber', width: 160, height: 600},
                    {label: 'Lille kvadrat', width: 200, height: 200},
                    {label: 'Kvadrat', width: 250, height: 250},
                    {label: 'Mellemstor rektangel', width: 300, height: 250},
                    {label: 'Stor rektangel', width: 336, height: 280},
                    {label: 'Halvside', width: 300, height: 600},
                    {label: 'Mobilbanner', width: 320, height: 50},
                    {label: 'Mobilbanner 2', width: 320, height: 100},
                    {label: 'Stort leaderboard', width: 970, height: 90}
                ],
                widgetSize: 0,
                selectedOption: '',
                // The search query.
                search: {
                    // "Manuel" search
                    query: null,
                    // Search by ereolen.dk url
                    url: null
                },
                searchState: null,
                // User message, e.g. "Searching for James Hetfield …"
                searchMessage: null,
                // Any errors reported while searching.
                searchError: null,
                // The search result after a succesful search.
                searchResult: null
            }
        },
        watch: {
            contentSearch: 'debouncedSearch',
            'search.query': 'debouncedSearch',
            'search.url': 'debouncedSearch'
        },
        created: function() {
            this._debouncedSearch = debounce(this.doSearch, 500)
            const search = queryString.parse(location.hash)
            if ('query' in search) {
                this.search.query = search['query']
            }
        },
        methods:{
            addMaterial: function(material) {
                const list = this.searchResult.data;
                const index = list.indexOf(material);
                if (index > -1) {
                    this.widgetContent.push(list[index]);
                    list.splice(index, 1);
                }
            },
            removeMaterial: function(material) {
                const list = this.widgetContent;
                const index = list.indexOf(material);
                if (index > -1) {
                    this.searchResult.data.push(list[index]);
                    list.splice(index, 1);
                }
            },
            addAllMaterials: function() {
                this.widgetContent = this.widgetContent.concat(this.searchResult.data)
                this.searchResult.data = []
            },
            removeAllMaterials: function() {
                this.searchResult.data = this.searchResult.data.concat(this.widgetContent)
                this.widgetContent = [];
            },
            selectSize:function() {
                this.selectedOption = '';
            },
            debouncedSearch: function() {
                this._debouncedSearch();
            },
            doSearch: function() {
                const searchUrl = '/widget/search';
                let searchMessage = null;
                let params = null;
                switch (this.contentSearch) {
                case 'search':
                    if (!this.search.url) {
                        return
                    }

                    searchMessage = 'Searching …'

                    params = {url: this.search.url}
                    break

                case 'manuel':
                    if (!this.search.query || this.search.query.length < 3) {
                        return;
                    }
                    searchMessage = 'Searching for "'+this.search.query+'" …'

                    params = {query: this.search.query}
                    break
                }

                var vm = this
                if (null !== params) {
                    vm.searchState = 'searching';
                    vm.searchError = null;
                    if (null !== cancelSearch) {
                        cancelSearch();
                    }
                    if (null !== searchMessage) {
                        this.searchMessage = searchMessage
                    }
                    axios.get(searchUrl, {
                        cancelToken: new CancelToken(function executor(c) {
                            // An executor function receives a cancel function as a parameter
                            cancelSearch = c;
                        }),
                        params: params
                    })
                    .then(result => {
                        vm.searchResult = result.data;
                        vm.searchState = null;
                        vm.searchMessage = null;
                        vm.searchError = null;
                    })
                    .catch(error => {
                        if (axios.isCancel(error)) {
                            return;
                        }
                        vm.searchError = error;
                        vm.searchState = null;
                    });
                }
            }
        }
    }
</script>
