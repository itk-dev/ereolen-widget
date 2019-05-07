<template>
    <div v-bind:class="[theme.class, 'wsize' + size.width + 'x' + size.height]">
        <div class="er-widget" v-bind:class="[widgetDirection]" v-bind:style="{height: size.height + 'px', width: size.width + 'px'}">
            <div class="er-widget-top">
                <h1 class="er-widget-title">{{ title }}</h1>
            </div>
            <div class="er-widget-main" ref="materials">

                <vue-slick v-if="widgetDirection === 'landscape'" ref="slick" :options="slickOptionsLandscape">
                    <a v-for="(material, index) in data" v-bind:key="index" v-bind:href="getUrl(material.url)" target="_top">
                        <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                    </a>
                </vue-slick>

                <vue-slick v-else ref="slick" :options="slickOptionsPortrait">
                    <a v-for="(material, index) in data" v-bind:key="index" v-bind:href="getUrl(material.url)" target="_top">
                        <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                    </a>
                </vue-slick>

            </div>
            <div class="er-widget-bottom">
                <a v-bind:href="getUrl(context.url)" class="er-widget-logo" target="_top"><img v-bind:src="context.logo" class="er-widget-logo-image" v-bind:alt="context.label"></a>
            </div>
            <div class="er-btns">
                <button type="button" class="er-btn er-btn-prev" role="button"><v-icon name="angle-left" /></button>
                <button type="button" class="er-btn er-btn-next" role="button"><v-icon name="angle-right" /></button>
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
                slickOptionsLandscape: {
                    infinite: true,
                    speed: 500,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    centerMode: false,
                    centerPadding: '8px',
                    adaptiveHeight: true,
                    prevArrow: '.er-btn-prev',
                    nextArrow: '.er-btn-next',
                    variableWidth: false,
                    respondTo: 'min',
                    responsive: [
                        {
                            breakpoint: 971,
                            settings: {
                                slidesToShow: 12
                            }
                        },
                        {
                            breakpoint: 469,
                            settings: {
                                slidesToShow: 8
                            }
                        },
                        {
                            breakpoint: 468,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 321,
                            settings: {
                                slidesToShow: 5
                            }
                        },
                        {
                            breakpoint: 320,
                            settings: {
                                slidesToShow: 2,
                            }
                        }
                    ]
                },
                slickOptionsPortrait: {
                    infinite: true,
                    speed: 500,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    centerMode: false,
                    centerPadding: '8px',
                    adaptiveHeight: false,
                    vertical: true,
                    verticalSwiping: true,
                    prevArrow: '.er-btn-prev',
                    nextArrow: '.er-btn-next',
                    respondTo: 'min',
                    responsive: [
                        {
                            breakpoint: 301,
                            settings: {
                                rows: 1,
                                slidesPerRow: 2,
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 280,
                            settings: {
                                rows: 0
                            }
                        }
                    ]
                },
                // @see https://stackoverflow.com/questions/3420004/access-parent-url-from-iframe
                pageUrl: (self===top) ? null : document.referrer
            }
        },
        computed: {
            widgetDirection: function() {
                return (this.size.width >= this.size.height) ? 'landscape' : 'portrait'
            }
        },
        watch: {
            size: function (val) {
                this.reCalculate()


            },
            data: function (val) {
                this.reCalculate()
            }
        },
        methods: {
            reCalculate: function () {
                this.currentOffset = 0
                this.$refs.slick.destroy()
                this.$nextTick(() => {
                    this.$refs.slick.create()
                })
            },
            getUrl: function (url) {
                if (null !== this.pageUrl) {
                    url += (url.indexOf('?') === -1 ? '?' : '&') + 'pageUrl='+encodeURIComponent(this.pageUrl)
                }
                return url
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
