<template>
    <div class="er-widget theme-light" v-bind:style="cssProps">
        <h1 class="er-widget-title">{{ title || $t('(no title)') }}</h1>
        <ul class="materials">
            <li v-for="material in widgetContent" class="material-item" v-bind:key="material.id">
                <a class="material-item-link" v-bind:href="material.url">
                    <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                </a>
            </li>
        </ul>
        <a class="er-widget-backlink" href="https://ereolen.dk/search/ting/dkcclterm.op%3D201809%2A%20AND%20dkcclterm.sp%3Ddan%20OR%20870970-basis%253A54643136">Se alle titler</a>
        <img src="https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg" class="er-widget-logo" alt="eReolen">
    </div>
</template>

<script>
    export default {
        name: 'Widget',
        props: {
          title: {
            type: String,
            required: true
          },
          width: {
            type: Number,
            required: true
          },
          height: {
            type: Number,
            required: true
          },
          widgetContent: {
            type: Array,
            required: true
          }
        },
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
        width: 100%;
        overflow-x: scroll;
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
