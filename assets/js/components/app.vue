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
                                <label for="">Søg efter bøger du vil vise i karrusellen</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="Skriv bogens forfatter, titel, isbn eller forlag">
                            </div>
                            <div class="form-group" v-if="contentSearch === 'search'">
                                <label for="">Indsæt url fra eReolen</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="F.eks. : https://ereolen.dk/search/ting/jussi%20adler">
                                <small id="helpId" class="form-text text-muted">Lav først en søgning på <a href="//ereolen.dk">eReolen.dk</a> og kopier url'en i adresselinjen. Indsæt derefter url'en her.</small>
                            </div>
                            <div class="form-group">
                                <label for="">Widget titel (Overskrift)</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="Skriv bogens forfatter, titel, isbn eller forlag" v-model="widgetTitle">
                                <small id="helpId" class="form-text text-muted">Denne vises som overskrift i widgeten</small>
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
                        <widget v-bind:height="widgetSizes[widgetSize].height" v-bind:width="widgetSizes[widgetSize].width" v-bind:title="widgetTitle" v-if="widgetTitle.length != 0"/>
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
                                            <a href="#" class="code-preview-header-copy">
                                                <v-icon name="copy" />Kopier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="code-preview-content">
                                    <pre><code>
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
    export default {
        name: "app",
        data() {
            return {
                widgetTitle: "",
                contentSearch: "manuel",
                widgetThemes: [
                    { label: "Lys", class: "light"},
                    { label: "Mørk", class: "dark" }
                ],
                widgetTheme: 0,
                widgetSizes: [
                    { label: "Rektangulær", width: "250", height: "250" },
                    { label: "Banner", width: "468", height: "60" },
                    { label: "Skyskraber", width: "120", height: "600" },
                    { label: "Bred skyskraber", width: "160", height: "600" },
                    { label: "Lille kvadrat", width: "200", height: "200" },
                    { label: "Kvadrat", width: "250", height: "250" },
                    { label: "Mellemstor rektangel", width: "300", height: "250" },
                    { label: "Stor rektangel", width: "336", height: "280" },
                    { label: "Halvside", width: "300", height: "600" },
                    { label: "Mobilbanner", width: "320", height: "50" },
                    { label: "Mobilbanner 2", width: "320", height: "100" },
                    { label: "Stort leaderboard", width: "970", height: "90" }
                ],
                widgetSize: 0,
                selectedOption: ""
            }
        },
        methods:{
            selectSize:function() {
                this.selectedOption = '';
            }
        }
    }
</script>
 
<style>

</style>
