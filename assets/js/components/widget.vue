<template>
    <div class="er-widget theme-light" v-bind:style="cssProps">
        <h1 class="er-widget-title">{{ title }}</h1>
        <ul class="materials">
            <li class="material-item">
                <a class="material-item-link active" href="https://ereolen.dk/ting/object/870970-basis%3A54769598"><img src="https://ereolen.dk/sites/default/files/styles/ereol_cover_base/public/ting/covers/ODcwOTcwLWJhc2lzOjU0NzY5NTk4.jpg?itok=75FnfW5A" alt="Hilmar Wulff: Ondt vejr (Ved Søren Elung Jensen)" title="Hilmar Wulff: Ondt vejr (Ved Søren Elung Jensen)"></a>
            </li>
            <li class="material-item">
                <a class="material-item-link" href="https://ereolen.dk/ting/object/870970-basis%3A54791275"><img src="https://ereolen.dk/sites/default/files/styles/ereol_cover_base/public/ting/covers/ODcwOTcwLWJhc2lzOjU0NzkxMjc1.jpg?itok=KrBUf4Dr" alt="Line Kyed Knudsen: Liv og Emma på cykeltur" title="Line Kyed Knudsen: Liv og Emma på cykeltur"></a>
            </li>
            <li class="material-item">
                <a class="material-item-link" href="https://ereolen.dk/ting/object/870970-basis%3A54768974"><img src="https://ereolen.dk/sites/default/files/styles/ereol_cover_base/public/ting/covers/ODcwOTcwLWJhc2lzOjU0NzY4OTc0.jpg?itok=Mw95ATJq" alt="A.P. Rosell, direktør - Bind 1" title="A.P. Rosell, direktør - Bind 1"></a>
            </li>
        </ul>
        <a class="er-widget-backlink" href="https://ereolen.dk/search/ting/dkcclterm.op%3D201809%2A%20AND%20dkcclterm.sp%3Ddan%20OR%20870970-basis%253A54643136">Se alle titler</a>
        <img src="https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg" class="er-widget-logo" alt="eReolen">
    </div>
</template>
 
<script>
    export default {
        name: 'widget',
        props: ['title', 'width', 'height'],
        computed: {
            cssProps() { 
                return {
                    '--widget-width': this.width + 'px',
                    '--widget-height': this.height + 'px'
                }
            }
        }
    }
</script>
 
<style scoped>
    .er-widget {
        width: var(--widget-width);
        height: var(--widget-height);
        overflow: hidden;
        position: relative;
    }
    .er-widget.theme-light {
        background-color:  #f0f0f0;
        color: #212121;
    }
    .er-widget.theme-dark {
        background-color:  #212121;
        color: #fff;
    }
    .er-widget .er-widget-title {
        margin: .5rem;
        font-size: 1.5rem;
    }
    .er-widget ul.materials{
        list-style: none;
        margin: 0 0 0 .5rem;
        padding: 0;
        width: 1000px;
    }
    .er-widget ul.materials li{
        margin: 0;
        display: inline-block;
    }
    .er-widget ul.materials li a{
        display: block;
    }
    .er-widget ul.materials li a img{
        width: 100px;
        max-height: 100%;
    }
    .er-widget-backlink {
        border: 1px solid #666;
        border-radius: 2px;
        padding: 5px 10px;
        color: #666;
        background-color: transparent;
        transition: color .2s ease-in, border-color .2s ease-in, background-color .2s ease-in;
        position: absolute;
        left: .5rem;
        bottom: .5rem;
    }
    .er-widget-backlink:hover {
        border: 1px solid #b81b40;
        color: #fff;
        background-color: #b81b40;
        text-decoration: none;
        transition: color .4s ease-out, border-color .4s ease-out, background-color .4s ease-out;
    }
    .er-widget-logo {
        height: 1.2rem;
        position: absolute;
        right: .5rem;
        bottom: .5rem;
    }
</style>