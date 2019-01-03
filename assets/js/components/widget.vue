<template>
    <div v-bind:class="[theme.class, 'wsize' + size.width + 'x' + size.height]">
        <div class="er-widget" v-bind:class="[widgetDirection]" v-bind:style="{height: size.height + 'px', width: size.width + 'px'}">
            <div class="er-widget-top">
                <h1 class="er-widget-title">{{ title }}</h1>
            </div>
            <div class="er-widget-main" ref="materials">
                <div v-if="widgetDirection === 'landscape'" class="materials" v-bind:style="'transform: translateX' + '(' + currentOffset + 'px' + '); '">
                    <a v-for="(material, index) in data" class="material-item" v-bind:key="index" v-bind:href="material.url" target="_top">
                        <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                    </a>
                </div>
                <div v-else class="materials" v-bind:style="'transform: translateY' + '(' + currentOffset + 'px' + '); width:' + size.width ">
                    <a v-for="(material, index) in data" class="material-item" v-bind:key="index" v-bind:href="material.url" target="_top">
                        <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                    </a>
                </div>
            </div>
            <div class="er-widget-bottom">
                <!-- <a class="er-widget-backlink" v-bind:href="context.url">Se flere titler</a> -->
                <a v-bind:href="context.url" class="er-widget-logo" target="_top"><img v-bind:src="context.logo" class="er-widget-logo-image" v-bind:alt="context.label"></a>
            </div>
            <div class="er-btns" v-if="widgetDirection === 'landscape' && materialContainer >= mainContainer || widgetDirection === 'portrait' && materialContainer >= mainContainer">
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
                materialContainer: 9999,
                mainContainer: 0,
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
            atEndOfList: function() {
                return this.currentOffset <= -this.lastMaterial
            },
            atHeadOfList: function() {
                return this.currentOffset >= 0
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
            }
        },
        methods: {
            moveCarousel: function (direction) {
                if (direction === 1 && !this.atEndOfList) {
                    if (this.widgetDirection == 'landscape') {
                        var width = -(this.materialContainer-(this.size.width+this.$refs.materials.querySelector('.materials a:last-child').offsetWidth))
                        if (this.currentOffset <= width) {
                            this.currentOffset = -this.lastMaterial
                        } else {
                            this.currentOffset -= this.materialContainer/this.data.length
                        }
                        console.log('width: ',width)
                        console.log('this.currentOffset: ',this.currentOffset)
                    } else {
                        var height = -(this.materialContainer-(this.size.height+this.$refs.materials.querySelector('.materials a:last-child').offsetHeight))
                        if (this.currentOffset <= height) {
                            this.currentOffset = -this.lastMaterial
                        } else {
                            this.currentOffset -= this.materialContainer/this.data.length
                        }
                    }
                } else if (direction === -1 && !this.atHeadOfList) {
                    if (this.widgetDirection == 'landscape') {
                        var width = -this.$refs.materials.querySelector('.materials a:first-child').offsetWidth
                        if (this.currentOffset >= width) {
                            this.currentOffset = 0
                        } else {
                            this.currentOffset += this.materialContainer/this.data.length
                        }
                    } else {
                        var height = -this.$refs.materials.querySelector('.materials a:first-child').offsetHeight
                        if (this.currentOffset >= height) {
                            this.currentOffset = 0
                        } else {
                            this.currentOffset += this.materialContainer/this.data.length
                        }
                    }
                }
            },
            reCalculate: function () {
                var main = this.$refs.materials
                var container = this.$refs.materials.querySelector('.materials')
                var el = container.querySelector('a:last-child')
                if (this.widgetDirection === 'landscape') {
                    this.lastMaterial = el.offsetLeft-(main.offsetWidth-el.offsetWidth)
                    this.materialContainer = container.offsetWidth
                    this.mainContainer = main.offsetWidth
                } else {
                    this.lastMaterial = el.offsetTop-(main.offsetHeight-el.offsetHeight)
                    this.materialContainer = container.offsetHeight
                    this.mainContainer = main.offsetHeight
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
