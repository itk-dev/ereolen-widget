<template>
    <div class="col-3 col-lg-2" @click="action(data)">
        <div class="result">
            <img v-bind:src="cover" v-bind:alt="title" class="img-fluid">
            <pre>icon: {{ icon }}</pre>
            <div class="action" v-bind:class="action">
                <v-icon v-bind:name="icon" />
            </div>
            <div class="title">
                <p>{{ title }}</p>
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

<style>

</style>
