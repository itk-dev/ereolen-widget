<template>
    <div class="col-3 col-lg-2">
        <a v-bind:href="url" class="result">
            <img v-bind:src="coverUrl" v-bind:alt="title" class="img-fluid">
            <div class="action" v-bind:class="action">
                <v-icon name="minus" v-if="action === 'remove'"/>
                <v-icon name="plus" v-if="action != 'remove'"/>
            </div>
            <div class="title">
                <p>{{ title }}</p>
            </div>
        </a>
    </div>
</template>
 
<script>
   export default {
        name: 'material',
        props: ['title', 'coverUrl', 'url', 'action']
    }
</script>
 
<style>

</style>
