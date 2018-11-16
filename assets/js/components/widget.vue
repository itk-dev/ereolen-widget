<template>
    <div v-bind:class="[theme.class, 'wsize' + size.width + 'x' + size.height]">
        <div class="er-widget" v-bind:class="[widgetDirection]" v-bind:style="{height: size.height + 'px', width: size.width + 'px'}" >
            <div class="er-widget-top">
                <h1 class="er-widget-title">{{ title }}</h1>
            </div>
            <div class="er-widget-main">
                <ul v-if="widgetDirection === 'landscape'" class="materials" v-bind:style="'transform: translateX' + '(' + currentOffset + 'px' + '); width:' + materialContainerWidth + 'px'">
                    <li v-for="(material, index) in data" class="material-item" v-bind:key="index">
                        <a class="material-item-link" v-bind:href="material.url">
                            <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'height: ' + materialDimensions + 'px;'" >
                        </a>
                    </li>
                </ul>
                <ul v-else class="materials" v-bind:style="'transform: translateY' + '(' + currentOffset + 'px' + '); width:' + size.width + 'px'">
                    <li v-for="(material, index) in data" class="material-item" v-bind:key="index">
                        <a class="material-item-link" v-bind:href="material.url">
                            <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'width: ' + materialDimensions + 'px;' + 'max-width:' + size.width + 'px'" >
                        </a>
                    </li>
                </ul>
            </div>
            <div class="er-widget-bottom">
                <a class="er-widget-backlink" v-bind:href="context.url">Se flere titler</a>
                <a v-bind:href="context.logo" class="er-widget-logo" ><img v-bind:src="context.logo" class="er-widget-logo-image" v-bind:alt="context.label"></a>
            </div>
            <div class="er-btns" v-if="widgetDirection === 'landscape' && materialContainerWidth > size.width || widgetDirection === 'portrait' && materialContainerWidth > size.height">
                <button class="er-btn er-btn-left" href="#" role="button" v-on:click.prevent="moveCarousel(-1)" v-bind:disabled="atHeadOfList"><v-icon name="angle-left" /></button>
                <button class="er-btn er-btn-right" href="#" role="button" v-on:click.prevent="moveCarousel(1)" v-bind:disabled="atEndOfList"><v-icon name="angle-right" /></button>
            </div>
        </div>
    </div>
</template>
<script>
    require('../../scss/widget.scss')
    export default {
        name: 'Widget',
        props: {
            data: {
                type: Array,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            size: {
                type: Object,
                required: true
            },
            theme: {
                type: Object,
                required: true,
                default: () => ({
                    class: 'theme-light'
                })
            },
            context: {
                url: 'https://ereolen.dk/',
                logo: 'https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg',
                label: 'eReolen'
            }
        },
        data () {
            return {
                currentOffset: 0,
                windowSize: {
                    landscape: 2,
                    portrait: 6
                }
            }
        },
        computed: {
            widgetDirection: function() {
                return (this.size.width >= this.size.height) ? 'landscape' : 'portrait'
            },
            materialDimensions: function() {

                if (this.size.width >= this.size.height) {
                    var tmpSize = (this.size.width / this.windowSize.landscape);
                    if (tmpSize < this.size.height) {
                        return (tmpSize)
                    }
                    else {
                        return (this.size.height * 0.8)
                    }
                }
                else {
                    return (this.size.height / this.windowSize.portrait)
                }
            },
            paginationFactor: function() {
                return this.materialDimensions
            },
            materialContainerWidth() {
                return this.data.length * this.paginationFactor
            },
            atEndOfList: function() {
                return this.currentOffset <= (this.paginationFactor * -1) * (this.data.length - this.windowSize.landscape)
            },
            atHeadOfList: function() {
                return this.currentOffset === 0
            }
        },
        methods: {
            moveCarousel: function(direction) {
                if (direction === 1 && !this.atEndOfList) {
                    this.currentOffset -= this.paginationFactor
                } else if (direction === -1 && !this.atHeadOfList) {
                    this.currentOffset += this.paginationFactor
                }
            }
        },
        watch: {
            size: function (val) {
                this.currentOffset = 0;
            }
        }
    }
</script>
