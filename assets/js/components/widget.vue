<template>
    <div v-bind:class="[theme.class, 'wsize' + size.width + 'x' + size.height]">
        <div class="er-widget" v-bind:class="[widgetDirection]" v-bind:style="{height: size.height + 'px', width: size.width + 'px'}">
            <div class="er-widget-top">
                <h1 class="er-widget-title">{{ title }}</h1>
            </div>
            <div class="er-widget-main" ref="materials">
                <ul v-if="widgetDirection === 'landscape'" class="materials" v-bind:style="'transform: translateX' + '(' + currentOffset + 'px' + '); width: 9999px'">
                    <li v-for="(material, index) in data" class="material-item" v-bind:key="index" v-bind:style="'width: ' + size.width/material.length + 'px'">
                        <a class="material-item-link" v-bind:href="material.url" target="_top">
                            <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'height: ' + materialDimensions + 'px;'">
                        </a>
                    </li>
                </ul>
                <ul v-else class="materials" v-bind:style="'transform: translateY' + '(' + currentOffset + 'px' + '); width:' + size.width + 'px'">
                    <li v-for="(material, index) in data" class="material-item" v-bind:key="index" v-bind:style="'min-height: ' + size.height/material.length + 'px'">
                        <a class="material-item-link" v-bind:href="material.url" target="_top">
                            <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'width: ' + materialDimensions + 'px;' + 'max-width:' + size.width*.8 + 'px'">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="er-widget-bottom">
                <!-- <a class="er-widget-backlink" v-bind:href="context.url">Se flere titler</a> -->
                <a v-bind:href="context.url" class="er-widget-logo" target="_top"><img v-bind:src="context.logo" class="er-widget-logo-image" v-bind:alt="context.label"></a>
            </div>
            <div class="er-btns" v-if="widgetDirection === 'landscape' || widgetDirection === 'portrait'">
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
                type: Object,
                required: true,
                default: () => ({
                    url: 'https://ereolen.dk/',
                    logo: 'https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg',
                    label: 'eReolen'
                })
            }
        },
        data () {
            return {
                currentOffset: 0,
                lastMaterial: 9999,
                windowSize: {
                    landscape: 2,
                    portrait: 3
                }
            }
        },
        computed: {
            widgetDirection: function() {
                return (this.size.width >= this.size.height) ? 'landscape' : 'portrait'
            },
            materialDimensions: function() {
                if (this.widgetDirection == 'landscape') {
                    var tmpSize = (this.size.width / this.windowSize.landscape)
                    if (tmpSize < this.size.height) {
                        return tmpSize
                    } else {
                        return this.size.height * 0.8
                    }
                } else {
                    var tmpSize = (this.size.height / this.windowSize.portrait)
                    if (tmpSize < this.size.height) {
                        return tmpSize
                    } else {
                        if (this.size.width === 300 && this.size.height === 600) {
                            return this.size.width * 0.4
                        } else {
                            return this.size.width * 0.8
                        }
                    }
                }
            },
            atEndOfList: function() {
                return this.currentOffset <= -this.lastMaterial
            },
            atHeadOfList: function() {
                return this.currentOffset === 0
            }
        },
        watch: {
            size: function (val) {
                this.reCalculate()
                this.currentOffset = 0

            },
            data: function (val) {
                this.reCalculate()
                this.currentOffset = 0
            },

        },
        methods: {
            moveCarousel: function (direction) {
                if (direction === 1 && !this.atEndOfList) {
                    if (this.widgetDirection == 'landscape') {
                        this.currentOffset -= this.size.width/this.windowSize.landscape
                    } else {
                        this.currentOffset -= this.size.height/this.windowSize.portrait
                    }
                } else if (direction === -1 && !this.atHeadOfList) {
                    if (this.widgetDirection == 'landscape') {
                        this.currentOffset += this.size.width/this.windowSize.landscape
                    } else {
                        this.currentOffset += this.size.height/this.windowSize.portrait
                    }
                }
            },
            reCalculate: function () {
                var container = this.$refs.materials.querySelector('ul')
                var el = container.querySelector('li:last-child')
                if (this.widgetDirection == 'landscape') {
                    this.lastMaterial = el.offsetLeft-(this.size.width-el.offsetWidth)
                } else {
                    this.lastMaterial = el.offsetTop-((this.size.height*0.8)-el.offsetHeight)
                }
            }
        },
        mounted() {
            const self = this
            // Waiting for images to finish loading
            window.addEventListener('load', function(){
                self.reCalculate()
            })
        }
    }
</script>
