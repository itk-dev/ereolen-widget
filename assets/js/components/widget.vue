<template>
    <div class="er-widget" v-bind:class="[theme.class, widgetDirection]" v-bind:style="{height: size.height + 'px', width: size.width + 'px'}" >
        <div class="er-widget-top">
            <h1 class="er-widget-title">{{ title }}</h1>
            <div class="er-btns">
                <a class="er-btn er-btn-left" href="#" role="button" v-on:click.stop="moveCarousel(1)" v-bind:disabled="atEndOfList"><v-icon name="angle-left" /></a>
                <a class="er-btn er-btn-right" href="#" role="button" v-on:click.stop="moveCarousel(-1)" v-bind:disabled="atHeadOfList"><v-icon name="angle-right" /></a>
            </div>
        </div>
        <div class="er-widget-main">
            <ul class="materials" v-bind:style="'transform: translateX' + '(' + currentOffset + 'px' + '); width:' + materialContainerWidth + 'px'">
                <li v-for="(material, index) in data" class="material-item" v-bind:key="index">
                    <a class="material-item-link" v-bind:href="material.url">
                        <img v-if="widgetDirection === 'landscape'" v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'height: ' + materialDimensions + 'px'" >
                        <img v-else v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title" v-bind:style="'width: ' + materialDimensions + 'px'" >
                    </a>
                </li>
            </ul>
        </div>
        <div class="er-widget-bottom">
            <a class="er-widget-backlink" href="https://ereolen.dk/">Se flere titler</a>
            <a href="https://ereolen.dk/" class="er-widget-logo" ><img src="https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg" class="er-widget-logo-image"  alt="eReolen"></a>
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
                default: {
                    class: 'theme-light'
                }
            }
        },
        data () {
            return {
                currentOffset: 0,
                windowSize: 3
            }
        },
        computed: {
            widgetDirection: function() {
                return (this.size.width >= this.size.height) ? 'landscape' : 'portrait'
            },
            materialDimensions: function() {
                if (this.size.width >= this.size.height) {
                    return ((this.size.width / this.data.length) + 10) * this.windowSize
                }
                else {
                    return (this.size.height / this.data.length) + 10
                }
            },
            paginationFactor: function() {
                return this.materialDimensions
            },
            materialContainerWidth() {
                return (this.data.length * this.windowSize) * this.paginationFactor
            },
            atEndOfList: function() {
                return this.currentOffset <= (this.paginationFactor * -1) * (this.data.length - this.windowSize)
            },
            atHeadOfList: function() {
                return this.currentOffset === 0
            }
        },
        methods: {
            moveCarousel: function(direction) {
                console.log(this.data.length);
                // Find a more elegant way to express the :style. consider using props to make it truly generic
                if (direction === 1 && !this.atEndOfList) {
                    this.currentOffset -= this.paginationFactor
                    console.log(this.currentOffset);
                } else if (direction === -1 && !this.atHeadOfList) {
                    this.currentOffset += this.paginationFactor
                    console.log(this.currentOffset);
                }
            }
        }
    }
</script>
