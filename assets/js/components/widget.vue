<template>
    <div class="er-widget theme-light" v-bind:style="cssProps">
        <h1 class="er-widget-title">{{ title }}</h1>
        <a class="btn btn-left" href="#" role="button"><v-icon name="angle-left" v-on:click="moveCarousel(-1)" v-bind:disabled="atHeadOfList" /></a>
        <ul class="materials" v-bind:style="{ transform: 'translateX' + '(' + currentOffset + 'px' + ')'}">
            <li v-for="(material, index) in data" class="material-item" v-bind:key="index">
                <a class="material-item-link" v-bind:href="material.url">
                    <img v-bind:src="material.cover" v-bind:alt="material.title" v-bind:title="material.title">
                </a>
            </li>
        </ul>
        <a class="btn btn-right" href="#" role="button"><v-icon name="angle-right" v-on:click="moveCarousel(1)"
      v-bind:disabled="atEndOfList"/></a>
        <a class="er-widget-backlink" href="https://ereolen.dk/">Se flere titler</a>
        <img src="https://ereolen.dk/sites/all/themes/orwell/svg/eReolen_Logo.svg" class="er-widget-logo" alt="eReolen">
    </div>
</template>

<script>
    import '../../scss/widget.scss'
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
            width: {
                type: Number,
                required: true
            },
            height: {
                type: Number,
                required: true
            }
        },
        computed: {
            cssProps() {
                return {
                    '--widget-width': this.width + 'px',
                    '--widget-height': this.height + 'px'
                }
            },
            atEndOfList() {
                return this.currentOffset <= (this.paginationFactor * -1) * (this.data.length - this.windowSize);
            },
            atHeadOfList() {
                return this.currentOffset === 0;
            }
        },
        data () {
            return {
                currentOffset: 0,
                windowSize: 3,
                paginationFactor: 220
            }
        },
        methods: {
            moveCarousel(direction) {
            // Find a more elegant way to express the :style. consider using props to make it truly generic
                if (direction === 1 && !this.atEndOfList) {
                    this.currentOffset -= this.paginationFactor;
                } else if (direction === -1 && !this.atHeadOfList) {
                    this.currentOffset += this.paginationFactor;
                }
            }
        }
    }
</script>
