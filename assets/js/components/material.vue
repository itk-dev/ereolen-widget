<template>
    <div class="col-4 col-sm-3 col-lg-2 mb-3" @click="action(data)">
        <div class="result">
            <img v-bind:src="cover" v-bind:alt="title" class="result-image img-fluid">
            <!-- #TODO: Show material type (Audiobook/ebook) -->
            <div class="result-action">
                <v-icon v-bind:name="icon" />
            </div>
            <div class="result-title">
                <p><strong>{{ title }}</strong> <span v-if="data.type" class="type-wrapper">(<span class="type">{{ data.type }}</span>)</span></p>
                <p v-if="authors">{{ $tc('Author', authors.length) }}: <span v-for="(author, index) in authors" v-bind:key="index">{{ author }}<span v-if="index < authors.length - 1">, </span></span></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Material',
        props: {
            data: {
                type: Object,
                required: true
            },
            id: {
                type: String,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            cover: {
                type: String,
                default: null
            },
            url: {
                type: String,
                required: true
            },
            icon: {
                type: String,
                required: true
            },
            action: {
                type: Function,
                required: true
            }
        },
        computed: {
            authors: function() {
                if (this.data.creators.length > 0) {
                    return this.data.creators
                } else if (this.data.contributors.length > 0) {
                    return this.data.contributors
                }
                return null
            }
        }
    }
</script>
