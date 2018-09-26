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
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="Skriv bogens forfatter, titel, isbn eller forlag">
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
                                <label for="widget_color">Farve</label>
                                <select class="form-control" name="widget_color" id="widget_color">
                                    <option>Lys</option>
                                    <option>Mørk</option>
                                </select>
                            </div>
                        
                            <div class="form-group">
                                <label for="widget_size">Farve</label>
                                <select class="form-control" name="widget_size" id="widget_size">
                                        <optgroup label="Rektangulære">
                                            <option>320 x 50</option>
                                            <option>320 x 100</option>
                                            <option>200 x 200</option>
                                            <option>250 x 250</option>
                                            <option>300 x 250</option>
                                            <option>336 x 280</option>
                                        </optgroup>
                                        <optgroup label="Horisontale">
                                            <option>468 x 60</option>
                                            <option>728 x 90</option>
                                            <option>970 x 90</option>
                                        </optgroup>
                                        <optgroup label="Vertikale">
                                            <option>120 x 600</option>
                                            <option>160 x 600</option>
                                            <option>300 x 600</option>
                                        </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <h3>Preview</h3>
                    <div class="widget-preview bg-white">
                        <widget />
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
                                        &lt;iframe src="https://widget.ereolen.dk/follow/1/?uri=ereolen:artist:6sFIWsNpZYqfjUpaCgueju&size=detail&theme=light" width="250" height="250" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowtransparency="true"&gt;&lt;/iframe&gt;
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
        data: function() {
            return {
                contentSearch: 'manuel'
            }
        }
    }
</script>
 
<style>

</style>