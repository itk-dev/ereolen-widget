<template>
    <div class="col-3 col-lg-2 mb-1" @click="action(data)">
        <div class="result">
            <img v-bind:src="cover" v-bind:alt="title" class="result-image img-fluid">
            <!-- #TODO: Show material type (Audiobook/ebook) -->
            <div class="result-action">
                <v-icon v-bind:name="icon" />
            </div>
            <div class="result-title">
                <p><strong>{{ title }}</strong></p>
                <p v-if="data.contributors">Af: <span v-for="(contributor, index) in data.contributors" v-bind:key="contributor">{{ contributor }}<span v-if="index < data.contributors.length - 1">, </span></span></p>
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
            required: true
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
        }
    }
</script>
